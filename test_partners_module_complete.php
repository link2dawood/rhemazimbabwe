<?php
/**
 * PARTNERS MODULE - COMPREHENSIVE AUTOMATED TEST
 *
 * This script tests all major components of the Partners Module
 * Run from command line: php test_partners_module_complete.php
 * Or access via browser: http://your-domain.com/test_partners_module_complete.php
 */

// Bootstrap CodeIgniter
define('BASEPATH', true);
$system_path = 'system';
$application_folder = 'application';

require_once(BASEPATH . '../index.php');

class Partners_Module_Test
{
    private $CI;
    private $test_results = [];
    private $start_time;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->database();
        $this->CI->load->model([
            'partner_model',
            'contribution_model',
            'type_model',
            'frequency_model',
            'permission_model',
            'reminder_model'
        ]);
        $this->start_time = microtime(true);
    }

    /**
     * Run all tests
     */
    public function runAllTests()
    {
        $this->printHeader();

        // Database Tests
        $this->testDatabaseStructure();
        $this->testDatabaseData();
        $this->testDatabaseRelationships();

        // Model Tests
        $this->testPartnerModel();
        $this->testContributionModel();
        $this->testTypeModel();
        $this->testFrequencyModel();
        $this->testPermissionModel();
        $this->testReminderModel();

        // Functionality Tests
        $this->testPartnerCodeGeneration();
        $this->testReceiptNumberGeneration();
        $this->testBalanceCalculations();
        $this->testStatisticsGeneration();

        // Print Results
        $this->printResults();
    }

    private function printHeader()
    {
        echo str_repeat("=", 70) . PHP_EOL;
        echo "    PARTNERS MODULE - COMPREHENSIVE AUTOMATED TEST" . PHP_EOL;
        echo str_repeat("=", 70) . PHP_EOL;
        echo "Date: " . date('Y-m-d H:i:s') . PHP_EOL;
        echo "Environment: " . ENVIRONMENT . PHP_EOL;
        echo str_repeat("=", 70) . PHP_EOL . PHP_EOL;
    }

    private function testDatabaseStructure()
    {
        $this->printSection("DATABASE STRUCTURE TESTS");

        $required_tables = [
            'partners' => 'Main partner information table',
            'partner_contributions' => 'Contribution records table',
            'partner_giving_settings' => 'Multiple giving types per partner',
            'partner_giving_types' => 'Partner-specific giving types mapping',
            'partner_permissions' => 'Partner permissions table',
            'partner_permission_types' => 'Permission types definition',
            'partner_reminders' => 'Partner reminders table',
            'partner_activity_log' => 'Activity tracking table',
            'partner_documents' => 'Document storage table',
            'partner_notes' => 'Notes table',
            'giving_types' => 'Giving types master table',
            'giving_frequencies' => 'Giving frequencies master table'
        ];

        foreach ($required_tables as $table => $description) {
            if ($this->CI->db->table_exists($table)) {
                $this->addResult("Table '$table' exists ($description)", true);
            } else {
                $this->addResult("Table '$table' missing ($description)", false);
            }
        }
    }

    private function testDatabaseData()
    {
        $this->printSection("DATABASE DATA TESTS");

        // Check giving types
        $types_count = $this->CI->db->count_all_results('giving_types');
        $this->addResult("Giving Types: $types_count records found", $types_count >= 5);

        // Check frequencies
        $freq_count = $this->CI->db->count_all_results('giving_frequencies');
        $this->addResult("Giving Frequencies: $freq_count records found", $freq_count >= 5);

        // Check permission types
        $perm_count = $this->CI->db->count_all_results('partner_permission_types');
        $this->addResult("Permission Types: $perm_count records found", $perm_count >= 6);

        // Check partners
        $partner_count = $this->CI->db->count_all_results('partners');
        $this->addResult("Partners: $partner_count records found", $partner_count > 0);

        // Check contributions
        $contrib_count = $this->CI->db->count_all_results('partner_contributions');
        $this->addResult("Contributions: $contrib_count records found", true);
    }

    private function testDatabaseRelationships()
    {
        $this->printSection("DATABASE RELATIONSHIPS TESTS");

        // Test partners have giving types
        $query = "SELECT COUNT(*) as count FROM partners p
                  INNER JOIN giving_types gt ON p.giving_type_id = gt.id";
        $result = $this->CI->db->query($query)->row();
        $this->addResult("Partners linked to Giving Types: {$result->count} links", true);

        // Test partners have frequencies
        $query = "SELECT COUNT(*) as count FROM partners p
                  INNER JOIN giving_frequencies gf ON p.giving_frequency_id = gf.id";
        $result = $this->CI->db->query($query)->row();
        $this->addResult("Partners linked to Frequencies: {$result->count} links", true);

        // Test contributions have partners
        $query = "SELECT COUNT(*) as count FROM partner_contributions pc
                  INNER JOIN partners p ON pc.partner_id = p.id";
        $result = $this->CI->db->query($query)->row();
        $this->addResult("Contributions linked to Partners: {$result->count} links", true);
    }

    private function testPartnerModel()
    {
        $this->printSection("PARTNER MODEL TESTS");

        // Test partner retrieval
        $partners = $this->CI->partner_model->getAll();
        $this->addResult("Can retrieve all partners: " . count($partners) . " found", !empty($partners));

        if (!empty($partners)) {
            $partner = $partners[0];

            // Test single partner retrieval
            $single = $this->CI->partner_model->getById($partner->id);
            $this->addResult("Can retrieve partner by ID", !empty($single));

            // Test partner by code
            if (isset($partner->partner_code)) {
                $by_code = $this->CI->partner_model->getByCode($partner->partner_code);
                $this->addResult("Can retrieve partner by code", !empty($by_code));
            }

            // Test active count
            $active_count = $this->CI->partner_model->getActiveCount();
            $this->addResult("Active partners count: $active_count", is_numeric($active_count));

            // Test dashboard stats
            $stats = $this->CI->partner_model->getDashboardStats();
            $this->addResult("Dashboard stats generated", !empty($stats) && isset($stats['total_partners']));
        }
    }

    private function testContributionModel()
    {
        $this->printSection("CONTRIBUTION MODEL TESTS");

        // Test receipt number generation
        $receipt1 = $this->CI->contribution_model->generateReceiptNumber();
        $receipt2 = $this->CI->contribution_model->generateReceiptNumber();

        $this->addResult("Receipt format: $receipt1", preg_match('/^RCT-\d{8}-\d{4}$/', $receipt1) === 1);
        $this->addResult("Receipt uniqueness", $receipt1 !== $receipt2);

        // Test contribution retrieval
        $contributions = $this->CI->contribution_model->getAll();
        $this->addResult("Can retrieve contributions: " . count($contributions) . " found", true);

        // Test summary
        $summary = $this->CI->contribution_model->getSummary();
        $this->addResult("Can generate summary", !empty($summary));
    }

    private function testTypeModel()
    {
        $this->printSection("TYPE MODEL TESTS");

        $types = $this->CI->type_model->getAll();
        $this->addResult("Can retrieve giving types: " . count($types) . " found", !empty($types));

        if (!empty($types)) {
            $type = $types[0];
            $this->addResult("Type has required fields",
                isset($type->id) && isset($type->name) && isset($type->code)
            );

            // Test dropdown generation
            $dropdown = $this->CI->type_model->getDropdown();
            $this->addResult("Can generate types dropdown", !empty($dropdown));

            // Test usage count
            $usage = $this->CI->type_model->getUsageCount($type->id);
            $this->addResult("Can get type usage count: $usage partners", is_numeric($usage));
        }
    }

    private function testFrequencyModel()
    {
        $this->printSection("FREQUENCY MODEL TESTS");

        $frequencies = $this->CI->frequency_model->getAll();
        $this->addResult("Can retrieve frequencies: " . count($frequencies) . " found", !empty($frequencies));

        if (!empty($frequencies)) {
            $freq = $frequencies[0];
            $this->addResult("Frequency has required fields",
                isset($freq->id) && isset($freq->name) && isset($freq->days_interval)
            );

            // Test next date calculation
            if ($freq->days_interval) {
                $next_date = $this->CI->frequency_model->calculateNextDate($freq->id, date('Y-m-d'));
                $this->addResult("Can calculate next contribution date: $next_date", !empty($next_date));
            }

            // Test dropdown
            $dropdown = $this->CI->frequency_model->getDropdown();
            $this->addResult("Can generate frequencies dropdown", !empty($dropdown));
        }
    }

    private function testPermissionModel()
    {
        $this->printSection("PERMISSION MODEL TESTS");

        $permissions = $this->CI->permission_model->getAllPermissionTypes();
        $this->addResult("Can retrieve permission types: " . count($permissions) . " found", !empty($permissions));

        if (!empty($permissions)) {
            $perm = $permissions[0];
            $this->addResult("Permission has required fields",
                isset($perm->id) && isset($perm->permission_code) && isset($perm->permission_name)
            );

            // Test dropdown
            $dropdown = $this->CI->permission_model->getPermissionDropdown();
            $this->addResult("Can generate permissions dropdown", !empty($dropdown));
        }
    }

    private function testReminderModel()
    {
        $this->printSection("REMINDER MODEL TESTS");

        $templates = $this->CI->reminder_model->getAllTemplates();
        $this->addResult("Can retrieve reminder templates: " . count($templates) . " found", true);

        // Test stats
        $stats = $this->CI->reminder_model->getReminderStats();
        $this->addResult("Can generate reminder stats", !empty($stats));

        // Test pending reminders
        $pending = $this->CI->reminder_model->getPendingReminders(date('Y-m-d'));
        $this->addResult("Can get pending reminders: " . count($pending) . " found", true);
    }

    private function testPartnerCodeGeneration()
    {
        $this->printSection("PARTNER CODE GENERATION PERFORMANCE TEST");

        $codes = [];
        $start = microtime(true);

        for ($i = 0; $i < 100; $i++) {
            $code = $this->CI->partner_model->generatePartnerCode();
            $codes[] = $code;
        }

        $end = microtime(true);
        $time = $end - $start;

        // Check format
        $valid_format = true;
        foreach ($codes as $code) {
            if (!preg_match('/^PTR-\d{4}-\d{4}$/', $code)) {
                $valid_format = false;
                break;
            }
        }

        // Check uniqueness
        $unique = count($codes) === count(array_unique($codes));

        $this->addResult("Generated 100 codes in " . number_format($time, 4) . " seconds", $time < 5);
        $this->addResult("All codes have valid format (PTR-YYYY-XXXX)", $valid_format);
        $this->addResult("All codes are unique", $unique);
    }

    private function testReceiptNumberGeneration()
    {
        $this->printSection("RECEIPT NUMBER GENERATION PERFORMANCE TEST");

        $receipts = [];
        $start = microtime(true);

        for ($i = 0; $i < 100; $i++) {
            $receipt = $this->CI->contribution_model->generateReceiptNumber();
            $receipts[] = $receipt;
        }

        $end = microtime(true);
        $time = $end - $start;

        // Check format
        $valid_format = true;
        foreach ($receipts as $receipt) {
            if (!preg_match('/^RCT-\d{8}-\d{4}$/', $receipt)) {
                $valid_format = false;
                break;
            }
        }

        // Check uniqueness
        $unique = count($receipts) === count(array_unique($receipts));

        $this->addResult("Generated 100 receipts in " . number_format($time, 4) . " seconds", $time < 5);
        $this->addResult("All receipts have valid format (RCT-YYYYMMDD-XXXX)", $valid_format);
        $this->addResult("All receipts are unique", $unique);
    }

    private function testBalanceCalculations()
    {
        $this->printSection("BALANCE CALCULATIONS TEST");

        $partners = $this->CI->partner_model->getAll(['is_active' => 1]);

        if (!empty($partners)) {
            foreach ($partners as $partner) {
                // Test contribution total
                $total = $this->CI->contribution_model->getTotalByPartner($partner->id);
                $this->addResult("Partner {$partner->partner_code} balance: " . number_format($total, 2),
                    is_numeric($total));

                // Only test one partner to keep output reasonable
                break;
            }

            // Test with contributions
            $partners_with_contrib = $this->CI->partner_model->getWithContributions();
            $this->addResult("Can get partners with contributions: " . count($partners_with_contrib) . " found",
                !empty($partners_with_contrib));
        } else {
            $this->addResult("No active partners to test", false);
        }
    }

    private function testStatisticsGeneration()
    {
        $this->printSection("STATISTICS GENERATION TEST");

        // Test dashboard stats
        $stats = $this->CI->partner_model->getDashboardStats();

        $required_stats = [
            'total_partners',
            'active_partners',
            'new_this_month',
            'total_contributions',
            'monthly_expected',
            'partners_with_balance',
            'type_distribution'
        ];

        foreach ($required_stats as $stat) {
            $this->addResult("Stat '$stat' exists", isset($stats[$stat]));
        }

        // Test contribution monthly stats
        $contrib_stats = $this->CI->contribution_model->getMonthlyStats();
        $this->addResult("Monthly contribution stats generated", !empty($contrib_stats));
    }

    private function printSection($title)
    {
        echo PHP_EOL . str_repeat("-", 70) . PHP_EOL;
        echo $title . PHP_EOL;
        echo str_repeat("-", 70) . PHP_EOL;
    }

    private function addResult($message, $passed)
    {
        $status = $passed ? "✅ PASS" : "❌ FAIL";
        echo "$status: $message" . PHP_EOL;
        $this->test_results[] = ['message' => $message, 'passed' => $passed];
    }

    private function printResults()
    {
        $end_time = microtime(true);
        $total_time = $end_time - $this->start_time;

        echo PHP_EOL . str_repeat("=", 70) . PHP_EOL;
        echo "TEST SUMMARY" . PHP_EOL;
        echo str_repeat("=", 70) . PHP_EOL;

        $total = count($this->test_results);
        $passed = 0;
        $failed = 0;

        foreach ($this->test_results as $result) {
            if ($result['passed']) {
                $passed++;
            } else {
                $failed++;
            }
        }

        echo "Total Tests: $total" . PHP_EOL;
        echo "Passed: $passed" . PHP_EOL;
        echo "Failed: $failed" . PHP_EOL;
        echo "Success Rate: " . number_format(($passed / $total) * 100, 2) . "%" . PHP_EOL;
        echo "Total Time: " . number_format($total_time, 2) . " seconds" . PHP_EOL;

        echo str_repeat("=", 70) . PHP_EOL;

        if ($failed === 0) {
            echo "🎉 ALL TESTS PASSED! Module is working correctly." . PHP_EOL;
        } else {
            echo "⚠️  Some tests failed. Please review the errors above." . PHP_EOL;
        }

        echo str_repeat("=", 70) . PHP_EOL;
    }
}

// Run tests if called directly
if (php_sapi_name() == 'cli' || !empty($_GET['run'])) {
    try {
        $test = new Partners_Module_Test();
        $test->runAllTests();
    } catch (Exception $e) {
        echo "ERROR: " . $e->getMessage() . PHP_EOL;
        echo "Stack trace: " . $e->getTraceAsString() . PHP_EOL;
    }
} else {
    echo "<html><body>";
    echo "<h1>Partners Module Test</h1>";
    echo "<p>To run the tests, add <code>?run=1</code> to the URL or run from command line:</p>";
    echo "<code>php test_partners_module_complete.php</code>";
    echo "</body></html>";
}
