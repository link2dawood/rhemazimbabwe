# PARTNERS MODULE - COMPLETE REQUIREMENTS BREAKDOWN

## 🎯 MODULE OVERVIEW
The Partners Module manages donations for the school, tracks payments, issues receipts automatically, and enables communication with partners (donors/supporters).

---

## 📋 PHASE 1 — Database & Base Setup ✅ COMPLETED
**Goal:** Build foundational database structure

### Completed Tasks:
- ✅ Created 7 migration files for all tables
- ✅ Created 3 seed data migrations
- ✅ Created 7 model files with complete CRUD operations
- ✅ Established all table relationships

**Status:** ✅ **DONE**

---

## 📋 PHASE 2 — Admin Backend - Partner Management
**Goal:** Create admin interface for managing partners

### 2.1 Partner CRUD (Admin)
**Files to Create:**
- `application/controllers/admin/Partners.php`
- `application/views/admin/partners/partnerlist.php`
- `application/views/admin/partners/partnershow.php`
- `application/views/admin/partners/partneradd.php`
- `application/views/admin/partners/partneredit.php`

**Features:**
- [ ] List all partners (datatable with search, filter, export)
- [ ] View partner details (profile, contributions, permissions, notes, reminders)
- [ ] Add new partner manually (admin creates partner account)
- [ ] Edit partner information
- [ ] Delete/deactivate partner
- [ ] Change partner status (active/inactive/suspended)
- [ ] Upload partner photo
- [ ] Link partner to existing student (if applicable)

**Filters:**
- By Status (active/inactive/suspended)
- By Giving Type (Type A, B, C)
- By Giving Frequency
- Search by name, email, phone, partner code

---

### 2.2 Contribution Management (Admin)
**Files to Create:**
- `application/controllers/admin/Partner_contributions.php`
- `application/views/admin/partners/contributionlist.php`
- `application/views/admin/partners/contributionadd.php`
- `application/views/admin/partners/contributionedit.php`
- `application/views/admin/partners/contributionshow.php`

**Features:**
- [ ] Record new contribution (manual entry)
- [ ] Edit contribution details
- [ ] View contribution details
- [ ] Delete contribution (with confirmation)
- [ ] Change contribution status (pending, completed, cancelled, failed, refunded)
- [ ] Approve pending contributions
- [ ] Upload payment proof/attachment
- [ ] Generate receipt (PDF) - automatic
- [ ] Send receipt via email/SMS
- [ ] Filter by date range, payment method, status

---

### 2.3 Permission Management (Admin)
**Files to Create:**
- `application/views/admin/partners/permissions.php`

**Features:**
- [ ] View partner permissions
- [ ] Grant permissions (Library, Online Courses, Download Centre, GMeet, Zoom)
- [ ] Revoke permissions
- [ ] Set permission expiry dates (optional)
- [ ] Bulk permission assignment
- [ ] Permission history/audit trail

---

### 2.4 Notes & Reminders (Admin)
**Files to Create:**
- `application/views/admin/partners/notes.php`
- `application/views/admin/partners/reminders.php`

**Notes Features:**
- [ ] Add note (with type: general, prayer request, feedback, complaint, appreciation, follow_up)
- [ ] Edit note
- [ ] Delete note
- [ ] Pin/unpin note
- [ ] Mark as private (staff only)
- [ ] Set priority (low, normal, high, urgent)
- [ ] Attach files to notes

**Reminders Features:**
- [ ] Create reminder (contribution due, missing contribution, thank you, custom, birthday, anniversary, renewal)
- [ ] Set reminder date & time
- [ ] Set reminder method (email, SMS, both, notification)
- [ ] Recurring reminders (daily, weekly, monthly, yearly)
- [ ] Edit reminder
- [ ] Cancel reminder
- [ ] View reminder history
- [ ] Mark as sent/failed

---

### 2.5 Settings Management (Admin)
**Files to Create:**
- `application/controllers/admin/Partner_settings.php`
- `application/views/admin/partners/giving_types.php`
- `application/views/admin/partners/giving_frequencies.php`
- `application/views/admin/partners/permission_types.php`

**Features:**
- [ ] Manage Giving Types (Add, Edit, Delete, Activate/Deactivate)
- [ ] Manage Giving Frequencies (Add, Edit, Delete, Activate/Deactivate)
- [ ] Manage Permission Types (Add, Edit, Delete, Activate/Deactivate)
- [ ] Set default contribution amounts per type
- [ ] Configure receipt templates
- [ ] Configure email/SMS templates for partners

---

## 📋 PHASE 3 — Admin Dashboard & Reports
**Goal:** Create dashboard widgets and comprehensive reports

### 3.1 Dashboard Widgets (Admin)
**Files to Modify:**
- `application/views/admin/admin/dashboard.php`

