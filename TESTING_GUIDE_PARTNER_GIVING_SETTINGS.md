# Testing Guide - Partner Giving Settings

## Prerequisites

Before testing, ensure:
1. ✅ XAMPP/Apache and MySQL are running
2. ✅ Database migrations have been applied
3. ✅ You have access to the admin panel and partner portal
4. ✅ At least one partner record exists

---

## Test Data Setup

### Step 1: Verify Database Tables

Run this query in phpMyAdmin or MySQL console:

```sql
-- Check if tables exist
SHOW TABLES LIKE 'partner%';
SHOW TABLES LIKE 'giving%';

-- Verify partner_giving_settings table structure
DESCRIBE partner_giving_settings;

-- Check giving types
SELECT * FROM giving_types WHERE is_active = 1;

-- Check giving frequencies
SELECT * FROM giving_frequencies WHERE is_active = 1;
```

Expected Results:
- ✓ `partner_giving_settings` table exists
- ✓ 5 giving types (Tuition, Scholarship, Building, General, Sponsorship)
- ✓ 5 frequencies (Once-Off, Weekly, Monthly, Quarterly, Annually)

### Step 2: Create Test Partner (If Needed)

If you don't have a partner yet, create one through the admin panel or run this SQL:

```sql
-- Create a test partner
INSERT INTO `partners` (
    `partner_code`, 
    `account_type`, 
    `firstname`, 
    `lastname`, 
    `email`, 
    `mobileno`, 
    `address`, 
    `city`, 
    `country`, 
    `currency`,
    `status`, 
    `is_active`, 
    `created_at`
) VALUES (
    'PTR-2025-0001',
    'individual',
    'Test',
    'Partner',
    'testpartner@example.com',
    '+263771234567',
    '123 Test Street',
    'Harare',
    'Zimbabwe',
    'USD',
    'active',
    1,
    NOW()
);

-- Get the partner ID for later use
SELECT id, partner_code, firstname, lastname, email FROM partners 
WHERE email = 'testpartner@example.com';
```

---

## Testing Scenarios

### 🧪 TEST 1: Access Partner Settings Page

**Steps:**
1. Log in to the **Partner Portal** (as a student, parent, or staff linked to a partner)
2. Navigate to: **Partners** menu
3. Click on your partner record
4. Click on **"Giving Settings"** or **"Settings"** button
5. Or directly access: `http://localhost/rhemazimbabwe/user/partner/settings?partner_id=1`
   (Replace `1` with your actual partner ID)

**Expected Results:**
- ✓ Settings page loads successfully
- ✓ Partner information card shows on the left
- ✓ Giving settings form shows on the right
- ✓ All giving types are listed in a table
- ✓ Checkboxes are visible next to each type
- ✓ Amount fields are present but disabled
- ✓ Frequency dropdown is populated
- ✓ Currency dropdown shows options

**Screenshot Location:** Partner Settings Page

---

### 🧪 TEST 2: Select Single Giving Type

**Steps:**
1. On the settings page, check **one** giving type (e.g., "Tuition Support")
2. Observe the amount field becomes enabled
3. Enter an amount: `100.00`
4. Check the total at the bottom of the table

**Expected Results:**
- ✓ Amount field becomes enabled when checkbox is checked
- ✓ Can enter numeric values
- ✓ Total shows: `$100.00`
- ✓ Selected types count shows: `1 Type Selected`
- ✓ Total amount display in partner info card updates

**Test Data:**
```
☑ Tuition Support → $100.00
Total: $100.00
```

---

### 🧪 TEST 3: Select Multiple Giving Types

**Steps:**
1. Check multiple giving types:
   - ☑ Tuition Support → `50.00`
   - ☑ Building Fund → `30.00`
   - ☑ Scholarship Fund → `20.00`
2. Observe total calculation

**Expected Results:**
- ✓ All selected amount fields are enabled
- ✓ Total calculates correctly: `$100.00`
- ✓ Selected types count shows: `3 Types Selected`
- ✓ Each amount can be edited independently
- ✓ Total updates in real-time when amounts change

**Test Data:**
```
☑ Tuition Support    → $50.00
☑ Building Fund      → $30.00
☑ Scholarship Fund   → $20.00
☐ General Donation   → (disabled)
☐ Sponsorship        → (disabled)
Total: $100.00
```

