# PARTNERS MODULE - COMPREHENSIVE TESTING GUIDE

## 📋 TABLE OF CONTENTS
1. [Pre-Testing Setup](#pre-testing-setup)
2. [Database Setup Testing](#database-setup-testing)
3. [Phase 1-2: Admin Backend Testing](#phase-1-2-admin-backend-testing)
4. [Phase 3: Reports & Dashboard Testing](#phase-3-reports-dashboard-testing)
5. [Phase 4: Public Registration Testing](#phase-4-public-registration-testing)
6. [Phase 5: Portal Integration Testing](#phase-5-portal-integration-testing)
7. [Phase 6: Communication Integration Testing](#phase-6-communication-integration-testing)
8. [Integration Testing](#integration-testing)
9. [Security Testing](#security-testing)
10. [Performance Testing](#performance-testing)
11. [Bug Report Template](#bug-report-template)

---

## 🔧 PRE-TESTING SETUP

### 1. Database Installation

```bash
# Step 1: Navigate to phpMyAdmin
# URL: http://localhost/phpmyadmin

# Step 2: Select your database (usually school database)

# Step 3: Import migration files in order
```

**Import Order:**
1. `application/migrations/001_create_partners_tables.php` (Run via Migrate controller)
2. `partner_login_schema.sql` (If exists)
3. `partner_message_templates.sql` (For Phase 6)

**Via CodeIgniter Migrate Controller:**
```
http://localhost/rhemazimbabwe/admin/migrate/version/10
```

**Verify Tables Created:**
```sql
SHOW TABLES LIKE 'partners%';
SHOW TABLES LIKE 'giving_%';
```

**Expected Tables:**
- `partners`
- `partner_contributions`
- `partner_reminders`
- `partner_notes`
- `partner_permissions`
- `partner_permission_types`
- `giving_types`
- `giving_frequencies`

### 2. Seed Test Data

```sql
-- Insert Giving Types
INSERT INTO `giving_types` (`name`, `description`, `is_active`) VALUES
('General Support', 'General school support', 1),
('Scholarship Fund', 'Student scholarship support', 1),
('Building Project', 'Infrastructure development', 1),
('Teacher Support', 'Teacher salaries and training', 1),
('Student Welfare', 'Student meals and welfare', 1);

-- Insert Giving Frequencies
INSERT INTO `giving_frequencies` (`name`, `description`, `interval_type`, `interval_value`, `is_active`) VALUES
('One-Time', 'Single contribution', 'once', 0, 1),
('Weekly', 'Every week', 'weekly', 1, 1),
('Monthly', 'Every month', 'monthly', 1, 1),
('Quarterly', 'Every 3 months', 'quarterly', 3, 1),
('Yearly', 'Once per year', 'yearly', 1, 1);

-- Insert Permission Types
INSERT INTO `partner_permission_types` (`permission_code`, `name`, `description`, `is_active`) VALUES
('library_access', 'Library Access', 'Access to school library', 1),
('courses_access', 'Online Courses', 'Access to online courses', 1),
('gmeet_access', 'Google Meet Access', 'Join scheduled Google Meet sessions', 1),
('zoom_access', 'Zoom Access', 'Join Zoom meetings', 1),
('newsletter', 'Newsletter', 'Receive monthly newsletter', 1);
```

### 3. Check File Permissions

**Windows (XAMPP):**
```bash
# Ensure uploads folder exists and is writable
mkdir uploads\partner_images
mkdir uploads\communicate\email_template_images
```

**Linux:**
```bash
chmod -R 755 uploads/
chmod -R 755 application/cache/
```

### 4. Configure Email Settings

**File:** `application/config/email.php`

Verify SMTP settings are configured for testing emails.

---

## 🗄️ DATABASE SETUP TESTING

### Test 1: Migration Verification

**Test Steps:**
1. Navigate to: `http://localhost/rhemazimbabwe/admin/migrate`
2. Run migration to version 10
3. Check for any errors

**Expected Result:**
- ✅ Success message displayed
- ✅ No errors in migration
- ✅ All tables created

**SQL Verification:**
```sql
-- Check all partner tables exist
SELECT COUNT(*) FROM information_schema.tables
WHERE table_schema = DATABASE()
AND table_name LIKE 'partners%' OR table_name LIKE 'giving_%';
-- Should return 8
```

### Test 2: Seed Data Verification

**SQL Check:**
```sql
SELECT COUNT(*) FROM giving_types; -- Should be 5+
SELECT COUNT(*) FROM giving_frequencies; -- Should be 5+
SELECT COUNT(*) FROM partner_permission_types; -- Should be 5+
```

---

## 👨‍💼 PHASE 1-2: ADMIN BACKEND TESTING

### Test 3: Admin Access Control

**Test Steps:**
1. Login as Super Admin
2. Navigate to: `Partners` menu in sidebar
3. Verify menu appears

**Expected Result:**
- ✅ Partners menu visible
- ✅ Submenu items appear (Manage Partners, Add Partner, etc.)

**Test Non-Admin User:**
1. Login as Teacher (non-admin)
2. Check if Partners menu appears

**Expected Result:**
- ✅ Menu should NOT appear or show "Access Denied"

### Test 4: Add Partner (Admin)

**URL:** `http://localhost/rhemazimbabwe/admin/partners/create`

**Test Case 4.1: Add Individual Partner**

**Input Data:**
```
Account Type: Individual Partner
First Name: John
Last Name: Doe
Email: john.doe@example.com
Phone: +263771234567
Address: 123 Main Street
City: Harare
Country: Zimbabwe
Giving Type: General Support
Giving Frequency: Monthly
Contribution Amount: 50
Currency: USD
Start Date: 2025-01-01
Status: Active
```

**Steps:**
1. Fill all required fields
2. Upload profile image (optional)
3. Click "Submit"

**Expected Result:**
- ✅ Success message: "Partner added successfully"
- ✅ Partner Code auto-generated (e.g., P-2025-0001)
- ✅ Redirected to partners list
- ✅ New partner appears in list
- ✅ Email sent if enabled

**Validation Tests:**
- Empty email → Error message
- Invalid email format → Error message
- Duplicate email → Error message
- Empty required fields → Error messages

**Test Case 4.2: Add Organization Partner**

**Input Data:**
```
Account Type: Organization Partner
Organization Name: ABC Corporation
Organization Type: Company
Contact Person First Name: Jane
Contact Person Last Name: Smith
Email: jane.smith@abccorp.com
Phone: +263772234567
Giving Type: Scholarship Fund
Giving Frequency: Quarterly
Contribution Amount: 500
Currency: USD
```

**Expected Result:**
- ✅ Organization fields enabled
- ✅ Partner code generated with org prefix (e.g., ORG-2025-0001)
- ✅ Organization name saved correctly

### Test 5: View Partners List

**URL:** `http://localhost/rhemazimbabwe/admin/partners`

**Test Steps:**
1. Navigate to partners list
2. Verify all columns display correctly

**Expected Columns:**
- Partner Code
- Name
- Email
- Phone
- Account Type
- Giving Type
- Frequency
- Amount
- Status
- Actions (Edit, Delete, View)

**Test Filters:**
- Filter by Status: Active/Inactive/Suspended
- Filter by Account Type: Individual/Organization
- Filter by Giving Type
- Search by name/email/code

**Expected Result:**
- ✅ All filters work correctly
- ✅ Search returns matching results
- ✅ DataTables features work (sort, pagination)

### Test 6: Edit Partner

**URL:** `http://localhost/rhemazimbabwe/admin/partners/edit/1`

**Test Steps:**
1. Click "Edit" on a partner
2. Modify details (e.g., change phone number)
3. Click "Update"

**Expected Result:**
- ✅ Form pre-filled with existing data
- ✅ Changes saved successfully
- ✅ Updated data reflects in list
- ✅ Partner code remains unchanged

### Test 7: Delete Partner

**Test Steps:**
1. Click "Delete" on a partner
2. Confirm deletion

**Expected Result:**
- ✅ Confirmation dialog appears
- ✅ Partner marked as inactive (soft delete)
- ✅ Partner disappears from active list
- ✅ Can be restored if needed

### Test 8: View Partner Details

**URL:** `http://localhost/rhemazimbabwe/admin/partners/view/1`

**Expected Sections:**
- ✅ Partner Information Card
- ✅ Giving Details
- ✅ Contact Information
- ✅ Contribution History Table
- ✅ Notes Section
- ✅ Reminders Section
- ✅ Permissions Section
- ✅ Action Buttons (Edit, Add Note, Add Reminder)

### Test 9: Add Contribution (Admin)

**URL:** `http://localhost/rhemazimbabwe/admin/partners/addContribution/1`

**Test Case 9.1: Manual Contribution Entry**

**Input Data:**
```
Partner: John Doe (P-2025-0001)
Contribution Date: 2025-01-15
Giving Type: General Support
Amount: 50
Currency: USD
Payment Method: Bank Transfer
Transaction ID: TXN123456789
Status: Completed
Notes: Monthly contribution for January
```

**Steps:**
1. Select partner
2. Fill contribution details
3. Upload proof of payment (optional)
4. Click "Submit"

**Expected Result:**
- ✅ Receipt number auto-generated (e.g., RCT-20250115-0001)
- ✅ Contribution saved successfully
- ✅ Receipt generated automatically
- ✅ Email sent to partner with receipt
- ✅ Contribution appears in partner's history

**Test Case 9.2: Pending Contribution**

**Input Data:**
```
Status: Pending
```

**Expected Result:**
- ✅ Saved as pending
- ✅ Admin can approve later
- ✅ No receipt generated until approved

### Test 10: Manage Giving Types

**URL:** `http://localhost/rhemazimbabwe/admin/partners/givingTypes`

**Test Steps:**
1. Add new giving type
2. Edit existing type
3. Deactivate a type

**Expected Result:**
- ✅ CRUD operations work correctly
- ✅ Inactive types don't appear in dropdowns
- ✅ Can't delete type if in use

### Test 11: Manage Giving Frequencies

**URL:** `http://localhost/rhemazimbabwe/admin/partners/givingFrequencies`

**Test Steps:**
1. Add custom frequency (e.g., Bi-Weekly)
2. Set interval type and value
3. Test in partner creation

**Expected Result:**
- ✅ Custom frequency created
- ✅ Appears in frequency dropdown
- ✅ Reminder calculation works with custom frequency

---

## 📊 PHASE 3: REPORTS & DASHBOARD TESTING

### Test 12: Partner Dashboard

**URL:** `http://localhost/rhemazimbabwe/admin/partners/dashboard`

**Expected Widgets:**
- ✅ Total Partners (with active count)
- ✅ Total Contributions (this month)
- ✅ Active Partners Count
- ✅ Pending Approvals Count
- ✅ Monthly Contribution Chart
- ✅ Top Contributors List
- ✅ Recent Activities

**Test Data Accuracy:**
1. Add 5 partners
2. Add 10 contributions
3. Refresh dashboard
4. Verify counts match

### Test 13: Contribution Reports

**URL:** `http://localhost/rhemazimbabwe/admin/partnerreports/contributions`

**Test Filters:**
- Date Range: 2025-01-01 to 2025-12-31
- Partner: Select specific partner
- Giving Type: General Support
- Status: Completed

**Expected Result:**
- ✅ Filtered results display correctly
- ✅ Total amounts calculated correctly
- ✅ Export to Excel works
- ✅ Export to PDF works
- ✅ Print view formatted correctly

### Test 14: Partner List Report

**URL:** `http://localhost/rhemazimbabwe/admin/partnerreports/partnersList`

**Test Filters:**
- Status: Active
- Account Type: Individual
- Giving Frequency: Monthly

**Expected Result:**
- ✅ Filtered list shows correct partners
- ✅ Export functionality works
- ✅ Report shows all required fields

### Test 15: Financial Summary Report

**URL:** `http://localhost/rhemazimbabwe/admin/partnerreports/financialSummary`

**Expected Data:**
- ✅ Total contributions by month
- ✅ Breakdown by giving type
- ✅ Breakdown by payment method
- ✅ Year-over-year comparison
- ✅ Currency conversion (if multiple currencies)

---

## 🌐 PHASE 4: PUBLIC REGISTRATION TESTING

### Test 16: Public Registration Form

**URL:** `http://localhost/rhemazimbabwe/partnerregistration`

**Test Case 16.1: Individual Registration**

**Input Data:**
```
Account Type: Individual Partner
First Name: Sarah
Last Name: Johnson
Email: sarah.johnson@example.com
Phone: +263773334444
Address: 456 Oak Avenue
City: Bulawayo
Country: Zimbabwe
Giving Type: Student Welfare
Giving Frequency: Monthly
Contribution Amount: 30
Currency: USD
```

**Steps:**
1. Fill registration form
2. Solve CAPTCHA
3. Click "Register"

**Expected Result:**
- ✅ Form validation works
- ✅ CAPTCHA validation required
- ✅ Partner created with status="inactive"
- ✅ Confirmation message displayed
- ✅ Confirmation email sent
- ✅ Redirected to thank you page

**Test Case 16.2: Organization Registration**

**Input Data:**
```
Account Type: Organization
Organization Name: Community Foundation
Organization Type: Foundation
Contact Person: Michael Brown
Email: michael@foundation.org
Giving Type: Building Project
Frequency: Yearly
Amount: 5000
```

**Expected Result:**
- ✅ Organization fields enabled/required
- ✅ Registration successful
- ✅ Pending admin approval

### Test 17: Registration Email Verification

**Test Steps:**
1. Register new partner
2. Check email inbox
3. Verify email received

**Expected Email Content:**
- ✅ Welcome message
- ✅ Partner code displayed
- ✅ Registration details
- ✅ Next steps (pending approval)
- ✅ School contact info
- ✅ Professional HTML design

### Test 18: Admin Approval Process

**Test Steps:**
1. Login as admin
2. Navigate to Partners > Pending Approvals
3. Review new registration
4. Approve partner

**Expected Result:**
- ✅ Pending list shows new partner
- ✅ Can view full details
- ✅ Approve button available
- ✅ Status changes to "active"
- ✅ Activation email sent to partner

---

## 👤 PHASE 5: PORTAL INTEGRATION TESTING

### Test 19: Student Portal Access

**Login as Student:**
1. URL: `http://localhost/rhemazimbabwe/site/userlogin`
2. Login with student credentials

**Test Steps:**
1. Check if "Partners" menu appears in sidebar
2. Click on Partners menu

**URL:** `http://localhost/rhemazimbabwe/user/partner`

**Expected Result:**
- ✅ Partners menu visible in sidebar
- ✅ Dashboard loads without errors

### Test 20: Partner Dashboard (Portal)

**Test Case 20.1: No Partners State**

**Expected Display:**
- ✅ "Become a Partner" invitation screen
- ✅ Partnership benefits listed
- ✅ "Register as Partner" button
- ✅ Beautiful design

**Test Case 20.2: With Partners State**

**Expected Display:**
- ✅ Statistics boxes (Total Partners, Active, Contributed, Year Total)
- ✅ Partner records table
- ✅ Quick actions panel
- ✅ Action buttons (View Contributions, Settings)

### Test 21: Register as Partner (Portal)

**URL:** `http://localhost/rhemazimbabwe/user/partner/register`

**Test Steps:**
1. Click "Register as Partner"
2. Verify form pre-filled with student data

**Expected Pre-filled Data:**
- ✅ First Name (from student record)
- ✅ Last Name (from student record)
- ✅ Email (from student record)
- ✅ Phone (from student record)
- ✅ Address (from student record)
- ✅ Student ID (auto-linked, hidden)

**Complete Registration:**
1. Fill remaining fields (Giving Type, Frequency, Amount)
2. Submit form

**Expected Result:**
- ✅ AJAX submission successful
- ✅ Partner created and linked to student_id
- ✅ Status set to "inactive"
- ✅ Confirmation email sent
- ✅ Redirected to dashboard
- ✅ New partner appears in list

### Test 22: Parent Portal Access

**Login as Parent:**
1. Login with parent credentials
2. Navigate to Partners

**Expected Result:**
- ✅ Can register as partner
- ✅ Form pre-filled with guardian info
- ✅ Partner linked to parent record (by email/phone)

### Test 23: Staff Portal Access

**Login as Staff:**
1. Login with staff credentials
2. Navigate to Partners

**Expected Result:**
- ✅ Can register as partner
- ✅ Form pre-filled with staff info
- ✅ Partner linked to staff_id

### Test 24: Giving Settings (Portal)

**URL:** `http://localhost/rhemazimbabwe/user/partner/settings?partner_id=1`

**Test Steps:**
1. Navigate to settings for a partner
2. Modify giving frequency (Monthly → Quarterly)
3. Update contribution amount (50 → 100)
4. Click "Save Changes"

**Expected Result:**
- ✅ Form pre-filled with current settings
- ✅ AJAX save successful
- ✅ Success message displayed
- ✅ Changes reflected immediately
- ✅ Dashboard updated with new amount

### Test 25: Contribution History (Portal)

**URL:** `http://localhost/rhemazimbabwe/user/partner/contributions?partner_id=1`

**Expected Display:**
- ✅ Summary cards (Total, Transactions, Status)
- ✅ Contributions table with DataTables
- ✅ Receipt number, date, amount, status
- ✅ Action buttons (Print, Download)
- ✅ Export options (Excel, PDF, Print)

**Test Filters:**
1. Search by receipt number
2. Sort by date
3. Filter by status

**Expected Result:**
- ✅ All DataTables features work
- ✅ Can export to Excel
- ✅ Can export to PDF

### Test 26: Receipt Generation (Portal)

**URL:** `http://localhost/rhemazimbabwe/user/partner/printReceipt/1?partner_id=1`

**Test Steps:**
1. Click "Print Receipt" on a contribution
2. Verify receipt opens in new window

**Expected Receipt Content:**
- ✅ School logo and name
- ✅ Receipt number (prominent)
- ✅ Partner information
- ✅ Contribution details
- ✅ Amount in numbers and words
- ✅ Payment method
- ✅ Transaction ID
- ✅ Status badge
- ✅ Thank you message
- ✅ Computer-generated footer
- ✅ Print button works

### Test 27: Security Testing (Portal)

**Test Case 27.1: Ownership Verification**

**Test Steps:**
1. Login as Student A (has partner_id=1)
2. Try to access: `user/partner/contributions?partner_id=2` (belongs to Student B)

**Expected Result:**
- ✅ Access denied or redirected
- ✅ Error message: "You don't have permission"

**Test Case 27.2: Unauthenticated Access**

**Test Steps:**
1. Logout
2. Try to access: `http://localhost/rhemazimbabwe/user/partner`

**Expected Result:**
- ✅ Redirected to login page
- ✅ Session required message

---

## 📧 PHASE 6: COMMUNICATION INTEGRATION TESTING

### Test 28: Install Message Templates

**Test Steps:**
1. Navigate to phpMyAdmin
2. Select database
3. Execute `partner_message_templates.sql`

**Verification:**
```sql
SELECT * FROM email_template WHERE template_type = 'partner';
SELECT * FROM sms_template WHERE template_type = 'partner';
```

**Expected Result:**
- ✅ 4 email templates inserted
- ✅ 4 SMS templates inserted
- ✅ No SQL errors

### Test 29: Partner Search in Communication Module

**URL:** `http://localhost/rhemazimbabwe/admin/mailsms/compose`

**Test Steps:**
1. Navigate to Compose Email
2. In "Send Message To" dropdown, select "Partner"
3. Search for partner by name

**Expected Result:**
- ✅ "Partner" option appears in dropdown
- ✅ Search field appears
- ✅ Typing shows matching partners
- ✅ Partners displayed with code: "John Doe (P-2025-0001)"
- ✅ Can select multiple partners

### Test 30: Send Email to Partner (Manual)

**URL:** `http://localhost/rhemazimbabwe/admin/mailsms/compose`

**Test Steps:**
1. Select "Partner" category
2. Search and select partner: John Doe
3. Select template: "Partner Contribution Thank You"
4. Review message (variables should be replaced)
5. Click "Send Message"

**Expected Result:**
- ✅ Template loads correctly
- ✅ Variables replaced with partner data
- ✅ Email sent successfully
- ✅ Logged in messages table
- ✅ Partner receives email

**Test Variable Replacement:**

Template contains:
```
Dear {partner_firstname} {partner_lastname},
Thank you for your contribution of {currency} {contribution_amount}.
Receipt Number: {receipt_number}
```

Should become:
```
Dear John Doe,
Thank you for your contribution of USD 50.
Receipt Number: RCT-20250115-0001
```

### Test 31: Send SMS to Partner (Manual)

**URL:** `http://localhost/rhemazimbabwe/admin/mailsms/compose_sms`

**Test Steps:**
1. Select "Partner" category
2. Select partner
3. Choose SMS template: "Partner Contribution Thank You SMS"
4. Send SMS

**Expected Result:**
- ✅ SMS template loads
- ✅ Character count shown
- ✅ SMS sent via gateway
- ✅ Logged in messages table

### Test 32: Bulk Communication

**Test Steps:**
1. Select "Partner" category
2. Select multiple partners (5+)
3. Choose email template
4. Send bulk email

**Expected Result:**
- ✅ All partners receive email
- ✅ Each email personalized
- ✅ All logged separately

### Test 33: Scheduled Messages

**Test Steps:**
1. Compose message to partner
2. Select "Schedule" option
3. Set date/time: Tomorrow 10:00 AM
4. Submit

**Expected Result:**
- ✅ Message saved with schedule
- ✅ Status: Scheduled
- ✅ Appears in scheduled messages list
- ✅ Will send at specified time (requires cron)

### Test 34: Automated Reminder Generation

**Test Method: Direct PHP Call (Since cron not yet implemented)**

**Create Test File:** `test_reminders.php` in root
```php
<?php
require_once 'index.php';

$CI =& get_instance();
$CI->load->model('reminder_model');

// Generate reminders
echo "<h2>Generating Reminders...</h2>";
$stats = $CI->reminder_model->generateContributionReminders();
echo "<pre>";
print_r($stats);
echo "</pre>";

// Process reminders
echo "<h2>Processing Reminders...</h2>";
$send_stats = $CI->reminder_model->processDueReminders();
echo "<pre>";
print_r($send_stats);
echo "</pre>";
?>
```

**Access:** `http://localhost/rhemazimbabwe/test_reminders.php`

**Expected Output:**
```
Generating Reminders...
Array
(
    [total_checked] => 5
    [reminders_created] => 3
    [errors] => Array()
)

Processing Reminders...
Array
(
    [total_processed] => 3
    [emails_sent] => 3
    [sms_sent] => 3
    [failed] => 0
    [errors] => Array()
)
```

**Verification:**
```sql
SELECT * FROM partner_reminders WHERE status = 'sent';
```

### Test 35: Reminder Calculation Logic

**Test Scenario:**
- Partner: John Doe
- Frequency: Monthly
- Start Date: 2025-01-01
- Last Contribution: 2025-01-01

**Expected:**
- Next contribution due: 2025-02-01
- Reminder date: 2025-01-29 (3 days before)

**Verification:**
```sql
SELECT partner_id, reminder_date, reminder_type, message
FROM partner_reminders
WHERE partner_id = 1;
```

---

## 🔗 INTEGRATION TESTING

### Test 36: End-to-End Partner Lifecycle

**Scenario: Complete Partner Journey**

1. **Public Registration:**
   - Sarah registers via public form
   - Status: Inactive
   - Email sent

2. **Admin Approval:**
   - Admin reviews Sarah's registration
   - Admin approves
   - Status: Active
   - Activation email sent

3. **First Contribution:**
   - Admin records Sarah's first contribution
   - Receipt generated
   - Thank you email sent

4. **Portal Access:**
   - Sarah logs into student portal
   - Views her partner profile
   - Downloads receipt

5. **Settings Update:**
   - Sarah changes frequency to Quarterly
   - Amount updated to $75

6. **Reminder System:**
   - System calculates next contribution date
   - Reminder created 3 days before
   - Email/SMS sent on reminder date

7. **Second Contribution:**
   - Sarah makes online payment
   - Admin records contribution
   - Receipt auto-generated
   - Thank you message sent

**All Steps Should Work Seamlessly:**
- ✅ No errors at any stage
- ✅ All emails sent
- ✅ Data consistent across admin/portal
- ✅ Reminders generated correctly

### Test 37: Multi-Role Integration

**Test Scenario:**
- Student registers as partner
- Parent also registers as partner (for same student)
- Staff member registers as partner

**Expected Result:**
- ✅ All three can register independently
- ✅ Each has separate partner record
- ✅ Each can access own portal
- ✅ No conflicts in data
- ✅ Student sees only their partnership
- ✅ Parent sees only their partnership

### Test 38: Contribution Workflow

**Test Full Contribution Flow:**

1. **Admin Records Contribution:**
   ```
   Partner: John Doe
   Amount: $50
   Method: Bank Transfer
   Status: Pending
   ```

2. **Verify Pending State:**
   - ✅ No receipt generated yet
   - ✅ Not counted in totals
   - ✅ Admin can see pending status

3. **Admin Approves:**
   - ✅ Status changed to Completed
   - ✅ Receipt auto-generated
   - ✅ Email sent to partner
   - ✅ Added to totals

4. **Partner Views in Portal:**
   - ✅ Contribution appears in history
   - ✅ Can download receipt
   - ✅ Total contributions updated

---

## 🔒 SECURITY TESTING

### Test 39: SQL Injection Prevention

**Test Inputs:**
```
Email: ' OR '1'='1
Name: '; DROP TABLE partners; --
Partner Code: <script>alert('XSS')</script>
```

**Expected Result:**
- ✅ All inputs sanitized
- ✅ No SQL errors
- ✅ No scripts executed
- ✅ Data saved safely

### Test 40: XSS Prevention

**Test Steps:**
1. Enter in partner name: `<script>alert('XSS')</script>`
2. Save partner
3. View partner list

**Expected Result:**
- ✅ Script not executed
- ✅ Displayed as plain text
- ✅ HTML entities escaped

### Test 41: CSRF Protection

**Test Steps:**
1. View page source of add partner form
2. Copy form and create external HTML file
3. Try to submit from external file

**Expected Result:**
- ✅ Submission blocked
- ✅ CSRF token mismatch error
- ✅ Request rejected

### Test 42: Access Control

**Test Matrix:**

| Role | Partners Menu | Add Partner | Edit Partner | Delete Partner | View Reports |
|------|--------------|-------------|--------------|----------------|--------------|
| Super Admin | ✅ | ✅ | ✅ | ✅ | ✅ |
| Admin | ✅ | ✅ | ✅ | ✅ | ✅ |
| Teacher | ❌ | ❌ | ❌ | ❌ | ❌ |
| Accountant | ✅ | ❌ | ❌ | ❌ | ✅ |
| Student | Portal Only | Portal Reg | Portal Settings | ❌ | ❌ |

Test each role to ensure correct access.

### Test 43: File Upload Security

**Test Steps:**
1. Try to upload PHP file as partner image
2. Try to upload .exe file
3. Try to upload oversized file (>10MB)

**Expected Result:**
- ✅ Only image formats allowed (jpg, png, gif)
- ✅ File size limit enforced
- ✅ File renamed on upload
- ✅ Stored in correct directory

---

## ⚡ PERFORMANCE TESTING

### Test 44: Load Testing

**Simulate Multiple Users:**

**Test Scenario:**
- 100 partners in database
- 1000 contributions
- 50 concurrent users

**Test Pages:**
1. Partner list page load time
2. Dashboard widget load time
3. Reports generation time

**Expected Performance:**
- ✅ List loads in < 2 seconds
- ✅ Dashboard loads in < 3 seconds
- ✅ Reports generate in < 5 seconds

### Test 45: Database Queries Optimization

**Check Query Count:**

Enable CodeIgniter profiler:
```php
$this->output->enable_profiler(TRUE);
```

**Acceptable Limits:**
- Partners list: < 10 queries
- Dashboard: < 15 queries
- Reports: < 20 queries

### Test 46: Large Dataset Testing

**Create Test Data:**
```sql
-- Insert 1000 partners
-- Insert 10000 contributions
```

**Test Operations:**
- ✅ Search performance
- ✅ Pagination works
- ✅ Export functionality
- ✅ No timeouts

---

## 🐛 BUG REPORT TEMPLATE

```markdown
### Bug Report

**Bug ID:** BUG-001
**Date Reported:** 2025-01-15
**Reporter:** [Your Name]
**Severity:** High/Medium/Low
**Status:** Open/In Progress/Resolved

**Module:** Partners > Add Contribution

**Description:**
Receipt number not generating automatically when adding contribution.

**Steps to Reproduce:**
1. Navigate to admin/partners/addContribution/1
2. Fill all required fields
3. Leave receipt number blank
4. Submit form

**Expected Result:**
Receipt number should auto-generate (e.g., RCT-20250115-0001)

**Actual Result:**
Form validation error: "Receipt number is required"

**Error Message:**
[Paste error message or screenshot]

**Browser:** Chrome 120
**OS:** Windows 11
**Database:** MySQL 8.0

**Additional Notes:**
This worked in Phase 2 testing but broke after recent changes.

**Screenshots:**
[Attach if applicable]
```

---

## ✅ TESTING CHECKLIST

### Phase 1-2: Admin Backend
- [ ] Migration runs successfully
- [ ] All tables created
- [ ] Seed data inserted
- [ ] Add partner works (Individual)
- [ ] Add partner works (Organization)
- [ ] Edit partner works
- [ ] Delete partner works (soft delete)
- [ ] View partner details works
- [ ] Add contribution works
- [ ] Giving types CRUD works
- [ ] Giving frequencies CRUD works

### Phase 3: Reports & Dashboard
- [ ] Dashboard widgets display correctly
- [ ] Statistics accurate
- [ ] Charts render properly
- [ ] Contribution report works
- [ ] Partner list report works
- [ ] Financial summary accurate
- [ ] Export to Excel works
- [ ] Export to PDF works

### Phase 4: Public Registration
- [ ] Public form accessible
- [ ] CAPTCHA validation works
- [ ] Form validation works
- [ ] Individual registration works
- [ ] Organization registration works
- [ ] Confirmation email sent
- [ ] Thank you page displays
- [ ] Partner created as inactive

### Phase 5: Portal Integration
- [ ] Student portal menu appears
- [ ] Parent portal menu appears
- [ ] Staff portal menu appears
- [ ] Dashboard shows correct state (no partners/with partners)
- [ ] Registration form pre-fills data
- [ ] Auto-linking to student/staff works
- [ ] Settings page works
- [ ] Contribution history displays
- [ ] Receipt generation works
- [ ] Security: ownership verification works

### Phase 6: Communication
- [ ] Message templates installed
- [ ] Partner category appears in Mailsms
- [ ] Partner search works
- [ ] Send email to partner works
- [ ] Send SMS to partner works
- [ ] Template variables replaced correctly
- [ ] Bulk messaging works
- [ ] Scheduled messages work
- [ ] Reminder generation works (manual test)
- [ ] Reminder processing works (manual test)

### Security
- [ ] SQL injection prevented
- [ ] XSS attacks prevented
- [ ] CSRF protection active
- [ ] Access control enforced
- [ ] File upload validation works
- [ ] Session management secure

### Performance
- [ ] Pages load within acceptable time
- [ ] Database queries optimized
- [ ] Large dataset handling works
- [ ] No memory leaks
- [ ] Export functions don't timeout

---

## 📊 TESTING METRICS

**Track Testing Progress:**

| Module | Tests Passed | Tests Failed | % Complete |
|--------|-------------|--------------|-----------|
| Phase 1-2 | 0/11 | 0 | 0% |
| Phase 3 | 0/4 | 0 | 0% |
| Phase 4 | 0/3 | 0 | 0% |
| Phase 5 | 0/9 | 0 | 0% |
| Phase 6 | 0/8 | 0 | 0% |
| Security | 0/5 | 0 | 0% |
| Performance | 0/3 | 0 | 0% |
| **TOTAL** | **0/43** | **0** | **0%** |

---

## 🎯 FINAL ACCEPTANCE CRITERIA

**System Ready for Production When:**

- ✅ All 43 tests pass
- ✅ No critical bugs
- ✅ Performance benchmarks met
- ✅ Security audit passed
- ✅ Documentation complete
- ✅ Training materials ready
- ✅ Backup/restore tested
- ✅ Cron jobs configured
- ✅ Email/SMS gateway tested
- ✅ User acceptance testing complete

---

**Last Updated:** 2025-01-15
**Version:** 1.0
**Status:** Ready for Testing
