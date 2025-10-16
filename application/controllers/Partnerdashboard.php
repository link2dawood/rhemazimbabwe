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
        $this->load->view('user/partner/settings', $data);
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
                $total += $setting['amount'];
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
        $giving_types = $this->input->post('giving_types');
        $amounts = $this->input->post('amounts');
        $frequency_id = $this->input->post('frequency_id');
        $currency = $this->input->post('currency') ?: 'USD';

        // Validation
        if (empty($giving_types) || empty($amounts)) {
            echo json_encode(['status' => false, 'message' => 'Please select at least one giving type with an amount']);
            return;
        }

        if (empty($frequency_id)) {
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
        $this->load->view('user/partner/change_password', $data);
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

    // Private helper method
    private function getStatistics($partner_id)
    {
        $stats = [];

        // Partner counts (for single partner view, these are always 1)
        $stats['total_partners'] = 1;
        $stats['active_partners'] = ($this->partner_data['status'] == 'active') ? 1 : 0;

        // Total contributed
        $stats['total_contributed'] = $this->contribution_model->getTotalContributed($partner_id);

        // This year contributed
        $stats['this_year_contributed'] = $this->contribution_model->getYearContributed($partner_id, date('Y'));

        // Total transactions
        $contributions = $this->contribution_model->getContributionsByPartner($partner_id);
        $stats['total_transactions'] = count($contributions);

        // Last contribution date
        if (!empty($contributions)) {
            $stats['last_contribution'] = $contributions[0]['contribution_date'];
        } else {
            $stats['last_contribution'] = null;
        }

        // Account status
        $stats['status'] = $this->partner_data['status'];

        return $stats;
    }
}
