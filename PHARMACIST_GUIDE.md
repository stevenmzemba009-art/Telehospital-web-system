# Pharmacist Role Guide

## Overview
Pharmacists manage medication dispensing, pharmacy tasks, and ensure patients receive correct medications as prescribed by healthcare providers.

## Pharmacist Credentials
```
Username: pharmacist
Password: pharm123
```

## Pharmacist Dashboard Features

### 1. Pharmacy Tasks
**Location:** Dashboard → Pharmacy Tasks (left sidebar)

**Capabilities:**
- View all medication requests
- See task details: Patient Name, Medication, Dosage, Frequency, Duration, Status
- Filter tasks by status (pending, completed)
- Mark tasks as completed after dispensing
- Track dispensing history

**API Endpoint:** `GET /api/pharmacy.php?action=list`

**Response Example:**
```json
[
  {
    "id": 1,
    "patient_id": 1,
    "patient_name": "Ahmed Hassan",
    "medication_name": "Amlodipine",
    "dosage": "5mg",
    "frequency": "Once daily",
    "duration": "30 days",
    "status": "pending",
    "requested_by": "Healthcare Provider",
    "completed_by": null,
    "created_at": "2025-01-15 14:20:00"
  }
]
```

### 2. Pending Tasks Count
**Location:** Dashboard → Pending Tasks indicator

**Shows:**
- Number of pending medication requests
- Count of completed tasks today
- Alert when new tasks arrive

**API Endpoint:** `GET /api/pharmacy.php?action=pending_count`

**Response Example:**
```json
{
  "pending": 3,
  "completed_today": 5,
  "total": 8
}
```

### 3. Mark Medication as Dispensed
**Location:** Pharmacy Tasks → Task row → Mark Complete button

**How to Complete:**
1. Click "Mark Complete" button on pending task
2. Verify patient details
3. Verify medication and dosage
4. Confirm dispensing amount
5. Click "Confirm Dispensed"
6. System records completion with your pharmacist ID

**Form Submission:**
```
PUT /api/pharmacy.php
Content-Type: application/json

{
  "id": 1,
  "status": "completed"
}
```

## Pharmacist Responsibilities

### Medication Management
1. **Verify Prescriptions**
   - Check healthcare provider order
   - Verify patient identity
   - Confirm medication and dosage
   - Check for contraindications

2. **Dispense Medications**
   - Count correct quantity
   - Label medication properly
   - Add instructions
   - Package securely

3. **Patient Education**
   - Explain how to take medication
   - Discuss side effects
   - Answer patient questions
   - Provide written instructions

4. **Record Keeping**
   - Log each dispensing
   - Track inventory
   - Update system status
   - Maintain records

### Quality Assurance
- Verify medication authenticity
- Check expiration dates
- Monitor medication storage
- Report expired medications
- Ensure proper inventory levels

### Safety
- Double-check medications before dispensing
- Verify patient identity
- Check for drug interactions
- Follow safety protocols
- Report adverse events

## Pharmacist Workflows

### Workflow 1: Dispense Standard Medication
```
1. View Pharmacy Tasks → See pending requests
2. Find medication in inventory
3. Verify patient information
4. Check medication name, dosage, amount
5. Prepare medication with label
6. Give to patient with instructions
7. Click "Mark Complete" in system
8. Record completion time
```

### Workflow 2: Handle Out-of-Stock Medication
```
1. View pending medication request
2. Check inventory → Medication not in stock
3. Notify healthcare provider of unavailability
4. Mark task as "pending" (don't complete)
5. Suggest alternative medication if available
6. Update request when stock arrives
```

### Workflow 3: Patient Question About Medication
```
1. Listen to patient concern
2. Review medication details
3. Explain usage, side effects, warnings
4. Provide patient education materials
5. Record interaction notes
6. Alert healthcare provider if serious concern
```

### Workflow 4: Inventory Management
```
1. Track dispensed medications daily
2. Update inventory count
3. Order new stock when low
4. Receive and verify new medications
5. Check expiration dates
6. Rotate old stock forward (FIFO)
```

## Common Tasks

### View All Pending Medications
1. Go to Dashboard → Pharmacy Tasks
2. See list of all pending requests
3. Medications sorted by date received
4. Note urgent/priority requests

