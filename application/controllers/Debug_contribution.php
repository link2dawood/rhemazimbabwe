<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Debug Controller for Contribution Issues
 * Access: http://www.rhemazimbabwe.com/debug_contribution
 */
class Debug_contribution extends CI_Controller
{
    public function index()
    {
        // Load database
        $this->load->database();
        
        echo "<!DOCTYPE html>";
        echo "<html><head><title>Contribution Debug</title>";
        echo "<style>
            body { font-family: Arial, sans-serif; padding: 20px; }
            h1 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
            h2 { color: #555; margin-top: 30px; }
            .success { color: green; font-weight: bold; }
            .error { color: red; font-weight: bold; }
            table { border-collapse: collapse; width: 100%; margin: 20px 0; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #007bff; color: white; }
            pre { background: #f4f4f4; padding: 10px; border: 1px solid #ddd; overflow-x: auto; }
        </style></head><body>";
        
        echo "<h1>🔍 Partner Contributions Debug</h1>";
        echo "<p><em>This page helps diagnose why contributions cannot be added</em></p>";
        echo "<hr>";
        
        // 1. Check if table exists
        echo "<h2>1. Check Database Tables</h2>";
        $tables = $this->db->list_tables();
        
        $required_tables = ['partner_contributions', 'partners', 'giving_types', 'giving_frequencies'];
        foreach ($required_tables as $table) {
            if (in_array($table, $tables)) {
                echo "<p class='success'>✓ Table '{$table}' EXISTS</p>";
            } else {
                echo "<p class='error'>✗ Table '{$table}' DOES NOT EXIST</p>";
            }
        }
        
        // 2. Check table structure
        if (in_array('partner_contributions', $tables)) {
            echo "<h2>2. Partner Contributions Table Structure</h2>";
            $fields = $this->db->field_data('partner_contributions');
            echo "<table>";
            echo "<tr><th>Field Name</th><th>Type</th><th>Max Length</th><th>Default</th><th>Nullable</th></tr>";
            foreach ($fields as $field) {
                echo "<tr>";
                echo "<td><strong>" . htmlspecialchars($field->name) . "</strong></td>";
                echo "<td>" . htmlspecialchars($field->type) . "</td>";
                echo "<td>" . htmlspecialchars($field->max_length) . "</td>";
                echo "<td>" . htmlspecialchars($field->default) . "</td>";
                echo "<td>" . ($field->null ? 'YES' : 'NO') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        
        // 3. Test insert with minimal data
        echo "<h2>3. Test Contribution Insert</h2>";
        
        // Get first active partner
        $partner = $this->db->select('id, firstname, lastname')
                           ->where('is_active', 1)
                           ->limit(1)
                           ->get('partners')
                           ->row();
        
        if (!$partner) {
            echo "<p class='error'>✗ No active partners found in database</p>";
            echo "<p><strong>FIX:</strong> Create at least one active partner first</p>";
        } else {
            echo "<p class='success'>✓ Found partner: {$partner->firstname} {$partner->lastname} (ID: {$partner->id})</p>";
            
            // Prepare test data
            $test_data = [
                'partner_id' => $partner->id,
                'contribution_date' => date('Y-m-d'),
                'amount' => 100.00,
                'currency' => 'USD',
                'payment_method' => 'cash',
                'status' => 'completed',
                'receipt_no' => 'DEBUG-' . time(),
                'notes' => 'Test contribution from debug script'
            ];
            
            echo "<p><strong>Test Data to Insert:</strong></p>";
            echo "<pre>" . print_r($test_data, true) . "</pre>";
            
            // Disable db_debug to catch errors manually
            $this->db->db_debug = FALSE;
            
            // Try insert
            $insert_result = $this->db->insert('partner_contributions', $test_data);
            
            if ($insert_result) {
                $insert_id = $this->db->insert_id();
                echo "<p class='success'>✓ TEST INSERT SUCCESSFUL! Insert ID: {$insert_id}</p>";
                echo "<p><strong>Last Query Executed:</strong></p>";
                echo "<pre>" . $this->db->last_query() . "</pre>";
                
                // Verify record was created
                $verify = $this->db->where('id', $insert_id)->get('partner_contributions')->row();
                if ($verify) {
                    echo "<p class='success'>✓ Record verified in database</p>";
                    echo "<pre>" . print_r($verify, true) . "</pre>";
                    
                    // Clean up
                    $this->db->where('id', $insert_id)->delete('partner_contributions');
                    echo "<p><em>Test record cleaned up (deleted)</em></p>";
                } else {
                    echo "<p class='error'>✗ Could not verify record after insert</p>";
                }
            } else {
                echo "<p class='error'>✗ INSERT FAILED!</p>";
                echo "<p><strong>Last Query Attempted:</strong></p>";
                echo "<pre>" . $this->db->last_query() . "</pre>";
                
                $error = $this->db->error();
                echo "<p><strong>Database Error Details:</strong></p>";
                echo "<pre>";
                echo "Error Code: " . $error['code'] . "\n";
                echo "Error Message: " . $error['message'];
                echo "</pre>";
                
                // Provide solutions
                echo "<h3>🔧 Possible Solutions:</h3>";
                echo "<ul>";
                echo "<li>Check if all required columns exist in the table</li>";
                echo "<li>Check column data types match the values being inserted</li>";
                echo "<li>Check for FOREIGN KEY constraints that might be failing</li>";
                echo "<li>Check if any columns have NOT NULL constraint without defaults</li>";
                echo "<li>Check database user permissions (INSERT privilege)</li>";
                echo "</ul>";
            }
        }
        
        // 4. Check upload directory
        echo "<h2>4. File Upload Directory</h2>";
        $upload_dir = './uploads/partner_contributions/';
        if (is_dir($upload_dir)) {
            echo "<p class='success'>✓ Upload directory exists: " . realpath($upload_dir) . "</p>";
            if (is_writable($upload_dir)) {
                echo "<p class='success'>✓ Directory is writable</p>";
            } else {
                echo "<p class='error'>✗ Directory is NOT writable</p>";
                echo "<p><strong>FIX:</strong> Run: <code>chmod 755 " . realpath($upload_dir) . "</code></p>";
            }
        } else {
            echo "<p class='error'>✗ Upload directory does NOT exist</p>";
            echo "<p><strong>FIX:</strong> Create directory: {$upload_dir}</p>";
            echo "<p><code>mkdir -p " . realpath('./uploads') . "/partner_contributions</code></p>";
        }
        
        // 5. Check if Contribution_model can be loaded
        echo "<h2>5. Check Models</h2>";
        try {
            $this->load->model('contribution_model');
            echo "<p class='success'>✓ Contribution_model loaded successfully</p>";
            
            // Test the generateReceiptNumber method
            $receipt = $this->contribution_model->generateReceiptNumber();
            echo "<p class='success'>✓ generateReceiptNumber() works: {$receipt}</p>";
        } catch (Exception $e) {
            echo "<p class='error'>✗ Failed to load Contribution_model</p>";
            echo "<pre>" . $e->getMessage() . "</pre>";
        }
        
        // 6. Check controller permissions
        echo "<h2>6. Check RBAC Permissions</h2>";
        if (file_exists(APPPATH . 'libraries/Rbac.php')) {
            echo "<p class='success'>✓ RBAC library exists</p>";
        } else {
            echo "<p class='error'>✗ RBAC library not found (this is OK for debugging)</p>";
        }
        
        echo "<hr>";
        echo "<p><strong>Debug Complete!</strong> Delete this controller file after troubleshooting:</p>";
        echo "<p><code>application/controllers/Debug_contribution.php</code></p>";
        echo "</body></html>";
    }
}

