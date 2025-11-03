<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

// Load Partner_Controller
require_once(APPPATH . 'core/Partner_Controller.php');

class Partner_debug extends Partner_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Debug permissions for logged-in partner
     */
    public function index()
    {
        echo "<h1>Partner Permissions Debug</h1>";
        echo "<style>
            body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
            .success { color: green; font-weight: bold; }
            .error { color: red; font-weight: bold; }
            table { border-collapse: collapse; width: 100%; background: white; margin: 10px 0; }
            th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
            th { background: #3c8dbc; color: white; }
            .box { background: white; padding: 20px; margin: 20px 0; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
            h2 { color: #3c8dbc; border-bottom: 2px solid #3c8dbc; padding-bottom: 10px; }
            pre { background: #f9f9f9; padding: 15px; border-left: 4px solid #3c8dbc; overflow-x: auto; }
            code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; color: #c7254e; }
        </style>";

        echo "<div class='box'>";
        echo "<h2>Step 1: Current Partner Information</h2>";
        echo "<table>";
        echo "<tr><th>Field</th><th>Value</th></tr>";
        echo "<tr><td>Partner ID</td><td><strong>{$this->partner_data['id']}</strong></td></tr>";
        echo "<tr><td>Partner Code</td><td><strong>{$this->partner_data['partner_code']}</strong></td></tr>";
        echo "<tr><td>Name</td><td>{$this->partner_data['firstname']} {$this->partner_data['lastname']}</td></tr>";
        echo "<tr><td>Email</td><td>{$this->partner_data['email']}</td></tr>";
        echo "<tr><td>Status</td><td><span class='success'>{$this->partner_data['status']}</span></td></tr>";
        echo "</table>";
        echo "</div>";

        echo "<div class='box'>";
        echo "<h2>Step 2: Permissions Loaded by Partner_Controller</h2>";
        echo "<p><strong>Permissions Array:</strong></p>";
        echo "<pre>";
        print_r($this->partner_permissions);
        echo "</pre>";
        echo "<p><strong>Count:</strong> " . count($this->partner_permissions) . " permission(s)</p>";
        echo "<p><strong>Codes:</strong> " . implode(', ', $this->partner_permissions) . "</p>";
        echo "</div>";

        echo "<div class='box'>";
        echo "<h2>Step 3: Permissions in Database (Direct Query)</h2>";
        $partner_id = $this->partner_data['id'];
        
        $query = $this->db->select('pp.*, ppt.permission_name')
                          ->from('partner_permissions pp')
                          ->join('partner_permission_types ppt', 'ppt.permission_code = pp.permission_code', 'left')
                          ->where('pp.partner_id', $partner_id)
                          ->where('pp.is_granted', 1)
                          ->get();
        
        $permissions = $query->result_array();
        
        if (empty($permissions)) {
            echo "<p class='error'>✗ No permissions found in database!</p>";
            echo "<p>SQL Query executed:</p>";
            echo "<pre>" . $this->db->last_query() . "</pre>";
        } else {
            echo "<p class='success'>✓ Found " . count($permissions) . " permission(s) in database</p>";
            echo "<table>";
            echo "<tr><th>Permission Code</th><th>Permission Name</th><th>Is Granted</th><th>Granted At</th></tr>";
            foreach ($permissions as $perm) {
                echo "<tr>";
                echo "<td><code>{$perm['permission_code']}</code></td>";
                echo "<td>{$perm['permission_name']}</td>";
                echo "<td>" . ($perm['is_granted'] ? '<span class="success">✓ Yes</span>' : '<span class="error">✗ No</span>') . "</td>";
                echo "<td>{$perm['granted_at']}</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        echo "</div>";

        echo "<div class='box'>";
        echo "<h2>Step 4: Available Permission Types in System</h2>";
        $all_types = $this->db->select('*')
                              ->from('partner_permission_types')
                              ->where('is_active', 1)
                              ->order_by('id', 'ASC')
                              ->get()
                              ->result_array();
        
        echo "<table>";
        echo "<tr><th>ID</th><th>Permission Name</th><th>Permission Code</th><th>In Sidebar?</th></tr>";
        
        $sidebar_codes = ['library', 'online_courses', 'download_centre', 'gmeet', 'zoom', 'events_access'];
        
        foreach ($all_types as $type) {
            $in_sidebar = in_array($type['permission_code'], $sidebar_codes);
            $is_granted = in_array($type['permission_code'], $this->partner_permissions);
            
            echo "<tr>";
            echo "<td>{$type['id']}</td>";
            echo "<td><strong>{$type['permission_name']}</strong></td>";
            echo "<td><code>{$type['permission_code']}</code></td>";
            echo "<td>";
            if ($is_granted) {
                echo '<span class="success">✓ GRANTED & Will show</span>';
            } elseif ($in_sidebar) {
                echo '<span style="color: orange;">⚠ Not granted (won\'t show)</span>';
            } else {
                echo '<span class="error">✗ Not in sidebar menu</span>';
            }
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";

        echo "<div class='box'>";
        echo "<h2>Step 5: Sidebar Menu Configuration</h2>";
        echo "<p>The sidebar checks for these exact permission codes:</p>";
        echo "<ul>";
        foreach ($sidebar_codes as $code) {
            $granted = in_array($code, $this->partner_permissions);
            echo "<li><code>$code</code> - " . ($granted ? '<span class="success">✓ GRANTED (will show)</span>' : '<span class="error">✗ NOT granted (won\'t show)</span>') . "</li>";
        }
        echo "</ul>";
        echo "</div>";

        echo "<div class='box'>";
        echo "<h2>Step 6: Quick Fix - Grant All Permissions</h2>";
        echo "<p>To grant ALL 6 permissions to this partner, run this SQL:</p>";
        echo "<pre>";
        echo "-- Partner ID: {$this->partner_data['id']}\n";
        echo "DELETE FROM partner_permissions WHERE partner_id = {$this->partner_data['id']};\n\n";
        echo "INSERT INTO partner_permissions (partner_id, permission_code, is_granted, granted_by, granted_at)\n";
        echo "VALUES\n";
        $values = [];
        foreach ($sidebar_codes as $code) {
            $values[] = "({$this->partner_data['id']}, '$code', 1, 1, NOW())";
        }
        echo implode(",\n", $values);
        echo ";";
        echo "</pre>";
        echo "<button onclick=\"window.location.href='" . base_url('partnerdashboard') . "'\">Back to Dashboard</button>";
        echo "</div>";
    }
}

