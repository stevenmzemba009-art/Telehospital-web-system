# Healthcare Provider Role Guide

## Overview
Healthcare Providers (Doctors/Medical Staff) manage patient consultations and health records in the telemedicine system.

## Healthcare Provider Credentials
```
Username: healthcare
Password: health123
```

## Healthcare Provider Dashboard Features

### 1. View Consultations
**Location:** Dashboard → Consultations (left sidebar)

**Capabilities:**
- View all consultations assigned to you
- See consultation details: Patient Name, Date, Status, Notes, Diagnosis
- Filter by date range
- Update consultation status (scheduled → completed → cancelled)
- Add/edit diagnosis notes

**API Endpoint:** `GET /api/consultations.php?action=list`

**Response Example:**
```json
[
  {
    "id": 1,
    "patient_id": 1,
    "patient_name": "Ahmed Hassan",
    "provider_id": 3,
    "consultation_date": "2025-01-20 10:00:00",
    "notes": "Patient complains of headache and fever",
    "diagnosis": "Possible flu - recommend rest and fluids",
    "status": "completed",
    "created_at": "2025-01-15 09:30:00"
  }
]
```

### 2. Patient Management
**Location:** Dashboard → Patients (left sidebar)

**Capabilities:**
- View list of all patients
- See patient details: Name, Age, Sex, Phone, Email, Medical History, Village
- Quick access to patient consultation history
- View patient visit records
- Check prescribed medications

**API Endpoint:** `GET /api/patients.php?action=list`

**Response Example:**
```json
[
  {
    "id": 1,
    "name": "Ahmed Hassan",
    "phone": "555-1234",
    "email": "ahmed@example.com",
    "address": "123 Main Street",
    "medical_history": "Diabetes, Hypertension",
    "sex": "M",
    "age": 45,
    "village": "Downtown",
    "created_by": 2,
    "created_at": "2025-01-01 08:00:00"
  }
]
```

### 3. Schedule Consultation
**Location:** Dashboard → Schedule Consultation button

**How to Schedule:**
1. Click "Schedule Consultation" button
2. Select patient from dropdown
3. Select consultation date and time
4. Enter consultation notes (reason for visit)
5. Click "Save Consultation"
6. System will assign to your provider ID

**Required Fields:**
- Patient: (dropdown selection)
- Date: (date picker)
- Time: (time picker)
- Notes: (textarea - reason for consultation)

**Form Submission:**
```
POST /api/consultations.php
Content-Type: application/json

{
  "patient_id": 1,
  "consultation_date": "2025-01-20 10:00:00",
  "notes": "Follow-up consultation for hypertension",
  "diagnosis": ""
}
```

## Healthcare Provider Responsibilities

### Patient Consultations
1. **Schedule Consultations**
   - Review patient availability
   - Pick appropriate time slots
   - Document reason for visit
   - Inform patient of appointment

2. **Conduct Consultations**
   - Review patient medical history
   - Listen to patient complaints
   - Take vital signs
   - Document findings

3. **Diagnose & Prescribe**
   - Document diagnosis
   - Add treatment notes
   - Prescribe medications
   - Request pharmacy follow-up

4. **Follow-up**
   - Schedule follow-up appointments
   - Monitor patient progress
   - Update medical records
   - Coordinate with pharmacy

### Patient Care Quality
- **Document thoroughly** - Clear notes help other providers
- **Update diagnosis** - Add findings after each consultation
- **Mark completion** - Update status when consultation done
- **Add prescriptions** - Request medications if needed

### Communication
- **Patient interaction** - Maintain professional manner
- **Pharmacy coordination** - Alert pharmacist of prescriptions
- **Admin notification** - Report critical findings to admin
- **Record keeping** - Maintain accurate medical records

## Healthcare Provider Workflows

### Workflow 1: New Patient Consultation
```
1. View Patients → Find patient or request patient creation
2. Click on patient → Review medical history
3. Schedule Consultation → Fill in date, time, notes
4. Conduct consultation (in real world or via video call)
5. Update diagnosis field with findings
6. Mark status as "completed"
7. If medications needed → Notify pharmacy
```

