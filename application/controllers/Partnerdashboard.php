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

    // Add Contribution
    public function add_contribution()
    {
        $partner_id = $this->partner_data['id'];
        
        $this->form_validation->set_rules('giving_type_id', 'Giving Type', 'required');
        $this->form_validation->set_rules('amount', 'Amount', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('contribution_date', 'Contribution Date', 'required');
        $this->form_validation->set_rules('payment_method', 'Payment Method', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => false, 'message' => validation_errors()]);
            return;
        }

        $contribution_data = [
            'partner_id' => $partner_id,
            'giving_type_id' => $this->input->post('giving_type_id'),
            'amount' => $this->input->post('amount'),
            'currency' => $this->input->post('currency') ?? 'USD',
            'contribution_date' => $this->input->post('contribution_date'),
            'payment_method' => $this->input->post('payment_method'),
            'transaction_id' => $this->input->post('transaction_id'),
            'notes' => $this->input->post('notes'),
            'status' => 'pending', // Pending admin approval
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($this->contribution_model->add($contribution_data)) {
            echo json_encode(['status' => true, 'message' => 'Contribution submitted successfully. It will be reviewed by admin.']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Failed to submit contribution']);
        }
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
    
    // Test method to add sample contributions (for testing purposes)
    public function add_sample_contributions()
    {
        $partner_id = $this->partner_data['id'];
        
        // Sample contributions data
        $sample_contributions = [
            [
                'partner_id' => $partner_id,
                'giving_type_id' => 1, // Assuming giving type 1 exists
                'amount' => 100.00,
                'currency' => 'USD',
                'contribution_date' => date('Y-m-d', strtotime('-30 days')),
                'payment_method' => 'bank_transfer',
                'transaction_id' => 'TXN' . time() . '1',
                'notes' => 'Sample contribution 1',
                'status' => 'completed',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'partner_id' => $partner_id,
                'giving_type_id' => 2, // Assuming giving type 2 exists
                'amount' => 50.00,
                'currency' => 'USD',
                'contribution_date' => date('Y-m-d', strtotime('-15 days')),
                'payment_method' => 'credit_card',
                'transaction_id' => 'TXN' . time() . '2',
                'notes' => 'Sample contribution 2',
                'status' => 'completed',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'partner_id' => $partner_id,
                'giving_type_id' => 1,
                'amount' => 75.00,
                'currency' => 'USD',
                'contribution_date' => date('Y-m-d'), // Today
                'payment_method' => 'paypal',
                'transaction_id' => 'TXN' . time() . '3',
                'notes' => 'Sample contribution 3',
                'status' => 'completed',
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];
        
        $added_count = 0;
        foreach ($sample_contributions as $contribution) {
            if ($this->contribution_model->add($contribution)) {
                $added_count++;
            }
        }
        
        if ($added_count > 0) {
            $this->session->set_flashdata('success', "Added {$added_count} sample contributions successfully!");
        } else {
            $this->session->set_flashdata('error', 'Failed to add sample contributions. Please check if giving types exist.');
        }
        
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
}