**Key Highlights to Display:**
- [ ] Total Partners Count
- [ ] Active Partners Count
- [ ] Total Contributions This Month
- [ ] Total Contributions This Year
- [ ] Pending Contributions Amount
- [ ] Recent Contributions (last 5)
- [ ] Contribution Breakdown by Type (chart)
- [ ] Monthly Contribution Trend (chart)
- [ ] Upcoming Reminders Count
- [ ] Overdue Reminders Count

---

### 3.2 Reports (Admin)
**Files to Create:**
- `application/controllers/admin/Partner_reports.php`
- `application/views/admin/partners/reports/partner_information.php`
- `application/views/admin/partners/reports/giving_collection_by_type.php`
- `application/views/admin/partners/reports/partner_statement.php`
- `application/views/admin/partners/reports/balance_giving.php`

**Report 1: Partner Information Report**
- [ ] List all partners with complete details
- [ ] Filter by status, type, frequency
- [ ] Export to PDF/Excel
- [ ] Show: Partner Code, Name, Contact, Type, Frequency, Total Contributed, Last Contribution Date

**Report 2: Giving Collection By Type Report**
- [ ] Show contributions grouped by Giving Type (Type A, B, C)
- [ ] Date range filter
- [ ] Show: Type, Number of Partners, Total Amount, Average Amount
- [ ] Visual chart representation
- [ ] Export to PDF/Excel

**Report 3: Partner Statement Report**
- [ ] Individual partner statement (like bank statement)
- [ ] Show: Date, Description, Amount, Balance
- [ ] Date range filter
- [ ] Generate PDF for email/print
- [ ] Show total contributed, outstanding pledges

**Report 4: Balance Giving Report with Remark**
- [ ] Show partners with outstanding/unpaid pledges
- [ ] Calculate expected vs actual contributions
- [ ] Add remarks/notes for each entry
- [ ] Filter by overdue period
- [ ] Export to PDF/Excel

---

## 📋 PHASE 4 — Frontend CMS - Public Registration
**Goal:** Create public-facing partner registration page

### 4.1 Frontend Partner Registration Page
**Files to Create:**
- `application/controllers/Partner_registration.php`
- `application/views/front/partner_registration.php`
- Add menu item in Front CMS

**Form Fields:**

**Step 1: Account Type Selection**
- [ ] Radio: Individual or Organization
  - If **Individual**: First Name, Last Name
  - If **Organization**: Organization Name, Organization Type (dropdown: Ministry, Church, Business, Other)

**Step 2: Contact Information**
- [ ] Phone Number (required)
- [ ] Email (required)
- [ ] Billing Address (textarea)
- [ ] City
- [ ] State/Province
- [ ] Country (dropdown)
- [ ] ZIP/Postal Code

**Step 3: Contribution Selection**
- [ ] Multiple select checkboxes for contribution types:
  - Type A (with amount input field)
  - Type B (with amount input field)
  - Type C (with amount input field)
- [ ] Auto-calculate Total Contributions
- [ ] Select Giving Frequency (dropdown: Once-Off, Weekly, Monthly, Quarterly, Annually)

**Step 4: Account Creation (Optional)**
- [ ] Checkbox: "Create an Account" (optional)
- [ ] If checked, show:
  - Password field
  - Confirm Password field
- [ ] Help text: "Adding a password will create an account and allow you to access your transaction history as well as manage account details. This is an optional step and can be completed at a later date if desired."

**Features:**
- [ ] Form validation (client-side & server-side)
- [ ] CAPTCHA for security
- [ ] Redirect to login if student/staff
- [ ] Email confirmation after registration
- [ ] Generate unique Partner Code
- [ ] Thank you page with instructions
- [ ] Responsive design (mobile-friendly)

**Student/Staff Detection:**
- [ ] Check if email exists in students/staff table
- [ ] Show message: "You are already a student/staff member. Please login to your portal to register as a partner."
- [ ] Redirect to appropriate login page

---

## 📋 PHASE 5 — Student/Staff Portal Integration
**Goal:** Allow students and staff to register and manage their partner accounts

### 5.1 Partner Registration Tab (Student/Staff Portal)
**Files to Create:**
- `application/controllers/user/Partner.php` (for student portal)
- `application/views/user/partner/register.php`
- `application/views/user/partner/dashboard.php`
- `application/views/user/partner/settings.php`

**Features:**
- [ ] Partner Registration Tab in student/staff sidebar menu
- [ ] Check if already registered as partner
- [ ] If not registered, show registration form
- [ ] Auto-fill name, email, phone from student/staff profile
- [ ] Same form as frontend (account type, contribution selection)
- [ ] Link partner record to student/staff account

---

### 5.2 Partner Dashboard (Student/Staff Portal)
**Files to Create:**
- `application/views/user/partner/dashboard.php`

