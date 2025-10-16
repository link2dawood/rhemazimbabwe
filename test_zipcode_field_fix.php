<?php
// Test Zipcode Field Fix
// Run: http://localhost/rhemazimbabwe/test_zipcode_field_fix.php

echo "<h1>Zipcode Field Fix Test</h1>";

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

echo "<h2>2. Database Column Check</h2>";
try {
    $result = $CI->db->query("DESCRIBE partners");
    $columns = $result->result_array();
    
    $found_zip_code = false;
    $found_zipcode = false;
    
    foreach ($columns as $column) {
        if ($column['Field'] === 'zip_code') {
            $found_zip_code = true;
        }
        if ($column['Field'] === 'zipcode') {
            $found_zipcode = true;
        }
    }
    
    if ($found_zip_code) {
        echo "<p style='color: green;'>✅ Correct column 'zip_code' exists in partners table</p>";
    } else {
        echo "<p style='color: red;'>❌ Column 'zip_code' missing from partners table</p>";
    }
    
    if ($found_zipcode) {
        echo "<p style='color: orange;'>⚠️ Incorrect column 'zipcode' found in partners table</p>";
    } else {
        echo "<p style='color: green;'>✅ Incorrect column 'zipcode' not found in partners table</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database query error: " . $e->getMessage() . "</p>";
}

echo "<h2>3. Controller Files Test</h2>";
$controller_file = 'application/controllers/admin/Partners.php';
if (file_exists($controller_file)) {
    echo "<p style='color: green;'>✅ Admin Partners controller exists</p>";
    
    $content = file_get_contents($controller_file);
    
    if (strpos($content, "'zip_code' => \$this->input->post('zip_code')") !== false) {
        echo "<p style='color: green;'>✅ Controller uses correct 'zip_code' field</p>";
    } else {
        echo "<p style='color: red;'>❌ Controller still uses incorrect field name</p>";
    }
    
    if (strpos($content, "'zipcode' => \$this->input->post('zipcode')") === false) {
        echo "<p style='color: green;'>✅ Controller no longer uses incorrect 'zipcode' field</p>";
    } else {
        echo "<p style='color: red;'>❌ Controller still has incorrect 'zipcode' field</p>";
    }
} else {
    echo "<p style='color: red;'>❌ Admin Partners controller missing</p>";
}

echo "<h2>4. View Files Test</h2>";
$view_files = [
    'application/views/admin/partners/partneredit.php' => 'Partner Edit View',
    'application/views/admin/partners/partneradd.php' => 'Partner Add View'
];

foreach ($view_files as $file => $description) {
    if (file_exists($file)) {
        echo "<p style='color: green;'>✅ $description exists</p>";
        
        $content = file_get_contents($file);
        
        if (strpos($content, 'name="zip_code"') !== false) {
            echo "<p style='color: green;'>✅ Uses correct 'zip_code' field name</p>";
        } else {
            echo "<p style='color: red;'>❌ 'zip_code' field name not found</p>";
        }
        
        if (strpos($content, 'name="zipcode"') === false) {
            echo "<p style='color: green;'>✅ No longer uses incorrect 'zipcode' field name</p>";
        } else {
            echo "<p style='color: red;'>❌ Still uses incorrect 'zipcode' field name</p>";
        }
        
        if (strpos($content, 'set_value(\'zip_code\'') !== false) {
            echo "<p style='color: green;'>✅ Uses correct set_value for 'zip_code'</p>";
        } else {
            echo "<p style='color: orange;'>⚠️ set_value for 'zip_code' not found</p>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ $description missing</p>";
    }
}

echo "<h2>5. Migration File Test</h2>";
$migration_file = 'application/migrations/126_create_partners_table.php';
if (file_exists($migration_file)) {
    echo "<p style='color: green;'>✅ Migration file exists</p>";
    
    $content = file_get_contents($migration_file);
    
    if (strpos($content, "'zip_code' => array(") !== false) {
        echo "<p style='color: green;'>✅ Migration uses correct 'zip_code' column</p>";
    } else {
        echo "<p style='color: red;'>❌ Migration still uses incorrect column name</p>";
    }
    
    if (strpos($content, "'zipcode' => array(") === false) {
        echo "<p style='color: green;'>✅ Migration no longer uses incorrect 'zipcode' column</p>";
    } else {
        echo "<p style='color: red;'>❌ Migration still has incorrect 'zipcode' column</p>";
    }
} else {
    echo "<p style='color: orange;'>⚠️ Migration file not found</p>";
}

echo "<h2>6. Fix Summary</h2>";
echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ Issues Fixed:</h3>";
echo "<ul>";
echo "<li><strong>Database Error 1054:</strong> Fixed 'Unknown column zipcode' error</li>";
echo "<li><strong>Form Field Names:</strong> Changed from 'zipcode' to 'zip_code' in all forms</li>";
echo "<li><strong>Controller Data Arrays:</strong> Updated to use correct field names</li>";
echo "<li><strong>Migration File:</strong> Fixed column name in migration</li>";
echo "<li><strong>View Files:</strong> Updated all partner views to use correct field names</li>";
echo "</ul>";

echo "<h3>✅ Files Updated:</h3>";
echo "<ul>";
echo "<li><strong>application/controllers/admin/Partners.php:</strong> Fixed data array field names</li>";
echo "<li><strong>application/views/admin/partners/partneredit.php:</strong> Fixed form field name</li>";
echo "<li><strong>application/views/admin/partners/partneradd.php:</strong> Fixed form field name</li>";
echo "<li><strong>application/migrations/126_create_partners_table.php:</strong> Fixed column name</li>";
echo "</ul>";
echo "</div>";

echo "<h2>7. Testing Instructions</h2>";
echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px;'>";
echo "<h3>Manual Testing Steps:</h3>";
echo "<ol>";
echo "<li><strong>Test Partner Edit:</strong>";
echo "<ul>";
echo "<li>Login as admin: <a href='" . base_url('admin') . "' target='_blank'>Admin Login</a></li>";
echo "<li>Go to Partners: <a href='" . base_url('admin/partners') . "' target='_blank'>Partners List</a> (requires login)</li>";
echo "<li>Click 'Edit' on any partner</li>";
echo "<li>Update the zip code field</li>";
echo "<li>Click 'Save' - should work without database errors</li>";
echo "</ul>";
echo "</li>";
echo "<li><strong>Test Partner Add:</strong>";
echo "<ul>";
echo "<li>Click 'Add Partner' button</li>";
echo "<li>Fill in the form including zip code</li>";
echo "<li>Click 'Save' - should work without database errors</li>";
echo "</ul>";
echo "</li>";
echo "</ol>";
echo "</div>";

echo "<h2>8. Expected Results</h2>";
echo "<div style='background: #d1ecf1; padding: 15px; border-radius: 5px;'>";
echo "<h3>✅ What Should Happen:</h3>";
echo "<ul>";
echo "<li>✅ No 'Unknown column zipcode' database errors</li>";
echo "<li>✅ Partner edit form saves successfully</li>";
echo "<li>✅ Partner add form saves successfully</li>";
echo "<li>✅ Zip code data is properly stored and retrieved</li>";
echo "<li>✅ All partner operations work without errors</li>";
echo "</ul>";

echo "<h3>❌ What Should NOT Happen:</h3>";
echo "<ul>";
echo "<li>❌ Database Error 1054</li>";
echo "<li>❌ 'Unknown column zipcode' errors</li>";
echo "<li>❌ Form submission failures</li>";
echo "<li>❌ Data not being saved</li>";
echo "</ul>";
echo "</div>";

echo "<h2>9. Database Schema Reference</h2>";
echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px;'>";
echo "<h3>Correct Field Mapping:</h3>";
echo "<ul>";
echo "<li><strong>Form Field:</strong> name=\"zip_code\"</li>";
echo "<li><strong>Database Column:</strong> zip_code</li>";
echo "<li><strong>Controller Array:</strong> 'zip_code' => \$this->input->post('zip_code')</li>";
echo "<li><strong>View Display:</strong> \$partner->zip_code</li>";
echo "</ul>";
echo "</div>";

echo "<p><strong>Test completed!</strong> The zipcode field naming issues should now be fixed and partner operations should work without database errors.</p>";
?>
