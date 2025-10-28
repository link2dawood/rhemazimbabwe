# Giving Types CRUD System Documentation

## Overview
Complete CRUD (Create, Read, Update, Delete) system for managing "Giving Types" used by the Partner Management module in the Rhema Zimbabwe School Management System.

**Version:** 1.0
**Date Created:** 2024
**Author:** Rhema Zimbabwe School Development Team

---

## Table of Contents
1. [System Features](#system-features)
2. [Database Structure](#database-structure)
3. [File Structure](#file-structure)
4. [User Access & Permissions](#user-access--permissions)
5. [How to Use](#how-to-use)
6. [URL Routes](#url-routes)
7. [Technical Details](#technical-details)
8. [Testing Guide](#testing-guide)
9. [Troubleshooting](#troubleshooting)

---

## System Features

### Core Functionality
- ✅ **List/Index Page** - View all giving types with DataTables (sorting, searching, pagination)
- ✅ **Add/Create** - Add new giving types with validation
- ✅ **Edit/Update** - Modify existing giving types
- ✅ **View/Show** - Display detailed information about a giving type
- ✅ **Delete** - Remove giving types (with usage protection)
- ✅ **Toggle Status** - Activate/deactivate giving types
- ✅ **Sort Order Management** - Control display order in lists

### Advanced Features
- **Unique Validation** - Name and code uniqueness checks
- **Usage Protection** - Cannot delete types in use by partners
- **Responsive Design** - Mobile-friendly using Bootstrap/AdminLTE
- **Search & Filter** - Built-in DataTables search functionality
- **Breadcrumb Navigation** - Easy navigation between pages
- **Flash Messages** - Success/error notifications
- **RBAC Integration** - Role-based access control
- **Activity Tracking** - Created/Updated timestamps
- **Partner Statistics** - Shows usage count per giving type

---

## Database Structure

### Table: `giving_types`

```sql
CREATE TABLE `giving_types` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `code` varchar(50) DEFAULT NULL,
    `description` text DEFAULT NULL,
    `is_active` tinyint(1) NOT NULL DEFAULT 1,
    `sort_order` int(11) NOT NULL DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`name`),
    UNIQUE KEY (`code`)
) ENGINE=InnoDB;
```

### Field Descriptions

| Field | Type | Description | Validation |
|-------|------|-------------|------------|
| `id` | int(11) | Primary key, auto-increment | Auto-generated |
| `name` | varchar(100) | Display name | Required, unique |
| `code` | varchar(50) | Short identifier code | Optional, unique if provided |
| `description` | text | Detailed description | Optional |
| `is_active` | tinyint(1) | Status (1=active, 0=inactive) | Default: 1 |
| `sort_order` | int(11) | Display order priority | Default: 0, numeric |
| `created_at` | timestamp | Creation timestamp | Auto-generated |
| `updated_at` | timestamp | Last update timestamp | Auto-updated |

### Relationships
- **Partners Table:** `partners.giving_type_id` references `giving_types.id`
- A giving type can be used by multiple partners (one-to-many)

---

## File Structure

```
application/
├── controllers/
│   └── admin/
│       └── Givingtypes.php                 # Main controller
├── models/
│   └── Type_model.php                      # Model (already exists)
├── views/
│   └── admin/
│       └── givingtypes/
│           ├── index.php                   # List page
│           ├── add.php                     # Add form
│           ├── edit.php                    # Edit form
│           └── show.php                    # Details page
├── migrations/
│   └── 128_create_giving_types_table.php   # Database migration
└── sql/
    └── giving_types_table.sql              # SQL structure documentation
```

---

## User Access & Permissions

### Required Permissions
This system uses the existing **Partners** permission group:

| Action | Required Permission | Description |
|--------|-------------------|-------------|
| View List | `partners → can_view` | View giving types list |
| View Details | `partners → can_view` | View giving type details |
| Add New | `partners → can_add` | Create new giving types |
| Edit | `partners → can_edit` | Modify existing giving types |
| Delete | `partners → can_delete` | Delete giving types (if not in use) |

### User Roles with Access
- **System Administrator** - Full access
- **Accountant** - May have view access
- **Other staff** - Based on assigned permissions

---

## How to Use

### 1. Accessing the System
Navigate to: **Admin Panel → Partners → Giving Types**

Or directly via URL: `http://yoursite.com/admin/givingtypes`

### 2. Viewing All Giving Types
- The index page displays all giving types in a DataTable
- Use the search box to filter results
- Click column headers to sort
- View usage statistics (number of partners using each type)

### 3. Adding a New Giving Type
1. Click **"Add New Giving Type"** button
2. Fill in the required fields:
   - **Name*** (required, unique)
   - **Code** (optional, unique, auto-uppercase)
   - **Description** (optional)
   - **Sort Order** (default: 0)
   - **Status** (Active/Inactive checkbox)
3. Click **"Save Giving Type"**
4. You'll be redirected to the details page

### 4. Editing a Giving Type
1. From the list, click the **Edit** (pencil) button
2. Modify the fields as needed
3. Click **"Update Giving Type"**
4. Changes are saved and you're redirected to details

### 5. Viewing Details
1. Click the **View** (eye) button from the list
2. See complete information including:
   - All field values
   - Usage statistics
   - List of partners using this type
   - Quick action buttons

### 6. Deleting a Giving Type
⚠️ **Important:** You can only delete giving types that are NOT in use.

1. From the list or details page, click **Delete** (trash icon)
2. Confirm the deletion
3. Type will be permanently removed

**Note:** If a giving type is being used by any partner, deletion will be blocked with an error message.

### 7. Managing Status
- **Active** types appear in partner selection dropdowns
- **Inactive** types are hidden from new selections but remain for existing partners
- Toggle status by editing the giving type

### 8. Sort Order
- Lower numbers appear first (0 = highest priority)
- Used in dropdown lists and reports
- Update via the edit form

---

## URL Routes

All routes use the standard CodeIgniter pattern:

| Action | URL | Method |
|--------|-----|--------|
| List | `/admin/givingtypes` | GET |
| DataTable Data | `/admin/givingtypes/getlist` | POST (AJAX) |
| Add Form | `/admin/givingtypes/add` | GET |
| Store New | `/admin/givingtypes/add` | POST |
| Show Details | `/admin/givingtypes/show/{id}` | GET |
| Edit Form | `/admin/givingtypes/edit/{id}` | GET |
| Update | `/admin/givingtypes/edit/{id}` | POST |
| Delete | `/admin/givingtypes/delete/{id}` | GET |
| Toggle Status | `/admin/givingtypes/toggle_status/{id}` | POST (AJAX) |

---

## Technical Details

### Controller: `Givingtypes.php`

**Location:** `application/controllers/admin/Givingtypes.php`

**Extends:** `Admin_Controller` (provides authentication and RBAC)

**Dependencies:**
- `type_model` - Database operations
- `form_validation` - Input validation
- `partner_model` - For showing related partners

**Key Methods:**
```php
index()                 // Display list page
getlist()              // AJAX data for DataTable
add()                  // Show add form & process submission
edit($id)              // Show edit form & process update
show($id)              // Display details page
delete($id)            // Delete giving type
toggle_status($id)     // Toggle active status (AJAX)
update_sort_order()    // Update sort order (AJAX)
```

### Model: `Type_model.php`

**Location:** `application/models/Type_model.php`

**Extends:** `MY_Model`

**Key Methods:**
```php
getAll($active_only)           // Get all types
getById($id)                   // Get by ID
getByCode($code)               // Get by code
add($data)                     // Insert new
update($id, $data)             // Update existing
delete($id)                    // Delete type
getUsageCount($id)             // Count partners using type
getWithCounts()                // Get with partner counts
toggleStatus($id)              // Toggle active status
reorder($order)                // Update sort order
getDropdown()                  // For dropdown lists
```

### Views

**Theme:** AdminLTE
**Framework:** Bootstrap 3
**DataTables:** Version compatible with AdminLTE

All views include:
- Responsive design
- Breadcrumb navigation
- Flash message handling
- Tooltip initialization
- Auto-hiding alerts (5 seconds)

---

## Testing Guide

### Manual Testing Checklist

#### 1. List Page Testing
- [ ] Page loads without errors
- [ ] All giving types display correctly
- [ ] DataTable search works
- [ ] DataTable sorting works
- [ ] DataTable pagination works
- [ ] Action buttons appear based on permissions
- [ ] Usage count displays correctly
- [ ] Active/inactive labels show correctly

#### 2. Add Functionality Testing
- [ ] Add form loads
- [ ] Required field validation works (name)
- [ ] Unique name validation works
- [ ] Unique code validation works (if provided)
- [ ] Code auto-converts to uppercase
- [ ] Sort order accepts numeric values only
- [ ] Active checkbox works
- [ ] Success message displays after save
- [ ] Redirects to details page after save
- [ ] Error messages display for invalid data

#### 3. Edit Functionality Testing
- [ ] Edit form loads with existing data
- [ ] All fields populate correctly
- [ ] Validation works (excluding current record)
- [ ] Update saves successfully
- [ ] Timestamps update correctly
- [ ] Usage warning displays if type is in use
- [ ] Cancel button returns to list

#### 4. View Details Testing
- [ ] Details page loads
- [ ] All information displays correctly
- [ ] Partner list shows if type is in use
- [ ] Usage statistics are accurate
- [ ] Quick action buttons work
- [ ] Breadcrumbs function correctly

#### 5. Delete Functionality Testing
- [ ] Delete works for unused types
- [ ] Delete blocked for types in use
- [ ] Confirmation dialog appears
- [ ] Success/error messages display
- [ ] Record actually removed from database

#### 6. Permission Testing
- [ ] Staff without permissions see "Access Denied"
- [ ] View-only users can't see add/edit/delete buttons
- [ ] Each permission level behaves correctly

### Database Testing
```sql
-- Check table exists
SHOW TABLES LIKE 'giving_types';

-- View structure
DESCRIBE giving_types;

-- Check indexes
SHOW INDEXES FROM giving_types;

-- Test unique constraints
INSERT INTO giving_types (name, code) VALUES ('Test', 'TEST');
INSERT INTO giving_types (name, code) VALUES ('Test', 'TEST2'); -- Should fail

-- Check relationships
SELECT gt.*, COUNT(p.id) as partner_count
FROM giving_types gt
LEFT JOIN partners p ON p.giving_type_id = gt.id
GROUP BY gt.id;
```

### Common Test Scenarios

**Scenario 1: Create Complete Record**
- Name: "Tuition Support"
- Code: "TUITION"
- Description: "Direct support for student tuition"
- Sort Order: 1
- Status: Active
- **Expected:** Record created successfully

**Scenario 2: Duplicate Name**
- Create two types with same name
- **Expected:** Error message about duplicate name

**Scenario 3: Delete In-Use Type**
- Assign type to a partner
- Try to delete the type
- **Expected:** Error preventing deletion

**Scenario 4: Status Toggle**
- Create active type
- Toggle to inactive
- **Expected:** Status changes, remains in database

---

## Troubleshooting

### Common Issues & Solutions

#### Issue 1: "Access Denied" Error
**Solution:** Check user permissions under Admin → User Roles → Partners permissions

#### Issue 2: DataTable Not Loading
**Solutions:**
- Clear browser cache
- Check browser console for JavaScript errors
- Verify jQuery and DataTables libraries are loaded
- Check `/admin/givingtypes/getlist` endpoint returns JSON

#### Issue 3: Validation Not Working
**Solutions:**
- Check `form_validation` library is loaded
- Verify validation rules in controller
- Check for JavaScript conflicts

#### Issue 4: Cannot Delete Type
**Solutions:**
- Check if type is being used by partners
- Verify `can_delete` permission
- Check database foreign key constraints

#### Issue 5: Code Not Converting to Uppercase
**Solution:** JavaScript may be disabled. Check browser settings.

#### Issue 6: Page Not Found (404)
**Solutions:**
- Verify `.htaccess` is configured correctly
- Check CodeIgniter routes
- Ensure controller file name matches class name (case-sensitive on Linux)

### Debug Mode
To enable debug mode:
```php
// In application/config/config.php
$config['log_threshold'] = 4; // Enable all logging

// Check logs at application/logs/
```

### Database Issues
```sql
-- Check table structure
DESCRIBE giving_types;

-- View all records
SELECT * FROM giving_types;

-- Check for orphaned relationships
SELECT p.* FROM partners p
LEFT JOIN giving_types gt ON p.giving_type_id = gt.id
WHERE p.giving_type_id IS NOT NULL AND gt.id IS NULL;
```

---

## Best Practices

### For Administrators
1. **Regular Review** - Periodically review and clean up unused giving types
2. **Descriptive Names** - Use clear, self-explanatory names
3. **Consistent Codes** - Maintain a consistent code naming convention
4. **Sort Order** - Set logical sort orders for better UX
5. **Status Management** - Deactivate instead of deleting when possible

### For Developers
1. **Validation** - Always validate input on both client and server side
2. **Error Handling** - Provide clear error messages to users
3. **Logging** - Log critical operations for audit trails
4. **Backup** - Backup database before major changes
5. **Testing** - Test all CRUD operations after deployment

---

## Future Enhancements

Potential improvements for future versions:

1. **Bulk Operations**
   - Bulk activate/deactivate
   - Bulk delete (unused types)
   - Bulk sort order update

2. **Advanced Features**
   - Import/export giving types (CSV/Excel)
   - Audit log for changes
   - Color coding for different types
   - Icon assignment per type

3. **Reporting**
   - Usage analytics dashboard
   - Contribution trends by type
   - Export reports to PDF

4. **API Integration**
   - RESTful API endpoints
   - Mobile app integration

---

## Support & Contact

For technical support or questions:
- **Email:** support@rhemazimbabwe.com
- **Documentation:** Check TESTING_GUIDE_PARTNER_GIVING_SETTINGS.md
- **GitHub Issues:** (if applicable)

---

## Version History

| Version | Date | Changes |
|---------|------|---------|
| 1.0 | 2024 | Initial release with full CRUD functionality |

---

## License

Copyright © 2024 Rhema Zimbabwe School. All rights reserved.

---

**End of Documentation**
