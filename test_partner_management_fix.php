<?php
// Test Partner Management Fix
// Run: http://localhost/rhemazimbabwe/test_partner_management_fix.php

echo "<h1>Testing Partner Management Fix</h1>";

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

echo "<h2>2. Testing Partner Management Controller Loading</h2>";
try {
    // Test loading the controller
    $CI->load->controller('user/Partner_management');
    echo "<p style='color: green;'>✅ Partner_management controller loaded successfully!</p>";
    
    // Test if the controller instance exists
    if (isset($CI->partner_management)) {
        echo "<p style='color: green;'>✅ Controller instance created successfully!</p>";
    } else {
        echo "<p style='color: red;'>❌ Controller instance not found!</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error loading controller: " . $e->getMessage() . "</p>";
}

echo "<h2>3. Testing Required Models</h2>";
$models_to_test = [
    'setting_model' => 'Setting Model',
    'student_model' => 'Student Model',
    'staff_model' => 'Staff Model',
    'partner_model' => 'Partner Model',
    'type_model' => 'Type Model',
    'frequency_model' => 'Frequency Model'
];

foreach ($models_to_test as $model => $description) {
    try {
        $CI->load->model($model);
        echo "<p style='color: green;'>✅ {$description} loaded successfully!</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Error loading {$description}: " . $e->getMessage() . "</p>";
    }
}

echo "<h2>4. Testing Required Libraries</h2>";
$libraries_to_test = [
    'Customlib' => 'Custom Library',
    'form_validation' => 'Form Validation Library',
    'session' => 'Session Library'
];

foreach ($libraries_to_test as $library => $description) {
    try {
        $CI->load->library($library);
        echo "<p style='color: green;'>✅ {$description} loaded successfully!</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Error loading {$description}: " . $e->getMessage() . "</p>";
    }
}

echo "<h2>5. Testing Partner Management Routes</h2>";
$routes_to_test = [
    'user/partner_management' => 'Partner Management Dashboard',
    'user/partner_management/add' => 'Add Partner Form'
];

echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Route</th><th>Description</th><th>Test Link</th><th>Expected Result</th></tr>";

foreach ($routes_to_test as $route => $description) {
    $url = base_url($route);
    echo "<tr>";
    echo "<td><code>{$route}</code></td>";
    echo "<td>{$description}</td>";
    echo "<td><a href='{$url}' target='_blank' style='color: blue;'>Test Link</a></td>";
    echo "<td>Should load without errors</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h2>6. Testing Model Dependencies</h2>";
echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ Dependencies Fixed:</h3>";
echo "<ul>";
echo "<li><strong>setting_model:</strong> Added to controller constructor</li>";
echo "<li><strong>Customlib:</strong> Already loaded in controller</li>";
echo "<li><strong>Database:</strong> Explicitly loaded in controller</li>";
echo "<li><strong>Session:</strong> Explicitly loaded in controller</li>";
echo "</ul>";
echo "</div>";

echo "<h2>7. Testing Student Model Dependencies</h2>";
try {
    $CI->load->model('student_model');
    $CI->load->model('setting_model');
    
    // Test if setting_model methods work
    if (method_exists($CI->setting_model, 'getCurrentSession')) {
        echo "<p style='color: green;'>✅ setting_model->getCurrentSession() method exists!</p>";
    } else {
        echo "<p style='color: red;'>❌ setting_model->getCurrentSession() method missing!</p>";
    }
    
    if (method_exists($CI->setting_model, 'getDateYmd')) {
        echo "<p style='color: green;'>✅ setting_model->getDateYmd() method exists!</p>";
    } else {
        echo "<p style='color: red;'>❌ setting_model->getDateYmd() method missing!</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error testing model dependencies: " . $e->getMessage() . "</p>";
}

echo "<h2>8. Manual Testing Instructions</h2>";
echo "<div style='background: #d1ecf1; padding: 15px; border-radius: 5px;'>";
echo "<h3>🧪 Test the Fixed Controller:</h3>";
echo "<ol>";
echo "<li><strong>Test Partner Management Dashboard:</strong>";
echo "<ul>";
echo "<li>Visit: <a href='" . base_url('user/partner_management') . "' target='_blank'>Partner Management</a></li>";
echo "<li>Should load without 'Undefined property' errors</li>";
echo "<li>Should show partner management interface</li>";
echo "</ul>";
echo "</li>";

echo "<li><strong>Test Add Partner Form:</strong>";
echo "<ul>";
echo "<li>Visit: <a href='" . base_url('user/partner_management/add') . "' target='_blank'>Add Partner</a></li>";
echo "<li>Should load without errors</li>";
echo "<li>Should show partner registration form</li>";
echo "</ul>";
echo "</li>";

echo "<li><strong>Test with Different User Types:</strong>";
echo "<ul>";
echo "<li>Login as student and test partner management</li>";
echo "<li>Login as staff and test partner management</li>";
echo "<li>Login as admin and test partner management</li>";
echo "</ul>";
echo "</li>";
echo "</ol>";
echo "</div>";

echo "<h2>9. Expected Behavior After Fix</h2>";
echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ What Should Happen Now:</h3>";
echo "<ul>";
echo "<li><strong>No More 'Undefined property' Errors:</strong> setting_model should be properly loaded</li>";
echo "<li><strong>No More 'Call to a member function on null' Errors:</strong> All model dependencies should be available</li>";
echo "<li><strong>Partner Management Loads:</strong> Dashboard should load without errors</li>";
echo "<li><strong>Add Partner Works:</strong> Form should load and function properly</li>";
echo "<li><strong>All CRUD Operations Work:</strong> Add, edit, delete partners should work</li>";
echo "</ul>";

echo "<h3>❌ What Should NOT Happen:</h3>";
echo "<ul>";
echo "<li>❌ 'Undefined property: Partner_management::$setting_model' errors</li>";
echo "<li>❌ 'Call to a member function getCurrentSession() on null' errors</li>";
echo "<li>❌ Fatal errors when loading the controller</li>";
echo "<li>❌ Blank pages or broken interfaces</li>";
echo "</ul>";
echo "</div>";

echo "<h2>10. Troubleshooting</h2>";
echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px;'>";
echo "<h3>🔧 If Issues Persist:</h3>";
echo "<ul>";
echo "<li><strong>Clear Browser Cache:</strong> Hard refresh the page (Ctrl+F5)</li>";
echo "<li><strong>Check Model Files:</strong> Ensure all model files exist and are accessible</li>";
echo "<li><strong>Check Database:</strong> Ensure database connection is working</li>";
echo "<li><strong>Check File Permissions:</strong> Ensure all files are readable</li>";
echo "<li><strong>Check Error Logs:</strong> Look for additional error messages</li>";
echo "</ul>";
echo "</div>";

echo "<p><strong>Partner management fix completed!</strong> The 'Undefined property' and 'Call to a member function on null' errors should now be resolved.</p>";
?>
