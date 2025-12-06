# Cashier Role Guide

## Overview
Cashiers handle patient billing, payment processing, and financial transactions in the telemedicine system. They manage fees for consultations, medications, and other services.

## Cashier Credentials
```
Username: cashier
Password: cashier123
```

## Cashier Dashboard Features

### 1. Patient Management
**Location:** Dashboard → Patient Management (left sidebar)

**Capabilities:**
- View all patients in system
- See patient details: Name, Phone, Email, Address, Age, Sex, Village
- Quick access to patient transaction history
- Create new patient records
- Update patient information
- Search for specific patients

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

### 2. Transaction Management
**Location:** Dashboard → Transactions (left sidebar)

**Capabilities:**
- View all transactions you've recorded
- See transaction details: Patient Name, Amount, Type, Description, Reference Number, Date
- Generate daily/weekly/monthly reports
- Track payment status
- View transaction history

**API Endpoint:** `GET /api/cashier.php?action=list_transactions`

**Response Example:**
```json
[
  {
    "id": 1,
    "cashier_id": 2,
    "patient_id": 1,
    "patient_name": "Ahmed Hassan",
    "amount": 50.00,
    "description": "Consultation fee",
    "type": "payment",
    "reference_number": "TRX-20250115143020-ABCD",
    "created_at": "2025-01-15 14:30:20"
  }
]
```

### 3. Daily Summary
**Location:** Dashboard → Daily Summary section

**Shows:**
- Total transactions today
- Total amount collected today
- Number of patients served today
- Outstanding payments count
- Daily revenue summary

**API Endpoint:** `GET /api/cashier.php?action=today_summary`

**Response Example:**
```json
{
  "today_transactions": 5,
  "today_amount": 250.50,
  "total_patients": 1245,
  "outstanding_payments": 12,
  "status": "ok"
}
```

### 4. Record Transaction
**Location:** Dashboard → Record Payment button

**How to Record Payment:**
1. Click "Record Payment" button
2. Select patient from dropdown
3. Enter payment amount
4. Select payment type (payment, refund, charge)
5. Enter description (reason/service)
6. Click "Save Transaction"
7. System generates reference number: TRX-YYYYMMDDHHmmss-XXXX

**Required Fields:**
- Patient: (dropdown selection)
- Amount: (numeric value in currency)
- Type: (payment/refund/charge)
- Description: (what service/item is being charged for)

**Form Submission:**
```
POST /api/cashier.php
Content-Type: application/json

{
  "patient_id": 1,
  "amount": 50.00,
  "type": "payment",
  "description": "Consultation fee for Dr. Ahmed"
}
```

## Cashier Responsibilities

### Payment Collection
1. **Verify Service**
   - Confirm patient received service
   - Check consultation/medication dispensed
   - Get service provider confirmation
   - Verify amount charged

2. **Process Payment**
   - Receive payment method
   - Process payment (cash/card/bank transfer)
   - Confirm payment received
   - Provide receipt to patient

3. **Record Transaction**
   - Enter payment in system immediately
   - Include complete description
   - Record correct amount
   - Generate and keep reference number

4. **Reconciliation**
   - Verify daily totals
   - Reconcile cash drawer
   - Match system records with receipts
   - Report discrepancies

### Financial Management
- Track daily revenue
- Monitor outstanding payments
- Generate financial reports
- Process refunds (if applicable)
- Manage credit/debit accounts

### Patient Interaction
- Professional payment collection
- Clear communication about costs
- Provide itemized receipts
- Answer payment questions
- Maintain confidentiality

### Record Keeping
- Accurate transaction logging
- Complete descriptions
- Timely recording
- Reference number tracking
- Daily reconciliation

## Cashier Workflows

### Workflow 1: Collect Payment for Consultation
```
1. Patient completes consultation with healthcare provider
2. Provider confirms service delivered
3. Check standard consultation fee ($50)
4. Patient provides payment (cash/card)
5. Go to Dashboard → Record Payment
6. Select patient from dropdown
7. Enter amount: 50.00
8. Type: "payment"
9. Description: "Consultation fee"
10. Click Save
11. System generates reference: TRX-20250115143020-ABCD
12. Provide receipt to patient
13. Keep reference number for records
```

### Workflow 2: Collect Payment for Medication
```
1. Patient receives medication from pharmacist
2. Pharmacist confirms dispensed amount
3. Go to Dashboard → Record Payment
4. Select patient
5. Enter medication cost ($25.50)
6. Type: "payment"
7. Description: "Medication - Amlodipine 5mg, 30 days"
8. Save transaction
9. Provide itemized receipt
10. Patient leaves with receipt and medication
```

### Workflow 3: Process Refund
```
1. Patient requests refund (e.g., cancellation, error)
2. Verify reason for refund
3. Get approval from supervisor/admin
4. Go to Dashboard → Record Payment
5. Select patient
6. Enter refund amount (negative)
7. Type: "refund"
8. Description: "Refund - Cancelled consultation"
9. Save transaction
10. Process refund to patient (cash back/card credit)
11. Provide refund receipt
```

### Workflow 4: Daily Reconciliation
```
1. End of day close time
2. Go to Dashboard → Daily Summary
3. Check total transactions recorded
4. Check total amount collected
5. Count physical cash in drawer
6. Match system total to actual cash
7. Investigate any discrepancies
8. Generate daily report
9. Submit report to admin/manager
10. Secure cash and receipts
```

