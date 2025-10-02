# Partners Module - Setup & Installation Guide

## 📋 Overview

This guide walks you through setting up the Partners Management Module for Rhema Zimbabwe School Management System.

**Phase 2 Status:** ✅ **COMPLETE**
- All views created
- Migration runner ready
- Sidebar menu configured
- Upload directories created
- Language files added

---

## 🚀 Quick Setup (5 Minutes)

### Step 1: Enable Migrations

Edit `application/config/migration.php`:

```php
// Line 11: Enable migrations
$config['migration_enabled'] = TRUE;  // Change from FALSE

// Line 69: Set target version to 136 (includes menu)
$config['migration_version'] = 136;   // Change from 125
```

### Step 2: Run Migrations

**Option A - Via Browser:**
1. Open your browser
2. Navigate to: `http://localhost/rhemazimbabwe/admin/migrate`
3. You should see: "Migration Successful! Database migrated to version: 136"

**Option B - Via Command Line:**
```bash
cd D:\xampp8.2\htdocs\rhemazimbabwe
php index.php admin/migrate
```

### Step 3: Disable Migrations (IMPORTANT!)

After successful migration, edit `application/config/migration.php`:

```php
$config['migration_enabled'] = FALSE;  // Change back to FALSE for security
```

### Step 4: Verify Setup

1. Login to admin panel
2. Look for "Partners" menu in sidebar (with handshake icon)
3. Navigate to: `http://localhost/rhemazimbabwe/admin/partners`
4. You should see the Partners List page

---

## 📊 What Was Created

### Database Tables (10 tables)

#### Core Tables:
1. **`partners`** - Main partner records
   - Contact information (name, email, phone, address)
   - Giving information (type, frequency, amount)
   - Status tracking (active/inactive/suspended)
   - Student linkage (optional)
   - Photo storage

2. **`partner_contributions`** - Donation tracking
   - Contribution amounts and dates
   - Payment methods (cash, bank, mobile money, etc.)
   - Receipt numbers and transaction IDs
   - Approval workflow
   - Notes per contribution

3. **`giving_types`** - Type A, B, C classification
   - Seeded with 3 default types
   - Customizable descriptions

4. **`giving_frequencies`** - Contribution schedules
   - Once-Off, Weekly, Monthly, Quarterly, Annually
   - Days interval for automation

5. **`partner_permissions`** - Access control
   - Library access
   - Online Courses
   - Download Centre
   - GMeet access
   - Zoom access
   - Grant/revoke tracking

6. **`partner_reminders`** - Automated reminders
   - Multiple reminder types
   - Email/SMS delivery
   - Recurring patterns
   - Status tracking

7. **`partner_notes`** - Notes system
   - Priority levels (urgent/high/normal/low)
   - Pinned notes
   - Private notes
   - Created by tracking

#### Support Tables:
8. **`permission_group`** (id: 32) - Partners permission group
9. **`permission_category`** - 8 permission categories for partners
10. **`sidebar_menus`** (id: 40) - Main Partners menu
11. **`sidebar_sub_menus`** - 6 sub-menu items

### Models (7 files)

All models extend `MY_Model` and include comprehensive CRUD operations:

1. **Partner_model.php**
   - Partner management
   - Code generation
   - Permission checking
   - Contribution aggregation

2. **Contribution_model.php**
   - Contribution tracking
   - Receipt generation
   - Summary reports
   - Approval workflow

3. **Frequency_model.php**
   - Frequency management
   - Next date calculation
   - Dropdown helpers

4. **Type_model.php**
   - Type management
   - Statistics
   - Ordering

5. **Permission_model.php**
   - Permission granting/revoking
   - Bulk sync operations
   - Permission checking

6. **Reminder_model.php**
   - Reminder scheduling
   - Pattern calculation
   - Automatic creation

7. **Note_model.php**
   - Note management
   - Priority filtering
   - Pinning system
   - Search functionality

