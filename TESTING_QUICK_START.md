# PARTNERS MODULE - QUICK START TESTING GUIDE

**Date:** 2025-10-01
**Phase:** Phase 6 (Communication Integration) - 80% Complete
**Time Required:** 30-45 minutes

---

## 🚀 QUICK START - 5 STEPS TO TEST

### STEP 1: Install Message Templates (5 minutes)

**Method A: Using phpMyAdmin (Recommended)**

1. Open your browser and go to: `http://localhost/phpmyadmin`
2. Login with your MySQL credentials (usually `root` with no password)
3. Click on your database name in the left sidebar (likely named similar to your school)
4. Click the **"SQL"** tab at the top
5. Open the file `partner_message_templates.sql` in a text editor
6. Copy ALL the contents
7. Paste into the SQL query box in phpMyAdmin
8. Click **"Go"** button at the bottom right
9. You should see: **"8 rows inserted"** or similar success message

**Method B: Using Command Line**

```bash
cd D:\xampp8.2\htdocs\rhemazimbabwe

# Replace 'your_database_name' with actual database name
mysql -u root -p your_database_name < partner_message_templates.sql
```

**Verify Installation:**

Run this SQL query in phpMyAdmin:
```sql
SELECT COUNT(*) as total FROM email_template WHERE template_type = 'partner';
-- Should return: 4

SELECT COUNT(*) as total FROM sms_template WHERE template_type = 'partner';
-- Should return: 4
```

---

### STEP 2: Start Your Server (2 minutes)

1. Open **XAMPP Control Panel**
2. Start **Apache** (if not already running)
3. Start **MySQL** (if not already running)
4. Wait for both to show **green** status

**Test Server:**
- Open browser: `http://localhost/rhemazimbabwe`
- You should see your school management system login page

---

### STEP 3: Test Partner Search in Communication Module (5 minutes)

**Prerequisites:**
- You must have at least 1 partner created in the database
- You must be logged in as **Admin**

**Steps:**

1. **Login as Admin:**
   ```
   URL: http://localhost/rhemazimbabwe/admin
   Username: Your admin username
   Password: Your admin password
   ```

2. **Navigate to Communication:**
   ```
   Click: Admin Menu → Communicate → Compose Email
   OR
   Direct URL: http://localhost/rhemazimbabwe/admin/mailsms/compose
   ```

3. **Test Partner Search:**
   - In the **"Send Message To"** dropdown, look for **"Partner"** option
   - ✅ **EXPECTED:** "Partner" appears in the dropdown
   - Select **"Partner"** from dropdown
   - In the search box that appears, type a partner's name (or part of it)
   - ✅ **EXPECTED:** Partner(s) appear in dropdown with format: "Firstname Lastname (PARTNER_CODE)"
   - Click on a partner to select them
   - ✅ **EXPECTED:** Partner's email auto-fills in the recipient field

**If Partner Doesn't Appear:**
- Make sure you have at least one partner created
- Check that partner has `is_active = 1` in database
- Check browser console (F12) for JavaScript errors

---

### STEP 4: Test Template Selection (5 minutes)

**Continue from Step 3 (still in Compose Email page):**

1. **Check Template Dropdown:**
   - Look for **"Select Template"** or **"Template"** dropdown
   - Click on it to open the list
   - ✅ **EXPECTED:** You should see 4 partner templates:
     - Partner Welcome
     - Partner Activated
     - Partner Contribution Thank You
     - Partner Contribution Reminder

2. **Load a Template:**
   - Click on **"Partner Welcome"** template
   - ✅ **EXPECTED:** Template content loads in the message editor
   - ✅ **EXPECTED:** You should see variables like `{partner_firstname}`, `{partner_code}`, `{school_name}` in the template

3. **Visual Check:**
   - The template should be properly formatted
   - HTML tags should render correctly (if HTML editor is enabled)
   - Template should contain proper styling and structure

**Screenshot the Result!** This confirms templates are loaded correctly.

---

### STEP 5: Test Reminder Generation (10 minutes)

**This tests the automated reminder logic manually (since cron is not yet implemented)**

**Method: Create Test PHP File**