### Workflow 5: Add New Patient
```
1. New patient arrives (not in system)
2. Go to Dashboard → Patient Management
3. Click "Add New Patient"
4. Fill patient information:
   - Full Name
   - Phone Number
   - Email
   - Address
   - Medical History (if known)
   - Sex
   - Age
   - Village
5. Click Save
6. Patient ID created in system
7. Can now record transactions for patient
```

## Common Tasks

### View All Patients
1. Go to Dashboard → Patient Management
2. See list of all registered patients
3. Can search/filter by name
4. Click on patient → See details and history

### Record Consultation Payment
**Standard Fees:**
- Basic Consultation: $50
- Follow-up Consultation: $30
- Emergency Consultation: $75

**Steps:**
1. Confirm patient received consultation
2. Click "Record Payment"
3. Select patient
4. Enter amount per fee schedule
5. Type: "payment"
6. Description: "Consultation with [Provider Name]"
7. Save

### Record Medication Payment
**Process:**
1. Receive dispensed medications from pharmacist
2. Get itemized medication cost
3. Record Payment form
4. Select patient
5. Enter total medication cost
6. Type: "payment"
7. Description: List medications or say "Pharmacy charges"
8. Save

### View Transaction History
1. Go to Dashboard → Transactions
2. See all transactions you've recorded
3. Last 50 transactions displayed
4. Click on transaction for details
5. Reference number visible for each transaction

### Generate Daily Report
1. End of business day
2. Go to Dashboard → Daily Summary
3. Note all key metrics:
   - Transactions today
   - Total revenue today
   - Patients served
   - Outstanding payments
4. Can print/export report (feature coming soon)

### Handle Outstanding Payment
1. Patient receives service but hasn't paid
2. Go to Patient Management
3. Find patient record
4. Note: "Outstanding payment pending"
5. When payment received, record transaction
6. Type: "payment"
7. Description: "Payment for [service]"
8. Reference when recording: "Payment for previous services"

### Process Batch Payments
Multiple patients paying same day:
1. Collect all payments
2. For each patient:
   - Select from dropdown
   - Enter amount
   - Record with description
   - Note reference number
3. At end of batch, verify total
4. Reconcile with cash received

### Resolve Discrepancy
If daily total doesn't match:
1. Check all transactions entered
2. Verify amounts are correct
3. Look for duplicate entries
4. Check for data entry errors
5. Review returned/cancelled transactions
6. Report discrepancy to admin
7. Wait for resolution before closing

## Best Practices

✅ **DO:**
- Record transactions immediately
- Verify patient identification
- Use clear descriptions
- Keep reference numbers
- Reconcile daily
- Maintain organized records
- Be professional with patients
- Double-check amounts
- Report discrepancies promptly
- Keep receipts secure

❌ **DON'T:**
- Delay transaction recording
- Accept payment without verification
- Lose reference numbers
- Leave cash unattended
- Skip daily reconciliation
- Share patient payment info
- Record incorrect amounts
- Forget descriptions
- Mix personal and clinic money
- Lose receipts

## Payment Methods

**Accepted Methods:**
- Cash (count carefully)
- Credit Card (process through terminal)
- Debit Card (process through terminal)
- Mobile Money (if available in your region)
- Bank Transfer (record confirmation)
- Check (if accepted by clinic)

**For Each Method:**
- Verify payment successfully received
- Get confirmation/receipt
- Record in system immediately
- Keep proof of payment
- Note payment method in description

## Financial Controls

### Daily Limits
- No single transaction above $500 (verify first)
- Flag unusual amounts to admin
- Verify large refunds with supervisor

### Access
- Only cashiers can record transactions
- Each transaction linked to your cashier ID
- Admin can audit your transactions
- Maintain accountability

### Security
- Protect login credentials
- Don't share cash drawer key
- Lock cash when leaving
- Secure receipt books
- Backup daily records

## Important Notes

⚠️ **Cash Handling:**
- Count carefully before accepting
- Verify bills are genuine
- Get change correct
- Secure cash in locked drawer
- Never leave unattended

✅ **Transaction Recording:**
- Record immediately
- Complete descriptions
- Accurate amounts
- Reference number generated by system
- No transaction without record

⚠️ **Confidentiality:**
- Patient payment info is sensitive
- Only discuss with authorized staff
- Don't post patient payments publicly
- Secure payment records

## API Reference for Developers

All cashier APIs require:
- Session authentication
- Cashier role verification
- Return 401 for unauthorized access
- Transactions linked to cashier_id

### Get Today's Summary
```
GET /api/cashier.php?action=today_summary
```
Returns: Daily statistics

### List Cashier Transactions
```
GET /api/cashier.php?action=list_transactions
```
Returns: Last 50 transactions for current cashier

### Record New Transaction
```
POST /api/cashier.php
Body: {
  "patient_id": 1,
  "amount": 50.00,
  "type": "payment",
  "description": "Consultation fee"
}
```
Returns: Transaction ID and reference number

## Sample Transaction Descriptions

**Consultation:** "Consultation with Dr. Ahmed - General checkup"
**Medication:** "Pharmacy charges - Amlodipine + Metformin"
**Lab Work:** "Blood test and vitals check"
**Refund:** "Refund - Patient cancelled appointment"
**Service Charge:** "Administrative fee for record request"
**Follow-up:** "Follow-up consultation with healthcare provider"

## Support
For cashier support:
1. Check this guide for common transactions
2. Verify amounts with healthcare providers
3. Ask supervisor for payment method questions
4. Report system errors to admin
5. Contact management for policy questions

---
**Cashier role fully configured and ready!**
