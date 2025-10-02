<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Test_partner_page extends CI_Controller
{
    public function index()
    {
        echo "<h2>Partner Registration Diagnostic Test</h2>";

        // Check PHP version
        echo "✓ PHP Version: " . phpversion() . "<br>";

        // Load DB using CI
        $this->load->database();

        echo "<h3>Database Connection Test</h3>";

        // Check if CI database loaded correctly
        if (!$this->db->conn_id) {
            echo "✗ Database error: Unable to connect to database.<br>";
            echo "Error message: " . mysqli_connect_error() . "<br>";
            return;
        }

        // Display connection details
        $conn = $this->db->conn_id;
        $host_info = $conn->host_info ?? 'N/A';
        echo "✓ Connected to database host: <b>{$host_info}</b><br>";
        echo "✓ Connected database name: <b>" . $this->db->database . "</b><br>";

        // Try listing tables
        $tables = $this->db->list_tables();
        if (empty($tables)) {
            echo "✗ No tables found in this database.<br>";
        } else {
            echo "✓ Found " . count($tables) . " tables:<br>";
            echo "<ul>";
            foreach ($tables as $t) {
                echo "<li>{$t}</li>";
            }
            echo "</ul>";
        }

        echo "<hr>✅ Diagnostic completed.<br>";
    }
}