**Check pending count:**
```
GET /api/pharmacy.php?action=pending_count
```

### Complete Medication Dispensing
1. Locate medication in inventory
2. Verify patient (check ID or name)
3. Count correct quantity
4. Label medication properly
5. Find task in Pharmacy Tasks
6. Click "Mark Complete"
7. System records: date, time, pharmacist ID
8. Medication moved to "completed"

**API Call:**
```
PUT /api/pharmacy.php
Content-Type: application/json

{
  "id": 1,
  "status": "completed"
}
```

### Dispense Multiple Medications
1. Healthcare provider may request multiple medications
2. Check each one carefully:
   - Patient name matches
   - Medication name correct
   - Dosage as prescribed
   - Quantity correct
3. Mark each as completed individually
4. Provide patient with all instructions

### Handle Special Requests
1. Controlled substances - follow legal requirements
2. Refrigerated medications - ensure proper storage
3. High-cost medications - verify insurance/payment
4. Rare medications - coordinate with provider

### Document Drug Interaction Warning
If medication has potential interactions:
1. Inform patient of concerns
2. Contact healthcare provider
3. Wait for provider approval before dispensing
4. Document the interaction and approval

### End-of-Day Report
1. Count total medications dispensed today
2. Check pending medications remaining
3. Verify inventory levels
4. Note medications needed
5. Report any unusual activity

## Best Practices

✅ **DO:**
- Always verify patient identity
- Double-check medications before dispensing
- Read prescriptions carefully
- Maintain accurate records
- Keep pharmacy organized and clean
- Update system status immediately
- Communicate with healthcare providers
- Follow all safety protocols
- Provide clear patient instructions

❌ **DON'T:**
- Dispense without verification
- Rush medication preparation
- Ignore expiration dates
- Skip documentation
- Leave pharmacy unattended
- Share patient information
- Dispense without proper labeling
- Ignore drug interactions

## Important Notes

⚠️ **Patient Safety First:**
- Verify every detail before dispensing
- Ask patients if they have questions
- Check for allergies or contraindications
- Keep medications secure
- Report any adverse reactions

✅ **Legal/Regulatory:**
- Follow local medication laws
- Maintain required documentation
- Report controlled substance handling
- Keep records for compliance
- Follow storage requirements

## Medication Tracking

### Pending Medications
- System shows total pending count
- Medications sorted by date received
- Urgent items marked if applicable
- New requests notify pharmacist

### Completed Medications
- Record shows who dispensed (your ID)
- Date and time of dispensing
- Patient received confirmation
- Available for audit/compliance

### Inventory Management
- Track dispensed quantities
- Monitor stock levels
- Order replacements when low
- Verify received shipments
- Check expiration regularly

## Common Medications (Example)

**Hypertension:**
- Amlodipine 5mg - once daily
- Lisinopril 10mg - once daily
- Metoprolol 50mg - twice daily

**Diabetes:**
- Metformin 500mg - twice daily
- Glipizide 5mg - before meals
- Insulin - as prescribed

**Infections:**
- Amoxicillin 500mg - 3x daily
- Azithromycin 250mg - once daily
- Ciprofloxacin 500mg - twice daily

**Pain/Fever:**
- Ibuprofen 400mg - as needed
- Paracetamol 500mg - 3-4x daily
- Aspirin 325mg - once daily

## API Reference for Developers

All pharmacist APIs require:
- Session authentication
- Pharmacist role verification
- Return 401 for unauthorized access

### Get All Pharmacy Tasks
```
GET /api/pharmacy.php?action=list
```
Returns: Array of pharmacy task objects

### Get Task Counts
```
GET /api/pharmacy.php?action=pending_count
```
Returns: Pending and completed counts

### Mark Task Completed
```
PUT /api/pharmacy.php
Body: {
  "id": 1,
  "status": "completed"
}
```

### Get Total Task Count
```
GET /api/pharmacy.php?action=count
```
Returns: `{ "count": 8 }`

## Support
For pharmacist support:
1. Check this guide for medication info
2. Verify prescriptions with healthcare providers
3. Check inventory for medication availability
4. Report system issues to admin
5. Contact supervisor for legal/compliance questions

---
**Pharmacist role fully configured and ready!**
