# Comprehensive Partner Module Testing Guide

## Table of Contents
1. [Prerequisites](#prerequisites)
2. [Part 1: Self-Registration Testing (Frontend)](#part-1-self-registration-testing-frontend)
3. [Part 2: User Portal Registration (Student/Parent)](#part-2-user-portal-registration-studentparent)
4. [Part 3: Staff Registration](#part-3-staff-registration)
5. [Part 4: Admin Partner Management](#part-4-admin-partner-management)
6. [Part 5: Partner Portal Testing](#part-5-partner-portal-testing)
7. [Part 6: Contribution Management](#part-6-contribution-management)
8. [Part 7: User Portal Partner Management](#part-7-user-portal-partner-management)
9. [Part 8: Reports and Analytics](#part-8-reports-and-analytics)
10. [Testing Checklist](#testing-checklist)

---

## Prerequisites

### Required Setup
1. XAMPP running (Apache and MySQL)
2. Database `ssdb` exists with all partner tables
3. Admin account with partner permissions
4. At least one student account
5. At least one staff account
6. Test email for notifications (optional)

### Test Data Required
- Valid email addresses
- Phone numbers
- Address information
- Test contribution amounts

### URLs to Bookmark
```
Frontend Registration: http://localhost/rhemazimbabwe/partner_registration
Partner Portal Login: http://localhost/rhemazimbabwe/partnerportal
Admin Login: http://localhost/rhemazimbabwe/admin
Student Login: http://localhost/rhemazimbabwe/site/userlogin
```

---

## Part 1: Self-Registration Testing (Frontend)

### Test 1.1: Access Registration Page
**Objective:** Verify public can access partner registration

**Steps:**
1. Open browser (incognito mode recommended)
2. Navigate to: `http://localhost/rhemazimbabwe/partner_registration`
3. Verify page loads without errors
4. Check for registration options: Individual and Organization

**Expected Results:**
- Registration page displays
- Both registration types are visible
- Form fields load correctly
- No PHP errors

**Test Data:** None required

---

### Test 1.2: Individual Self-Registration (Without Account Creation)
**Objective:** Register as individual partner without creating login account

**Steps:**
1. Go to: `http://localhost/rhemazimbabwe/partner_registration`
2. Click "Register as Individual" button
3. Fill in form:
   ```
   First Name: John
   Last Name: Doe
   Email: john.doe@test.com
   Phone: +1234567890
   Billing Address: 123 Test Street
   City: Harare
   State: Harare Province
   Country: Zimbabwe
   Zip Code: 12345

   Giving Types: Select "General Fund" (check checkbox)
   Amount for General Fund: 100
   Giving Frequency: Monthly
   Currency: USD
   Total Amount: 100

   Notes: Test registration without account

   Create Account: Leave UNCHECKED
   ```
4. Click "Submit Registration"
5. Wait for redirect

**Expected Results:**
- Form validates successfully
- Redirects to success page
- Success message displays
- Partner code generated (PTR-YYYY-XXXX format)
- Database entry created with status = 'pending'
- account_creation_status = 'skipped'

**Verification:**
- Check database: `SELECT * FROM partners WHERE email = 'john.doe@test.com'`
- Partner status should be 'pending'
- No password field set

---

### Test 1.3: Individual Self-Registration (With Account Creation)
**Objective:** Register as individual partner with login account

**Steps:**
1. Go to: `http://localhost/rhemazimbabwe/partner_registration`
2. Click "Register as Individual" button
3. Fill in form:
   ```
   First Name: Jane
   Last Name: Smith
   Email: jane.smith@test.com
   Phone: +1234567891
   Billing Address: 456 Test Avenue
   City: Bulawayo
   State: Bulawayo Province
   Country: Zimbabwe
   Zip Code: 54321

   Giving Types: Select "Building Project" (check checkbox)
   Amount for Building Project: 200
   Giving Frequency: Quarterly
   Currency: USD
   Total Amount: 200

   Notes: Test registration with account

   Create Account: CHECK THIS BOX
   Password: Test@123456
   Confirm Password: Test@123456
   ```
4. Click "Submit Registration"

**Expected Results:**
- Form validates successfully
- Password fields validate (minimum 6 characters, passwords match)
- Redirects to success page
- Partner code generated
- Database entry created with password hash
- account_creation_status = 'completed'

**Verification:**
- Check database: `SELECT * FROM partners WHERE email = 'jane.smith@test.com'`
- Password field should contain hash
- Partner can login to partner portal

---

### Test 1.4: Organization Self-Registration
**Objective:** Register as organization partner

**Steps:**
1. Go to: `http://localhost/rhemazimbabwe/partner_registration`
2. Click "Register as Organization" button
3. Fill in form:
   ```
   Organization Name: Test Church Ministry
   Organization Type: Church

   Contact First Name: Michael
   Contact Last Name: Johnson
   Email: ministry@testchurch.com
   Phone: +1234567892

   Billing Address: 789 Church Road
   City: Harare
   State: Harare Province
   Country: Zimbabwe
   Zip Code: 11111

   Giving Types: Select "Missions" and "Scholarships"
   Amount for Missions: 500
   Amount for Scholarships: 300
   Giving Frequency: Monthly
   Currency: USD
   Total Amount: 800

   Notes: Test organization registration

   Create Account: CHECK THIS BOX
   Password: Church@2025
   Confirm Password: Church@2025
   ```
4. Click "Submit Registration"

**Expected Results:**
- Form validates successfully
- Organization fields saved
- account_type = 'organization'
- Multiple giving types saved
- Partner code generated
- Account created with login capability

**Verification:**
- Check partners table
- Check partner_giving_settings table for multiple entries

---

### Test 1.5: Validation Testing
**Objective:** Test form validation

**Test Cases:**

**a) Missing Required Fields:**
1. Try submitting form with empty First Name
2. Try submitting with empty Email
3. Try submitting with no Giving Type selected

**Expected:** Validation errors displayed

**b) Invalid Email:**
1. Enter "invalidEmail" as email
2. Submit form

**Expected:** Email validation error

**c) Password Mismatch:**
1. Check "Create Account"
2. Password: Test123
3. Confirm Password: Test456
4. Submit

**Expected:** Password mismatch error

**d) Short Password:**
1. Password: Test
2. Confirm: Test

**Expected:** Minimum length error (6 characters)

---

## Part 2: User Portal Registration (Student/Parent)

### Test 2.1: Student Registration
**Objective:** Student registers as partner through their portal

**Prerequisites:**
- Valid student account logged in

**Steps:**
1. Login as student at `http://localhost/rhemazimbabwe/site/userlogin`
   ```
   Username: [student username]
   Password: [student password]
   ```
2. Navigate to "Partners" menu in student dashboard
3. Click "Register as Partner" or similar option
4. Fill in registration form:
   ```
   Account Type: Individual
   Email: student.partner@test.com
   Phone: +1234567893
   Address: Student Address 123
   City: Harare
   Country: Zimbabwe
   Zip Code: 10101

   Giving Types: General Fund
   Amount: 50
   Giving Frequency: Monthly
   Currency: USD
   ```
5. Submit form

**Expected Results:**
- Student information auto-populated (firstname, lastname from student record)
- student_id field set automatically
- status = 'active' (auto-approved for logged-in users)
- Partner created and linked to student
- Redirect to student partner dashboard
- Success message displayed

**Verification:**
- Database: `SELECT * FROM partners WHERE student_id = [student_id]`
- Check partner_registrations table for registration_source = 'student_portal'

---

### Test 2.2: Parent Registration
**Objective:** Parent registers as partner through student portal

**Prerequisites:**
- Valid parent account logged in

**Steps:**
1. Login as parent at `http://localhost/rhemazimbabwe/site/userlogin`
   ```
   Username: [parent username]
   Password: [parent password]
   ```
2. Navigate to "Partners" or similar menu
3. Access partner registration
4. Fill in registration form with parent details
5. Submit

**Expected Results:**
- Parent information pre-filled from guardian records
- Registration created
- Partner associated with parent's email/phone
- status = 'active'

**Verification:**
- Check partners table
- Verify email/phone match parent guardian records

---

### Test 2.3: Duplicate Registration Check
**Objective:** Prevent duplicate registrations

**Steps:**
1. Login as student who already registered as partner (from Test 2.1)
2. Try to access partner registration again

**Expected Results:**
- System detects existing partner
- Shows message: "You are already registered as a partner"
- Or redirects to partner management/dashboard

---

## Part 3: Staff Registration

### Test 3.1: Staff Self-Registration
**Objective:** Staff member registers as partner

**Prerequisites:**
- Valid staff account logged in

**Steps:**
1. Login as staff at `http://localhost/rhemazimbabwe/site/login`
   ```
   Username: [staff username]
   Password: [staff password]
   ```
2. Navigate to partner registration in staff portal
3. Fill in form:
   ```
   Account Type: Individual
   Email: staff.partner@test.com
   Phone: +1234567894
   Address: Staff Address 456
   City: Bulawayo
   Country: Zimbabwe

   Giving Types: Building Project
   Amount: 150
   Giving Frequency: Monthly
   ```
4. Submit

**Expected Results:**
- Staff information auto-filled
- staff_id field set
- status = 'active'
- Created and redirected to dashboard

**Verification:**
- `SELECT * FROM partners WHERE staff_id = [staff_id]`
- Check registration_source = 'staff_portal'

---

### Test 3.2: Staff Adding Partners on Behalf of Others
**Objective:** Staff can add partners for others

**Steps:**
1. Login as staff
2. Navigate to Partners menu
3. Click "Add Partner"
4. Fill in form for external person:
   ```
   First Name: External
   Last Name: Partner
   Email: external@test.com
   Phone: +1234567895
   Address: External Address
   City: Harare

   Giving Type: Missions
   Amount: 75
   Frequency: Quarterly
   ```
5. Submit

**Expected Results:**
- Partner created
- created_by = staff_id
- Partner not linked to staff's own account
- Partner status = 'active'

---

## Part 4: Admin Partner Management

### Test 4.1: View Partner Requests
**Objective:** Admin can view pending partner registrations

**Prerequisites:**
- At least one pending partner registration (from Test 1.2)
- Admin logged in with partner permissions

**Steps:**
1. Login as admin: `http://localhost/rhemazimbabwe/admin`
2. Navigate to: Partners > Partner Requests
3. Review list of pending partners

**Expected Results:**
- Pending partners displayed
- Shows: Partner Code, Name, Email, Phone, Giving Type, Frequency, Amount, Date
- Action buttons: View, Approve, Reject

**Verification:**
- Only partners with status = 'pending' shown

---

### Test 4.2: Approve Partner Request
**Objective:** Admin approves pending partner

**Steps:**
1. In Partner Requests page
2. Find pending partner (e.g., John Doe from Test 1.2)
3. Click "Approve" button
4. Optionally add approval reason
5. Confirm approval

**Expected Results:**
- Partner status changes to 'active'
- Success message displayed
- Partner removed from requests list
- Note added to partner record about approval
- Partner can now login (if account was created)

**Verification:**
- `SELECT status FROM partners WHERE email = 'john.doe@test.com'`
- Status should be 'active'
- Check partner_notes table for approval note

---

### Test 4.3: Reject Partner Request
**Objective:** Admin rejects pending partner

**Steps:**
1. In Partner Requests page
2. Find another pending partner
3. Click "Reject" button
4. Enter rejection reason: "Incomplete information"
5. Confirm rejection

**Expected Results:**
- Partner status changes to 'suspended' or deleted
- Rejection note added
- Removed from requests list

**Verification:**
- Check partner status in database
- Check partner_notes for rejection note

---

### Test 4.4: View All Partners
**Objective:** Admin views approved partners list

**Steps:**
1. Navigate to: Partners > Partner List
2. Review partners list

**Expected Results:**
- All active/approved partners displayed
- Excludes pending partners
- Shows: Partner Code, Name, Email, Phone, Giving Type, Frequency, Amount, Status
- Filter options available (by status, giving type, frequency)
- Search functionality works

---

### Test 4.5: Add Partner (Admin)
**Objective:** Admin manually adds a partner

**Steps:**
1. Navigate to: Partners > Add Partner
2. Fill in form:
   ```
   First Name: Admin
   Last Name: Added
   Email: adminadded@test.com
   Phone: +1234567896
   Address: Admin Added Address
   City: Harare

   Giving Type: General Fund
   Giving Frequency: Monthly
   Contribution Amount: 120
   Currency: USD
   Status: Active
   ```
3. Submit

**Expected Results:**
- Partner created immediately
- status = 'active' (no approval needed)
- created_by = admin_id
- Partner appears in partners list

---

### Test 4.6: Edit Partner
**Objective:** Admin edits existing partner information

**Steps:**
1. In Partners list
2. Click "Edit" button for any partner
3. Update information:
   ```
   Phone: +9999999999
   Contribution Amount: 250
   ```
4. Save changes

**Expected Results:**
- Partner information updated
- Success message displayed
- Changes reflected in partner details

**Verification:**
- Check database for updated values

---

### Test 4.7: View Partner Details
**Objective:** Admin views complete partner profile

**Steps:**
1. Click "View" button for any partner
2. Review partner details page

**Expected Results:**
- Complete partner information displayed
- Sections visible:
  - Basic Information (name, email, phone, address)
  - Giving Settings (types, frequency, amounts)
  - Contribution History (last 5 contributions)
  - Total Contributions (summary)
  - Notes (if any)
  - Reminders (if any)
  - Activity Log

---

### Test 4.8: Add Partner Note
**Objective:** Admin adds internal note about partner

**Steps:**
1. In partner details page
2. Click "Add Note"
3. Fill in:
   ```
   Title: Follow-up Required
   Note: Partner requested information about scholarship program
   Priority: High
   Pin Note: Yes
   ```
4. Save

**Expected Results:**
- Note created and visible
- Shows creation date and admin name
- High priority notes highlighted
- Pinned notes appear at top

---

### Test 4.9: Add Partner Reminder
**Objective:** Admin sets reminder for partner follow-up

**Steps:**
1. In partner details page
2. Click "Add Reminder"
3. Fill in:
   ```
   Reminder Type: Follow-up
   Date: [tomorrow's date]
   Time: 10:00 AM
   Message: Call partner about scholarship program
   Active: Yes
   ```
4. Save

**Expected Results:**
- Reminder created
- Appears in partner details
- Shows in admin's reminder dashboard
- Can be activated/deactivated

---

### Test 4.10: Delete Partner
**Objective:** Admin deletes partner (if has permission)

**Prerequisites:**
- Delete permission enabled for admin

**Steps:**
1. Find test partner to delete
2. Click "Delete" button
3. Confirm deletion

**Expected Results:**
- Confirmation dialog appears
- After confirmation, partner deleted
- Success message displayed
- Partner removed from list

**Note:** Consider if delete is hard delete or soft delete (status change)

---

## Part 5: Partner Portal Testing

### Test 5.1: Partner Login
**Objective:** Partner logs in to their portal

**Prerequisites:**
- Partner with account created (Jane Smith from Test 1.3)
- Partner status = 'active'

**Steps:**
1. Navigate to: `http://localhost/rhemazimbabwe/partnerportal`
2. Enter credentials:
   ```
   Email: jane.smith@test.com
   Password: Test@123456
   ```
3. Click "Login"

**Expected Results:**
- Login successful
- Redirects to partner dashboard
- Partner name displayed in header
- No errors

**Test Failed Login:**
- Try wrong password: Error message displayed
- Try inactive partner: Access denied or pending message

---

### Test 5.2: Partner Dashboard
**Objective:** Partner views their dashboard

**Steps:**
1. After login, view dashboard

**Expected Results:**
Dashboard displays:
- Welcome message with partner name
- Statistics cards:
  - Total Contributed (lifetime)
  - This Year Contributed
  - Total Transactions
  - Account Status (Active/Pending/Inactive)
- Recent contributions table (last 5)
- Quick action buttons:
  - Add Contribution
  - View All Contributions
  - Update Settings
- Partner profile sidebar or section

---

### Test 5.3: View Partner Profile
**Objective:** Partner views their profile information

**Steps:**
1. Click "Profile" or "Settings" in navigation
2. Review information displayed

**Expected Results:**
- Shows all partner information:
  - Personal/Organization details
  - Contact information
  - Billing address
  - Current giving settings
  - Giving types and amounts
  - Frequency
  - Partner code
  - Account status
  - Registration date

---

### Test 5.4: Update Profile Information
**Objective:** Partner updates their profile

**Steps:**
1. In Profile/Settings page
2. Click "Edit Profile" or similar
3. Update fields:
   ```
   Phone: +1111111111
   Address: Updated Address 789
   City: Gweru
   ```
4. Save changes

**Expected Results:**
- Form validates
- Information updated in database
- Success message displayed
- Changes reflected immediately

**Verification:**
- Logout and login again
- Verify updated information persists

---

### Test 5.5: Update Giving Settings
**Objective:** Partner modifies giving preferences

**Steps:**
1. In Profile/Settings page
2. Find "Giving Settings" section
3. Modify:
   ```
   Change Amount: 250 (was 200)
   Change Frequency: Monthly (was Quarterly)
   Add new Giving Type: Missions - $50
   ```
4. Save

**Expected Results:**
- Giving settings updated
- Total contribution amount recalculated
- Changes saved to partner_giving_settings table
- Success confirmation

**Verification:**
- Check database: `SELECT * FROM partner_giving_settings WHERE partner_id = [id]`

---

### Test 5.6: View Contribution History
**Objective:** Partner views all their contributions

**Steps:**
1. Click "Contributions" in navigation
2. Review contributions list

**Expected Results:**
- Table/list of all contributions:
  - Date
  - Giving Type
  - Amount
  - Payment Method
  - Status (Completed/Pending)
  - Receipt link
- Sorted by date (newest first)
- Summary totals:
  - Total All Time
  - Total This Year
  - Total This Month
- Filter options (by date range, giving type)

---

### Test 5.7: Add New Contribution (Partner Portal)
**Objective:** Partner records a new contribution

**Steps:**
1. Click "Add Contribution" button
2. Fill in form:
   ```
   Giving Type: Building Project
   Amount: 150
   Contribution Date: [today's date]
   Payment Method: Bank Transfer
   Transaction ID: TXN123456
   Notes: Monthly contribution
   ```
3. Submit

**Expected Results:**
- Contribution recorded
- status = 'pending' (awaiting admin approval)
- Appears in contribution list with "Pending" status
- Success message: "Contribution submitted for review"

**Verification:**
- Check contributions table
- Admin can see pending contribution for approval

---

### Test 5.8: Download Receipt
**Objective:** Partner downloads contribution receipt

**Prerequisites:**
- At least one completed contribution

**Steps:**
1. In Contributions list
2. Find completed contribution
3. Click "Download Receipt" or receipt icon

**Expected Results:**
- Receipt generates (PDF or HTML)
- Shows:
  - School/Organization information
  - Receipt number
  - Partner information
  - Contribution details
  - Date
  - Amount
  - Giving type
  - Tax-deductible statement (if applicable)
  - Thank you message

---

### Test 5.9: Change Password
**Objective:** Partner changes their password

**Steps:**
1. Navigate to Settings or Account
2. Click "Change Password"
3. Fill in:
   ```
   Current Password: Test@123456
   New Password: NewPass@789
   Confirm Password: NewPass@789
   ```
4. Submit

**Expected Results:**
- Password validates (minimum length, match)
- Current password verified
- New password saved (hashed)
- Success message
- Can login with new password

**Verification:**
1. Logout
2. Login with new password
3. Should succeed

---

### Test 5.10: Partner Logout
**Objective:** Partner logs out of portal

**Steps:**
1. Click "Logout" button
2. Confirm logout (if prompted)

**Expected Results:**
- Session destroyed
- Redirected to login page
- Cannot access partner pages without re-login

---

## Part 6: Contribution Management

### Test 6.1: Admin View All Contributions
**Objective:** Admin views all partner contributions

**Steps:**
1. Login as admin
2. Navigate to: Partners > Contributions (or Partner Contributions)
3. Review contributions list

**Expected Results:**
- All contributions from all partners displayed
- Columns: Date, Partner Name, Giving Type, Amount, Payment Method, Status
- Filter options: By partner, by date range, by giving type, by status
- Total amount displayed
- Export option (optional)

---

### Test 6.2: Admin Add Contribution for Partner
**Objective:** Admin manually records contribution for partner

**Steps:**
1. Navigate to: Partners > Contributions
2. Click "Add Contribution"
3. Select Partner: Jane Smith
4. Fill in:
   ```
   Giving Type: General Fund
   Amount: 300
   Date: [yesterday's date]
   Payment Method: Cash
   Status: Completed
   Notes: Received in person
   ```
5. Submit

**Expected Results:**
- Contribution created
- Linked to selected partner
- Status = 'completed' immediately
- Appears in partner's contribution history

---

### Test 6.3: Admin Approve Pending Contribution
**Objective:** Admin approves partner-submitted contribution

**Prerequisites:**
- Pending contribution from Test 5.7

**Steps:**
1. Navigate to contributions list
2. Filter by Status: Pending
3. Find pending contribution
4. Click "Approve" button
5. Confirm

**Expected Results:**
- Status changes to 'completed'
- Partner notified (if notifications enabled)
- Receipt becomes available
- Contribution counts toward partner totals

---

### Test 6.4: Admin Reject Contribution
**Objective:** Admin rejects invalid contribution

**Steps:**
1. Find pending contribution
2. Click "Reject" button
3. Enter reason: "Invalid transaction ID"
4. Confirm

**Expected Results:**
- Status changes to 'rejected'
- Rejection reason saved
- Partner can see rejection reason
- Does not count toward totals

---

### Test 6.5: Edit Contribution
**Objective:** Admin edits contribution details

**Steps:**
1. Find any contribution
2. Click "Edit"
3. Modify:
   ```
   Amount: 325 (correction)
   Notes: Amount corrected per receipt
   ```
4. Save

**Expected Results:**
- Contribution updated
- Edit history logged (optional but recommended)
- Totals recalculated

---

### Test 6.6: Delete Contribution
**Objective:** Admin removes erroneous contribution

**Steps:**
1. Find contribution to delete
2. Click "Delete"
3. Confirm deletion

**Expected Results:**
- Contribution deleted from database
- Totals updated
- Audit log entry created (recommended)

---

### Test 6.7: Bulk Import Contributions
**Objective:** Admin imports multiple contributions via CSV/Excel

**Note:** This feature may not be implemented yet

**Steps:**
1. Navigate to Contributions
2. Click "Import" or "Bulk Upload"
3. Select CSV file with format:
   ```
   partner_code,giving_type,amount,date,payment_method
   PTR-2025-0001,General Fund,100,2025-01-15,Bank Transfer
   PTR-2025-0002,Missions,200,2025-01-15,Cash
   ```
4. Upload and process

**Expected Results:**
- Contributions imported
- Success/error report shown
- Contributions appear in list

---

## Part 7: User Portal Partner Management

### Test 7.1: Student Views Their Partners
**Objective:** Student sees partners they created

**Steps:**
1. Login as student (who created partner in Test 2.1)
2. Navigate to "My Partners" or similar menu

**Expected Results:**
- List of partners created by this student
- Shows: Partner Name, Type, Amount, Frequency, Status
- Quick actions: View, Add Contribution
- Statistics: Total partners, Total contributed

---

### Test 7.2: Student Adds Contribution for Their Partner
**Objective:** Student records contribution for their partner

**Steps:**
1. In student's partner list
2. Select their partner
3. Click "Add Contribution"
4. Fill in:
   ```
   Giving Type: General Fund
   Amount: 50
   Date: [today]
   Payment Method: Mobile Money
   ```
5. Submit

**Expected Results:**
- Contribution created
- Linked to student's partner
- Status may be 'pending' or 'completed' based on permissions
- Visible in partner's contribution history

---

### Test 7.3: Student Views Partner Contributions
**Objective:** Student views contribution history for their partner

**Steps:**
1. Select partner from list
2. Click "View Contributions" or similar
3. Review list

**Expected Results:**
- All contributions for this partner displayed
- Can filter by date
- Shows totals
- Download receipt option

---

### Test 7.4: Parent Views Family Partners
**Objective:** Parent sees all partners associated with their children

**Steps:**
1. Login as parent
2. Navigate to Partners section

**Expected Results:**
- Shows partners for all children
- Grouped by child (optional)
- Can add contributions
- View reports

---

### Test 7.5: Staff Views Partners They Created
**Objective:** Staff sees partners they added

**Steps:**
1. Login as staff (from Test 3.1)
2. Navigate to Partners menu

**Expected Results:**
- Lists all partners created by this staff member
- Can manage partners
- Add contributions
- View statistics

---

## Part 8: Reports and Analytics

### Test 8.1: Admin Partner Summary Report
**Objective:** Admin views overall partner statistics

**Steps:**
1. Navigate to: Partners > Reports (or Dashboard)
2. View summary statistics

**Expected Results:**
Displays:
- Total Partners (count)
- Active Partners
- Pending Partners
- Total Contributions (all time)
- This Year Contributions
- This Month Contributions
- Average Contribution per Partner
- Top Contributing Partners
- Contributions by Giving Type (pie chart)
- Contributions by Frequency
- Monthly Contribution Trend (line graph)

---

### Test 8.2: Partner Information Report
**Objective:** Generate detailed partner list report

**Steps:**
1. Navigate to: Partners > Reports > Partner Information
2. Set filters:
   ```
   Status: Active
   Giving Type: All
   Date Range: Last 12 months
   ```
3. Generate report

**Expected Results:**
- Report displays with columns:
  - Partner Code
  - Name
  - Type (Individual/Organization)
  - Email
  - Phone
  - Giving Type
  - Frequency
  - Contribution Amount
  - Total Contributed
  - Last Contribution Date
  - Status
- Export options: PDF, Excel, CSV
- Print option

---

### Test 8.3: Partner Statement Report
**Objective:** Generate contribution statement for specific partner

**Steps:**
1. Navigate to: Partners > Reports > Partner Statement
2. Select Partner: Jane Smith
3. Set Date Range: Jan 1, 2025 - Dec 31, 2025
4. Generate

**Expected Results:**
- Statement shows:
  - Partner information header
  - Date range
  - List of all contributions in period
  - Total amount
  - Breakdown by giving type
  - Thank you message
- Can download as PDF
- Suitable for tax purposes

---

### Test 8.4: Giving Type Report
**Objective:** View contributions by giving type

**Steps:**
1. Navigate to: Partners > Reports > Giving Types
2. Set date range: Last 6 months
3. Generate

**Expected Results:**
- Report shows:
  - Each giving type
  - Number of contributors
  - Total amount
  - Percentage of total
  - Top contributors per type
- Visual chart (bar/pie)

---

### Test 8.5: User Portal Partner Reports
**Objective:** Student/parent views their partner reports

**Steps:**
1. Login as student
2. Navigate to My Partners > Reports
3. View available reports

**Expected Results:**
- My Contribution Summary
- Contribution History
- Downloadable statements
- Year-to-date totals

---

## Testing Checklist

### Registration Testing
- [ ] Self-registration (Individual without account)
- [ ] Self-registration (Individual with account)
- [ ] Self-registration (Organization)
- [ ] Student registration
- [ ] Parent registration
- [ ] Staff registration
- [ ] Form validation (all fields)
- [ ] Duplicate registration prevention
- [ ] Email validation
- [ ] Password strength validation

### Admin Management Testing
- [ ] View partner requests
- [ ] Approve partner requests
- [ ] Reject partner requests
- [ ] View all partners
- [ ] Add partner manually
- [ ] Edit partner
- [ ] Delete partner
- [ ] View partner details
- [ ] Add partner note
- [ ] Add partner reminder
- [ ] Filter partners by status
- [ ] Filter partners by giving type
- [ ] Search partners
- [ ] Change partner status

### Partner Portal Testing
- [ ] Partner login
- [ ] Partner dashboard display
- [ ] View profile
- [ ] Update profile
- [ ] Update giving settings
- [ ] View contribution history
- [ ] Add contribution
- [ ] Download receipt
- [ ] Change password
- [ ] Logout
- [ ] Failed login handling
- [ ] Session expiry handling

### Contribution Testing
- [ ] Admin view all contributions
- [ ] Admin add contribution
- [ ] Admin approve pending contribution
- [ ] Admin reject contribution
- [ ] Admin edit contribution
- [ ] Admin delete contribution
- [ ] Student add contribution for partner
- [ ] Staff add contribution for partner
- [ ] Partner add contribution (self)
- [ ] Receipt generation
- [ ] Contribution totals calculation
- [ ] Filter contributions by status
- [ ] Filter contributions by date
- [ ] Filter contributions by partner

### User Portal Testing
- [ ] Student view their partners
- [ ] Student add contribution
- [ ] Student view partner contributions
- [ ] Parent view family partners
- [ ] Staff view their partners
- [ ] Staff manage partners
- [ ] User portal navigation
- [ ] Permission checks

### Reports Testing
- [ ] Admin partner summary report
- [ ] Partner information report
- [ ] Partner statement report
- [ ] Giving type report
- [ ] Export reports (PDF, Excel)
- [ ] User portal reports
- [ ] Date range filtering
- [ ] Report accuracy verification

### Security Testing
- [ ] Unauthorized access prevention
- [ ] Permission checks (RBAC)
- [ ] SQL injection prevention
- [ ] XSS prevention
- [ ] CSRF protection
- [ ] Password hashing
- [ ] Session security
- [ ] Data validation

### Database Testing
- [ ] Partners table data integrity
- [ ] Contributions table data integrity
- [ ] Giving settings table data integrity
- [ ] Foreign key relationships
- [ ] Cascade deletes (if applicable)
- [ ] Duplicate prevention
- [ ] Data consistency

### Performance Testing
- [ ] Large dataset handling (1000+ partners)
- [ ] Contribution list pagination
- [ ] Search performance
- [ ] Report generation speed
- [ ] Dashboard load time

### Edge Cases
- [ ] Empty data handling
- [ ] Large amounts (millions)
- [ ] Special characters in names
- [ ] Multiple giving types
- [ ] Zero amount handling
- [ ] Future dates
- [ ] Past dates
- [ ] Timezone handling
- [ ] Currency handling

---

## Test Execution Notes

### Recording Results
For each test, document:
1. Test ID and Name
2. Date/Time executed
3. Tester name
4. Result: PASS / FAIL / BLOCKED
5. Screenshots (for failures)
6. Error messages
7. Steps to reproduce (if failed)

### Bug Reporting Template
```
Bug ID: BUG-XXX
Test Case: [Test ID]
Severity: Critical / High / Medium / Low
Summary: [One-line description]
Steps to Reproduce:
1.
2.
3.
Expected Result:
Actual Result:
Screenshots: [attach]
Environment: Windows/XAMPP 8.2, PHP 8.x, MySQL 8.x
```

### Test Environment
- OS: Windows
- Web Server: XAMPP 8.2
- PHP Version: 8.x
- MySQL Version: 8.x
- Browser: Chrome/Firefox latest

### Test Data Cleanup
After testing, you may want to clean up test data:
```sql
-- Backup first!
DELETE FROM partners WHERE email LIKE '%@test.com';
DELETE FROM contributions WHERE partner_id NOT IN (SELECT id FROM partners);
DELETE FROM partner_giving_settings WHERE partner_id NOT IN (SELECT id FROM partners);
```

---

## Quick Test Scenarios

### Scenario 1: End-to-End Partner Journey (30 minutes)
1. Self-register as individual with account creation (5 min)
2. Wait for admin approval (or approve as admin) (5 min)
3. Login to partner portal (2 min)
4. Update profile and giving settings (5 min)
5. Add a contribution (3 min)
6. Download receipt (2 min)
7. View dashboard statistics (3 min)
8. Change password and logout (5 min)

### Scenario 2: Admin Full Workflow (20 minutes)
1. Login as admin (2 min)
2. Review and approve partner request (3 min)
3. Add new partner manually (3 min)
4. Add contribution for partner (3 min)
5. View partner details and add note (3 min)
6. Generate partner statement report (3 min)
7. Review dashboard statistics (3 min)

### Scenario 3: Student Partner Management (15 minutes)
1. Login as student (2 min)
2. Register as partner (5 min)
3. View partner dashboard (2 min)
4. Add contribution (3 min)
5. View contribution history (3 min)

---

## Troubleshooting Common Issues

### Issue: Registration form not submitting
**Check:**
- JavaScript console for errors
- PHP error log
- Form validation rules
- CSRF token
- Database connection

### Issue: Partner cannot login
**Check:**
- Partner status is 'active'
- Password is correct (try reset)
- Account was created (account_creation_status = 'completed')
- No session conflicts

### Issue: Contributions not showing
**Check:**
- Contribution status (pending vs completed)
- Partner ID matches
- Date filters applied
- Database query execution

### Issue: Permissions errors
**Check:**
- RBAC permissions for partners module
- User role assigned correctly
- Permission group setup
- Sidebar menu permissions

### Issue: Receipt not generating
**Check:**
- Contribution exists and is 'completed'
- PDF library installed (if using PDF)
- Template file exists
- File write permissions

---

## Next Steps After Testing

1. **Document Issues:** Create detailed bug reports for any failures
2. **Prioritize Fixes:** Categorize by severity
3. **Regression Testing:** Re-test after fixes
4. **User Acceptance Testing:** Have actual users test
5. **Performance Optimization:** If issues found
6. **Security Audit:** Professional security review
7. **Training Materials:** Create user guides based on test results
8. **Go-Live Checklist:** Prepare deployment plan

---

## Contact & Support

For issues or questions during testing:
- Technical Lead: [Name]
- Database Admin: [Name]
- Project Manager: [Name]

---

**Document Version:** 1.0
**Last Updated:** 2025-01-16
**Prepared By:** Claude Code Assistant
**Status:** Ready for Testing
