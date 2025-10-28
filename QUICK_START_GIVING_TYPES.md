# Quick Start Guide - Giving Types CRUD System

## Getting Started in 5 Minutes

### Step 1: Access the System
1. Log in to the admin panel
2. Navigate to **Partners → Giving Types**
3. URL: `http://localhost/rhemazimbabwe/admin/givingtypes`

### Step 2: Add Your First Giving Type
1. Click **"Add New Giving Type"** button
2. Fill in the form:
   ```
   Name: Tuition Support
   Code: TUITION
   Description: Direct financial support for student tuition fees
   Sort Order: 1
   Status: ✓ Active
   ```
3. Click **"Save Giving Type"**
4. You'll see the details page

### Step 3: Test the System
- ✅ View the list of giving types
- ✅ Search for types using the search box
- ✅ Edit a giving type
- ✅ View details
- ✅ Try to delete (will fail if in use by partners)

---

## Quick Reference

### URL Routes
| Action | URL |
|--------|-----|
| List All | `/admin/givingtypes` |
| Add New | `/admin/givingtypes/add` |
| View Details | `/admin/givingtypes/show/1` |
| Edit | `/admin/givingtypes/edit/1` |
| Delete | `/admin/givingtypes/delete/1` |

### Required Fields
- **Name** (required, unique)
- All other fields are optional

### Field Validation
- Name must be unique
- Code must be unique (if provided)
- Sort order must be numeric
- Code auto-converts to uppercase

---

## Quick Test Checklist

Copy and paste these test records:

### Test Record 1
```
Name: Tuition Support
Code: TUITION
Description: Direct support for student tuition fees
Sort Order: 1
Status: Active
```

### Test Record 2
```
Name: Building Fund
Code: BUILDING
Description: Contributions towards infrastructure development
Sort Order: 2
Status: Active
```

### Test Record 3
```
Name: Scholarship Fund
Code: SCHOLARSHIP
Description: Dedicated to student scholarships
Sort Order: 3
Status: Active
```

---

## Common Operations

### How to Add a Giving Type
```
1. Click "Add New Giving Type"
2. Enter name (required)
3. Enter code (optional, but recommended)
4. Add description
5. Set sort order (lower = higher priority)
6. Check "Active" if it should be available
7. Click "Save"
```

### How to Edit a Giving Type
```
1. From list, click Edit (pencil icon)
2. Modify fields
3. Click "Update Giving Type"
```

### How to Delete a Giving Type
```
1. From list, click Delete (trash icon)
2. Confirm deletion
3. Note: Cannot delete if used by partners
```

### How to View Details
```
1. From list, click View (eye icon)
2. See all information and usage statistics
```

---

## Troubleshooting

### Can't See the Menu?
- Check user permissions: Admin → User Roles
- Need: `partners → can_view` permission

### Can't Add/Edit/Delete?
- Check permissions:
  - Add: `partners → can_add`
  - Edit: `partners → can_edit`
  - Delete: `partners → can_delete`

### Page Not Found (404)?
- Clear browser cache
- Check `.htaccess` exists in root
- Verify controller file: `application/controllers/admin/Givingtypes.php`

### DataTable Not Working?
- Check browser console for errors
- Verify jQuery is loaded
- Clear browser cache

---

## Features at a Glance

✅ **List View**
- DataTables with search, sort, pagination
- Shows usage count (partners using each type)
- Quick action buttons

✅ **Add/Edit Forms**
- Form validation
- Auto-uppercase for codes
- Help text and guidelines
- Responsive design

✅ **Details Page**
- Complete information display
- Usage statistics
- List of partners using the type
- Quick action buttons

✅ **Delete Protection**
- Cannot delete types in use
- Confirmation dialog
- Clear error messages

---

## Sample Data (for testing)

Run this SQL to populate with sample data:

```sql
INSERT INTO `giving_types` (`name`, `code`, `description`, `is_active`, `sort_order`) VALUES
('Tuition Support', 'TUITION', 'Direct financial support for student tuition fees and educational expenses', 1, 1),
('Building Fund', 'BUILDING', 'Contributions towards infrastructure development and facility improvements', 1, 2),
('Scholarship Fund', 'SCHOLARSHIP', 'Dedicated funding for student scholarships and financial aid programs', 1, 3),
('General Fund', 'GENERAL', 'Unrestricted donations that can be used for any school operational needs', 1, 4),
('Special Projects', 'PROJECTS', 'Support for specific initiatives, programs, or special school projects', 1, 5);
```

---

## Next Steps

1. ✅ Test all CRUD operations
2. ✅ Add real giving types for your school
3. ✅ Assign giving types to partners
4. ✅ Review usage statistics
5. ✅ Set up proper sort orders

---

## Need More Help?

📖 **Full Documentation:** See `GIVING_TYPES_CRUD_DOCUMENTATION.md`

🗄️ **Database Structure:** See `application/sql/giving_types_table.sql`

🧪 **Testing Guide:** See section in full documentation

---

**Happy Managing! 🎉**
