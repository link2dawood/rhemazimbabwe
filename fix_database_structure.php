<?php
// Fix Database Structure for Partner Reminders
// Run: http://localhost/rhemazimbabwe/fix_database_structure.php

echo "<h1>Fixing Partner Reminders Database Structure</h1>";

// Load CodeIgniter
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

echo "<h2>2. Checking Current Table Structure</h2>";
try {
    $result = $CI->db->query("DESCRIBE partner_reminders");
    $columns = $result->result_array();
    
    $existing_columns = array();
    foreach ($columns as $column) {
        $existing_columns[] = $column['Field'];
    }
    
    echo "<p style='color: green;'>✅ Current columns: " . implode(', ', $existing_columns) . "</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error checking table structure: " . $e->getMessage() . "</p>";
    exit;
}

echo "<h2>3. Adding Missing Columns</h2>";

$fixes = [
    [
        'column' => 'message',
        'sql' => 'ALTER TABLE partner_reminders ADD COLUMN message TEXT NULL AFTER title',
        'description' => 'Add message column for reminder content'
    ],
    [
        'column' => 'is_active',
        'sql' => 'ALTER TABLE partner_reminders ADD COLUMN is_active TINYINT(1) DEFAULT 1 AFTER status',
        'description' => 'Add is_active column for reminder status'
    ],
    [
        'column' => 'created_by',
        'sql' => 'ALTER TABLE partner_reminders ADD COLUMN created_by INT(11) UNSIGNED NULL AFTER next_reminder_date',
        'description' => 'Add created_by column for tracking creator'
    ],
    [
        'column' => 'created_at',
        'sql' => 'ALTER TABLE partner_reminders ADD COLUMN created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER created_by',
        'description' => 'Add created_at column for creation timestamp'
    ],
    [
        'column' => 'updated_at',
        'sql' => 'ALTER TABLE partner_reminders ADD COLUMN updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_at',
        'description' => 'Add updated_at column for update timestamp'
    ]
];

foreach ($fixes as $fix) {
    try {
        // Check if column already exists
        $result = $CI->db->query("SHOW COLUMNS FROM partner_reminders LIKE '{$fix['column']}'");
        if ($result->num_rows() > 0) {
            echo "<p style='color: orange;'>⚠️ Column '{$fix['column']}' already exists - skipping</p>";
            continue;
        }
        
        // Add the column
        $CI->db->query($fix['sql']);
        echo "<p style='color: green;'>✅ Added column '{$fix['column']}' - {$fix['description']}</p>";
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Failed to add column '{$fix['column']}': " . $e->getMessage() . "</p>";
    }
}

echo "<h2>4. Updating Reminder Type Enum</h2>";
try {
    $sql = "ALTER TABLE partner_reminders MODIFY COLUMN reminder_type ENUM('contribution_due','missing_contribution','thank_you','custom','birthday','anniversary','renewal','payment_due','follow_up','meeting','other') NOT NULL DEFAULT 'contribution_due'";
    $CI->db->query($sql);
    echo "<p style='color: green;'>✅ Updated reminder_type enum to include new values</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Failed to update reminder_type enum: " . $e->getMessage() . "</p>";
}

echo "<h2>5. Verifying Final Table Structure</h2>";
try {
    $result = $CI->db->query("DESCRIBE partner_reminders");
    $columns = $result->result_array();
    
    echo "<p style='color: green;'>✅ Final table structure:</p>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . $column['Field'] . "</td>";
        echo "<td>" . $column['Type'] . "</td>";
        echo "<td>" . $column['Null'] . "</td>";
        echo "<td>" . $column['Key'] . "</td>";
        echo "<td>" . $column['Default'] . "</td>";
        echo "<td>" . $column['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error verifying table structure: " . $e->getMessage() . "</p>";
}

echo "<h2>6. Testing Reminder Insert</h2>";
try {
    // Test data
    $test_data = [
        'partner_id' => 1,
        'reminder_type' => 'other',
        'reminder_date' => date('Y-m-d'),
        'reminder_time' => '09:00:00',
        'title' => 'Test Reminder',
        'message' => 'This is a test reminder message',
        'is_active' => 1,
        'created_by' => 1,
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    // Try to insert test data
    $CI->db->insert('partner_reminders', $test_data);
    $insert_id = $CI->db->insert_id();
    
    if ($insert_id) {
        echo "<p style='color: green;'>✅ Test reminder inserted successfully (ID: $insert_id)</p>";
        
        // Clean up test data
        $CI->db->where('id', $insert_id)->delete('partner_reminders');
        echo "<p style='color: green;'>✅ Test data cleaned up</p>";
    } else {
        echo "<p style='color: red;'>❌ Failed to insert test reminder</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error testing reminder insert: " . $e->getMessage() . "</p>";
}

echo "<h2>7. Fix Summary</h2>";
echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ Database Structure Fixed:</h3>";
echo "<ul>";
echo "<li><strong>message:</strong> TEXT column for reminder content</li>";
echo "<li><strong>is_active:</strong> TINYINT(1) for active status</li>";
echo "<li><strong>created_by:</strong> INT(11) UNSIGNED for creator tracking</li>";
echo "<li><strong>created_at:</strong> TIMESTAMP for creation time</li>";
echo "<li><strong>updated_at:</strong> TIMESTAMP for update time</li>";
echo "<li><strong>reminder_type:</strong> Updated ENUM with new values</li>";
echo "</ul>";
echo "</div>";

echo "<h2>8. Next Steps</h2>";
echo "<div style='background: #d1ecf1; padding: 15px; border-radius: 5px;'>";
echo "<h3>Testing Instructions:</h3>";
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

echo "<p><strong>Database structure fix completed!</strong> The partner reminders functionality should now work without database errors.</p>";
?>
