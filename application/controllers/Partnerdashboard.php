<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

// Load Partner_Controller
require_once(APPPATH . 'core/Partner_Controller.php');

class Partnerdashboard extends Partner_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'contribution_model',
            'type_model' => 'giving_type_model',
            'frequency_model' => 'giving_frequency_model',
            'Partner_giving_setting_model',
            'setting_model'
        ));
        $this->load->library('form_validation');
        $this->load->helper('number');
    }

    // Dashboard
    public function index()
    {
        $data = [];
        $data['title'] = 'Partner Dashboard';
        $data['page_title'] = 'Dashboard';
        $data['page_description'] = 'Welcome to your partner portal';
        $data['active_menu'] = 'dashboard';
        $data['partner'] = $this->partner_data;
        $data['partner_permissions'] = $this->partner_permissions;

        // Load giving types and frequencies for dashboard view
        $data['giving_types'] = $this->giving_type_model->getAll();
        $data['giving_frequencies'] = $this->giving_frequency_model->getAll();

        // Get statistics
        $partner_id = $this->partner_data['id'];
        $data['statistics'] = $this->getStatistics($partner_id);

        // Get recent contributions
        $data['recent_contributions'] = $this->contribution_model->getContributionsByPartner($partner_id);
        if (count($data['recent_contributions']) > 5) {
            $data['recent_contributions'] = array_slice($data['recent_contributions'], 0, 5);
        }

        // Make setting_model available in views
        $data['setting_model'] = $this->setting_model;
        
        $this->load->view('layout/partner/header', $data);
        $this->load->view('partner/dashboard', $data);
        $this->load->view('layout/partner/footer', $data);
    }

    // Profile/Settings
    public function profile()
    {
        $data = [];
        $data['title'] = 'My Profile & Settings';
        $data['page_title'] = 'My Profile';
        $data['page_description'] = 'Manage your profile information';
        $data['active_menu'] = 'profile';
        $data['partner'] = $this->partner_data;
        $data['partner_permissions'] = $this->partner_permissions;
        $data['giving_types'] = $this->giving_type_model->getAll();
        $data['giving_frequencies'] = $this->giving_frequency_model->getAll();
        
        // Get current giving settings
        $partner_id = $this->partner_data['id'];
        $data['current_settings'] = $this->Partner_giving_setting_model->getByPartnerId($partner_id);

        // Make setting_model available in views
        $data['setting_model'] = $this->setting_model;
        
        $this->load->view('layout/partner/header', $data);
        $this->load->view('partner/profile', $data);
        $this->load->view('layout/partner/footer', $data);
    }

    // Update Profile
    public function update_profile()
    {
        $partner_id = $this->partner_data['id'];

        $update_data = [
            'firstname' => $this->input->post('firstname'),
            'lastname' => $this->input->post('lastname'),
            'mobileno' => $this->input->post('mobileno'),
            'address' => $this->input->post('address'),
            'city' => $this->input->post('city'),
            'state' => $this->input->post('state'),
            'country' => $this->input->post('country'),
            'zip_code' => $this->input->post('zip_code'),
            'notes' => $this->input->post('notes')
        ];

        if ($this->partner_model->update($partner_id, $update_data)) {
            echo json_encode(['status' => true, 'message' => 'Profile updated successfully']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Failed to update profile']);
        }
    }

    // Giving Settings Page (Dedicated)
    public function giving_settings()
    {
        $data = [];
        $data['title'] = 'Giving Settings';
        $data['page_title'] = 'Giving Settings';
        $data['page_description'] = 'Manage your contribution preferences';
        $data['active_menu'] = 'giving_settings';
        $data['partner'] = $this->partner_data;
        $data['partner_permissions'] = $this->partner_permissions;

        // Get giving types and frequencies
        $data['giving_types'] = $this->giving_type_model->getAll();
        $data['giving_frequencies'] = $this->giving_frequency_model->getAll();

        // Get current giving settings
        $partner_id = $this->partner_data['id'];
        $data['current_settings'] = $this->Partner_giving_setting_model->getByPartnerId($partner_id);

        // Get current frequency from partner table
        $data['current_frequency'] = $this->partner_data['giving_frequency_id'] ?? '';

        // Calculate total contribution amount
        $total = 0;
        if (!empty($data['current_settings'])) {
            foreach ($data['current_settings'] as $setting) {
                $total += $setting->amount;
            }
        }
        $data['total_amount'] = $total;

        // Make setting_model available in views
        $data['setting_model'] = $this->setting_model;
        
        $this->load->view('layout/partner/header', $data);
        $this->load->view('partner/giving_settings', $data);
        $this->load->view('layout/partner/footer', $data);
    }

    // Update Giving Settings
    public function update_giving_settings()
    {
        $partner_id = $this->partner_data['id'];

        // Get posted data
        $giving_types = $this->input->post('giving_types') ?: array();
        $amounts = $this->input->post('amounts') ?: array();
        $frequency_id = $this->input->post('giving_frequency_id');
        $currency = $this->input->post('currency') ?: 'USD';

        // Validation
        if (empty($giving_types) || !is_array($giving_types) || count($giving_types) == 0) {
            echo json_encode(['status' => false, 'message' => 'Please select at least one giving type']);
            return;
        }

        if (empty($amounts) || !is_array($amounts) || count($amounts) == 0) {
            echo json_encode(['status' => false, 'message' => 'Please enter amounts for selected giving types']);
            return;
        }

        if (empty($frequency_id) || $frequency_id == '') {
            echo json_encode(['status' => false, 'message' => 'Please select a giving frequency']);
            return;
        }

        // Prepare settings data
        $settings_data = [];
        $total_amount = 0;

        foreach ($giving_types as $index => $type_id) {
            if (isset($amounts[$index]) && $amounts[$index] > 0) {
                $settings_data[] = [
                    'partner_id' => $partner_id,
                    'giving_type_id' => $type_id,
                    'amount' => $amounts[$index],
                    'currency' => $currency,
                    'is_active' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                $total_amount += $amounts[$index];
            }
        }

        if (empty($settings_data)) {
            echo json_encode(['status' => false, 'message' => 'Please enter valid amounts greater than 0']);
            return;
        }

        // Update partner's main giving settings
        $partner_update = [
            'giving_frequency_id' => $frequency_id,
            'contribution_amount' => $total_amount,
            'currency' => $currency,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->partner_model->update($partner_id, $partner_update);

        // Save individual giving type settings
        if ($this->Partner_giving_setting_model->saveSettings($partner_id, $settings_data)) {
            $this->session->set_flashdata('success', 'Giving settings updated successfully!');
            echo json_encode([
                'status' => true,
                'message' => 'Giving settings updated successfully!',
                'total_amount' => $total_amount
            ]);
        } else {
            echo json_encode(['status' => false, 'message' => 'Failed to update giving settings']);
        }
    }

    // Add Contribution - DISABLED (Only admin can add contributions)
    public function add_contribution()
    {
        // Partners cannot add contributions themselves
        echo json_encode([
            'status' => false, 
            'message' => 'Contributions can only be added by administrators. Please contact the office to make a contribution.'
        ]);
    }

    // Contributions
    public function contributions()
    {
        $data = [];
        $data['title'] = 'My Contributions';
        $data['page_title'] = 'My Contributions';
        $data['page_description'] = 'View your contribution history';
        $data['active_menu'] = 'contributions';
        $data['partner'] = $this->partner_data;
        $data['partner_permissions'] = $this->partner_permissions;

        // Load giving types and frequencies for contributions view
        $data['giving_types'] = $this->giving_type_model->getAll();
        $data['giving_frequencies'] = $this->giving_frequency_model->getAll();

        $partner_id = $this->partner_data['id'];
        $data['contributions'] = $this->contribution_model->getContributionsByPartner($partner_id);
        $data['total_contributed'] = $this->contribution_model->getTotalContributed($partner_id);

        // Make setting_model available in views
        $data['setting_model'] = $this->setting_model;
        
        $this->load->view('layout/partner/header', $data);
        $this->load->view('user/partner/contributions', $data);
        $this->load->view('layout/partner/footer', $data);
    }

    // Download Receipt
    public function receipt($contribution_id)
    {
        $contribution = $this->contribution_model->get($contribution_id);

        if (!$contribution || $contribution['partner_id'] != $this->partner_data['id']) {
            show_404();
        }

        $data['contribution'] = $contribution;
        $data['partner'] = $this->partner_data;
        $data['school_setting'] = $this->setting_model->getSetting();
        
        // Get giving type for the receipt
        if ($contribution['giving_type_id']) {
            $data['giving_type'] = $this->giving_type_model->get($contribution['giving_type_id']);
        } else {
            $data['giving_type'] = null;
        }

        $this->load->view('user/partner/receipt', $data);
    }

    // Change Password
    public function change_password()
    {
        $data = [];
        $data['title'] = 'Change Password';
        $data['page_title'] = 'Change Password';
        $data['page_description'] = 'Update your account password';
        $data['active_menu'] = 'change_password';
        $data['partner'] = $this->partner_data;
        $data['partner_permissions'] = $this->partner_permissions;

        // Load giving types and frequencies for consistency
        $data['giving_types'] = $this->giving_type_model->getAll();
        $data['giving_frequencies'] = $this->giving_frequency_model->getAll();

        // Make setting_model available in views
        $data['setting_model'] = $this->setting_model;
        
        $this->load->view('layout/partner/header', $data);
        $this->load->view('partner/change_password', $data);
        $this->load->view('layout/partner/footer', $data);
    }

    // Update Password
    public function update_password()
    {
        $this->form_validation->set_rules('current_password', 'Current Password', 'required');
        $this->form_validation->set_rules('new_password', 'New Password', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[new_password]');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => false, 'message' => validation_errors()]);
            return;
        }

        $partner_id = $this->partner_data['id'];
        $current_password = $this->input->post('current_password');
        $new_password = $this->input->post('new_password');

        $result = $this->partner_auth->change_password($partner_id, $current_password, $new_password);
        echo json_encode($result);
    }

    // Private helper method - Get statistics for THIS specific partner only
    private function getStatistics($partner_id)
    {
        $stats = [];

        // Get all contributions for THIS specific partner only (regardless of status)
        $contributions = $this->contribution_model->getContributionsByPartner($partner_id);
        
        // Total contributions count for THIS partner
        $stats['total_contributions'] = count($contributions);

        // Calculate total amount from all contributions for THIS partner
        $total_amount = 0;
        $this_year_amount = 0;
        $current_year = date('Y');
        
        foreach ($contributions as $contribution) {
            // Double-check that this contribution belongs to the correct partner
            if ($contribution['partner_id'] == $partner_id) {
                $total_amount += $contribution['amount'];
                
                // Check if contribution is from current year
                if (date('Y', strtotime($contribution['contribution_date'])) == $current_year) {
                    $this_year_amount += $contribution['amount'];
                }
            }
        }
        
        $stats['total_amount'] = $total_amount;
        $stats['this_year_amount'] = $this_year_amount;

        // Last contribution date for THIS partner
        if (!empty($contributions)) {
            $stats['last_contribution'] = $contributions[0]['contribution_date'];
        } else {
            $stats['last_contribution'] = null;
        }

        // Additional stats for compatibility
        $stats['total_transactions'] = $stats['total_contributions'];
        $stats['total_contributed'] = $stats['total_amount'];
        $stats['this_year_contributed'] = $stats['this_year_amount'];

        return $stats;
    }
    
    // Test method to add sample contributions - DISABLED in production
    public function add_sample_contributions()
    {
        // Disabled - Only admin can add contributions
        $this->session->set_flashdata('error', 'Contributions can only be added by administrators.');
        redirect('partnerdashboard');
    }
    
    // Debug method to verify partner data and contributions
    public function debug_stats()
    {
        $partner_id = $this->partner_data['id'];
        
        echo "<h2>Debug Information for Partner ID: {$partner_id}</h2>";
        echo "<h3>Partner Data:</h3>";
        echo "<pre>" . print_r($this->partner_data, true) . "</pre>";
        
        echo "<h3>Contributions for this partner:</h3>";
        $contributions = $this->contribution_model->getContributionsByPartner($partner_id);
        echo "<pre>" . print_r($contributions, true) . "</pre>";
        
        echo "<h3>Statistics:</h3>";
        $stats = $this->getStatistics($partner_id);
        echo "<pre>" . print_r($stats, true) . "</pre>";
        
        echo "<h3>Total from model method:</h3>";
        $total = $this->contribution_model->getTotalContributed($partner_id);
        echo "Total Contributed: " . $total . "<br>";
        
        echo "<h3>Year total from model method:</h3>";
        $year_total = $this->contribution_model->getYearContributed($partner_id, date('Y'));
        echo "This Year Contributed: " . $year_total . "<br>";
    }

    // Debug method to check permissions
    public function debug_permissions()
    {
        echo "<style>
            body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
            .success { color: green; font-weight: bold; }
            .error { color: red; font-weight: bold; }
            .warning { color: orange; font-weight: bold; }
            table { border-collapse: collapse; width: 100%; background: white; margin: 10px 0; }
            th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
            th { background: #3c8dbc; color: white; }
            .box { background: white; padding: 20px; margin: 20px 0; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
            h2 { color: #3c8dbc; border-bottom: 2px solid #3c8dbc; padding-bottom: 10px; }
            pre { background: #f9f9f9; padding: 15px; border-left: 4px solid #3c8dbc; overflow-x: auto; }
            code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; color: #c7254e; }
            .btn { display: inline-block; padding: 10px 20px; background: #3c8dbc; color: white; text-decoration: none; border-radius: 4px; margin: 5px; }
        </style>";

        echo "<h1>🔍 Partner Permissions Debug</h1>";

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
        echo "<h2>Step 2: Permissions Loaded in Controller</h2>";
        echo "<p><strong>Permissions Array from Partner_Controller:</strong></p>";
        echo "<pre>";
        print_r($this->partner_permissions);
        echo "</pre>";
        echo "<p><strong>Count:</strong> <span class='success'>" . count($this->partner_permissions) . " permission(s)</span></p>";
        echo "<p><strong>Codes:</strong> <code>" . implode(', ', $this->partner_permissions) . "</code></p>";
        echo "</div>";

        echo "<div class='box'>";
        echo "<h2>Step 3: Permissions in Database (Raw Query)</h2>";
        $partner_id = $this->partner_data['id'];
        
        $query = $this->db->select('pp.*, ppt.permission_name')
                          ->from('partner_permissions pp')
                          ->join('partner_permission_types ppt', 'ppt.permission_code = pp.permission_code', 'left')
                          ->where('pp.partner_id', $partner_id)
                          ->where('pp.is_granted', 1)
                          ->get();
        
        $permissions = $query->result_array();
        
        echo "<p><strong>SQL Query:</strong></p>";
        echo "<pre>" . $this->db->last_query() . "</pre>";
        
        if (empty($permissions)) {
            echo "<p class='error'>✗ NO PERMISSIONS FOUND IN DATABASE!</p>";
            echo "<p class='warning'>This partner has no permissions granted. Admin needs to grant them.</p>";
        } else {
            echo "<p class='success'>✓ Found " . count($permissions) . " permission(s) in database</p>";
            echo "<table>";
            echo "<tr><th>Permission Code</th><th>Permission Name</th><th>Is Granted</th><th>Granted At</th></tr>";
            foreach ($permissions as $perm) {
                echo "<tr>";
                echo "<td><code>{$perm['permission_code']}</code></td>";
                echo "<td>{$perm['permission_name']}</td>";
                echo "<td><span class='success'>✓ YES</span></td>";
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
        
        echo "<p class='success'>Found " . count($all_types) . " permission type(s) in system</p>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Permission Name</th><th>Permission Code</th><th>Granted to Partner?</th><th>Will Show in Sidebar?</th></tr>";
        
        $sidebar_codes = ['library', 'online_courses', 'download_centre', 'gmeet', 'zoom', 'events_access'];
        
        foreach ($all_types as $type) {
            $in_sidebar = in_array($type['permission_code'], $sidebar_codes);
            $is_granted = in_array($type['permission_code'], $this->partner_permissions);
            
            echo "<tr>";
            echo "<td>{$type['id']}</td>";
            echo "<td><strong>{$type['permission_name']}</strong></td>";
            echo "<td><code>{$type['permission_code']}</code></td>";
            echo "<td>" . ($is_granted ? '<span class="success">✓ YES</span>' : '<span class="error">✗ NO</span>') . "</td>";
            echo "<td>";
            if ($is_granted && $in_sidebar) {
                echo '<span class="success">✓ YES - WILL SHOW</span>';
            } elseif ($is_granted && !$in_sidebar) {
                echo '<span class="warning">⚠ Granted but not in sidebar menu</span>';
            } elseif (!$is_granted && $in_sidebar) {
                echo '<span class="error">✗ NO - Not granted</span>';
            } else {
                echo '<span style="color: #999;">Not applicable</span>';
            }
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";

        echo "<div class='box'>";
        echo "<h2>Step 5: Why Permissions May Not Show</h2>";
        echo "<ul>";
        
        $granted_codes = array_column($permissions, 'permission_code');
        $showing_count = count(array_intersect($granted_codes, $sidebar_codes));
        
        echo "<li><strong>Total granted:</strong> " . count($granted_codes) . "</li>";
        echo "<li><strong>Should show in sidebar:</strong> " . $showing_count . "</li>";
        echo "<li><strong>Sidebar expects these codes:</strong> <code>" . implode(', ', $sidebar_codes) . "</code></li>";
        echo "<li><strong>Database has these codes:</strong> <code>" . implode(', ', $granted_codes) . "</code></li>";
        
        $missing = array_diff($sidebar_codes, $granted_codes);
        if (!empty($missing)) {
            echo "<li class='error'><strong>Missing permissions:</strong> <code>" . implode(', ', $missing) . "</code></li>";
        }
        
        $extra = array_diff($granted_codes, $sidebar_codes);
        if (!empty($extra)) {
            echo "<li class='warning'><strong>Extra permissions (not in sidebar):</strong> <code>" . implode(', ', $extra) . "</code></li>";
        }
        
        echo "</ul>";
        echo "</div>";

        echo "<div class='box'>";
        echo "<h2>Step 6: Quick Fix SQL</h2>";
        echo "<p>Copy and run this SQL to grant ALL 6 permissions with correct codes:</p>";
        echo "<pre>";
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
        echo "</div>";

        echo "<div class='box'>";
        echo "<a href='" . base_url('partnerdashboard') . "' class='btn'>← Back to Dashboard</a>";
        echo "<a href='javascript:window.location.reload()' class='btn'>🔄 Refresh Debug</a>";
        echo "</div>";
    }

    // =================================================================
    // PERMISSION-BASED PAGES
    // =================================================================

    /**
     * Library Access
     */
    public function library()
    {
        // Check if partner has library permission
        if (!in_array('library', $this->partner_permissions)) {
            $this->session->set_flashdata('error', 'You do not have permission to access the Library.');
            redirect('partnerdashboard');
        }

        $data = [];
        $data['title'] = 'Library';
        $data['page_title'] = 'Library Access';
        $data['page_description'] = 'Browse library resources and books';
        $data['active_menu'] = 'library';
        $data['partner'] = $this->partner_data;
        $data['partner_permissions'] = $this->partner_permissions;
        $data['giving_types'] = $this->giving_type_model->getAll();
        $data['giving_frequencies'] = $this->giving_frequency_model->getAll();
        $data['setting_model'] = $this->setting_model;

        // Load library view
        $this->load->view('layout/partner/header', $data);
        $this->load->view('partner/library', $data);
        $this->load->view('layout/partner/footer', $data);
    }

    /**
     * Online Courses Access
     */
    public function courses()
    {
        // Check if partner has online courses permission
        if (!in_array('online_courses', $this->partner_permissions)) {
            $this->session->set_flashdata('error', 'You do not have permission to access Online Courses.');
            redirect('partnerdashboard');
        }

        $data = [];
        $data['title'] = 'Online Courses';
        $data['page_title'] = 'Online Courses';
        $data['page_description'] = 'Access online courses and learning materials';
        $data['active_menu'] = 'online_courses';
        $data['partner'] = $this->partner_data;
        $data['partner_permissions'] = $this->partner_permissions;
        $data['giving_types'] = $this->giving_type_model->getAll();
        $data['giving_frequencies'] = $this->giving_frequency_model->getAll();
        $data['setting_model'] = $this->setting_model;

        // Check if online course module is enabled
        if ($this->module_lib->hasModule('online_course')) {
            // Redirect to online course student portal
            redirect('user/course');
        } else {
            // Show custom page explaining courses are not available yet
            $this->load->view('layout/partner/header', $data);
            $this->load->view('partner/courses', $data);
            $this->load->view('layout/partner/footer', $data);
        }
    }

    /**
     * Download Centre Access
     */
    public function downloads()
    {
        // Check if partner has download centre permission
        if (!in_array('download_centre', $this->partner_permissions)) {
            $this->session->set_flashdata('error', 'You do not have permission to access the Download Centre.');
            redirect('partnerdashboard');
        }

        $data = [];
        $data['title'] = 'Download Centre';
        $data['page_title'] = 'Download Centre';
        $data['page_description'] = 'Access digital resources and materials';
        $data['active_menu'] = 'download_centre';
        $data['partner'] = $this->partner_data;
        $data['partner_permissions'] = $this->partner_permissions;
        $data['giving_types'] = $this->giving_type_model->getAll();
        $data['giving_frequencies'] = $this->giving_frequency_model->getAll();
        $data['setting_model'] = $this->setting_model;

        // Load downloads or redirect to content page
        $this->load->view('layout/partner/header', $data);
        $this->load->view('partner/downloads', $data);
        $this->load->view('layout/partner/footer', $data);
    }

    /**
     * Google Meet Access
     */
    public function gmeet()
    {
        // Check if partner has gmeet permission
        if (!in_array('gmeet', $this->partner_permissions)) {
            $this->session->set_flashdata('error', 'You do not have permission to access Google Meet.');
            redirect('partnerdashboard');
        }

        // Check if gmeet module is enabled
        if (!$this->module_lib->hasModule('gmeet_live_class')) {
            $this->session->set_flashdata('error', 'Google Meet module is not available.');
            redirect('partnerdashboard');
        }

        // Redirect to gmeet module
        redirect('user/gmeet');
    }

    /**
     * Zoom Access
     */
    public function zoom()
    {
        // Check if partner has zoom permission
        if (!in_array('zoom', $this->partner_permissions)) {
            $this->session->set_flashdata('error', 'You do not have permission to access Zoom.');
            redirect('partnerdashboard');
        }

        // Check if zoom module is enabled
        if (!$this->module_lib->hasModule('zoom_live_class')) {
            $this->session->set_flashdata('error', 'Zoom module is not available.');
            redirect('partnerdashboard');
        }

        // Redirect to zoom module
        redirect('user/zoom');
    }

    /**
     * Events Access
     */
    public function events()
    {
        // Check if partner has events permission
        if (!in_array('events_access', $this->partner_permissions)) {
            $this->session->set_flashdata('error', 'You do not have permission to access Events.');
            redirect('partnerdashboard');
        }

        $data = [];
        $data['title'] = 'Events & Calendar';
        $data['page_title'] = 'Events & Calendar';
        $data['page_description'] = 'View school events and calendar';
        $data['active_menu'] = 'events_access';
        $data['partner'] = $this->partner_data;
        $data['partner_permissions'] = $this->partner_permissions;
        $data['giving_types'] = $this->giving_type_model->getAll();
        $data['giving_frequencies'] = $this->giving_frequency_model->getAll();
        $data['setting_model'] = $this->setting_model;

        // Load events view
        $this->load->view('layout/partner/header', $data);
        $this->load->view('partner/events', $data);
        $this->load->view('layout/partner/footer', $data);
    }
}