**Dashboard Widgets:**
- [ ] My Partner Information (Partner Code, Type, Frequency)
- [ ] Total Contributions This Year
- [ ] Last Contribution Date
- [ ] Next Expected Contribution Date
- [ ] Recent Contributions (last 5)
- [ ] My Permissions (list of granted permissions)
- [ ] Contribution History (full list, filterable)
- [ ] Download Receipts (PDF)
- [ ] Download Statement (PDF)

---

### 5.3 Giving Settings (Student/Staff Portal)
**Files to Create:**
- `application/views/user/partner/giving_settings.php`

**Settings Features:**
- [ ] Select Type(s) of contribution (multiple select with amount)
  - Type A: $______
  - Type B: $______
  - Type C: $______
- [ ] Show Total Contributions
- [ ] Select Giving Frequency (dropdown)
- [ ] Update Start Date
- [ ] Set End Date (optional)
- [ ] Update Contact Information
- [ ] Update Billing Address
- [ ] Save Changes button

---

### 5.4 Partner Portal Features
**Additional Features:**
- [ ] Make a Contribution (integrate payment gateway)
- [ ] View Contribution History
- [ ] Download Receipts
- [ ] Download Annual Statement
- [ ] Update Profile Information
- [ ] Manage Notification Preferences
- [ ] View Granted Permissions
- [ ] Access permitted resources (Library, Courses, etc.)

---

## 📋 PHASE 6 — Communication Integration
**Goal:** Integrate partner communication with existing SMS/Email system

### 6.1 Communication Module Extension
**Files to Modify:**
- `application/controllers/admin/Communicate.php`
- `application/views/admin/communicate/index.php`

**Features:**
- [ ] Add "Partners" as message recipient option
- [ ] Select partner groups:
  - All Partners
  - By Giving Type
  - By Giving Frequency
  - By Status (Active/Inactive)
  - Individual Partner
- [ ] Message templates for partners:
  - Thank you message
  - Receipt notification
  - Reminder notification
  - Update/Newsletter
  - Prayer request
  - Custom message
- [ ] Send via Email/SMS/Both
- [ ] Schedule messages
- [ ] Track sent messages
- [ ] View message history per partner

---

### 6.2 Automated Notifications
**Files to Create:**
- `application/controllers/Cron_partners.php` (for scheduled tasks)

**Automated Messages:**
- [ ] Contribution receipt (immediate upon contribution)
- [ ] Thank you message (configurable delay after contribution)
- [ ] Reminder before due date (configurable days before)
- [ ] Reminder after missed contribution (configurable days after)
- [ ] Monthly statement (automatic on 1st of month)
- [ ] Annual statement (automatic on January 1st)
- [ ] Birthday wishes (if birthday in profile)
- [ ] Anniversary wishes (partner anniversary)

---

## 📋 PHASE 7 — Payment Gateway Integration
**Goal:** Enable online contributions directly through the system

### 7.1 Online Payment Integration
**Files to Create:**
- `application/controllers/partner/Payment.php`
- `application/views/partner/payment.php`
- Payment gateway controllers (use existing gateway structure)

**Features:**
- [ ] Integration with existing payment gateways (Stripe, PayPal, etc.)
- [ ] "Make a Contribution" button on partner portal
- [ ] Contribution amount entry (or use pledged amount)
- [ ] Contribution type selection
- [ ] Payment method selection
- [ ] Payment processing
- [ ] Success/failure handling
- [ ] Automatic receipt generation
- [ ] Automatic contribution record creation
- [ ] Email/SMS confirmation

---

### 7.2 Recurring Payments (Optional)
**Advanced Feature:**
- [ ] Setup recurring payment authorization
- [ ] Automatic charge based on frequency
- [ ] Card/payment method storage (tokenized)
- [ ] Notification before each charge
- [ ] Ability to pause/cancel recurring payments
- [ ] Failed payment retry logic
- [ ] Failed payment notifications

---

## 📋 PHASE 8 — Receipt & Document Generation
**Goal:** Automatic receipt generation with professional templates

### 8.1 Receipt Templates
**Files to Create:**
- `application/libraries/Partner_receipt.php`
- `application/views/admin/partners/receipt_template.php`

**Features:**
- [ ] Professional PDF receipt design
- [ ] School logo and letterhead
- [ ] Receipt number generation
- [ ] QR code for verification
- [ ] Contribution details (date, amount, method)
- [ ] Partner information
- [ ] Tax receipt compliance (if applicable)
- [ ] Digital signature
- [ ] Multiple receipt templates (selectable)
- [ ] Preview before sending

---

### 8.2 Statement Generation
**Files to Create:**
- `application/libraries/Partner_statement.php`

