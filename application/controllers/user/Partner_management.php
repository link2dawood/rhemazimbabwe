<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Partner_management extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Load libraries first as models depend on them
        $this->load->library('Customlib');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('user_agent');
        $this->load->helper('url');
        $this->load->database();
        
        // Load setting_model first as other models depend on it
        $this->load->model('setting_model');
        
        // Load other models
        $this->load->model(array(
            'partner_model',
            'contribution_model',
            'partner_giving_setting_model',
            'type_model',
            'frequency_model',
            'student_model',
            'staff_model'
        ));
    }

    /**
     * Check if user is logged in and has permission to manage partners
     */
    private function checkPermission()
    {
        // Check if user is logged in
        if (!$this->session->userdata('student_id') && 
            !$this->session->userdata('staff_id') && 
            !$this->session->userdata('admin_id')) {
            
            $this->session->set_flashdata('error', 'Please login to access partner management.');
            redirect('userlogin');
            return false;
        }
        
        return true;
    }

    /**
     * Get user role and ID
     */
    private function getUserInfo()
    {
        $role = 'guest';
        $user_id = null;
        
        if ($this->session->userdata('student_id')) {
            $role = 'student';
            $user_id = $this->session->userdata('student_id');
        } elseif ($this->session->userdata('staff_id')) {
            $role = 'staff';
            $user_id = $this->session->userdata('staff_id');
        } elseif ($this->session->userdata('admin_id')) {
            $role = 'admin';
            $user_id = $this->session->userdata('admin_id');
        }
        
        return array('role' => $role, 'user_id' => $user_id);
    }

    /**
     * Partner Dashboard
     */
    public function index()
    {
        if (!$this->checkPermission()) {
            return;
        }

        $user_info = $this->getUserInfo();
        $data = array();
        $data['title'] = 'Partner Management';
        $data['user_info'] = $user_info;

        // Get partners based on user role
        if ($user_info['role'] == 'student') {
            $data['partners'] = $this->partner_model->getByStudentId($user_info['user_id']);
        } elseif ($user_info['role'] == 'staff') {
            $data['partners'] = $this->partner_model->getByStaffId($user_info['user_id']);
        } elseif ($user_info['role'] == 'admin') {
            $data['partners'] = $this->partner_model->getAll();
        }

        $this->load->view('user/partner/management_dashboard', $data);
    }

    /**
     * Add New Partner
     */
    public function add()
    {
        if (!$this->checkPermission()) {
            return;
        }

        $user_info = $this->getUserInfo();
        $data = array();
        $data['title'] = 'Add Partner';
        $data['user_info'] = $user_info;
        $data['giving_types'] = $this->type_model->getAll();
        $data['giving_frequencies'] = $this->frequency_model->getAll();

        // Pre-fill user data
        if ($user_info['role'] == 'student') {
            $student = $this->student_model->get($user_info['user_id']);
            $data['prefill_data'] = array(
                'firstname' => $student['firstname'] ?? '',
                'lastname' => $student['lastname'] ?? '',
                'email' => $student['email'] ?? '',
                'mobileno' => $student['mobileno'] ?? ''
            );
        } elseif ($user_info['role'] == 'staff') {
            $staff = $this->staff_model->get($user_info['user_id']);
            $data['prefill_data'] = array(
                'firstname' => $staff['name'] ?? '',
                'lastname' => $staff['surname'] ?? '',
                'email' => $staff['email'] ?? '',
                'mobileno' => $staff['contact_no'] ?? ''
            );
        }

        $this->load->view('user/partner/add_partner', $data);
    }

    /**
     * Process Add Partner
     */
    public function process_add()
    {
        if (!$this->checkPermission()) {
            return;
        }

        $user_info = $this->getUserInfo();

        // Validation rules
        $this->form_validation->set_rules('firstname', 'First Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('lastname', 'Last Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
        $this->form_validation->set_rules('mobileno', 'Mobile Number', 'trim|required|xss_clean');
        $this->form_validation->set_rules('account_type', 'Account Type', 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('user/partner_management/add');
            return;
        }

        // Prepare partner data
        $partner_data = array(
            'partner_code' => $this->generatePartnerCode(),
            'account_type' => $this->input->post('account_type'),
            'firstname' => $this->input->post('firstname'),
            'lastname' => $this->input->post('lastname'),
            'email' => $this->input->post('email'),
            'mobileno' => $this->input->post('mobileno'),
            'address' => $this->input->post('address'),
            'city' => $this->input->post('city'),
            'state' => $this->input->post('state'),
            'country' => $this->input->post('country'),
            'zip_code' => $this->input->post('zip_code'),
            'giving_type_id' => $this->input->post('giving_type_id'),
            'giving_frequency_id' => $this->input->post('giving_frequency_id'),
            'contribution_amount' => $this->input->post('contribution_amount'),
            'currency' => $this->input->post('currency') ?: 'USD',
            'notes' => $this->input->post('notes'),
            'status' => 'active', // Auto-approve for logged-in users
            'created_at' => date('Y-m-d H:i:s')
        );

        // Add user-specific information
        if ($user_info['role'] == 'student') {
            $partner_data['student_id'] = $user_info['user_id'];
        } elseif ($user_info['role'] == 'staff') {
            $partner_data['staff_id'] = $user_info['user_id'];
        }

        // Add organization information if applicable
        if ($this->input->post('account_type') === 'organization') {
            $partner_data['organization_name'] = $this->input->post('organization_name');
            $partner_data['organization_type'] = $this->input->post('organization_type');
        }

        // Insert partner
        $partner_id = $this->partner_model->add($partner_data);

        if ($partner_id) {
            // Save giving types if provided
            $this->saveGivingTypes($partner_id);
            
            $this->session->set_flashdata('success', 'Partner added successfully!');
            redirect('user/partner_management');
        } else {
            $this->session->set_flashdata('error', 'Failed to add partner. Please try again.');
            redirect('user/partner_management/add');
        }
    }

    /**
     * Edit Partner
     */
    public function edit($partner_id)
    {
        if (!$this->checkPermission()) {
            return;
        }

        $user_info = $this->getUserInfo();
        
        // Check if user can edit this partner
        if (!$this->canEditPartner($partner_id, $user_info)) {
            $this->session->set_flashdata('error', 'You do not have permission to edit this partner.');
            redirect('user/partner_management');
            return;
        }

        $data = array();
        $data['title'] = 'Edit Partner';
        $data['user_info'] = $user_info;
        $data['partner'] = $this->partner_model->getById($partner_id);
        $data['giving_types'] = $this->type_model->getAll();
        $data['giving_frequencies'] = $this->frequency_model->getAll();

        if (!$data['partner']) {
            $this->session->set_flashdata('error', 'Partner not found.');
            redirect('user/partner_management');
            return;
        }

        $this->load->view('user/partner/edit_partner', $data);
    }

    /**
     * Process Edit Partner
     */
    public function process_edit($partner_id)
    {
        if (!$this->checkPermission()) {
            return;
        }

        $user_info = $this->getUserInfo();
        
        // Check if user can edit this partner
        if (!$this->canEditPartner($partner_id, $user_info)) {
            $this->session->set_flashdata('error', 'You do not have permission to edit this partner.');
            redirect('user/partner_management');
            return;
        }

        // Validation rules
        $this->form_validation->set_rules('firstname', 'First Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('lastname', 'Last Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
        $this->form_validation->set_rules('mobileno', 'Mobile Number', 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('user/partner_management/edit/' . $partner_id);
            return;
        }

        // Prepare partner data
        $partner_data = array(
            'firstname' => $this->input->post('firstname'),
            'lastname' => $this->input->post('lastname'),
            'email' => $this->input->post('email'),
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
            'notes' => $this->input->post('notes'),
            'updated_at' => date('Y-m-d H:i:s')
        );

        // Add organization information if applicable
        if ($this->input->post('account_type') === 'organization') {
            $partner_data['organization_name'] = $this->input->post('organization_name');
            $partner_data['organization_type'] = $this->input->post('organization_type');
        }

        // Update partner
        if ($this->partner_model->update($partner_id, $partner_data)) {
            $this->session->set_flashdata('success', 'Partner updated successfully!');
            redirect('user/partner_management');
        } else {
            $this->session->set_flashdata('error', 'Failed to update partner. Please try again.');
            redirect('user/partner_management/edit/' . $partner_id);
        }
    }

    /**
     * Delete Partner
     */
    public function delete($partner_id)
    {
        if (!$this->checkPermission()) {
            return;
        }

        $user_info = $this->getUserInfo();
        
        // Check if user can delete this partner
        if (!$this->canEditPartner($partner_id, $user_info)) {
            $this->session->set_flashdata('error', 'You do not have permission to delete this partner.');
            redirect('user/partner_management');
            return;
        }

        if ($this->partner_model->delete($partner_id)) {
            $this->session->set_flashdata('success', 'Partner deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete partner. Please try again.');
        }
        
        redirect('user/partner_management');
    }

    /**
     * Check if user can edit/delete a partner
     */
    private function canEditPartner($partner_id, $user_info)
    {
        $partner = $this->partner_model->getById($partner_id);
        
        if (!$partner) {
            return false;
        }

        // Admin can edit all partners
        if ($user_info['role'] == 'admin') {
            return true;
        }

        // Students can edit their own partners
        if ($user_info['role'] == 'student' && $partner['student_id'] == $user_info['user_id']) {
            return true;
        }

        // Staff can edit their own partners
        if ($user_info['role'] == 'staff' && $partner['staff_id'] == $user_info['user_id']) {
            return true;
        }

        return false;
    }

    /**
     * Save giving types for partner
     */
    private function saveGivingTypes($partner_id)
    {
        $giving_types = $this->input->post('giving_types');
        $amounts = $this->input->post('amounts');
        
        if ($giving_types && $amounts) {
            // Delete existing giving types
            $this->db->where('partner_id', $partner_id);
            $this->db->delete('partner_giving_settings');
            
            // Insert new giving types
            foreach ($giving_types as $index => $type_id) {
                if (!empty($amounts[$index]) && $amounts[$index] > 0) {
                    $this->db->insert('partner_giving_settings', array(
                        'partner_id' => $partner_id,
                        'giving_type_id' => $type_id,
                        'amount' => $amounts[$index],
                        'currency' => $this->input->post('currency') ?: 'USD',
                        'is_active' => 1,
                        'created_at' => date('Y-m-d H:i:s')
                    ));
                }
            }
        }
    }

    /**
     * Generate unique partner code
     */
    private function generatePartnerCode()
    {
        do {
            $code = 'PTR-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $exists = $this->db->where('partner_code', $code)->count_all_results('partners');
        } while ($exists > 0);

        return $code;
    }
}
