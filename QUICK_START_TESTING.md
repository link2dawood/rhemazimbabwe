# 🚀 Quick Start - Testing Partner Giving Settings

## Step-by-Step Testing Instructions

### 📋 Step 1: Setup Test Data (2 minutes)

1. **Open Terminal/Command Prompt** in the project directory:
   ```bash
   cd D:\xampp8.2\htdocs\rhemazimbabwe
   ```

2. **Run the test data script**:
   ```bash
   php insert_test_data.php
   ```

3. **Expected Output**:
   ```
   ✓ Created test partners
   ✓ Created giving settings
   ✓ Updated partner totals
   📋 Partner: John Doe
      Code: PTR-TEST-0001
      Total Amount: USD 150.00
   ```

---

### 🌐 Step 2: Open Testing Dashboard (1 minute)

**Open in browser**:
```
http://localhost/rhemazimbabwe/test_partner_giving.html
```

This gives you:
- ✅ Interactive testing checklist
- 🔗 Quick access links
- 📊 Progress tracking
- 💾 Auto-saves your progress

---

### 🧪 Step 3: Run Basic Tests (5 minutes)

#### Test 1: Access Settings Page
1. Click **"Partner Settings"** link in the dashboard
2. Or go to: `http://localhost/rhemazimbabwe/user/partner/settings?partner_id=1`
3. ✅ Page should load with partner info and giving types table

#### Test 2: Select Giving Types
1. Check **"Tuition Support"** checkbox
2. Enter amount: `100.00`
3. ✅ Amount field should become enabled
4. ✅ Total should show: `$100.00`

#### Test 3: Multiple Types
1. Also check **"Building Fund"** → Enter: `50.00`
2. Also check **"Scholarship Fund"** → Enter: `25.00`
3. ✅ Total should update to: `$175.00`
4. ✅ "3 Types Selected" badge should show

#### Test 4: Save Settings
1. Select frequency: **"Monthly"**
2. Click **"Save Settings"**
3. ✅ Success message appears
4. ✅ Page reloads with settings preserved

---

### 💾 Step 4: Verify in Database (2 minutes)

**Open phpMyAdmin**: `http://localhost/phpmyadmin`

**Run these queries**:

```sql
-- View saved settings
SELECT 
    p.partner_code,
    gt.name as type,
    pgs.amount
FROM partner_giving_settings pgs
JOIN partners p ON p.id = pgs.partner_id
JOIN giving_types gt ON gt.id = pgs.giving_type_id
WHERE p.id = 1;
```

**Expected**: 3 rows showing your selected types with amounts

---

### 🎛️ Step 5: Test Admin Panel (3 minutes)

1. **Login to Admin**: `http://localhost/rhemazimbabwe/admin`

2. **Navigate to**: Partners → Partner Contributions

3. **Click**: "Add Contribution"

4. **Fill in**:
   - Partner: Select "John Doe"
   - Date: Today
   - Amount: `100.00`
   - Payment Method: "Bank Transfer"
   - Transaction ID: `TXN123`
   - **Reference No**: `REF-001` ← **NEW FIELD**
   - Giving Type: "Tuition Support"
   - Status: "Completed"

5. **Click**: "Save Contribution"

6. ✅ Should redirect to details page
7. ✅ Reference number should display

---

### ✅ Quick Validation Checklist

Run through this in 2 minutes:

- [ ] Settings page loads
- [ ] Can check/uncheck giving types
- [ ] Amount fields enable/disable correctly
- [ ] Total calculates in real-time
- [ ] Can save settings successfully
- [ ] Settings persist after reload
- [ ] Can add contribution with reference_no
- [ ] Admin can view all fields correctly

---

## 🐛 Troubleshooting

### Issue: Settings Page Not Loading

**Check:**
1. XAMPP is running (Apache & MySQL)
2. You're logged in to the partner portal
3. Partner ID is valid: `?partner_id=1`

**Fix:**
```bash
# Verify partner exists
mysql -u root -e "SELECT id, partner_code FROM ssdb.partners LIMIT 5;"
```

### Issue: Table Not Found Error

**Error**: `Table 'ssdb.partner_giving_settings' doesn't exist`

**Fix:**
```bash
php -r "
\$conn = new mysqli('localhost', 'root', '', 'ssdb');
\$sql = file_get_contents('partner_giving_settings_schema.sql');
\$conn->multi_query(\$sql);
echo 'Table created!';
"
```

### Issue: JavaScript Not Working

**Check:**
1. Open browser console (F12)
2. Look for errors
3. Make sure jQuery is loaded

**Test:**
```javascript
// In browser console
console.log(typeof jQuery); // Should show "function"
```

### Issue: Can't Login to Partner Portal

**Solution**: Create a student and link to partner:

```sql
-- Link partner to a student
UPDATE partners 
SET student_id = 1 
WHERE id = 1;

-- Or create test login in students table
-- (Use existing student login)
```

---

## 📊 Testing Scenarios

### Scenario 1: New Partner Setup (Complete Flow)
1. Partner logs in
2. Goes to Settings
3. Selects 3 giving types
4. Sets amounts
5. Chooses "Monthly" frequency
6. Saves settings
7. ✅ Total shows correctly

### Scenario 2: Update Settings
1. Load existing settings
2. Change amounts
3. Add new type
4. Remove old type
5. Change frequency
6. Save
7. ✅ Updates correctly

### Scenario 3: Different Currencies
1. Set USD with amounts
2. Change to ZWL
3. ✅ Symbols update
4. Change to ZAR
5. ✅ Symbols update
6. Save
7. ✅ Currency persists

---

## 🎯 Success Criteria

### ✅ Basic Functionality
- Settings page accessible
- Can select multiple types
- Real-time calculation works
- Can save and reload

### ✅ Data Persistence
- Settings save to database
- Load correctly on page refresh
- Admin can view contributions
- Reference numbers work

### ✅ User Experience
- Smooth interactions
- No JavaScript errors
- Clear error messages
- Success confirmations

---

## 📁 Testing Files Created

1. **TESTING_GUIDE_PARTNER_GIVING_SETTINGS.md** - Detailed test cases
2. **test_partner_giving.html** - Interactive testing dashboard
3. **insert_test_data.php** - Test data generator
4. **partner_giving_settings_schema.sql** - Database schema
5. **QUICK_START_TESTING.md** - This file

---

## 🔗 Quick Access URLs

**Partner Portal**:
- Settings: `http://localhost/rhemazimbabwe/user/partner/settings?partner_id=1`
- Dashboard: `http://localhost/rhemazimbabwe/user/partner`

**Admin Panel**:
- Contributions: `http://localhost/rhemazimbabwe/admin/partnercontributions`
- Add: `http://localhost/rhemazimbabwe/admin/partnercontributions/add`

**Testing Tools**:
- Test Dashboard: `http://localhost/rhemazimbabwe/test_partner_giving.html`
- phpMyAdmin: `http://localhost/phpmyadmin`

---

## ⏱️ Time Estimate

- **Setup**: 2-3 minutes
- **Basic Tests**: 5-7 minutes
- **Database Verification**: 2 minutes
- **Admin Panel Tests**: 3-5 minutes
- **Full Test Suite**: 15-20 minutes

---

## 📞 Need Help?

1. Check **TESTING_GUIDE_PARTNER_GIVING_SETTINGS.md** for detailed tests
2. Open browser console (F12) for JavaScript errors
3. Check phpMyAdmin for database issues
4. Verify XAMPP services are running

---

**Happy Testing! 🚀**

All features are implemented and ready for testing. Start with Step 1 and work through the checklist!




