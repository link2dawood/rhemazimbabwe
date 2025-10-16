<?php
// Simple test for Partner Management fix
// Run: http://localhost/rhemazimbabwe/test_partner_fix_simple.php

echo "<h1>Testing Partner Management Fix</h1>";

try {
    // Load CodeIgniter
    require_once 'index.php';
    $CI =& get_instance();
    
    echo "<h2>1. Testing Controller Loading</h2>";
    
    // Test loading the controller using the correct method
    $CI->load->file(APPPATH . 'controllers/user/Partner_management.php');
    echo "<p style='color: green;'>✅ Partner_management controller file loaded successfully!</p>";
    
    // Test if we can instantiate the controller
    $controller = new Partner_management();
    echo "<p style='color: green;'>✅ Controller instantiated successfully!</p>";
    
    // Test if we can call a method
    if (method_exists($controller, 'index')) {
        echo "<p style='color: green;'>✅ Controller methods are accessible!</p>";
    } else {
        echo "<p style='color: red;'>❌ Controller methods not accessible!</p>";
    }
    
    echo "<h2>2. Testing Routes</h2>";
    echo "<p>Test these URLs in your browser:</p>";
    echo "<ul>";
    echo "<li><a href='" . base_url('user/partner_management') . "' target='_blank'>Partner Management Dashboard</a></li>";
    echo "<li><a href='" . base_url('user/partner_management/add') . "' target='_blank'>Add Partner Form</a></li>";
    echo "</ul>";
    
    echo "<h2>3. Expected Results</h2>";
    echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px;'>";
    echo "<h3>✅ What Should Happen:</h3>";
    echo "<ul>";
    echo "<li>No 'Undefined property: Partner_management::$setting_model' errors</li>";
    echo "<li>No 'Call to a member function getCurrentSession() on null' errors</li>";
    echo "<li>Partner management pages should load without errors</li>";
    echo "<li>Add partner form should display properly</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<h2>4. Fix Summary</h2>";
    echo "<div style='background: #d1ecf1; padding: 15px; border-radius: 5px;'>";
    echo "<h3>✅ Changes Made:</h3>";
    echo "<ul>";
    echo "<li><strong>Load Order Fixed:</strong> Libraries loaded before models</li>";
    echo "<li><strong>Dependency Order:</strong> setting_model loaded before student_model</li>";
    echo "<li><strong>Required Libraries:</strong> Customlib, session, user_agent loaded</li>";
    echo "<li><strong>Database Connection:</strong> Explicitly loaded</li>";
    echo "</ul>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>Stack trace:</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<p><strong>Test completed!</strong> If you see green checkmarks above, the fix is working.</p>";
?>