### Controller (1 file)

**`admin/Partners.php`**
- Extends `Admin_Controller`
- RBAC integrated
- Methods:
  - `index()` - Partner list page
  - `getlist()` - AJAX DataTable data
  - `add()` - Add new partner with validation
  - `edit($id)` - Edit partner
  - `show($id)` - View partner details
  - `delete($id)` - Delete partner
  - `changestatus()` - AJAX status updates

### Views (4 files)

1. **`partnerlist.php`** - Partner listing
   - DataTable with server-side processing
   - Advanced filters (status, type, frequency)
   - Export buttons (Excel, CSV, PDF, Print)
   - Bulk actions support

2. **`partneradd.php`** - Add partner form
   - Personal information section
   - Giving information section
   - Photo upload
   - Student linkage
   - Form validation

3. **`partneredit.php`** - Edit partner form
   - Pre-filled fields
   - Status dropdown
   - Photo update option
   - End date management

4. **`partnershow.php`** - Partner details
   - Profile card with photo
   - 5 tabs:
     - Profile (personal & giving info)
     - Contributions (recent contributions + stats)
     - Permissions (granted permissions)
     - Notes (timeline with priority badges)
     - Reminders (scheduled reminders)

### Language File

**`English/app_files/partners_lang.php`**
- 150+ language keys
- All partner-related translations
- Easily extendable for other languages

### Additional Files

1. **`admin/Migrate.php`** - Migration runner controller
2. **`uploads/partner_images/`** - Photo storage directory

---

## 📁 Complete File Structure

```
rhemazimbabwe/
├── application/
│   ├── controllers/
│   │   └── admin/
│   │       ├── Partners.php ✅
│   │       └── Migrate.php ✅
│   ├── models/
│   │   ├── Partner_model.php ✅
│   │   ├── Contribution_model.php ✅
│   │   ├── Frequency_model.php ✅
│   │   ├── Type_model.php ✅
│   │   ├── Permission_model.php ✅
│   │   ├── Reminder_model.php ✅
│   │   └── Note_model.php ✅
│   ├── views/
│   │   └── admin/
│   │       └── partners/
│   │           ├── partnerlist.php ✅
│   │           ├── partneradd.php ✅
│   │           ├── partneredit.php ✅
│   │           └── partnershow.php ✅
│   ├── migrations/
│   │   ├── 126_create_partners_table.php ✅
│   │   ├── 127_create_partner_contributions_table.php ✅
│   │   ├── 128_create_giving_types_table.php ✅
│   │   ├── 129_create_giving_frequencies_table.php ✅
│   │   ├── 130_create_partner_permissions_table.php ✅
│   │   ├── 131_create_partner_reminders_table.php ✅
│   │   ├── 132_create_partner_notes_table.php ✅
│   │   ├── 133_seed_giving_frequencies.php ✅
│   │   ├── 134_seed_giving_types.php ✅
│   │   ├── 135_seed_partner_permissions_defaults.php ✅
│   │   └── 136_add_partners_menu.php ✅
│   └── language/
│       └── English/
│           └── app_files/
│               └── partners_lang.php ✅
└── uploads/
    └── partner_images/ ✅
```

---

## 🎯 Testing Your Setup

### 1. Access Partners Module

Navigate to: `http://localhost/rhemazimbabwe/admin/partners`

You should see:
- Empty partners list with DataTable
- Filters (Status, Giving Type, Giving Frequency)
- "Add Partner" button (if you have permission)
- Export buttons (Copy, Excel, CSV, PDF, Print)

### 2. Add a Test Partner

1. Click "Add Partner"
2. Fill in required fields:
   - First Name: John
   - Last Name: Doe
3. Fill optional fields:
   - Email: john.doe@example.com
   - Phone: +263771234567
   - Giving Type: Type A
   - Giving Frequency: Monthly
   - Contribution Amount: 100
   - Currency: USD
   - Start Date: Today's date