### Workflow 2: Follow-up Consultation
```
1. View Consultations → Find previous consultation
2. Check diagnosis from previous visit
3. Schedule new consultation → Reference previous notes
4. Review patient progress
5. Update diagnosis with current findings
6. Adjust treatment if needed
```

### Workflow 3: Emergency Case
```
1. View Consultations → Add urgent consultation
2. Set date/time to immediate
3. Add emergency notes
4. Alert admin if critical
5. Follow up with patient
```

## Common Tasks

### Add New Patient
1. Go to Dashboard → Patients
2. Click "Add Patient" button
3. Fill patient form:
   - Full Name
   - Phone Number
   - Email
   - Address
   - Medical History
   - Sex (M/F)
   - Age
   - Village
4. Click "Save Patient"

**Form Submission:**
```
POST /api/patients.php
Content-Type: application/json

{
  "name": "New Patient Name",
  "phone": "555-9999",
  "email": "newpatient@example.com",
  "address": "Patient Address",
  "medical_history": "Any known conditions",
  "sex": "M",
  "age": 30,
  "village": "Village Name"
}
```

### Update Consultation Diagnosis
1. Find consultation in list
2. Click consultation → Edit button
3. Update diagnosis field
4. Update notes if needed
5. Save changes

**API Request:**
```
PUT /api/consultations.php
Content-Type: application/json

{
  "id": 1,
  "diagnosis": "Hypertension - Recommend antihypertensive medication",
  "notes": "Updated after patient examination"
}
```

### Mark Consultation Complete
1. Find consultation in list
2. Click consultation → Edit
3. Change status to "completed"
4. Add final diagnosis
5. Save

### Request Pharmacy Medication
1. After diagnosis, note medication needed
2. Go to pharmacy page (if available)
3. Create pharmacy task with:
   - Patient ID
   - Medication name
   - Dosage
   - Frequency
   - Duration

**API Request:**
```
POST /api/pharmacy.php
Content-Type: application/json

{
  "patient_id": 1,
  "medication_name": "Amlodipine",
  "dosage": "5mg",
  "frequency": "Once daily",
  "duration": "30 days"
}
```

## Best Practices

✅ **DO:**
- Update patient records after each consultation
- Document all findings clearly
- Schedule timely follow-ups
- Coordinate with pharmacy on medications
- Keep patient confidentiality
- Maintain accurate timestamps

❌ **DON'T:**
- Leave consultations incomplete
- Prescribe without proper diagnosis
- Share patient info without permission
- Delay important follow-ups
- Leave notes unclear or incomplete

## Important Notes

⚠️ **Medical Record Confidentiality:**
- Access only needed patient records
- Do not share patient data with unauthorized users
- Keep login credentials secure
- Log out after each session

✅ **Consultation Documentation:**
- Be specific in diagnosis
- Include relevant medical history
- Note vital signs if applicable
- Document treatment given

## API Reference for Developers

All healthcare provider APIs require:
- Session authentication
- Minimum healthcare role verification
- Return 401 for unauthorized access

### Schedule New Consultation
```
POST /api/consultations.php
Body: {
  "patient_id": 1,
  "consultation_date": "2025-01-20 10:00:00",
  "notes": "Reason for visit",
  "diagnosis": ""
}
```

### Get My Consultations
```
GET /api/consultations.php?action=list&provider_id=3
```

### Update Consultation
```
PUT /api/consultations.php
Body: {
  "id": 1,
  "diagnosis": "...",
  "status": "completed"
}
```

### List All Patients
```
GET /api/patients.php?action=list
```

### Create Patient
```
POST /api/patients.php
Body: {
  "name": "...",
  "phone": "...",
  "age": 30,
  ...
}
```

## Support
For healthcare provider support:
1. Check this guide for common tasks
2. Review patient records for complete history
3. Coordinate with pharmacy for medications
4. Contact admin for system issues
5. Report patient emergencies immediately

---
**Healthcare Provider role fully configured and ready!**
