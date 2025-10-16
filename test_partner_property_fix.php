<?php
// Test Partner Property Fix
// Run: http://localhost/rhemazimbabwe/test_partner_property_fix.php

echo "<h1>Partner Property Fix Test</h1>";

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

echo "<h2>2. Database Schema Check</h2>";
try {
    $result = $CI->db->query("DESCRIBE partners");
    $columns = $result->result_array();
    
    $expected_columns = ['zip_code', 'photo'];
    $found_columns = [];
    
    foreach ($columns as $column) {
        $found_columns[] = $column['Field'];
    }
    
    foreach ($expected_columns as $expected) {
        if (in_array($expected, $found_columns)) {
            echo "<p style='color: green;'>✅ Column '$expected' exists in partners table</p>";
        } else {
            echo "<p style='color: red;'>❌ Column '$expected' missing from partners table</p>";
        }
    }
    
    // Check for incorrect column names
    $incorrect_columns = ['zipcode', 'image'];
    foreach ($incorrect_columns as $incorrect) {
        if (in_array($incorrect, $found_columns)) {
            echo "<p style='color: orange;'>⚠️ Incorrect column '$incorrect' found in partners table</p>";
        } else {
            echo "<p style='color: green;'>✅ Incorrect column '$incorrect' not found in partners table</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database query error: " . $e->getMessage() . "</p>";
}

echo "<h2>3. View Files Test</h2>";
$view_files = [
    'application/views/admin/partners/partneredit.php' => 'Partner Edit View',
    'application/views/admin/partners/partnershow.php' => 'Partner Show View'
];

foreach ($view_files as $file => $description) {
    if (file_exists($file)) {
        echo "<p style='color: green;'>✅ $description exists</p>";
        
        $content = file_get_contents($file);
        
        // Check for fixed property names
        if (strpos($content, 'partner->zip_code') !== false) {
            echo "<p style='color: green;'>✅ Uses correct 'zip_code' property</p>";
        } else {
            echo "<p style='color: red;'>❌ 'zip_code' property not found</p>";
        }
        
        if (strpos($content, 'partner->photo') !== false) {
            echo "<p style='color: green;'>✅ Uses correct 'photo' property</p>";
        } else {
            echo "<p style='color: red;'>❌ 'photo' property not found</p>";
        }
        
        // Check for removed incorrect property names
        if (strpos($content, 'partner->zipcode') === false) {
            echo "<p style='color: green;'>✅ Incorrect 'zipcode' property removed</p>";
        } else {
            echo "<p style='color: red;'>❌ Incorrect 'zipcode' property still exists</p>";
        }
        
        if (strpos($content, 'partner->image') === false) {
            echo "<p style='color: green;'>✅ Incorrect 'image' property removed</p>";
        } else {
            echo "<p style='color: red;'>❌ Incorrect 'image' property still exists</p>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ $description missing</p>";
    }
}

echo "<h2>4. Fix Summary</h2>";
echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ Issues Fixed:</h3>";
echo "<ul>";
echo "<li><strong>Undefined property zipcode:</strong> Changed to correct 'zip_code' property</li>";
echo "<li><strong>Undefined property image:</strong> Changed to correct 'photo' property</li>";
echo "<li><strong>Partner Edit View:</strong> Fixed property names in partneredit.php</li>";
echo "<li><strong>Partner Show View:</strong> Fixed property names in partnershow.php</li>";
echo "</ul>";

echo "<h3>✅ Property Mappings:</h3>";
echo "<ul>";
echo "<li><strong>zipcode → zip_code:</strong> Matches database column name</li>";
echo "<li><strong>image → photo:</strong> Matches database column name</li>";
echo "</ul>";
echo "</div>";

echo "<h2>5. Testing Instructions</h2>";
echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px;'>";
echo "<h3>Manual Testing Steps:</h3>";
echo "<ol>";
echo "<li><strong>Test Partner Edit:</strong>";
echo "<ul>";
echo "<li>Login as admin: <a href='" . base_url('admin') . "' target='_blank'>Admin Login</a></li>";
echo "<li>Go to Partners: <a href='" . base_url('admin/partners') . "' target='_blank'>Partners List</a> (requires login)</li>";
echo "<li>Click 'Edit' on any partner</li>";
echo "<li>Page should load without 'Undefined property' errors</li>";
echo "<li>Zip code and photo fields should display correctly</li>";
echo "</ul>";
echo "</li>";
echo "<li><strong>Test Partner Show:</strong>";
echo "<ul>";
echo "<li>Click 'View' on any partner</li>";
echo "<li>Page should load without 'Undefined property' errors</li>";
echo "<li>Address information should display correctly</li>";
echo "</ul>";
echo "</li>";
echo "</ol>";
echo "</div>";

echo "<h2>6. Expected Results</h2>";
echo "<div style='background: #d1ecf1; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ What Should Happen:</h3>";
echo "<ul>";
echo "<li>✅ No 'Undefined property zipcode' errors</li>";
echo "<li>✅ No 'Undefined property image' errors</li>";
echo "<li>✅ Partner edit page loads without PHP warnings</li>";
echo "<li>✅ Partner show page loads without PHP warnings</li>";
echo "<li>✅ Zip code field displays partner's zip code</li>";
echo "<li>✅ Photo field displays partner's photo if available</li>";
echo "</ul>";

echo "<h3>❌ What Should NOT Happen:</h3>";
echo "<ul>";
echo "<li>❌ PHP warnings about undefined properties</li>";
echo "<li>❌ Empty zip code or photo fields</li>";
echo "<li>❌ Page loading errors</li>";
echo "</ul>";
echo "</div>";

echo "<h2>7. Database Column Reference</h2>";
echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px;'>";
echo "<h3>Correct Column Names in Partners Table:</h3>";
echo "<ul>";
echo "<li><strong>zip_code:</strong> For postal/zip code</li>";
echo "<li><strong>photo:</strong> For partner photo/image</li>";
echo "<li><strong>Other columns:</strong> firstname, lastname, email, mobileno, address, city, state, country, etc.</li>";
echo "</ul>";
echo "</div>";

echo "<p><strong>Test completed!</strong> The partner property name issues should now be fixed.</p>";
?>
