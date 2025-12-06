#!/usr/bin/php
<?php
/**
 * Scheduler for Telemedicine System
 * Runs background tasks like sending reminders and checking absences
 * 
 * Usage: php scheduler.php or set up as cron job
 * Cron example: 0 * * * * /usr/bin/php /path/to/scheduler.php
 */

require_once dirname(__FILE__) . '/config/db.php';

// Log file
$log_file = dirname(__FILE__) . '/logs/scheduler.log';
if (!is_dir(dirname($log_file))) {
    mkdir(dirname($log_file), 0755, true);
}

function log_message($message) {
    global $log_file;
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($log_file, "[$timestamp] $message\n", FILE_APPEND);
    echo "$timestamp - $message\n";
}

try {
    log_message("=== SCHEDULER STARTED ===");

    // 1. Send pending reminders
    log_message("Processing pending reminders...");
    $stmt = $pdo->prepare('
        SELECT r.*, p.phone
        FROM reminders r
        JOIN patients p ON r.patient_id = p.id
        WHERE r.status = "pending" AND r.scheduled_at <= NOW() AND r.retry_count < 3
        ORDER BY r.scheduled_at ASC
        LIMIT 50
    ');
    $stmt->execute();
    $reminders = $stmt->fetchAll();

    $sent_count = 0;
    $failed_count = 0;

    foreach ($reminders as $reminder) {
        $phone = $reminder['phone'];
        $message = $reminder['message'];
        
        // Send SMS via gateway
        $gateway_url = getenv('SMS_GATEWAY_URL') ?? 'http://localhost:9000/api/sms';
        $payload = json_encode([
            'to' => $phone,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s')
        ]);

        $ch = curl_init($gateway_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code === 200) {
            $update_stmt = $pdo->prepare('
                UPDATE reminders 
                SET status = "sent", sent_at = NOW() 
                WHERE id = ?
            ');
            $update_stmt->execute([$reminder['id']]);
            $sent_count++;
            log_message("Reminder #{$reminder['id']} sent to $phone");
        } else {
            $update_stmt = $pdo->prepare('
                UPDATE reminders 
                SET retry_count = retry_count + 1
                WHERE id = ?
            ');
            $update_stmt->execute([$reminder['id']]);
            $failed_count++;
            log_message("Failed to send reminder #{$reminder['id']} to $phone (HTTP $http_code)");
        }
    }

    log_message("Reminders processed - Sent: $sent_count, Failed: $failed_count");

    // 2. Create appointment reminders for next 24 hours
    log_message("Creating appointment reminders...");
    $stmt = $pdo->prepare('
        SELECT c.id, c.patient_id, c.consultation_date, p.phone, p.name
        FROM consultations c
        JOIN patients p ON c.patient_id = p.id
        WHERE c.status = "scheduled"
        AND c.consultation_date BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 24 HOUR)
        AND NOT EXISTS (
            SELECT 1 FROM reminders r 
            WHERE r.reminder_type = "appointment" 
            AND DATE(r.created_at) = CURDATE()
            AND r.patient_id = c.patient_id
        )
    ');
    $stmt->execute();
    $consultations = $stmt->fetchAll();

    $appt_count = 0;
    foreach ($consultations as $consultation) {
        $appointment_time = new DateTime($consultation['consultation_date']);
        $reminder_msg = "Dear {$consultation['name']}, you have an appointment scheduled for " . 
                       $appointment_time->format('Y-m-d H:i') . 
                       ". Please arrive 15 minutes early. Reply YES to confirm or NO if you need to reschedule.";

        $insert_stmt = $pdo->prepare('
            INSERT INTO reminders 
            (patient_id, reminder_type, title, message, phone_number, scheduled_at, status)
            VALUES (?, "appointment", "Appointment Reminder", ?, ?, ?, "pending")
        ');
        $insert_stmt->execute([
            $consultation['patient_id'],
            $reminder_msg,
            $consultation['phone'],
            $consultation['consultation_date']
        ]);
        $appt_count++;
    }

    log_message("Created $appt_count appointment reminders");

    // 3. Check for patients with 2+ consecutive absences and send reminders
    log_message("Checking for high-absence patients...");
    $stmt = $pdo->prepare('
        SELECT DISTINCT at.patient_id, p.phone, p.name, MAX(at.consecutive_absences) as absences
        FROM attendance_tracking at
        JOIN patients p ON at.patient_id = p.id
        WHERE at.consecutive_absences >= 2
        AND NOT EXISTS (
            SELECT 1 FROM reminders r 
            WHERE r.reminder_type = "absent" 
            AND r.patient_id = at.patient_id
            AND DATE(r.created_at) = CURDATE()
        )
        GROUP BY at.patient_id
    ');
    $stmt->execute();
    $absent_patients = $stmt->fetchAll();

    $absence_reminder_count = 0;
    foreach ($absent_patients as $patient) {
        $reminder_msg = "Dear {$patient['name']}, you have been absent for {$patient['absences']} consecutive appointments. " .
                       "Please contact us immediately if you need any assistance or wish to reschedule. Your health is important to us.";

        $insert_stmt = $pdo->prepare('
            INSERT INTO reminders 
            (patient_id, reminder_type, title, message, phone_number, scheduled_at, status)
            VALUES (?, "absent", ?, ?, ?, NOW(), "pending")
        ');
        $insert_stmt->execute([
            $patient['patient_id'],
            "Absence Alert - {$patient['absences']} Consecutive Absences",
            $reminder_msg,
            $patient['phone']
        ]);
        $absence_reminder_count++;
    }

    log_message("Created $absence_reminder_count absence reminders");

    // 4. Clean up old reminders (older than 90 days)
    log_message("Cleaning up old records...");
    $cleanup_stmt = $pdo->prepare('
        DELETE FROM reminders 
        WHERE status = "sent" 
        AND created_at < DATE_SUB(NOW(), INTERVAL 90 DAY)
    ');
    $cleanup_stmt->execute();
    $deleted = $cleanup_stmt->rowCount();
    log_message("Deleted $deleted old reminder records");

    // 5. Log scheduler run
    log_message("=== SCHEDULER COMPLETED SUCCESSFULLY ===\n");

} catch (Exception $e) {
    log_message("ERROR: " . $e->getMessage());
    error_log("Scheduler Error: " . $e->getMessage());
}
?>
