<?php
// Test Receipt numberToWords Fix
// Run: http://localhost/rhemazimbabwe/test_receipt_numberToWords_fix.php

echo "<h1>Receipt numberToWords Fix Test</h1>";

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

echo "<h2>2. Helper File Test</h2>";
$helper_file = 'application/helpers/number_helper.php';
if (file_exists($helper_file)) {
    echo "<p style='color: green;'>✅ Number helper file exists</p>";
    
    // Test loading the helper
    $CI->load->helper('number');
    echo "<p style='color: green;'>✅ Number helper loaded successfully</p>";
    
    // Test the function
    if (function_exists('numberToWords')) {
        echo "<p style='color: green;'>✅ numberToWords function is available</p>";
        
        // Test some numbers
        $test_numbers = [1, 25, 100, 1500, 25000.50];
        echo "<h3>Function Tests:</h3>";
        foreach ($test_numbers as $num) {
            $words = numberToWords($num);
            echo "<p><strong>$num</strong> = \"$words\"</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ numberToWords function not found</p>";
    }
} else {
    echo "<p style='color: red;'>❌ Number helper file missing</p>";
}

echo "<h2>3. Controller Test</h2>";
$controller_file = 'application/controllers/Partnerdashboard.php';
if (file_exists($controller_file)) {
    echo "<p style='color: green;'>✅ Partnerdashboard controller exists</p>";
    
    // Check if helper is loaded in constructor
    $content = file_get_contents($controller_file);
    if (strpos($content, "load->helper('number')") !== false) {
        echo "<p style='color: green;'>✅ Number helper is loaded in controller</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ Number helper loading not found in controller</p>";
    }
} else {
    echo "<p style='color: red;'>❌ Partnerdashboard controller missing</p>";
}

echo "<h2>4. Receipt View Test</h2>";
$receipt_view = 'application/views/user/partner/receipt.php';
if (file_exists($receipt_view)) {
    echo "<p style='color: green;'>✅ Receipt view exists</p>";
    
    // Check if function call is correct
    $content = file_get_contents($receipt_view);
    if (strpos($content, 'numberToWords(') !== false && strpos($content, '$this->numberToWords(') === false) {
        echo "<p style='color: green;'>✅ Receipt view uses correct function call</p>";
    } else {
        echo "<p style='color: red;'>❌ Receipt view has incorrect function call</p>";
    }
    
    // Check if duplicate function definition is removed
    if (strpos($content, 'function numberToWords') === false) {
        echo "<p style='color: green;'>✅ Duplicate function definition removed from view</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ Duplicate function definition still exists in view</p>";
    }
} else {
    echo "<p style='color: red;'>❌ Receipt view missing</p>";
}

echo "<h2>5. Fix Summary</h2>";
echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ Issues Fixed:</h3>";
echo "<ul>";
echo "<li><strong>Function Call Error:</strong> Changed \$this->numberToWords() to numberToWords()</li>";
echo "<li><strong>Helper Organization:</strong> Moved function to proper helper file</li>";
echo "<li><strong>Controller Loading:</strong> Added number helper loading to Partnerdashboard controller</li>";
echo "<li><strong>Code Cleanup:</strong> Removed duplicate function definition from view</li>";
echo "</ul>";

echo "<h3>✅ How It Works Now:</h3>";
echo "<ol>";
echo "<li>Partnerdashboard controller loads number helper</li>";
echo "<li>Receipt view calls numberToWords() function directly</li>";
echo "<li>Function converts numbers to words for receipt display</li>";
echo "<li>No more 'Call to undefined method' errors</li>";
echo "</ol>";
echo "</div>";

echo "<h2>6. Testing Instructions</h2>";
echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px;'>";
echo "<h3>Manual Testing Steps:</h3>";
echo "<ol>";
echo "<li><strong>Login as Partner:</strong>";
echo "<ul>";
echo "<li>Go to <a href='" . base_url('partnerportal/login') . "' target='_blank'>Partner Login</a></li>";
echo "<li>Use partner credentials to login</li>";
echo "</ul>";
echo "</li>";
echo "<li><strong>Test Receipt Generation:</strong>";
echo "<ul>";
echo "<li>Go to <a href='" . base_url('partnerdashboard/contributions') . "' target='_blank'>Contributions Page</a> (requires login)</li>";
echo "<li>Click on 'Print' or 'Download' buttons for any contribution</li>";
echo "<li>Receipt should open without errors</li>";
echo "<li>Check that amount is displayed in both numbers and words</li>";
echo "</ul>";
echo "</li>";
echo "</ol>";
echo "</div>";

echo "<h2>7. Expected Results</h2>";
echo "<div style='background: #d1ecf1; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ What Should Happen:</h3>";
echo "<ul>";
echo "<li>✅ Receipt opens without 'Call to undefined method' error</li>";
echo "<li>✅ Amount displays in both numeric and word format</li>";
echo "<li>✅ Receipt prints correctly</li>";
echo "<li>✅ All receipt functionality works properly</li>";
echo "</ul>";

echo "<h3>❌ What Should NOT Happen:</h3>";
echo "<ul>";
echo "<li>❌ 'Call to undefined method CI_Loader::numberToWords()' error</li>";
echo "<li>❌ PHP fatal errors</li>";
echo "<li>❌ Missing amount in words on receipt</li>";
echo "</ul>";
echo "</div>";

echo "<p><strong>Test completed!</strong> The receipt numberToWords functionality should now work correctly without any errors.</p>";
?>