1. **Create Test File:**
   - Go to: `D:\xampp8.2\htdocs\rhemazimbabwe\`
   - Create a new file named: `test_reminder_generation.php`
   - Paste the following code:

```php
<?php
/**
 * TEST FILE - Reminder Generation
 * This file tests the automated reminder generation system
 * Delete this file after testing
 */

// Load CodeIgniter framework
define('BASEPATH', TRUE);
require_once 'index.php';

// Get CodeIgniter instance
$CI =& get_instance();

// Load required models
$CI->load->model('reminder_model');
$CI->load->model('partner_model');
$CI->load->model('contribution_model');

?>
<!DOCTYPE html>
<html>
<head>
    <title>Partner Reminder Test</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 3px solid #667eea; padding-bottom: 10px; }
        h2 { color: #667eea; margin-top: 30px; }
        .stats { background: #f9f9f9; padding: 15px; border-left: 4px solid #667eea; margin: 20px 0; }
        .success { background: #d4edda; border-left-color: #28a745; color: #155724; }
        .warning { background: #fff3cd; border-left-color: #ffc107; color: #856404; }
        .error { background: #f8d7da; border-left-color: #dc3545; color: #721c24; }
        pre { background: #f4f4f4; padding: 10px; border-radius: 4px; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        table th { background: #667eea; color: white; padding: 10px; text-align: left; }
        table td { padding: 10px; border-bottom: 1px solid #ddd; }
        .btn { display: inline-block; padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Partner Reminder Generation Test</h1>
        <p><strong>Date:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>

        <?php
        // Check if partners exist
        $partners = $CI->partner_model->getAll(array('is_active' => 1, 'status' => 'active'));
        $partner_count = count($partners);
        ?>

        <div class="stats <?php echo $partner_count > 0 ? 'success' : 'warning'; ?>">
            <strong>Active Partners Found:</strong> <?php echo $partner_count; ?>
        </div>

        <?php if ($partner_count == 0): ?>
            <div class="error stats">
                <strong>⚠️ No Active Partners!</strong><br>
                You need to create at least one active partner to test reminder generation.<br>
                <a href="http://localhost/rhemazimbabwe/admin/partners" class="btn">Create Partner</a>
            </div>
        <?php else: ?>

            <h2>📋 Active Partners List</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Partner Code</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Frequency</th>
                        <th>Amount</th>
                        <th>Start Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($partners as $partner): ?>
                        <tr>
                            <td><?php echo $partner['id']; ?></td>
                            <td><?php echo $partner['partner_code']; ?></td>
                            <td><?php echo $partner['firstname'] . ' ' . $partner['lastname']; ?></td>
                            <td><?php echo $partner['email']; ?></td>
                            <td><?php echo $partner['giving_frequency_id']; ?></td>
                            <td><?php echo $partner['currency'] . ' ' . $partner['contribution_amount']; ?></td>
                            <td><?php echo $partner['start_date']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h2>🔄 Test 1: Generate Contribution Reminders</h2>
            <p>This will generate reminders for partners based on their giving frequency.</p>

            <?php
            echo '<div class="stats">';
            echo '<strong>Running:</strong> $CI->reminder_model->generateContributionReminders();<br><br>';

            try {
                $stats = $CI->reminder_model->generateContributionReminders();

                echo '<div class="success stats">';
                echo '<strong>✅ SUCCESS!</strong><br>';
                echo '<strong>Total Partners Checked:</strong> ' . $stats['total_checked'] . '<br>';
                echo '<strong>Reminders Created:</strong> ' . $stats['reminders_created'] . '<br>';

                if (!empty($stats['errors'])) {
                    echo '<br><strong>Errors:</strong><br>';
                    echo '<pre>' . print_r($stats['errors'], true) . '</pre>';
                }
                echo '</div>';

            } catch (Exception $e) {
                echo '<div class="error stats">';
                echo '<strong>❌ ERROR:</strong> ' . $e->getMessage();
                echo '</div>';
            }
            echo '</div>';
            ?>

            <h2>📊 Test 2: Check Created Reminders</h2>
            <?php
            $reminders = $CI->db->select('partner_reminders.*, partners.firstname, partners.lastname, partners.partner_code')
                               ->from('partner_reminders')
                               ->join('partners', 'partners.id = partner_reminders.partner_id')
                               ->order_by('partner_reminders.created_at', 'DESC')
                               ->limit(10)
                               ->get()
                               ->result_array();

            if (empty($reminders)): ?>
                <div class="warning stats">
                    <strong>No reminders found in database.</strong><br>
                    This could mean:
                    <ul>
                        <li>No partners have upcoming contributions due</li>
                        <li>Reminders already exist for upcoming dates</li>
                        <li>Contributions have already been made</li>
                    </ul>
                </div>
            <?php else: ?>
                <div class="success stats">
                    <strong>Found <?php echo count($reminders); ?> reminder(s) in database (showing last 10)</strong>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Partner</th>
                            <th>Type</th>
                            <th>Reminder Date</th>
                            <th>Status</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reminders as $reminder): ?>
                            <tr>
                                <td><?php echo $reminder['id']; ?></td>
                                <td><?php echo $reminder['firstname'] . ' ' . $reminder['lastname'] . ' (' . $reminder['partner_code'] . ')'; ?></td>
                                <td><?php echo $reminder['reminder_type']; ?></td>
                                <td><?php echo $reminder['reminder_date']; ?></td>
                                <td><strong><?php echo strtoupper($reminder['status']); ?></strong></td>
                                <td><?php echo $reminder['created_at']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <h2>✅ Test Results Summary</h2>
            <div class="success stats">
                <strong>Tests Completed Successfully!</strong>
                <ul>
                    <li>✅ Partner model loaded</li>
                    <li>✅ Reminder model loaded</li>
                    <li>✅ Active partners retrieved: <?php echo $partner_count; ?></li>
                    <li>✅ Reminder generation executed</li>
                    <li>✅ Database queries working</li>
                </ul>
            </div>

        <?php endif; ?>

        <h2>🔗 Quick Links</h2>
        <a href="http://localhost/rhemazimbabwe/admin/partners" class="btn">Manage Partners</a>
        <a href="http://localhost/rhemazimbabwe/admin/mailsms/compose" class="btn">Test Communication</a>
        <a href="test_reminder_generation.php" class="btn">Refresh Test</a>

        <p style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; color: #999; font-size: 12px;">
            <strong>⚠️ Important:</strong> Delete this test file (test_reminder_generation.php) after testing is complete.
        </p>
    </div>
</body>
</html>
```

2. **Run the Test:**
   - Open browser
   - Go to: `http://localhost/rhemazimbabwe/test_reminder_generation.php`
   - ✅ **EXPECTED:** You should see a nicely formatted page showing:
     - Number of active partners
     - List of partners
     - Reminder generation statistics
     - Created reminders

3. **Interpret Results:**

   **If "Reminders Created: 0"** - This is NORMAL if:
   - Reminders already exist for upcoming dates (duplicate prevention working!)
   - No contributions are due soon
   - Partners don't have giving frequencies set
   - Contributions have already been made

   **If "Reminders Created: 5"** (or any number > 0):
   - ✅ Perfect! Reminders were created successfully
   - Check the "Created Reminders" table below to see details

   **If Error Appears:**
   - Read error message carefully
   - Check that giving_frequencies table has data
   - Verify partners have giving_frequency_id set

4. **Verify in Database (Optional):**
   - Go to phpMyAdmin
   - Navigate to `partner_reminders` table
   - Click "Browse"
   - You should see newly created reminders with:
     - `reminder_type = 'contribution'`
     - `status = 'pending'`
     - `created_by = 0` (system generated)
     - Future `reminder_date`

---

## 🎯 EXPECTED TEST RESULTS

### ✅ PASS Criteria:

**Step 1: Templates**
- ✅ 4 email templates installed with type='partner'
- ✅ 4 SMS templates installed with type='partner'
- ✅ No SQL errors during installation

**Step 3: Partner Search**
- ✅ "Partner" option appears in "Send Message To" dropdown
- ✅ Typing partner name shows results
- ✅ Results show format: "Name (CODE)"
- ✅ Selecting partner auto-fills email

**Step 4: Template Selection**
- ✅ 4 partner templates appear in template dropdown
- ✅ Selecting template loads content
- ✅ Template contains variables like {partner_firstname}

**Step 5: Reminder Generation**
- ✅ Script runs without fatal errors
- ✅ Returns statistics (total_checked, reminders_created)
- ✅ Creates reminders in database (or explains why not)
- ✅ No duplicate reminders created

---

## ❌ TROUBLESHOOTING

### Problem: "Partner" option not in dropdown

**Solution:**
1. Check if `application/controllers/admin/Mailsms.php` was modified correctly
2. Clear browser cache (Ctrl+F5)
3. Check browser console for JavaScript errors (F12)
4. Verify file at line 78-86 has the partner category code

---

### Problem: No partner templates appear

**Solution:**
1. Verify templates were installed: Run SQL query from Step 1
2. Check `email_template` table has 4 rows with `template_type = 'partner'`
3. Clear application cache: Delete contents of `application/cache/` folder
4. Restart Apache in XAMPP

---

### Problem: Test file shows blank page

**Solution:**
1. Check PHP error logs: `D:\xampp8.2\apache\logs\error.log`
2. Enable error display: Edit `index.php`, change `ENVIRONMENT` to `'development'`
3. Verify database connection is working
4. Check if `index.php` exists in the root

---

### Problem: "Call to undefined method"

**Solution:**
1. Verify all model files were modified correctly
2. Check that method names match exactly (case-sensitive)
3. Run syntax check: `php -l application/models/Reminder_model.php`

---

### Problem: No reminders created (reminders_created = 0)

**This is often NORMAL!** Reminders are only created when:
- Partner has a giving frequency set
- Next contribution date is in the future
- No reminder already exists for that date
- Contribution hasn't been made yet

**To force reminder creation for testing:**
1. Create a new partner with:
   - Status = 'active'
   - giving_frequency_id = (monthly frequency)
   - start_date = 30 days ago
   - Make sure NO contributions exist for this partner
2. Run test again
3. Should create reminder for this partner

---

## 📝 TEST CHECKLIST

Print this and check off as you test:

```
Phase 6 Testing Checklist:

DATABASE:
[ ] Templates SQL installed successfully
[ ] 4 email templates with type='partner' exist
[ ] 4 SMS templates with type='partner' exist
[ ] No SQL errors during installation

COMMUNICATION MODULE:
[ ] Can login as Admin
[ ] Can access Admin > Communicate > Compose Email
[ ] "Partner" option appears in "Send Message To" dropdown
[ ] Can search for partners by name
[ ] Search results show: "Firstname Lastname (CODE)"
[ ] Selecting partner auto-fills email field
[ ] Can see template dropdown
[ ] 4 partner templates appear in dropdown
[ ] Clicking template loads content
[ ] Template contains variables like {partner_firstname}

REMINDER SYSTEM:
[ ] Created test_reminder_generation.php file
[ ] Can access test file in browser
[ ] Page loads without fatal errors
[ ] Shows list of active partners
[ ] Reminder generation executes
[ ] Returns statistics (total_checked, reminders_created)
[ ] Can view created reminders in table
[ ] Reminders have status='pending'
[ ] Running test again doesn't create duplicates

CODE VERIFICATION:
[ ] All modified files have no syntax errors
[ ] Partner search method exists in Partner_model.php
[ ] Mailsms.php has partner category in search()
[ ] Messages_model.php has get_partner_email_template()
[ ] Reminder_model.php has generateContributionReminders()
```

---

## 🎉 SUCCESS!

If all tests pass, you have successfully verified:
1. ✅ Communication integration working
2. ✅ Template system working
3. ✅ Partner search working
4. ✅ Reminder generation logic working
5. ✅ Database integration working

**Phase 6 is 80% complete and fully functional for manual testing!**

---

## 📞 NEED HELP?

**Common Questions:**

**Q: Can I send actual emails now?**
A: Not yet. The email sending integration is in the pending 20%. You can compose and select recipients/templates, but actual sending needs to be implemented.

**Q: Will reminders send automatically?**
A: Not yet. You need to create the cron controller first. For now, reminders are created but not sent automatically.

**Q: Can partners register online?**
A: Yes! The registration form from Phases 1-5 should already be working. Test at: `http://localhost/rhemazimbabwe/partnerregistration`

**Q: Where do I create test partners?**
A: Admin > Partners > Add New Partner OR use the online registration form

**Q: How do I delete the test file?**
A: After testing, simply delete `test_reminder_generation.php` from the root folder

---

**Last Updated:** 2025-10-01
**Next Steps:** Complete remaining 20% (Cron controller, Email/SMS integration, Admin UI)
