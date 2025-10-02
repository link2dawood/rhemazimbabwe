<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate extends Admin_Controller {

    public function __construct() {
        parent::__construct();

        // Only allow in development/testing environment
        if (ENVIRONMENT === 'production') {
            show_error('Migration controller is disabled in production mode.', 403, 'Access Denied');
        }

        $this->load->library('migration');
    }

    /**
     * Run all pending migrations
     */
    public function index() {
        echo "<h2>Database Migration Runner</h2>";
        echo "<hr>";

        try {
            $current_version = $this->migration->current();

            if ($current_version === FALSE) {
                echo "<div style='color: red; padding: 10px; border: 1px solid red;'>";
                echo "<strong>Migration Failed!</strong><br>";
                echo $this->migration->error_string();
                echo "</div>";
            } else {
                echo "<div style='color: green; padding: 10px; border: 1px solid green;'>";
                echo "<strong>Migration Successful!</strong><br>";
                echo "Database migrated to version: <strong>" . $current_version . "</strong>";
                echo "</div>";

                echo "<br><h3>Migration Summary:</h3>";
                echo "<ul>";
                echo "<li>✓ Partners table created</li>";
                echo "<li>✓ Partner contributions table created</li>";
                echo "<li>✓ Giving types table created</li>";
                echo "<li>✓ Giving frequencies table created</li>";
                echo "<li>✓ Partner permissions table created</li>";
                echo "<li>✓ Partner reminders table created</li>";
                echo "<li>✓ Partner notes table created</li>";
                echo "<li>✓ Giving frequencies seeded</li>";
                echo "<li>✓ Giving types seeded</li>";
                echo "<li>✓ Partner permission types seeded</li>";
                echo "</ul>";

                echo "<br><div style='background: #f0f0f0; padding: 10px; border-left: 4px solid #ff9800;'>";
                echo "<strong>⚠ Important:</strong> Now go to <code>application/config/migration.php</code> and set:<br>";
                echo "<code>\$config['migration_enabled'] = FALSE;</code>";
                echo "</div>";
            }
        } catch (Exception $e) {
            echo "<div style='color: red; padding: 10px; border: 1px solid red;'>";
            echo "<strong>Exception Occurred!</strong><br>";
            echo $e->getMessage();
            echo "</div>";
        }

        echo "<br><hr>";
        echo "<p><a href='" . base_url('admin/partners') . "' style='padding: 10px 20px; background: #3c8dbc; color: white; text-decoration: none; border-radius: 4px;'>Go to Partners Module</a></p>";
    }

    /**
     * Show current migration version
     */
    public function version() {
        echo "<h2>Current Migration Version</h2>";
        echo "<hr>";

        $version = $this->db->query("SELECT version FROM migrations ORDER BY version DESC LIMIT 1")->row();

        if ($version) {
            echo "<p>Current database version: <strong>" . $version->version . "</strong></p>";
        } else {
            echo "<p>No migrations have been run yet.</p>";
        }
    }

    /**
     * Rollback to a specific version
     * Usage: admin/migrate/rollback/125
     */
    public function rollback($version = 0) {
        if (empty($version)) {
            show_error('Please specify a version number to rollback to.');
        }

        echo "<h2>Database Migration Rollback</h2>";
        echo "<hr>";

        try {
            if ($this->migration->version($version)) {
                echo "<div style='color: green; padding: 10px; border: 1px solid green;'>";
                echo "<strong>Rollback Successful!</strong><br>";
                echo "Database rolled back to version: <strong>" . $version . "</strong>";
                echo "</div>";
            } else {
                echo "<div style='color: red; padding: 10px; border: 1px solid red;'>";
                echo "<strong>Rollback Failed!</strong><br>";
                echo $this->migration->error_string();
                echo "</div>";
            }
        } catch (Exception $e) {
            echo "<div style='color: red; padding: 10px; border: 1px solid red;'>";
            echo "<strong>Exception Occurred!</strong><br>";
            echo $e->getMessage();
            echo "</div>";
        }
    }
}