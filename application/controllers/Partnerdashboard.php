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
            'frequency_model' => 'giving_frequency_model'
        ));
    }

    // Dashboard
    public function index()
    {
        $data = [];
        $data['title'] = 'Partner Dashboard';
        $data['page'] = 'partner_dashboard';
        $data['role'] = 'partner';
        $data['partner'] = $this->partner_data;
        $data['partners'] = [$this->partner_data]; // For compatibility with dashboard view
        $data['is_partner_portal'] = true; // Flag to hide add partner buttons

        // Add student_data for header compatibility
        $data['student_data'] = [
            'image' => $this->partner_data['photo'] ?? '',
            'gender' => 'Male', // Default
            'id' => $this->partner_data['id']
        ];

        // Get statistics
        $partner_id = $this->partner_data['id'];
        $data['statistics'] = $this->getStatistics($partner_id);

        // Get recent contributions
        $data['recent_contributions'] = $this->contribution_model->getContributionsByPartner($partner_id);
        if (count($data['recent_contributions']) > 5) {
            $data['recent_contributions'] = array_slice($data['recent_contributions'], 0, 5);
        }

        $this->load->view('layout/student/header', $data);
        $this->load->view('user/partner/dashboard', $data);
        $this->load->view('layout/student/footer', $data);
    }

    // Profile
    public function profile()
    {
        $data = [];
        $data['title'] = 'My Profile';
        $data['role'] = 'partner';
        $data['partner'] = $this->partner_data;
        $data['student_data'] = [
            'image' => $this->partner_data['photo'] ?? '',
            'gender' => 'Male',
            'id' => $this->partner_data['id']
        ];
        $data['giving_types'] = $this->giving_type_model->getAll();
        $data['giving_frequencies'] = $this->giving_frequency_model->getAll();

        $this->load->view('layout/student/header', $data);
        $this->load->view('user/partner/settings', $data);
        $this->load->view('layout/student/footer', $data);
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
            'giving_type_id' => $this->input->post('giving_type_id'),
            'giving_frequency_id' => $this->input->post('giving_frequency_id'),
            'contribution_amount' => $this->input->post('contribution_amount'),
            'currency' => $this->input->post('currency'),
            'notes' => $this->input->post('notes')
        ];

        if ($this->partner_model->update($partner_id, $update_data)) {
            echo json_encode(['status' => true, 'message' => 'Profile updated successfully']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Failed to update profile']);
        }
    }

    // Contributions
    public function contributions()
    {
        $data = [];
        $data['title'] = 'My Contributions';
        $data['role'] = 'partner';
        $data['partner'] = $this->partner_data;
        $data['student_data'] = [
            'image' => $this->partner_data['photo'] ?? '',
            'gender' => 'Male',
            'id' => $this->partner_data['id']
        ];

        $partner_id = $this->partner_data['id'];
        $data['contributions'] = $this->contribution_model->getContributionsByPartner($partner_id);
        $data['total_contributed'] = $this->contribution_model->getTotalContributed($partner_id);

        $this->load->view('layout/student/header', $data);
        $this->load->view('user/partner/contributions', $data);
        $this->load->view('layout/student/footer', $data);
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

        $this->load->view('user/partner/receipt', $data);
    }

    // Change Password
    public function change_password()
    {
        $data = [];
        $data['title'] = 'Change Password';
        $data['role'] = 'partner';
        $data['partner'] = $this->partner_data;
        $data['student_data'] = [
            'image' => $this->partner_data['photo'] ?? '',
            'gender' => 'Male',
            'id' => $this->partner_data['id']
        ];

        $this->load->view('layout/student/header', $data);
        $this->load->view('user/partner/change_password', $data);
        $this->load->view('layout/student/footer', $data);
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
