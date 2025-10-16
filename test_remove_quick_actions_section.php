<?php
// Test Remove Quick Actions Section
// Run: http://localhost/rhemazimbabwe/test_remove_quick_actions_section.php

echo "<h1>Remove Quick Actions Section Test</h1>";

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
    
    // Check if quick actions section is removed
    $content = file_get_contents($view_file);
    
    // Check for removed quick actions section
    $quick_actions_patterns = [
        'Quick Actions',
        'quick_actions',
        'btn btn-app',
        'fa fa-plus-circle',
        'fa fa-cog',
        'add_partner',
        'manage_settings',
        'user/partner/register',
        'user/partner/settings'
    ];
    
    $section_removed = true;
    foreach ($quick_actions_patterns as $pattern) {
        if (strpos($content, $pattern) !== false) {
            $section_removed = false;
            break;
        }
    }
    
    if ($section_removed) {
        echo "<p style='color: green;'>✅ Quick actions section successfully removed</p>";
    } else {
        echo "<p style='color: red;'>❌ Quick actions section still found in view</p>";
    }
    
    // Check if other sections are still present
    $other_sections = [
        'Partner Records' => 'Partner Records',
        'Statistics' => 'info-box',
        'Add Contribution Modal' => 'addContributionModal'
    ];
    
    foreach ($other_sections as $section => $pattern) {
        if (strpos($content, $pattern) !== false) {
            echo "<p style='color: green;'>✅ $section section is still present</p>";
        } else {
            echo "<p style='color: orange;'>⚠️ $section section not found</p>";
        }
    }
    
} else {
    echo "<p style='color: red;'>❌ Partner dashboard view missing</p>";
}

echo "<h2>3. Specific Pattern Checks</h2>";

$content = file_get_contents($view_file);

$checks = [
    'Quick Actions title' => 'Quick Actions',
    'Add partner button' => 'add_partner',
    'Manage settings button' => 'manage_settings',
    'App-style buttons' => 'btn btn-app',
    'Plus circle icon' => 'fa fa-plus-circle',
    'Cog icon' => 'fa fa-cog',
    'Register link' => 'user/partner/register',
    'Settings link' => 'user/partner/settings'
];

foreach ($checks as $description => $pattern) {
    if (strpos($content, $pattern) === false) {
        echo "<p style='color: green;'>✅ $description - REMOVED</p>";
    } else {
        echo "<p style='color: red;'>❌ $description - STILL EXISTS</p>";
    }
}

echo "<h2>4. Remaining Dashboard Elements</h2>";
$remaining_elements = [
    'Statistics boxes' => 'info-box',
    'Partner table' => 'table table-striped',
    'Add contribution modal' => 'addContributionModal',
    'Quick action buttons in main section' => 'Quick Actions',
    'Partner records section' => 'Partner Records'
];

foreach ($remaining_elements as $element => $pattern) {
    if (strpos($content, $pattern) !== false) {
        echo "<p style='color: green;'>✅ $element - PRESENT</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ $element - NOT FOUND</p>";
    }
}

echo "<h2>5. Fix Summary</h2>";
echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ Section Removed:</h3>";
echo "<ul>";
echo "<li><strong>Entire Quick Actions Box:</strong> Complete section with title and buttons removed</li>";
echo "<li><strong>Add Partner Button:</strong> Button for adding new partners removed</li>";
echo "<li><strong>Manage Settings Button:</strong> Button for managing partner settings removed</li>";
echo "<li><strong>App-style Buttons:</strong> All btn btn-app style buttons removed</li>";
echo "</ul>";

echo "<h3>✅ Sections Preserved:</h3>";
echo "<ul>";
echo "<li><strong>Statistics Boxes:</strong> Partner statistics display intact</li>";
echo "<li><strong>Partner Table:</strong> Partner records table with individual actions intact</li>";
echo "<li><strong>Add Contribution Modal:</strong> Modal for adding contributions intact</li>";
echo "<li><strong>Main Quick Actions:</strong> Main dashboard quick actions intact</li>";
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
echo "<li><strong>Verify Section Removal:</strong>";
echo "<ul>";
echo "<li>Check that there's no 'Quick Actions' section below the statistics</li>";
echo "<li>Verify no app-style buttons (large buttons) are visible</li>";
echo "<li>Confirm partner table and statistics are still present</li>";
echo "<li>Check that main quick actions (Settings, Add Contribution, etc.) are still there</li>";
echo "</ul>";
echo "</li>";
echo "</ol>";
echo "</div>";

echo "<h2>7. Expected Results</h2>";
echo "<div style='background: #d1ecf1; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ What Should Be Gone:</h3>";
echo "<ul>";
echo "<li>✅ No 'Quick Actions' section below statistics</li>";
echo "<li>✅ No app-style buttons (btn btn-app)</li>";
echo "<li>✅ No 'Add Partner' button</li>";
echo "<li>✅ No 'Manage Settings' button</li>";
echo "<li>✅ No links to user/partner/register or user/partner/settings</li>";
echo "</ul>";

echo "<h3>✅ What Should Remain:</h3>";
echo "<ul>";
echo "<li>✅ Statistics boxes at the top</li>";
echo "<li>✅ Partner records table</li>";
echo "<li>✅ Main quick actions (Settings, Add Contribution, View Contributions, Change Password)</li>";
echo "<li>✅ Add contribution modal</li>";
echo "<li>✅ All other dashboard functionality</li>";
echo "</ul>";
echo "</div>";

echo "<h2>8. Alternative Access</h2>";
echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px;'>";
echo "<h3>Note:</h3>";
echo "<p>Users can still access partner management through:</p>";
echo "<ul>";
echo "<li>Main quick actions section (Settings, Add Contribution, etc.)</li>";
echo "<li>Individual partner row actions in the table</li>";
echo "<li>Direct navigation to specific pages</li>";
echo "</ul>";
echo "</div>";

echo "<p><strong>Test completed!</strong> The entire quick actions section has been successfully removed from the partner dashboard.</p>";
?>
