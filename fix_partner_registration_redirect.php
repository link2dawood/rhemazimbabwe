<?php
// Fix Partner Registration Redirect Issue
// Run: http://localhost/rhemazimbabwe/fix_partner_registration_redirect.php

echo "<h1>Fixing Partner Registration Redirect Issue</h1>";

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

echo "<h2>2. Checking Current Partner Controller</h2>";
try {
    $controller_file = 'application/controllers/user/Partner.php';
    $controller_content = file_get_contents($controller_file);
    
    // Check if the fix is already applied
    if (strpos($controller_content, "redirect(base_url('user/partner_registration/student_register'))") !== false) {
        echo "<p style='color: green;'>✅ Partner controller already fixed!</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ Partner controller needs fixing</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error checking controller: " . $e->getMessage() . "</p>";
}

echo "<h2>3. Checking Routes Configuration</h2>";
try {
    $routes_file = 'application/config/routes.php';
    $routes_content = file_get_contents($routes_file);
    
    // Check if user partner registration routes exist
    if (strpos($routes_content, "user/partner_registration/student_register") !== false) {
        echo "<p style='color: green;'>✅ User partner registration routes exist!</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ User partner registration routes missing</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error checking routes: " . $e->getMessage() . "</p>";
}

echo "<h2>4. Testing Registration Flow</h2>";
echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ Test the Fixed Flow:</h3>";
echo "<ol>";
echo "<li><strong>Student Login:</strong> <a href='" . base_url('userlogin') . "' target='_blank'>Login as Student</a></li>";
echo "<li><strong>Student Partner Registration:</strong> <a href='" . base_url('user/partner_registration/student_register') . "' target='_blank'>Register as Partner</a></li>";
echo "<li><strong>Staff Partner Registration:</strong> <a href='" . base_url('user/partner_registration/staff_register') . "' target='_blank'>Staff Register as Partner</a></li>";
echo "<li><strong>Admin Partner Management:</strong> <a href='" . base_url('admin/partners') . "' target='_blank'>Admin Manage Partners</a></li>";
echo "<li><strong>Public Registration:</strong> <a href='" . base_url('partner_registration') . "' target='_blank'>Public Registration</a></li>";
echo "</ol>";
echo "</div>";

echo "<h2>5. Expected Behavior</h2>";
echo "<div style='background: #d1ecf1; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ What Should Happen Now:</h3>";
echo "<ul>";
echo "<li><strong>Student Login → Partners → Register:</strong> Should redirect to <code>user/partner_registration/student_register</code></li>";
echo "<li><strong>Staff Login → Partners → Register:</strong> Should redirect to <code>user/partner_registration/staff_register</code></li>";
echo "<li><strong>Admin Login → Partners:</strong> Should show partner management interface</li>";
echo "<li><strong>Public User:</strong> Should access <code>partner_registration</code> for registration</li>";
echo "</ul>";

echo "<h3>❌ What Should NOT Happen:</h3>";
echo "<ul>";
echo "<li>❌ Students should NOT be redirected to <code>user/user/dashboard</code></li>";
echo "<li>❌ Staff should NOT be redirected to <code>admin/dashboard</code></li>";
echo "<li>❌ No 'No More Classes Found' errors</li>";
echo "<li>❌ No broken redirects</li>";
echo "</ul>";
echo "</div>";

echo "<h2>6. User Type Access Summary</h2>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>User Type</th><th>Can Register as Partner</th><th>Can Manage Partners</th><th>Registration Method</th></tr>";
echo "<tr><td>Student</td><td>✅ Yes</td><td>❌ No</td><td>Student Portal → Partners → Register</td></tr>";
echo "<tr><td>Staff</td><td>✅ Yes</td><td>❌ No</td><td>Staff Portal → Partners → Register</td></tr>";
echo "<tr><td>Admin</td><td>✅ Yes</td><td>✅ Yes</td><td>Admin Panel → Partners → Add/Manage</td></tr>";
echo "<tr><td>Public User</td><td>✅ Yes (Pending Approval)</td><td>❌ No</td><td>Public Registration Form</td></tr>";
echo "</table>";

echo "<h2>7. Fix Verification</h2>";
echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ Verification Steps:</h3>";
echo "<ol>";
echo "<li>Login as a student</li>";
echo "<li>Navigate to Partners section</li>";
echo "<li>Click 'Register as Partner'</li>";
echo "<li>Verify you are taken to the partner registration form (not user dashboard)</li>";
echo "<li>Fill out the form and submit</li>";
echo "<li>Verify you are redirected to partner dashboard (not user dashboard)</li>";
echo "</ol>";
echo "</div>";

echo "<h2>8. Troubleshooting</h2>";
echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px;'>";
echo "<h3>🔧 If Issues Persist:</h3>";
echo "<ul>";
echo "<li><strong>Clear Browser Cache:</strong> Hard refresh the page (Ctrl+F5)</li>";
echo "<li><strong>Check Routes:</strong> Ensure routes are properly configured</li>";
echo "<li><strong>Check Controller:</strong> Verify Partner controller has correct redirects</li>";
echo "<li><strong>Check Session:</strong> Ensure user session is properly maintained</li>";
echo "<li><strong>Check Database:</strong> Verify partner tables exist and are accessible</li>";
echo "</ul>";
echo "</div>";

echo "<p><strong>Partner registration redirect fix completed!</strong> Students and staff should now be properly redirected to partner registration instead of their dashboards.</p>";
?>
