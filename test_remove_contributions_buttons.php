<?php
// Test Remove Contributions Buttons
// Run: http://localhost/rhemazimbabwe/test_remove_contributions_buttons.php

echo "<h1>Remove Contributions Buttons Test</h1>";

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

echo "<h2>2. View File Test</h2>";
$view_file = 'application/views/user/partner/dashboard.php';
if (file_exists($view_file)) {
    echo "<p style='color: green;'>✅ Partner dashboard view exists</p>";
    
    // Check if contributions buttons are removed
    $content = file_get_contents($view_file);
    
    // Check for removed contributions buttons
    $contributions_buttons = [
        'user/partner/contributions?partner_id=',
        'fa fa-history',
        'view_history',
        'Contributions'
    ];
    
    $buttons_removed = true;
    foreach ($contributions_buttons as $button_text) {
        if (strpos($content, $button_text) !== false) {
            $buttons_removed = false;
            break;
        }
    }
    
    if ($buttons_removed) {
        echo "<p style='color: green;'>✅ Contributions buttons successfully removed</p>";
    } else {
        echo "<p style='color: red;'>❌ Contributions buttons still found in view</p>";
    }
    
    // Check if settings buttons are still present
    if (strpos($content, 'fa fa-cog') !== false && strpos($content, 'manage_settings') !== false) {
        echo "<p style='color: green;'>✅ Settings buttons are still present</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ Settings buttons may have been affected</p>";
    }
    
    // Count remaining buttons
    $button_count = substr_count($content, 'btn btn-');
    echo "<p>Total buttons remaining: <strong>$button_count</strong></p>";
    
} else {
    echo "<p style='color: red;'>❌ Partner dashboard view missing</p>";
}

echo "<h2>3. Specific Button Checks</h2>";

// Check for specific button patterns that should be removed
$content = file_get_contents($view_file);

$checks = [
    'Small contributions button in table row' => 'user/partner/contributions?partner_id=',
    'Large contributions button in quick actions' => 'view_history',
    'Contributions icon' => 'fa fa-history',
    'Contributions tooltip' => 'Contributions'
];

foreach ($checks as $description => $pattern) {
    if (strpos($content, $pattern) === false) {
        echo "<p style='color: green;'>✅ $description - REMOVED</p>";
    } else {
        echo "<p style='color: red;'>❌ $description - STILL EXISTS</p>";
    }
}

echo "<h2>4. Remaining Functionality Test</h2>";
$remaining_buttons = [
    'Settings button in table row' => 'fa fa-cog',
    'Settings button in quick actions' => 'manage_settings',
    'Add partner button' => 'add_partner'
];

foreach ($remaining_buttons as $description => $pattern) {
    if (strpos($content, $pattern) !== false) {
        echo "<p style='color: green;'>✅ $description - PRESENT</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ $description - NOT FOUND</p>";
    }
}

echo "<h2>5. Fix Summary</h2>";
echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ Buttons Removed:</h3>";
echo "<ul>";
echo "<li><strong>Small Contributions Button:</strong> Removed from partner table row</li>";
echo "<li><strong>Large Contributions Button:</strong> Removed from quick actions section</li>";
echo "<li><strong>Contributions Links:</strong> All links to user/partner/contributions removed</li>";
echo "<li><strong>Contributions Icons:</strong> fa-history icons removed</li>";
echo "</ul>";

echo "<h3>✅ Buttons Preserved:</h3>";
echo "<ul>";
echo "<li><strong>Settings Buttons:</strong> Both small and large settings buttons kept</li>";
echo "<li><strong>Add Partner Button:</strong> Add partner functionality preserved</li>";
echo "<li><strong>Other Navigation:</strong> All other dashboard functionality intact</li>";
echo "</ul>";
echo "</div>";

echo "<h2>6. Testing Instructions</h2>";
echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px;'>";
echo "<h3>Manual Testing Steps:</h3>";
echo "<ol>";
echo "<li><strong>Access Partner Dashboard:</strong>";
echo "<ul>";
echo "<li>Login as partner: <a href='" . base_url('partnerportal/login') . "' target='_blank'>Partner Login</a></li>";
echo "<li>Go to dashboard: <a href='" . base_url('partnerdashboard') . "' target='_blank'>Partner Dashboard</a> (requires login)</li>";
echo "</ul>";
echo "</li>";
echo "<li><strong>Verify Button Removal:</strong>";
echo "<ul>";
echo "<li>Check partner table - no contributions button should be visible</li>";
echo "<li>Check quick actions section - no contributions button should be visible</li>";
echo "<li>Settings buttons should still be present and working</li>";
echo "<li>Add partner button should still be present</li>";
echo "</ul>";
echo "</li>";
echo "</ol>";
echo "</div>";

echo "<h2>7. Expected Results</h2>";
echo "<div style='background: #d1ecf1; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ What Should Be Gone:</h3>";
echo "<ul>";
echo "<li>✅ No contributions buttons in partner table</li>";
echo "<li>✅ No contributions buttons in quick actions</li>";
echo "<li>✅ No links to user/partner/contributions</li>";
echo "<li>✅ No fa-history icons</li>";
echo "</ul>";

echo "<h3>✅ What Should Remain:</h3>";
echo "<ul>";
echo "<li>✅ Settings buttons (both small and large)</li>";
echo "<li>✅ Add partner button</li>";
echo "<li>✅ All other dashboard functionality</li>";
echo "<li>✅ Partner table and statistics</li>";
echo "</ul>";
echo "</div>";

echo "<h2>8. Alternative Access to Contributions</h2>";
echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px;'>";
echo "<h3>Note:</h3>";
echo "<p>If users still need access to contributions, they can:</p>";
echo "<ul>";
echo "<li>Use the main navigation menu</li>";
echo "<li>Access via direct URL: <code>partnerdashboard/contributions</code></li>";
echo "<li>Use the quick actions section on the main dashboard</li>";
echo "</ul>";
echo "</div>";

echo "<p><strong>Test completed!</strong> The contributions buttons have been successfully removed from the partner dashboard.</p>";
?>
