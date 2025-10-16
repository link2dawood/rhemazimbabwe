<?php
// Test Partner Reminders Fix
// Run: http://localhost/rhemazimbabwe/test_partner_reminders_fix.php

echo "<h1>Partner Reminders Database Fix Test</h1>";

// Test database connection
require_once 'index.php';
$CI =& get_instance();
$CI->load->database();

echo "<h2>1. Database Connection Test</h2>";
if ($CI->db->conn_id) {
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
} else {
    echo "<p style='color: red;'>❌ Database connection failed!</p>";
    exit;
}

echo "<h2>2. Table Structure Analysis</h2>";
try {
    $result = $CI->db->query("DESCRIBE partner_reminders");
    $columns = $result->result_array();
    
    $existing_columns = array();
    foreach ($columns as $column) {
        $existing_columns[] = $column['Field'];
    }
    
    echo "<p style='color: green;'>✅ Table structure retrieved successfully</p>";
    echo "<h3>Current Columns:</h3>";
    echo "<ul>";
    foreach ($existing_columns as $column) {
        echo "<li>$column</li>";
    }
    echo "</ul>";
    
    // Check for required columns
    $required_columns = [
        'id' => 'Primary key',
        'partner_id' => 'Foreign key to partners',
        'reminder_type' => 'Type of reminder',
        'reminder_date' => 'Date for reminder',
        'reminder_time' => 'Time for reminder',
        'title' => 'Reminder title',
        'message' => 'Reminder message',
        'is_active' => 'Active status',
        'created_by' => 'Creator ID',
        'created_at' => 'Creation timestamp',
        'updated_at' => 'Update timestamp'
    ];
    
    echo "<h3>Required Columns Check:</h3>";
    $missing_columns = array();
    foreach ($required_columns as $column => $description) {
        if (in_array($column, $existing_columns)) {
            echo "<p style='color: green;'>✅ $column - $description</p>";
        } else {
            echo "<p style='color: red;'>❌ $column - $description (MISSING)</p>";
            $missing_columns[] = $column;
        }
    }
    
    if (empty($missing_columns)) {
        echo "<p style='color: green;'>✅ All required columns exist!</p>";
    } else {
        echo "<p style='color: red;'>❌ Missing columns: " . implode(', ', $missing_columns) . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error analyzing table structure: " . $e->getMessage() . "</p>";
}

echo "<h2>3. Controller Data Mapping Test</h2>";
echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px;'>";
echo "<h3>Controller tries to insert these fields:</h3>";
echo "<ul>";
echo "<li><strong>partner_id:</strong> Partner ID</li>";
echo "<li><strong>reminder_type:</strong> Type of reminder</li>";
echo "<li><strong>reminder_date:</strong> Date for reminder</li>";
echo "<li><strong>reminder_time:</strong> Time for reminder</li>";
echo "<li><strong>message:</strong> Reminder message</li>";
echo "<li><strong>is_active:</strong> Active status (0 or 1)</li>";
echo "<li><strong>created_by:</strong> Creator ID</li>";
echo "<li><strong>created_at:</strong> Creation timestamp</li>";
echo "</ul>";
echo "</div>";

echo "<h2>4. Fix Commands</h2>";
echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px;'>";
echo "<h3>If missing columns, run these SQL commands:</h3>";
echo "<pre>";
echo "-- Add message column\n";
echo "ALTER TABLE partner_reminders ADD COLUMN message TEXT NULL AFTER title;\n\n";
echo "-- Add is_active column\n";
echo "ALTER TABLE partner_reminders ADD COLUMN is_active TINYINT(1) DEFAULT 1 AFTER status;\n\n";
echo "-- Add created_by column\n";
echo "ALTER TABLE partner_reminders ADD COLUMN created_by INT(11) UNSIGNED NULL AFTER next_reminder_date;\n\n";
echo "-- Add created_at column\n";
echo "ALTER TABLE partner_reminders ADD COLUMN created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER created_by;\n\n";
echo "-- Add updated_at column\n";
echo "ALTER TABLE partner_reminders ADD COLUMN updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_at;\n\n";
echo "-- Update reminder_type enum to include new values\n";
echo "ALTER TABLE partner_reminders MODIFY COLUMN reminder_type ENUM('contribution_due','missing_contribution','thank_you','custom','birthday','anniversary','renewal','payment_due','follow_up','meeting','other') NOT NULL DEFAULT 'contribution_due';";
echo "</pre>";
echo "</div>";

echo "<h2>5. Alternative: Run Migration</h2>";
echo "<div style='background: #d1ecf1; padding: 15px; border-radius: 5px;'>";
echo "<h3>If you prefer to run the migration:</h3>";
echo "<ol>";
echo "<li>Check if migration file exists: <code>application/migrations/131_create_partner_reminders_table.php</code></li>";
echo "<li>Run migration: <code>php index.php migrate</code></li>";
echo "<li>Or run specific migration: <code>php index.php migrate version 131</code></li>";
echo "</ol>";
echo "</div>";

echo "<h2>6. Testing After Fix</h2>";
echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px;'>";
echo "<h3>After applying the fix:</h3>";
echo "<ol>";
echo "<li>Login as admin: <a href='" . base_url('admin') . "' target='_blank'>Admin Login</a></li>";
echo "<li>Go to Partners: <a href='" . base_url('admin/partners') . "' target='_blank'>Partners List</a> (requires login)</li>";
echo "<li>Click 'View' on any partner</li>";
echo "<li>Click 'Reminders' tab</li>";
echo "<li>Click 'Add Reminder' button</li>";
echo "<li>Fill in reminder details and click 'Save'</li>";
echo "<li>Should work without 'Unknown column message' error</li>";
echo "</ol>";
echo "</div>";

echo "<h2>7. Expected Results After Fix</h2>";
echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ What Should Happen:</h3>";
echo "<ul>";
echo "<li>✅ No 'Unknown column message' database errors</li>";
echo "<li>✅ Reminder forms submit successfully</li>";
echo "<li>✅ Reminders are saved to database</li>";
echo "<li>✅ All reminder operations work (add, edit, delete, toggle)</li>";
echo "<li>✅ Reminder data displays correctly</li>";
echo "</ul>";

echo "<h3>❌ What Should NOT Happen:</h3>";
echo "<ul>";
echo "<li>❌ Database Error 1054</li>";
echo "<li>❌ 'Unknown column message' errors</li>";
echo "<li>❌ Form submission failures</li>";
echo "<li>❌ Data not being saved</li>";
echo "</ul>";
echo "</div>";

echo "<p><strong>Test completed!</strong> Apply the recommended fixes and test the reminder functionality.</p>";
?>
