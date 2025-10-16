<?php
// Test Notes and Reminders Functionality
// Run: http://localhost/rhemazimbabwe/test_notes_reminders_functionality.php

echo "<h1>Notes and Reminders Functionality Test</h1>";

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

echo "<h2>2. Database Tables Check</h2>";
$tables = ['partner_notes', 'partner_reminders'];
foreach ($tables as $table) {
    try {
        $result = $CI->db->query("DESCRIBE $table");
        if ($result) {
            echo "<p style='color: green;'>✅ Table '$table' exists</p>";
        } else {
            echo "<p style='color: red;'>❌ Table '$table' missing</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Table '$table' error: " . $e->getMessage() . "</p>";
    }
}

echo "<h2>3. Model Loading Test</h2>";
$models = ['Note_model', 'Reminder_model'];
foreach ($models as $model) {
    try {
        $CI->load->model($model);
        echo "<p style='color: green;'>✅ $model loaded successfully</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ $model loading failed: " . $e->getMessage() . "</p>";
    }
}

echo "<h2>4. Controller Methods Test</h2>";
$controller_file = 'application/controllers/admin/Partners.php';
if (file_exists($controller_file)) {
    echo "<p style='color: green;'>✅ Admin Partners controller exists</p>";
    
    $content = file_get_contents($controller_file);
    
    $methods = [
        'add_note' => 'Add Note Method',
        'update_note' => 'Update Note Method',
        'delete_note' => 'Delete Note Method',
        'add_reminder' => 'Add Reminder Method',
        'update_reminder' => 'Update Reminder Method',
        'delete_reminder' => 'Delete Reminder Method',
        'toggle_reminder_status' => 'Toggle Reminder Status Method'
    ];
    
    foreach ($methods as $method => $description) {
        if (strpos($content, "public function $method") !== false) {
            echo "<p style='color: green;'>✅ $description exists</p>";
        } else {
            echo "<p style='color: red;'>❌ $description missing</p>";
        }
    }
} else {
    echo "<p style='color: red;'>❌ Admin Partners controller missing</p>";
}

echo "<h2>5. View Files Test</h2>";
$view_file = 'application/views/admin/partners/partnershow.php';
if (file_exists($view_file)) {
    echo "<p style='color: green;'>✅ Partner show view exists</p>";
    
    $content = file_get_contents($view_file);
    
    $features = [
        'noteModal' => 'Note Modal',
        'reminderModal' => 'Reminder Modal',
        'edit-note' => 'Edit Note Buttons',
        'delete-note' => 'Delete Note Buttons',
        'edit-reminder' => 'Edit Reminder Buttons',
        'delete-reminder' => 'Delete Reminder Buttons',
        'toggle-reminder' => 'Toggle Reminder Buttons',
        'noteForm' => 'Note Form',
        'reminderForm' => 'Reminder Form'
    ];
    
    foreach ($features as $feature => $description) {
        if (strpos($content, $feature) !== false) {
            echo "<p style='color: green;'>✅ $description found</p>";
        } else {
            echo "<p style='color: red;'>❌ $description missing</p>";
        }
    }
} else {
    echo "<p style='color: red;'>❌ Partner show view missing</p>";
}

echo "<h2>6. Model Methods Test</h2>";
try {
    $CI->load->model('Note_model');
    $note_methods = ['add', 'update', 'delete', 'getByPartnerId', 'togglePin'];
    foreach ($note_methods as $method) {
        if (method_exists($CI->Note_model, $method)) {
            echo "<p style='color: green;'>✅ Note_model::$method exists</p>";
        } else {
            echo "<p style='color: red;'>❌ Note_model::$method missing</p>";
        }
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Note_model test failed: " . $e->getMessage() . "</p>";
}

try {
    $CI->load->model('Reminder_model');
    $reminder_methods = ['add', 'update', 'delete', 'getByPartnerId', 'toggleStatus'];
    foreach ($reminder_methods as $method) {
        if (method_exists($CI->Reminder_model, $method)) {
            echo "<p style='color: green;'>✅ Reminder_model::$method exists</p>";
        } else {
            echo "<p style='color: red;'>❌ Reminder_model::$method missing</p>";
        }
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Reminder_model test failed: " . $e->getMessage() . "</p>";
}

echo "<h2>7. Functionality Summary</h2>";
echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ Notes Management Features:</h3>";
echo "<ul>";
echo "<li><strong>Add Notes:</strong> Create new notes with title, content, priority, and pin status</li>";
echo "<li><strong>Edit Notes:</strong> Update existing notes with all fields</li>";
echo "<li><strong>Delete Notes:</strong> Remove notes with confirmation</li>";
echo "<li><strong>Priority Levels:</strong> Low, Normal, High, Urgent</li>";
echo "<li><strong>Pin Notes:</strong> Pin important notes to the top</li>";
echo "<li><strong>Visual Indicators:</strong> Color-coded priority labels</li>";
echo "</ul>";

echo "<h3>✅ Reminders Management Features:</h3>";
echo "<ul>";
echo "<li><strong>Add Reminders:</strong> Create reminders with date, time, type, and message</li>";
echo "<li><strong>Edit Reminders:</strong> Update existing reminders</li>";
echo "<li><strong>Delete Reminders:</strong> Remove reminders with confirmation</li>";
echo "<li><strong>Toggle Status:</strong> Activate/deactivate reminders</li>";
echo "<li><strong>Reminder Types:</strong> Payment Due, Follow Up, Meeting, Other</li>";
echo "<li><strong>Status Tracking:</strong> Active/Inactive status indicators</li>";
echo "</ul>";
echo "</div>";

echo "<h2>8. Testing Instructions</h2>";
echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px;'>";
echo "<h3>Manual Testing Steps:</h3>";
echo "<ol>";
echo "<li><strong>Access Partner Details:</strong>";
echo "<ul>";
echo "<li>Login as admin: <a href='" . base_url('admin') . "' target='_blank'>Admin Login</a></li>";
echo "<li>Go to Partners: <a href='" . base_url('admin/partners') . "' target='_blank'>Partners List</a> (requires login)</li>";
echo "<li>Click 'View' on any partner</li>";
echo "</ul>";
echo "</li>";
echo "<li><strong>Test Notes Management:</strong>";
echo "<ul>";
echo "<li>Click on 'Notes' tab</li>";
echo "<li>Click 'Add Note' button</li>";
echo "<li>Fill in note details (title, content, priority, pin status)</li>";
echo "<li>Click 'Save' - note should be added</li>";
echo "<li>Click 'Edit' button on a note - should open edit modal</li>";
echo "<li>Click 'Delete' button on a note - should delete with confirmation</li>";
echo "</ul>";
echo "</li>";
echo "<li><strong>Test Reminders Management:</strong>";
echo "<ul>";
echo "<li>Click on 'Reminders' tab</li>";
echo "<li>Click 'Add Reminder' button</li>";
echo "<li>Fill in reminder details (type, date, time, message, active status)</li>";
echo "<li>Click 'Save' - reminder should be added</li>";
echo "<li>Click 'Edit' button on a reminder - should open edit modal</li>";
echo "<li>Click 'Toggle' button to activate/deactivate reminder</li>";
echo "<li>Click 'Delete' button on a reminder - should delete with confirmation</li>";
echo "</ul>";
echo "</li>";
echo "</ol>";
echo "</div>";

echo "<h2>9. Expected Results</h2>";
echo "<div style='background: #d1ecf1; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ What Should Happen:</h3>";
echo "<ul>";
echo "<li>✅ Notes and Reminders tabs are visible in partner details</li>";
echo "<li>✅ Add buttons open modals with proper forms</li>";
echo "<li>✅ Forms submit via AJAX without page refresh</li>";
echo "<li>✅ Notes display with priority labels and pin indicators</li>";
echo "<li>✅ Reminders display with status indicators</li>";
echo "<li>✅ Edit buttons populate modals with existing data</li>";
echo "<li>✅ Delete buttons show confirmation dialogs</li>";
echo "<li>✅ Toggle buttons change reminder status</li>";
echo "<li>✅ All operations work without errors</li>";
echo "</ul>";

echo "<h3>❌ What Should NOT Happen:</h3>";
echo "<ul>";
echo "<li>❌ JavaScript errors in console</li>";
echo "<li>❌ AJAX request failures</li>";
echo "<li>❌ Form submission errors</li>";
echo "<li>❌ Missing action buttons</li>";
echo "<li>❌ Broken modals or forms</li>";
echo "</ul>";
echo "</div>";

echo "<h2>10. Database Schema Reference</h2>";
echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px;'>";
echo "<h3>Notes Table (partner_notes):</h3>";
echo "<ul>";
echo "<li><strong>id:</strong> Primary key</li>";
echo "<li><strong>partner_id:</strong> Foreign key to partners table</li>";
echo "<li><strong>title:</strong> Note title</li>";
echo "<li><strong>note:</strong> Note content</li>";
echo "<li><strong>priority:</strong> low, normal, high, urgent</li>";
echo "<li><strong>is_pinned:</strong> 0 or 1</li>";
echo "<li><strong>created_by:</strong> Staff ID who created the note</li>";
echo "<li><strong>created_at:</strong> Creation timestamp</li>";
echo "<li><strong>updated_at:</strong> Last update timestamp</li>";
echo "</ul>";

echo "<h3>Reminders Table (partner_reminders):</h3>";
echo "<ul>";
echo "<li><strong>id:</strong> Primary key</li>";
echo "<li><strong>partner_id:</strong> Foreign key to partners table</li>";
echo "<li><strong>reminder_type:</strong> payment_due, follow_up, meeting, other</li>";
echo "<li><strong>reminder_date:</strong> Date for reminder</li>";
echo "<li><strong>reminder_time:</strong> Time for reminder</li>";
echo "<li><strong>message:</strong> Reminder message</li>";
echo "<li><strong>is_active:</strong> 0 or 1</li>";
echo "<li><strong>created_by:</strong> Staff ID who created the reminder</li>";
echo "<li><strong>created_at:</strong> Creation timestamp</li>";
echo "<li><strong>updated_at:</strong> Last update timestamp</li>";
echo "</ul>";
echo "</div>";

echo "<p><strong>Test completed!</strong> The notes and reminders management functionality should now be fully operational.</p>";
?>