4. Click "Save"
5. You should be redirected to partner list with success message

### 3. View Partner Details

1. Click the eye icon next to the test partner
2. Verify all 5 tabs load:
   - ✅ Profile tab shows personal information
   - ✅ Contributions tab shows empty state
   - ✅ Permissions tab shows no permissions
   - ✅ Notes tab shows no notes
   - ✅ Reminders tab shows no reminders

### 4. Edit Partner

1. Click "Edit" button from partner details page
2. Update any field (e.g., change phone number)
3. Upload a photo (optional)
4. Click "Save"
5. Verify changes are saved

### 5. Check Database

Run these SQL queries to verify data:

```sql
-- Check partners table
SELECT * FROM partners;

-- Check seeded data
SELECT * FROM giving_types;
SELECT * FROM giving_frequencies;
SELECT * FROM partner_permission_types;

-- Check menu was added
SELECT * FROM sidebar_menus WHERE id = 40;
SELECT * FROM sidebar_sub_menus WHERE sidebar_menu_id = 40;

-- Check permissions
SELECT * FROM permission_group WHERE id = 32;
SELECT * FROM permission_category WHERE perm_group_id = 32;
```

---

## 🔐 Permissions Setup

### Grant Partners Permissions to Admin Role

1. Login as Super Admin
2. Navigate to: **Admin → User Roles**
3. Edit "Admin" or "Super Admin" role
4. Find "Partners Management" section
5. Grant permissions:
   - ✅ Partners (View, Add, Edit, Delete)
   - ✅ Partner Contributions (View, Add, Edit, Delete)
   - ✅ Giving Types (View, Add, Edit, Delete)
   - ✅ Giving Frequencies (View, Add, Edit, Delete)
   - ✅ Partner Permissions (View, Add, Edit, Delete)
   - ✅ Partner Notes (View, Add, Edit, Delete)
   - ✅ Partner Reminders (View, Add, Edit, Delete)
   - ✅ Partner Reports (View)
6. Click "Save Permissions"

---

## 🎨 Sidebar Menu

After setup, your sidebar will show:

```
📋 Partners
   ├─ Partner List
   ├─ Add Partner
   ├─ Contributions
   ├─ Giving Types
   ├─ Giving Frequencies
   └─ Partner Reports
```

**Menu Properties:**
- Icon: `fa fa-handshake-o` (handshake icon)
- Position: Level 35 (after Reports, before System Settings)
- Permission Group ID: 32
- Access controlled by RBAC

---

## 🐛 Troubleshooting

### Migration Fails

**Error: "Migration class already exists"**
- Solution: Migrations already run. Check database version:
  ```sql
  SELECT * FROM migrations ORDER BY version DESC LIMIT 1;
  ```

**Error: "Table already exists"**
- Solution: Drop tables manually or rollback:
  ```php
  http://localhost/rhemazimbabwe/admin/migrate/rollback/125
  ```

### Menu Not Showing

**Partners menu not visible in sidebar:**
1. Check migration 136 ran successfully:
   ```sql
   SELECT * FROM migrations WHERE version = 136;
   ```
2. Check menu exists:
   ```sql
   SELECT * FROM sidebar_menus WHERE id = 40;
   ```
3. Verify you have permissions (see Permissions Setup above)
4. Clear browser cache and reload

### Cannot Upload Photos

**File upload fails:**
1. Check directory exists:
   ```bash
   ls uploads/partner_images/
   ```
2. Check directory is writable:
   ```bash
   chmod 777 uploads/partner_images/
   ```
3. Check `php.ini` upload settings:
   ```ini
   upload_max_filesize = 10M
   post_max_size = 10M
   ```

### Language Keys Not Working

**Seeing language keys instead of text:**
1. Check language file exists:
   ```
   application/language/English/app_files/partners_lang.php
   ```
2. Check language is loaded in controller:
   ```php
   $this->load->language('app_files/partners');
   ```