---

### 🧪 TEST 4: Real-Time Total Calculation

**Steps:**
1. Select "Tuition Support" with amount `50.00`
2. Without saving, change the amount to `75.00`
3. Add "Building Fund" with amount `25.00`
4. Change "Tuition Support" to `60.00`

**Expected Results:**
- ✓ Total updates immediately after each change
- ✓ No page refresh required
- ✓ Step 1: Total = `$50.00`
- ✓ Step 2: Total = `$75.00`
- ✓ Step 3: Total = `$100.00`
- ✓ Step 4: Total = `$85.00`

---

### 🧪 TEST 5: Checkbox Toggle Behavior

**Steps:**
1. Check "Tuition Support" checkbox
2. Enter amount: `100.00`
3. Uncheck "Tuition Support" checkbox
4. Re-check "Tuition Support" checkbox

**Expected Results:**
- ✓ Step 1: Amount field becomes enabled
- ✓ Step 2: Amount is entered successfully
- ✓ Step 3: Amount field becomes disabled and value clears
- ✓ Step 3: Total becomes `$0.00`
- ✓ Step 4: Amount field becomes enabled but is empty (value was cleared)

---

### 🧪 TEST 6: Select All Functionality

**Steps:**
1. Click the checkbox in the table header (Select All)
2. Observe all giving types are checked
3. Enter different amounts for each type
4. Click the header checkbox again (Unselect All)

**Expected Results:**
- ✓ Step 1: All checkboxes get checked
- ✓ Step 1: All amount fields become enabled
- ✓ Step 3: Can enter amounts for all types
- ✓ Step 3: Total reflects sum of all amounts
- ✓ Step 4: All checkboxes get unchecked
- ✓ Step 4: All amount fields become disabled
- ✓ Step 4: Total becomes `$0.00`

---

### 🧪 TEST 7: Currency Selection

**Steps:**
1. Select "USD" currency
2. Check "Tuition Support" with amount `100.00`
3. Observe currency symbol: `$`
4. Change currency to "ZWL"
5. Observe currency symbol changes to: `Z$`
6. Change to "ZAR"
7. Observe currency symbol changes to: `R`

**Expected Results:**
- ✓ Currency symbol in table header updates
- ✓ Currency symbol in total row updates
- ✓ Currency symbol in partner info card updates
- ✓ Amount values remain the same
- ✓ USD → `$`
- ✓ ZWL → `Z$`
- ✓ ZAR → `R`
- ✓ EUR → `€`
- ✓ GBP → `£`

---

### 🧪 TEST 8: Save Settings - Valid Data

**Steps:**
1. Select multiple giving types with amounts:
   - Tuition Support: `50.00`
   - Building Fund: `30.00`
2. Select frequency: "Monthly"
3. Select currency: "USD"
4. Click "Save Settings" button

**Expected Results:**
- ✓ Button shows loading state: "Saving..."
- ✓ Success message appears: "Giving settings updated successfully!"
- ✓ Page reloads automatically after 1.5 seconds
- ✓ Settings are preserved (checkboxes remain checked)
- ✓ Amounts are preserved
- ✓ Total shows correctly: `$80.00`

**Database Verification:**
```sql
-- Check saved settings
SELECT 
    pgs.id,
    p.partner_code,
    gt.name as giving_type,
    pgs.amount,
    pgs.currency
FROM partner_giving_settings pgs
JOIN partners p ON p.id = pgs.partner_id
JOIN giving_types gt ON gt.id = pgs.giving_type_id
WHERE p.partner_code = 'PTR-2025-0001';

-- Expected: 2 rows showing Tuition ($50) and Building ($30)

-- Check partner record
SELECT 
    partner_code,
    giving_frequency_id,
    contribution_amount,
    currency
FROM partners
WHERE partner_code = 'PTR-2025-0001';

-- Expected: frequency_id=3 (Monthly), contribution_amount=80.00, currency=USD
```

---

### 🧪 TEST 9: Validation - No Type Selected

**Steps:**
1. Leave all giving type checkboxes unchecked
2. Select frequency: "Monthly"
3. Click "Save Settings"

