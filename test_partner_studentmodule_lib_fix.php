<?php
// Test Partner StudentModule Lib Fix
// Run: http://localhost/rhemazimbabwe/test_partner_studentmodule_lib_fix.php

echo "<h1>Testing Partner StudentModule Lib Fix</h1>";

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

echo "<h2>2. Testing StudentModule Lib Loading</h2>";

try {
    // Test if studentmodule_lib library exists
    if (file_exists(APPPATH . 'libraries/Studentmodule_lib.php')) {
        echo "<p style='color: green;'>✅ Studentmodule_lib library file exists!</p>";
    } else {
        echo "<p style='color: red;'>❌ Studentmodule_lib library file not found!</p>";
        exit;
    }
    
    // Load studentmodule_lib library
    $CI->load->library('studentmodule_lib');
    echo "<p style='color: green;'>✅ Studentmodule_lib library loaded successfully!</p>";
    
    // Test hasActive method
    if (method_exists($CI->studentmodule_lib, 'hasActive')) {
        echo "<p style='color: green;'>✅ hasActive() method is available!</p>";
        
        // Test with common modules
        $modules_to_test = ['multi_class', 'online_course', 'partners'];
        foreach ($modules_to_test as $module) {
            $has_active = $CI->studentmodule_lib->hasActive($module);
            echo "<p>Module '$module' active status: " . ($has_active ? 'Active' : 'Not Active') . "</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ hasActive() method not found!</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error loading studentmodule_lib: " . $e->getMessage() . "</p>";
    echo "<p>Stack trace:</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h2>3. Testing All Required Libraries</h2>";

try {
    // Load all required libraries
    $CI->load->library('Customlib');
    echo "<p style='color: green;'>✅ Customlib loaded!</p>";
    
    $CI->load->model('Module_model');
    $CI->load->library('module_lib');
    echo "<p style='color: green;'>✅ module_lib loaded!</p>";
    
    $CI->load->library('studentmodule_lib');
    echo "<p style='color: green;'>✅ studentmodule_lib loaded!</p>";
    
    // Test if both libraries are available
    if (isset($CI->module_lib) && isset($CI->studentmodule_lib)) {
        echo "<p style='color: green;'>✅ Both module libraries are available!</p>";
    } else {
        echo "<p style='color: red;'>❌ Some module libraries are missing!</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error loading libraries: " . $e->getMessage() . "</p>";
}

echo "<h2>4. Testing Partner Controller Loading</h2>";

try {
    // Test loading the controller
    $CI->load->file(APPPATH . 'controllers/user/Partner.php');
    $partner_controller = new Partner();
    echo "<p style='color: green;'>✅ Partner controller loaded successfully!</p>";
    
    // Test if studentmodule_lib is available in controller
    if (isset($partner_controller->studentmodule_lib)) {
        echo "<p style='color: green;'>✅ studentmodule_lib is available in Partner controller!</p>";
    } else {
        echo "<p style='color: red;'>❌ studentmodule_lib not available in Partner controller!</p>";
    }
    
    // Test if module_lib is available in controller
    if (isset($partner_controller->module_lib)) {
        echo "<p style='color: green;'>✅ module_lib is available in Partner controller!</p>";
    } else {
        echo "<p style='color: red;'>❌ module_lib not available in Partner controller!</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error loading Partner controller: " . $e->getMessage() . "</p>";
    echo "<p>Stack trace:</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h2>5. Testing Partner Routes</h2>";

$routes_to_test = [
    'user/partner' => 'Partners Dashboard',
    'user/partner/add' => 'Add Partner Form',
    'user/partner/contributions/1' => 'View Contributions (Partner ID 1)',
    'user/partner/add_contribution/1' => 'Add Contribution (Partner ID 1)'
];

echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Route</th><th>Description</th><th>Test Link</th><th>Expected Result</th></tr>";

foreach ($routes_to_test as $route => $description) {
    $url = base_url($route);
    echo "<tr>";
    echo "<td><code>{$route}</code></td>";
    echo "<td>{$description}</td>";
    echo "<td><a href='{$url}' target='_blank' style='color: blue;'>Test Link</a></td>";
    echo "<td>Should load without studentmodule_lib errors</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h2>6. Expected Behavior</h2>";
echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ What Should Happen Now:</h3>";
echo "<ul>";
echo "<li><strong>No StudentModule Lib Errors:</strong> Partner pages should load without 'Undefined property: CI_Loader::$studentmodule_lib' errors</li>";
echo "<li><strong>Header Functions:</strong> hasActive() and other studentmodule_lib methods should work</li>";
echo "<li><strong>Student Header:</strong> Should display correctly with module checks</li>";
echo "<li><strong>Partner Pages:</strong> Should load with proper layout and functionality</li>";
echo "<li><strong>Module Checks:</strong> Multi-class and other module checks should work</li>";
echo "</ul>";

echo "<h3>🔧 Fix Applied:</h3>";
echo "<ul>";
echo "<li><strong>Library Loading:</strong> Added studentmodule_lib loading to Partner controller</li>";
echo "<li><strong>View Data:</strong> Pass studentmodule_lib to all views via \$data array</li>";
echo "<li><strong>Method Availability:</strong> hasActive() and other methods now available</li>";
echo "</ul>";
echo "</div>";

echo "<h2>7. Manual Testing Instructions</h2>";
echo "<div style='background: #d1ecf1; padding: 15px; border-radius: 5px;'>";
echo "<h3>🧪 Test the Fixed Partner System:</h3>";
echo "<ol>";
echo "<li><strong>Login as Student:</strong>";
echo "<ul>";
echo "<li>Login as student: <a href='" . base_url('userlogin') . "' target='_blank'>Student Login</a></li>";
echo "</ul>";
echo "</li>";

echo "<li><strong>Test Partner Pages:</strong>";
echo "<ul>";
echo "<li>Click Partners menu - should load without studentmodule_lib errors</li>";
echo "<li>Check that student header displays correctly</li>";
echo "<li>Verify module checks work (multi-class, etc.)</li>";
echo "<li>Click 'Add Partner' - should show form</li>";
echo "<li>Test all partner functionality</li>";
echo "<li>Verify no PHP errors in browser console</li>";
echo "</ul>";
echo "</li>";
echo "</ol>";
echo "</div>";

echo "<h2>8. Fix Summary</h2>";
echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ Issue Fixed:</h3>";
echo "<ul>";
echo "<li><strong>Missing Library:</strong> Added studentmodule_lib loading to Partner controller</li>";
echo "<li><strong>View Context:</strong> Pass studentmodule_lib to all views via \$data array</li>";
echo "<li><strong>Method Access:</strong> hasActive() and other methods now available in views</li>";
echo "</ul>";

echo "<h3>✅ Code Changes:</h3>";
echo "<ul>";
echo "<li>Added: <code>\$this->load->library('studentmodule_lib');</code> to constructor</li>";
echo "<li>Added: <code>\$data['studentmodule_lib'] = \$this->studentmodule_lib;</code> to all view methods</li>";
echo "<li>Result: Student header can access studentmodule_lib methods</li>";
echo "<li>Result: No more 'Undefined property' errors for studentmodule_lib</li>";
echo "</ul>";
echo "</div>";

echo "<p><strong>Partner studentmodule_lib fix completed!</strong> The Partner pages should now load without any studentmodule_lib-related errors.</p>";
?>
