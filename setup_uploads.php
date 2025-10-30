<?php
/**
 * Setup Upload Directories for Contributions
 * Run this once: http://localhost/rhemazimbabwe/setup_uploads.php
 */

echo "<html><head><title>Setup Upload Directories</title>";
echo "<style>
body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
.container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 5px; }
h1 { color: #333; }
.success { color: green; font-weight: bold; }
.error { color: red; font-weight: bold; }
.info { background: #d9edf7; padding: 15px; margin: 10px 0; border-left: 4px solid #31b0d5; }
</style></head><body>";

echo "<div class='container'>";
echo "<h1>📁 Upload Directories Setup</h1>";

$base_path = dirname(__FILE__);
$directories = [
    'uploads',
    'uploads/partner_contributions',
    'uploads/partner_images',
    'uploads/partner_documents'
];

$results = [];

foreach ($directories as $dir) {
    $full_path = $base_path . DIRECTORY_SEPARATOR . $dir;

    echo "<h3>Setting up: {$dir}</h3>";

    // Check if exists
    if (file_exists($full_path)) {
        echo "<p>✓ Directory exists: <code>{$full_path}</code></p>";

        // Check if writable
        if (is_writable($full_path)) {
            echo "<p class='success'>✓ Directory is writable</p>";
            $results[$dir] = 'OK';
        } else {
            echo "<p class='error'>✗ Directory is NOT writable</p>";

            // Try to make writable
            if (chmod($full_path, 0777)) {
                echo "<p class='success'>✓ Fixed: Made directory writable</p>";
                $results[$dir] = 'FIXED';
            } else {
                echo "<p class='error'>✗ Failed to make writable - please set permissions manually</p>";
                $results[$dir] = 'ERROR';
            }
        }
    } else {
        echo "<p>Creating directory: <code>{$full_path}</code></p>";

        // Try to create
        if (mkdir($full_path, 0777, true)) {
            echo "<p class='success'>✓ Directory created successfully</p>";
            $results[$dir] = 'CREATED';
        } else {
            echo "<p class='error'>✗ Failed to create directory</p>";
            echo "<p>Please create manually and set write permissions (777)</p>";
            $results[$dir] = 'ERROR';
        }
    }

    echo "<hr>";
}

// Create .htaccess to allow access to uploads
$htaccess_path = $base_path . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . '.htaccess';
if (!file_exists($htaccess_path)) {
    $htaccess_content = "# Allow access to uploaded files\nOptions -Indexes\n<Files *>\n    Order allow,deny\n    Allow from all\n</Files>";

    if (file_put_contents($htaccess_path, $htaccess_content)) {
        echo "<p class='success'>✓ Created .htaccess for uploads security</p>";
    }
}

// Create index.html to prevent directory listing
foreach ($directories as $dir) {
    $index_path = $base_path . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . 'index.html';
    if (!file_exists($index_path)) {
        file_put_contents($index_path, '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><h1>Directory access is forbidden.</h1></body></html>');
    }
}

// Summary
echo "<h2>📊 Summary</h2>";
echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Directory</th><th>Status</th></tr>";

$all_ok = true;
foreach ($results as $dir => $status) {
    $color = 'black';
    if ($status == 'OK' || $status == 'CREATED' || $status == 'FIXED') {
        $color = 'green';
    } else {
        $color = 'red';
        $all_ok = false;
    }

    echo "<tr>";
    echo "<td><code>{$dir}</code></td>";
    echo "<td style='color:{$color};font-weight:bold;'>{$status}</td>";
    echo "</tr>";
}
echo "</table>";

if ($all_ok) {
    echo "<div class='info' style='background:#d4edda;border-color:#28a745;'>";
    echo "<h3>✅ Success!</h3>";
    echo "<p>All upload directories are set up correctly and ready to use.</p>";
    echo "<p><strong>Next Steps:</strong></p>";
    echo "<ol>";
    echo "<li>Run the SQL fix: <code>fix_contributions_complete.sql</code></li>";
    echo "<li>Test adding a contribution: <a href='admin/partnercontributions/add'>Add Contribution</a></li>";
    echo "<li>Delete this file for security: <code>setup_uploads.php</code></li>";
    echo "</ol>";
    echo "</div>";
} else {
    echo "<div class='info' style='background:#f8d7da;border-color:#dc3545;'>";
    echo "<h3>⚠️ Action Required</h3>";
    echo "<p>Some directories need manual setup. Please:</p>";
    echo "<ol>";
    echo "<li>Create missing directories manually</li>";
    echo "<li>Set permissions to 777 (or appropriate for your server)</li>";
    echo "<li>Refresh this page to verify</li>";
    echo "</ol>";

    echo "<h4>Windows Command:</h4>";
    echo "<pre>mkdir uploads\\partner_contributions\nmkdir uploads\\partner_images\nmkdir uploads\\partner_documents</pre>";

    echo "<h4>Linux/Mac Command:</h4>";
    echo "<pre>mkdir -p uploads/partner_contributions\nmkdir -p uploads/partner_images\nmkdir -p uploads/partner_documents\nchmod -R 777 uploads/</pre>";
    echo "</div>";
}

// Test upload
echo "<h2>🧪 Test Upload</h2>";
if (isset($_POST['test_upload'])) {
    $test_file = $base_path . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'partner_contributions' . DIRECTORY_SEPARATOR . 'test_' . time() . '.txt';
    $test_content = "Test upload file created at " . date('Y-m-d H:i:s');

    if (file_put_contents($test_file, $test_content)) {
        echo "<p class='success'>✓ Test file created successfully!</p>";
        echo "<p>File: <code>{$test_file}</code></p>";

        // Try to delete it
        if (unlink($test_file)) {
            echo "<p class='success'>✓ Test file deleted successfully - upload system is working!</p>";
        }
    } else {
        echo "<p class='error'>✗ Failed to create test file - check permissions</p>";
    }
}

echo "<form method='POST'>";
echo "<button type='submit' name='test_upload' style='padding:10px 20px; background:#3c8dbc; color:white; border:none; border-radius:3px; cursor:pointer;'>Test Upload System</button>";
echo "</form>";

echo "<p style='margin-top:40px; color:#666; font-size:12px;'>Setup Tool - Delete this file after setup is complete for security</p>";

echo "</div></body></html>";
?>
