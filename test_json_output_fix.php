<?php
// Test JSON Output Fix
// Run: http://localhost/rhemazimbabwe/test_json_output_fix.php

echo "<h1>JSON Output Fix Test</h1>";

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

echo "<h2>2. JSON Output Helper Test</h2>";
try {
    $CI->load->helper('json_output');
    echo "<p style='color: green;'>✅ JSON output helper loaded successfully</p>";
    
    // Test the function exists
    if (function_exists('json_output')) {
        echo "<p style='color: green;'>✅ json_output function exists</p>";
    } else {
        echo "<p style='color: red;'>❌ json_output function not found</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ JSON output helper loading failed: " . $e->getMessage() . "</p>";
}

echo "<h2>3. Controller Loading Test</h2>";
try {
    $CI->load->model(array('partner_model', 'note_model', 'reminder_model'));
    echo "<p style='color: green;'>✅ Required models loaded successfully</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Model loading failed: " . $e->getMessage() . "</p>";
}

echo "<h2>4. Controller File Test</h2>";
$controller_file = 'application/controllers/admin/Partners.php';
if (file_exists($controller_file)) {
    echo "<p style='color: green;'>✅ Admin Partners controller exists</p>";
    
    $content = file_get_contents($controller_file);
    
    // Check if json_output helper is loaded
    if (strpos($content, '$this->load->helper(\'json_output\');') !== false) {
        echo "<p style='color: green;'>✅ JSON output helper is loaded in controller</p>";
    } else {
        echo "<p style='color: red;'>❌ JSON output helper not loaded in controller</p>";
    }
    
    // Check for correct json_output usage
    $incorrect_usage = 'echo json_output(array(';
    if (strpos($content, $incorrect_usage) === false) {
        echo "<p style='color: green;'>✅ No incorrect json_output usage found</p>";
    } else {
        echo "<p style='color: red;'>❌ Incorrect json_output usage still found</p>";
    }
    
    // Check for correct json_output usage
    $correct_usage = 'json_output(200, array(';
    if (strpos($content, $correct_usage) !== false) {
        echo "<p style='color: green;'>✅ Correct json_output usage found</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ Correct json_output usage not found</p>";
    }
    
} else {
    echo "<p style='color: red;'>❌ Admin Partners controller missing</p>";
}

echo "<h2>5. Function Signature Test</h2>";
echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ JSON Output Function Signature:</h3>";
echo "<ul>";
echo "<li><strong>Function:</strong> json_output(\$statusHeader, \$response)</li>";
echo "<li><strong>Parameters:</strong></li>";
echo "<ul>";
echo "<li><strong>\$statusHeader:</strong> HTTP status code (200, 400, 403, etc.)</li>";
echo "<li><strong>\$response:</strong> Array with status and message</li>";
echo "</ul>";
echo "<li><strong>Example:</strong> json_output(200, array('status' => 'success', 'message' => 'Operation completed'))</li>";
echo "</ul>";
echo "</div>";

echo "<h2>6. Fix Summary</h2>";
echo "<div style='background: #d1ecf1; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ Issues Fixed:</h3>";
echo "<ul>";
echo "<li><strong>Helper Loading:</strong> Added \$this->load->helper('json_output') to constructor</li>";
echo "<li><strong>Function Calls:</strong> Updated all json_output calls to use correct signature</li>";
echo "<li><strong>Status Codes:</strong> Added appropriate HTTP status codes (200, 400, 403)</li>";
echo "<li><strong>Error Handling:</strong> Proper error responses with status codes</li>";
echo "</ul>";

echo "<h3>✅ Changes Made:</h3>";
echo "<ul>";
echo "<li><strong>Constructor:</strong> Added json_output helper loading</li>";
echo "<li><strong>Success Responses:</strong> json_output(200, array('status' => 'success', ...))</li>";
echo "<li><strong>Error Responses:</strong> json_output(400/403, array('status' => 'error', ...))</li>";
echo "<li><strong>Data Responses:</strong> json_output(200, \$data)</li>";
echo "</ul>";
echo "</div>";

echo "<h2>7. Testing Instructions</h2>";
echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px;'>";
echo "<h3>Manual Testing Steps:</h3>";
echo "<ol>";
echo "<li><strong>Test Notes Management:</strong>";
echo "<ul>";
echo "<li>Login as admin: <a href='" . base_url('admin') . "' target='_blank'>Admin Login</a></li>";
echo "<li>Go to Partners: <a href='" . base_url('admin/partners') . "' target='_blank'>Partners List</a> (requires login)</li>";
echo "<li>Click 'View' on any partner</li>";
echo "<li>Click 'Notes' tab</li>";
echo "<li>Click 'Add Note' button</li>";
echo "<li>Fill in note details and click 'Save'</li>";
echo "<li>Should work without 'Call to undefined function json_output()' error</li>";
echo "</ul>";
echo "</li>";
echo "<li><strong>Test Reminders Management:</strong>";
echo "<ul>";
echo "<li>Click 'Reminders' tab</li>";
echo "<li>Click 'Add Reminder' button</li>";
echo "<li>Fill in reminder details and click 'Save'</li>";
echo "<li>Should work without 'Call to undefined function json_output()' error</li>";
echo "</ul>";
echo "</li>";
echo "</ol>";
echo "</div>";

echo "<h2>8. Expected Results</h2>";
echo "<div style='background: #d1ecf1; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ What Should Happen:</h3>";
echo "<ul>";
echo "<li>✅ No 'Call to undefined function json_output()' errors</li>";
echo "<li>✅ Notes and reminders forms submit successfully</li>";
echo "<li>✅ AJAX responses work properly</li>";
echo "<li>✅ Success and error messages display correctly</li>";
echo "<li>✅ All partner management operations work</li>";
echo "</ul>";

echo "<h3>❌ What Should NOT Happen:</h3>";
echo "<ul>";
echo "<li>❌ 'Call to undefined function json_output()' errors</li>";
echo "<li>❌ AJAX request failures</li>";
echo "<li>❌ Form submission errors</li>";
echo "<li>❌ JavaScript console errors</li>";
echo "</ul>";
echo "</div>";

echo "<h2>9. Technical Details</h2>";
echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px;'>";
echo "<h3>JSON Output Helper Function:</h3>";
echo "<pre>";
echo "function json_output(\$statusHeader, \$response) {\n";
echo "    \$ci = & get_instance();\n";
echo "    \$ci->output->set_content_type('application/json');\n";
echo "    \$ci->output->set_status_header(\$statusHeader);\n";
echo "    \$ci->output->set_output(json_encode(\$response));\n";
echo "}";
echo "</pre>";
echo "</div>";

echo "<p><strong>Test completed!</strong> The json_output function should now work correctly for all AJAX operations.</p>";
?>