**Features:**
- [ ] Annual giving statement (tax purposes)
- [ ] Custom date range statement
- [ ] Detailed transaction history
- [ ] Summary totals by type
- [ ] PDF format
- [ ] Email delivery option
- [ ] Download from portal

---

## 📋 PHASE 9 — Access Control & Permissions
**Goal:** Implement permission-based access to school resources

### 9.1 Permission Integration
**Files to Modify:**
- Library module
- Online Courses module
- Download Centre module
- GMeet module
- Zoom module

**Features:**
- [ ] Check partner permissions before granting access
- [ ] Redirect unauthorized partners
- [ ] Permission expiry checking
- [ ] Grace period handling
- [ ] Notification when permission expiring
- [ ] Automatic permission renewal based on contributions
- [ ] Permission audit trail

---

### 9.2 Permission Rules (Admin Config)
**Files to Create:**
- `application/views/admin/partners/permission_rules.php`

**Configurable Rules:**
- [ ] Minimum contribution amount for each permission
- [ ] Contribution frequency requirement
- [ ] Permission validity period
- [ ] Auto-renewal conditions
- [ ] Grace period after missed contribution
- [ ] Multi-tier permission levels

---

## 📋 PHASE 10 — Testing, Security & Optimization
**Goal:** Ensure system is secure, tested, and optimized

### 10.1 Security Implementation
- [ ] SQL injection prevention (using Query Builder)
- [ ] XSS protection
- [ ] CSRF tokens on all forms
- [ ] Input validation and sanitization
- [ ] File upload security
- [ ] Payment data encryption
- [ ] Role-based access control (RBAC)
- [ ] Audit logging (who did what when)

---

### 10.2 Testing
- [ ] Unit tests for models
- [ ] Integration tests for payment flows
- [ ] User acceptance testing (UAT)
- [ ] Cross-browser testing
- [ ] Mobile responsiveness testing
- [ ] Payment gateway sandbox testing
- [ ] Email/SMS delivery testing
- [ ] Performance testing (load testing)

---

### 10.3 Optimization
- [ ] Database query optimization
- [ ] Index optimization
- [ ] Caching implementation
- [ ] Image optimization
- [ ] Asset minification
- [ ] Lazy loading
- [ ] AJAX pagination for large datasets

---

## 📋 PHASE 11 — Documentation & Training
**Goal:** Complete user documentation and staff training

### 11.1 Documentation
- [ ] Admin user manual (PDF)
- [ ] Partner portal user guide
- [ ] API documentation (if applicable)
- [ ] Video tutorials
- [ ] FAQ section
- [ ] Troubleshooting guide

---

### 11.2 Training
- [ ] Admin staff training session
- [ ] Finance team training
- [ ] IT support training
- [ ] Partner onboarding materials
- [ ] Help desk setup

---

## 🎯 SUMMARY OF DELIVERABLES

### Database & Backend (Phases 1-2)
- ✅ 7 Database tables (partners, contributions, types, frequencies, permissions, reminders, notes)
- ✅ 7 Models with complete CRUD operations
- ⏳ Admin controllers and views for full partner management

### Reports & Analytics (Phase 3)
- ⏳ 4 comprehensive reports
- ⏳ Dashboard widgets and charts
- ⏳ Export to PDF/Excel functionality

### Frontend & Portal (Phases 4-5)
- ⏳ Public registration page
- ⏳ Student/staff partner portal
- ⏳ Giving settings management
- ⏳ Contribution history and receipts

### Communication (Phase 6)
- ⏳ Integration with SMS/Email system
- ⏳ Automated notifications
- ⏳ Message templates
- ⏳ Scheduled messages

### Payments (Phase 7)
- ⏳ Online payment integration
- ⏳ Recurring payments (optional)
- ⏳ Multiple gateway support

### Documents (Phase 8)
- ⏳ Receipt generation
- ⏳ Statement generation
- ⏳ PDF templates

### Security & Access (Phases 9-10)
- ⏳ Permission-based resource access
- ⏳ Security hardening
- ⏳ Testing and optimization

---

## 📅 RECOMMENDED TIMELINE

**Phase 1:** ✅ **COMPLETED** (1 day)
**Phase 2:** 5-7 days
**Phase 3:** 3-4 days
**Phase 4:** 3-4 days
**Phase 5:** 4-5 days
**Phase 6:** 2-3 days
**Phase 7:** 4-5 days
**Phase 8:** 2-3 days
**Phase 9:** 3-4 days
**Phase 10:** 3-5 days
**Phase 11:** 2-3 days

**Total Estimated Time:** 32-46 days

---

## ✅ CURRENT STATUS

**Phase 1 - Database & Base Setup:** ✅ **100% COMPLETE**

**Next Up:** Phase 2 - Admin Backend Partner Management

---

*Document Version: 1.0*
*Last Updated: September 30, 2025*
*Project: Rhema Zimbabwe Partners Module*