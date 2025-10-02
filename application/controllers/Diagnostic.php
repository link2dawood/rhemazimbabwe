<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Diagnostic extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Check partner registration requirements
     * URL: http://localhost/rhemazimbabwe/diagnostic/partner_check
     */
    public function partner_check() {
        echo "<!DOCTYPE html>
<html>
<head>
    <title>Partner Registration Diagnostic</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 5px; }
        h2 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        .check { margin: 15px 0; padding: 10px; border-left: 4px solid #28a745; background: #f0f9ff; }
        .error { border-left: 4px solid #dc3545; background: #fff0f0; }
        .warning { border-left: 4px solid #ffc107; background: #fffbf0; }
        .success { color: #28a745; font-weight: bold; }
        .fail { color: #dc3545; font-weight: bold; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 3px; overflow-x: auto; }
        .sql-block { background: #2d3748; color: #68d391; padding: 15px; border-radius: 5px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class='container'>
        <h2>🔍 Partner Registration System Diagnostic</h2>
        <p><strong>Date:</strong> " . date('Y-m-d H:i:s') . "</p>
        <hr>";

        // Check database connection
        echo "<div class='check'>";
        echo "<h3>✓ Database Connection</h3>";
        if ($this->db->conn_id) {
            echo "<p class='success'>✓ Connected to database: <strong>" . $this->db->database . "</strong></p>";
        } else {
            echo "<p class='fail'>✗ Database connection failed</p>";
        }
        echo "</div>";

        // Check required tables
        echo "<div class='check'>";
        echo "<h3>📊 Required Tables</h3>";

        $required_tables = ['giving_types', 'giving_frequencies', 'partners'];
        foreach ($required_tables as $table) {
            if ($this->db->table_exists($table)) {
                echo "<p class='success'>✓ Table exists: <strong>$table</strong></p>";
            } else {
                echo "<p class='fail'>✗ Table missing: <strong>$table</strong></p>";
            }
        }
        echo "</div>";

        // Check giving_types data
        echo "<div class='check'>";
        echo "<h3>🎯 Giving Types Data</h3>";

        if ($this->db->table_exists('giving_types')) {
            $total = $this->db->count_all('giving_types');
            $active = $this->db->where('is_active', 1)->count_all_results('giving_types');

            echo "<p>Total records: <strong>$total</strong></p>";
            echo "<p>Active records: <strong>$active</strong></p>";

            if ($active == 0) {
                echo "<div class='error' style='margin-top: 10px;'>";
                echo "<p class='fail'>✗ NO ACTIVE GIVING TYPES FOUND - This is causing the 500 error!</p>";
                echo "<p><strong>Fix:</strong> Run this SQL in phpMyAdmin:</p>";
                echo "<div class='sql-block'><pre style='color: #68d391; background: transparent; margin: 0;'>";
                echo "INSERT INTO `giving_types` (`name`, `description`, `code`, `is_active`, `sort_order`, `created_at`) VALUES
('Tithe', '10% of income', 'tithe', 1, 1, NOW()),
('Building Fund', 'Church building project', 'building', 1, 2, NOW()),
('Missions', 'Missionary support', 'missions', 1, 3, NOW()),
('Offering', 'General offering', 'offering', 1, 4, NOW());</pre></div>";
                echo "</div>";
            } else {
                echo "<p class='success'>✓ Giving types data found</p>";

                // Show sample data
                $types = $this->db->where('is_active', 1)->limit(5)->get('giving_types')->result();
                echo "<p><strong>Sample records:</strong></p><ul>";
                foreach ($types as $type) {
                    echo "<li>" . htmlspecialchars($type->name) . " (ID: $type->id)</li>";
                }
                echo "</ul>";
            }
        }
        echo "</div>";

        // Check giving_frequencies data
        echo "<div class='check'>";
        echo "<h3>📅 Giving Frequencies Data</h3>";

        if ($this->db->table_exists('giving_frequencies')) {
            $total = $this->db->count_all('giving_frequencies');
            $active = $this->db->where('is_active', 1)->count_all_results('giving_frequencies');

            echo "<p>Total records: <strong>$total</strong></p>";
            echo "<p>Active records: <strong>$active</strong></p>";

            if ($active == 0) {
                echo "<div class='error' style='margin-top: 10px;'>";
                echo "<p class='fail'>✗ NO ACTIVE GIVING FREQUENCIES FOUND - This is causing the 500 error!</p>";
                echo "<p><strong>Fix:</strong> Run this SQL in phpMyAdmin:</p>";
                echo "<div class='sql-block'><pre style='color: #68d391; background: transparent; margin: 0;'>";
                echo "INSERT INTO `giving_frequencies` (`name`, `description`, `interval_type`, `interval_value`, `is_active`, `sort_order`, `created_at`) VALUES
('Once-Off', 'One time contribution', 'once', 0, 1, 1, NOW()),
('Weekly', 'Every week', 'weekly', 1, 1, 2, NOW()),
('Monthly', 'Every month', 'monthly', 1, 1, 3, NOW()),
('Quarterly', 'Every 3 months', 'quarterly', 3, 1, 4, NOW());</pre></div>";
                echo "</div>";
            } else {
                echo "<p class='success'>✓ Giving frequencies data found</p>";

                // Show sample data
                $frequencies = $this->db->where('is_active', 1)->limit(5)->get('giving_frequencies')->result();
                echo "<p><strong>Sample records:</strong></p><ul>";
                foreach ($frequencies as $freq) {
                    echo "<li>" . htmlspecialchars($freq->name) . " (ID: $freq->id)</li>";
                }
                echo "</ul>";
            }
        }
        echo "</div>";

        // Check model files
        echo "<div class='check'>";
        echo "<h3>📁 Model Files</h3>";

        $models = ['Type_model', 'Frequency_model', 'Partner_model'];
        foreach ($models as $model) {
            $file = APPPATH . 'models/' . $model . '.php';
            if (file_exists($file)) {
                echo "<p class='success'>✓ Model exists: <strong>$model</strong></p>";
            } else {
                echo "<p class='fail'>✗ Model missing: <strong>$model</strong></p>";
            }
        }
        echo "</div>";

        // Final verdict
        echo "<div class='check'>";
        echo "<h3>🎯 Final Verdict</h3>";

        $giving_types_ok = $this->db->table_exists('giving_types') &&
                           $this->db->where('is_active', 1)->count_all_results('giving_types') > 0;
        $giving_frequencies_ok = $this->db->table_exists('giving_frequencies') &&
                                 $this->db->where('is_active', 1)->count_all_results('giving_frequencies') > 0;

        if ($giving_types_ok && $giving_frequencies_ok) {
            echo "<p class='success' style='font-size: 18px;'>✓ ALL CHECKS PASSED! Registration pages should work now.</p>";
            echo "<p><strong>Test URLs:</strong></p>";
            echo "<ul>";
            echo "<li><a href='" . base_url('partnerregistration/register/individual') . "' target='_blank'>Individual Registration</a></li>";
            echo "<li><a href='" . base_url('partnerregistration/register/organization') . "' target='_blank'>Organization Registration</a></li>";
            echo "</ul>";
        } else {
            echo "<p class='fail' style='font-size: 18px;'>✗ ISSUES FOUND - Please run the SQL statements above to fix.</p>";
        }
        echo "</div>";

        echo "</div></body></html>";
    }
}