**Expected Results:**
- ✓ Error message appears: "Please select at least one contribution type with an amount greater than 0"
- ✓ Form does not submit
- ✓ No database changes

---

### 🧪 TEST 10: Validation - No Frequency Selected

**Steps:**
1. Check "Tuition Support" with amount `100.00`
2. Leave frequency dropdown at "Select Frequency"
3. Click "Save Settings"

**Expected Results:**
- ✓ Error message appears: "Please select a frequency of contributions"
- ✓ Form does not submit
- ✓ No database changes

---

### 🧪 TEST 11: Validation - Zero Amounts

**Steps:**
1. Check "Tuition Support" checkbox
2. Leave amount field empty or enter `0`
3. Select frequency: "Monthly"
4. Click "Save Settings"

**Expected Results:**
- ✓ Error message appears: "Please select at least one contribution type with an amount greater than 0"
- ✓ Form does not submit

---

### 🧪 TEST 12: Update Existing Settings

**Steps:**
1. Save settings with:
   - Tuition Support: `50.00`
   - Building Fund: `30.00`
   - Frequency: Monthly
2. Reload the page
3. Verify existing settings are loaded
4. Change to:
   - Tuition Support: `75.00` (updated)
   - Scholarship Fund: `25.00` (new)
   - Building Fund: (unchecked/removed)
   - Frequency: Quarterly (updated)
5. Save again

**Expected Results:**
- ✓ Step 2: Previous settings are displayed correctly
- ✓ Step 2: Tuition and Building are checked with correct amounts
- ✓ Step 5: New settings save successfully
- ✓ Step 5: Total updates to `$100.00`
- ✓ Database shows updated records

**Database Verification:**
```sql
-- After update, should show Tuition ($75) and Scholarship ($25)
-- Building Fund should be removed
SELECT gt.name, pgs.amount 
FROM partner_giving_settings pgs
JOIN giving_types gt ON gt.id = pgs.giving_type_id
WHERE pgs.partner_id = 1 AND pgs.is_active = 1;
```

---

### 🧪 TEST 13: Admin Panel - View Contributions

**Steps:**
1. Log in to **Admin Panel**
2. Navigate to: **Partners** → **Partner Contributions**
3. Click "Add Contribution"
4. Verify the form loads correctly with all fields

**Expected Results:**
- ✓ Partner dropdown populated
- ✓ Contribution date field works
- ✓ Amount field accepts decimal values
- ✓ Payment method dropdown has options
- ✓ Reference number field available
- ✓ Attachment upload field available
- ✓ Giving type and frequency dropdowns populated

---

### 🧪 TEST 14: Admin Panel - Add Contribution

**Steps:**
1. In Admin Panel → Add Contribution
2. Fill in the form:
   - Partner: Select test partner
   - Date: Today's date
   - Amount: `100.00`
   - Currency: USD
   - Payment Method: Bank Transfer
   - Transaction ID: `TXN12345`
   - Reference No: `REF-001`
   - Giving Type: Tuition Support
   - Status: Completed
3. Upload a test file (optional)
4. Click "Save Contribution"

**Expected Results:**
- ✓ Success message appears
- ✓ Redirected to contribution details page
- ✓ All data is displayed correctly
- ✓ Reference number shows: `REF-001`
- ✓ Attachment link appears (if uploaded)

**Database Verification:**
```sql
SELECT * FROM partner_contributions 
WHERE reference_no = 'REF-001';
-- Should show the new record with all fields populated
```

---

### 🧪 TEST 15: Admin Panel - View Contribution Details

**Steps:**
1. Navigate to: **Partners** → **Partner Contributions**
2. Click "View" (eye icon) on any contribution
3. Verify all fields are displayed

**Expected Results:**
- ✓ Receipt number displayed
- ✓ Contribution date shown
- ✓ Amount displayed in correct currency
- ✓ Payment method shown
- ✓ Status badge with correct color
- ✓ Transaction ID displayed (or N/A)
- ✓ Reference number displayed (or N/A)
- ✓ Giving type shown
- ✓ Recorded by staff name shown
- ✓ Partner information section populated
- ✓ Partner code shown correctly
- ✓ Partner phone shown correctly (mobileno field)

---