3. Clear cache if using OpCache

---

## 📊 Database Schema Reference

### Partners Table Structure

| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key |
| partner_code | VARCHAR(50) | Auto-generated unique code |
| student_id | INT | Optional link to students table |
| firstname | VARCHAR(100) | Required |
| lastname | VARCHAR(100) | Required |
| email | VARCHAR(100) | Optional, unique |
| mobileno | VARCHAR(20) | Optional |
| address | TEXT | Full address |
| city | VARCHAR(100) | City name |
| state | VARCHAR(100) | State/Province |
| country | VARCHAR(100) | Country name |
| zipcode | VARCHAR(20) | Postal code |
| image | VARCHAR(255) | Photo path |
| giving_type_id | INT | FK to giving_types |
| giving_frequency_id | INT | FK to giving_frequencies |
| contribution_amount | DECIMAL(10,2) | Pledged amount |
| currency | VARCHAR(3) | USD, ZMW, etc. |
| start_date | DATE | Partnership start |
| end_date | DATE | Partnership end (nullable) |
| status | ENUM | active/inactive/suspended |
| notes | TEXT | Additional notes |
| is_active | TINYINT | Soft delete flag |
| created_by | INT | Staff ID who created |
| updated_by | INT | Staff ID who updated |
| created_at | TIMESTAMP | Record creation |
| updated_at | TIMESTAMP | Last update |

### Partner Code Format

Auto-generated format: `P-YYYYMMDD-XXXXX`

Example: `P-20250130-00001`

Where:
- `P` = Partner prefix
- `YYYYMMDD` = Date (20250130)
- `XXXXX` = Sequential number (00001)

---

## 🚦 Next Steps - Phase 3

After successful Phase 2 setup, you can move to:

### **Phase 3 - Reports & Dashboard** (3-5 days)

Will include:
1. ✅ Dashboard widget with partner statistics
2. ✅ Contribution summary reports
3. ✅ Giving type breakdown
4. ✅ Frequency analysis
5. ✅ Export functionality
6. ✅ Charts and graphs

**To implement Phase 3:**
- Partner reports controller
- Dashboard widgets
- Report views
- Chart.js integration
- PDF report generation

---

## 📞 Support

### Need Help?

**Check these files:**
- `PHASE_2_PROGRESS.md` - Implementation progress
- `PARTNERS_MODULE_REQUIREMENTS.md` - Full requirements breakdown

**Common Issues:**
1. Migration errors → Check database connection
2. Permissions issues → Grant Partners permissions to your role
3. Menu not showing → Run migration 136
4. Photos not uploading → Check directory permissions

---

## ✅ Setup Checklist

Before going live, verify:

- [ ] All 11 migrations completed (126-136)
- [ ] Partners menu visible in sidebar
- [ ] Can add a test partner successfully
- [ ] Can view partner details
- [ ] Can edit partner
- [ ] Can upload partner photo
- [ ] All 5 tabs load on partner details page
- [ ] Filters work on partner list
- [ ] Export buttons work (Excel, CSV, PDF)
- [ ] Permissions properly assigned to roles
- [ ] Language keys displaying correctly
- [ ] Upload directory is writable
- [ ] Migration controller is disabled (set migration_enabled = FALSE)

---

## 📝 Summary

**Phase 2 Complete:** ✅ **100%**

You now have:
- ✅ Full partner CRUD functionality
- ✅ Professional UI with DataTables
- ✅ Photo upload support
- ✅ Permission-based access control
- ✅ Comprehensive partner details view
- ✅ Export functionality (Excel, CSV, PDF, Print)
- ✅ Database foundation for contributions, permissions, notes, reminders
- ✅ Sidebar menu integration
- ✅ Language file support
- ✅ Migration system ready

**Ready for Phase 3:** Reports & Dashboard

---

*Last Updated: Phase 2 - 100% Complete*
*Generated: 2025-01-30*