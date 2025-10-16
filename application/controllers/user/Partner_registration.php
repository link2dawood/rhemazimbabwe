<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Partner_registration extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'partner_model',
            'type_model',
            'frequency_model',
            'Partner_giving_setting_model'
        ));
        $this->load->library('form_validation');
        $this->load->helper('url');
    }

    /**
     * Check if user is logged in and redirect accordingly
     */
    public function index()
    {
        // Check if user is already logged in
        if ($this->session->userdata('student_id') || $this->session->userdata('staff_id')) {
            $this->session->set_flashdata('info', 'You are already logged in. Please register as a partner through your portal.');
            redirect('user/partner/register');
        }
        
        // Redirect to frontend registration
        redirect('partner_registration');
    }

    /**
     * Student Portal Partner Registration
     */
    public function student_register()
    {
        // Check if student is logged in
        if (!$this->session->userdata('student_id')) {
            $this->session->set_flashdata('error', 'Please login to register as a partner.');
            redirect('userlogin');
        }

        $data['title'] = 'Partner Registration - Student Portal';
        $data['giving_types'] = $this->type_model->getAll();
        $data['giving_frequencies'] = $this->frequency_model->getAll();
        $data['user_type'] = 'student';
        $data['user_id'] = $this->session->userdata('student_id');
        
        $this->load->view('user/partner/registration', $data);
    }

    /**
     * Staff Portal Partner Registration
     */
    public function staff_register()
    {
        // Check if staff is logged in
        if (!$this->session->userdata('staff_id')) {
            $this->session->set_flashdata('error', 'Please login to register as a partner.');
            redirect('userlogin');
        }

        $data['title'] = 'Partner Registration - Staff Portal';
        $data['giving_types'] = $this->type_model->getAll();
        $data['giving_frequencies'] = $this->frequency_model->getAll();
        $data['user_type'] = 'staff';
        $data['user_id'] = $this->session->userdata('staff_id');
        
        $this->load->view('user/partner/registration', $data);
    }

    /**
     * Process Student Partner Registration
     */
    public function process_student()
    {
        // Check if student is logged in
        if (!$this->session->userdata('student_id')) {
            $this->session->set_flashdata('error', 'Please login to register as a partner.');
            redirect('userlogin');
        }

        $this->processRegistration('student');
    }

    /**
     * Process Staff Partner Registration
     */
    public function process_staff()
    {
        // Check if staff is logged in
        if (!$this->session->userdata('staff_id')) {
            $this->session->set_flashdata('error', 'Please login to register as a partner.');
            redirect('userlogin');
        }

        $this->processRegistration('staff');
    }

    /**
     * Process Registration for both student and staff
     */
    private function processRegistration($user_type)
    {
        // Validation rules
        $this->form_validation->set_rules('account_type', 'Account Type', 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
        $this->form_validation->set_rules('mobileno', 'Phone Number', 'trim|required|xss_clean');
        $this->form_validation->set_rules('address', 'Billing Address', 'trim|required|xss_clean');
        $this->form_validation->set_rules('city', 'City', 'trim|required|xss_clean');
        $this->form_validation->set_rules('country', 'Country', 'trim|required|xss_clean');
        $this->form_validation->set_rules('giving_types[]', 'Giving Types', 'required');
        $this->form_validation->set_rules('giving_frequency_id', 'Giving Frequency', 'required');

        // Organization specific validation
        if ($this->input->post('account_type') === 'organization') {
            $this->form_validation->set_rules('organization_name', 'Organization Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('organization_type', 'Organization Type', 'trim|required|xss_clean');
        }

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('user/partner_registration/' . $user_type . '_register');
            return;
        }

        // Get user information based on type
        $user_info = $this->getUserInfo($user_type);

        // Prepare partner data
        $partner_data = array(
            'partner_code' => $this->generatePartnerCode(),
            'account_type' => $this->input->post('account_type'),
            'email' => $this->input->post('email'),
            'mobileno' => $this->input->post('mobileno'),
            'address' => $this->input->post('address'),
            'city' => $this->input->post('city'),
            'state' => $this->input->post('state'),
            'country' => $this->input->post('country'),
            'zip_code' => $this->input->post('zip_code'),
            'giving_frequency_id' => $this->input->post('giving_frequency_id'),
            'contribution_amount' => $this->input->post('total_amount'),
            'currency' => $this->input->post('currency') ?: 'USD',
            'notes' => $this->input->post('notes'),
            'status' => 'active', // Auto-approve for logged-in users
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s')
        );

        // Add user-specific information
        if ($user_type === 'student') {
            $partner_data['student_id'] = $this->session->userdata('student_id');
            $partner_data['firstname'] = $user_info['firstname'];
            $partner_data['lastname'] = $user_info['lastname'];
        } else {
            $partner_data['staff_id'] = $this->session->userdata('staff_id');
            $partner_data['firstname'] = $user_info['firstname'];
            $partner_data['lastname'] = $user_info['lastname'];
        }

        // Add organization information if applicable
        if ($this->input->post('account_type') === 'organization') {
            $partner_data['organization_name'] = $this->input->post('organization_name');
            $partner_data['organization_type'] = $this->input->post('organization_type');
        }

        // Insert partner
        $partner_id = $this->partner_model->add($partner_data);

        if ($partner_id) {
            // Save giving types
            $this->saveGivingTypes($partner_id);
            
            // Create registration record
            $this->createRegistrationRecord($partner_id, $user_type);
            
            // Log activity
            $this->logActivity($partner_id, 'registration', 'Partner registered through ' . $user_type . ' portal');
            
            $this->session->set_flashdata('success', 'Partner registration completed successfully!');
            redirect('user/partner/dashboard');
        } else {
            $this->session->set_flashdata('error', 'Registration failed. Please try again.');
            redirect('user/partner_registration/' . $user_type . '_register');
        }
    }

    /**
     * Get user information based on type
     */
    private function getUserInfo($user_type)
    {
        if ($user_type === 'student') {
            $this->load->model('student_model');
            return $this->student_model->get($this->session->userdata('student_id'));
        } else {
            $this->load->model('staff_model');
            return $this->staff_model->get($this->session->userdata('staff_id'));
        }
    }

    /**
     * Save giving types for partner
     */
    private function saveGivingTypes($partner_id)
    {
        $giving_types = $this->input->post('giving_types');
        $amounts = $this->input->post('amounts');
        
        if ($giving_types && $amounts) {
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
     * Create registration record
     */
    private function createRegistrationRecord($partner_id, $user_type)
    {
        $this->db->insert('partner_registrations', array(
            'partner_id' => $partner_id,
            'registration_type' => $this->input->post('account_type'),
            'registration_source' => $user_type . '_portal',
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent(),
            'status' => 'approved', // Auto-approve for logged-in users
            'created_at' => date('Y-m-d H:i:s')
        ));
    }

    /**
     * Log partner activity
     */
    private function logActivity($partner_id, $activity_type, $description)
    {
        $this->db->insert('partner_activity_log', array(
            'partner_id' => $partner_id,
            'activity_type' => $activity_type,
            'activity_description' => $description,
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent(),
            'created_at' => date('Y-m-d H:i:s')
        ));
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

    /**
     * Check if user is already a partner
     */
    public function check_existing_partner()
    {
        $user_type = $this->input->post('user_type');
        $user_id = $this->input->post('user_id');
        
        $field = $user_type . '_id';
        $existing_partner = $this->partner_model->getByField($field, $user_id);
        
        if ($existing_partner) {
            echo json_encode(array(
                'exists' => true,
                'partner' => $existing_partner,
                'message' => 'You are already registered as a partner.'
            ));
        } else {
            echo json_encode(array(
                'exists' => false,
                'message' => 'You can proceed with registration.'
            ));
        }
    }
}