### 🧪 TEST 16: Different Currencies

**Steps:**
1. Test with USD currency:
   - Tuition: `100.00`
   - Save settings
2. Change currency to ZWL:
   - Keep same types
   - Save settings
3. Change currency to ZAR:
   - Keep same types
   - Save settings

**Expected Results:**
- ✓ Each save preserves amounts
- ✓ Currency symbol updates correctly throughout interface
- ✓ Database stores correct currency code
- ✓ Partner info card shows correct symbol

---

### 🧪 TEST 17: Different Frequencies

Test each frequency option:
1. Once-Off
2. Weekly
3. Monthly
4. Quarterly
5. Annually

**Expected Results:**
- ✓ All options selectable
- ✓ Settings save with correct frequency
- ✓ Database updates `giving_frequency_id`

---

## 🐛 Common Issues & Solutions

### Issue 1: Settings Page Not Loading
**Error:** "Unable to load the requested file: user/partner/settings.php"

**Solution:**
1. Check file exists: `application/views/user/partner/settings.php`
2. Verify controller method exists
3. Check for PHP syntax errors

### Issue 2: Model Not Found
**Error:** "Unable to locate the model you have specified: Partner_giving_setting_model"

**Solution:**
1. Verify file exists: `application/models/Partner_giving_setting_model.php`
2. Check filename capitalization
3. Ensure model is loaded in controller

### Issue 3: Database Error - Table Not Found
**Error:** "Table 'ssdb.partner_giving_settings' doesn't exist"

**Solution:**
```bash
# Run this in terminal
php -r "
\$conn = new mysqli('localhost', 'root', '', 'ssdb');
\$sql = file_get_contents('partner_giving_settings_schema.sql');
\$conn->multi_query(\$sql);
echo 'Table created successfully';
"
```

Or manually run `partner_giving_settings_schema.sql` in phpMyAdmin.

### Issue 4: Total Not Calculating
**Check:**
1. Browser console for JavaScript errors (F12)
2. jQuery is loaded
3. Input fields have correct class names
4. JavaScript at bottom of settings.php file

### Issue 5: Settings Not Saving
**Debug:**
```sql
-- Check if records are being created
SELECT COUNT(*) FROM partner_giving_settings;

-- Check for errors in recent records
SELECT * FROM partner_giving_settings ORDER BY created_at DESC LIMIT 5;
```

---

## 📊 Quick Test Checklist

Use this checklist for rapid testing:

- [ ] Can access settings page
- [ ] Can select single giving type
- [ ] Can select multiple giving types
- [ ] Total calculates correctly
- [ ] Checkbox toggles enable/disable amounts
- [ ] Select all works
- [ ] Currency changes update symbols
- [ ] Can save valid settings
- [ ] Validation prevents invalid submission
- [ ] Settings persist after reload
- [ ] Can update existing settings
- [ ] Admin can add contributions with reference_no
- [ ] Admin can view contribution details
- [ ] Partner code displays correctly
- [ ] Partner phone displays correctly

---

## 📹 Testing Video Script

Record these actions for a complete demo:

1. **Login** → Partner Portal
2. **Navigate** → Partners → Settings
3. **Show** → Empty settings (or existing)
4. **Select** → 3 different giving types
5. **Enter** → Different amounts for each
6. **Show** → Real-time total calculation
7. **Select** → Frequency (Monthly)
8. **Click** → Save
9. **Show** → Success message
10. **Reload** → Page to verify persistence
11. **Switch** → To Admin Panel
12. **Navigate** → Partner Contributions
13. **Add** → New contribution
14. **Fill** → All fields including reference_no
15. **Save** → And view details
16. **Show** → All fields displaying correctly

---

## 🎯 Success Criteria

All tests pass when:
- ✅ All 17 test scenarios complete successfully
- ✅ No JavaScript errors in console
- ✅ No PHP errors or warnings
- ✅ All database queries execute correctly
- ✅ Data persists across page reloads
- ✅ All validation works as expected
- ✅ Real-time calculations are accurate
- ✅ Currency changes work smoothly
- ✅ Admin panel shows all fields correctly

---

**Testing Status:** Ready for Testing ✅

All features have been implemented and are ready for comprehensive testing!

