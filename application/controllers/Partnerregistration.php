<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Partnerregistration extends Front_Controller {

    public function __construct() {
        parent::__construct();

        // Load database first
        $this->load->database();

        // Load setting_model first (required by Partner_model)
        $this->load->model('setting_model');
        $this->load->model('Partner_model');
        $this->load->model('Type_model');
        $this->load->model('Frequency_model');
        $this->load->model('Student_model');
        $this->load->model('Staff_model');
        $this->load->library('form_validation');
        $this->load->library('email');
        $this->load->helper(['url', 'form']);

        // Set active menu for partner section
        $this->data['active_menu'] = 'partners';
    }

    /**
     * Landing page - Account type selection
     */
    public function index() {
        $this->data['title'] = 'Become a Partner';
        $this->data['page'] = 'registration';

        $this->load->view('themes/' . $this->data['theme'] . '/partnerregistration/index', $this->data);
    }

    /**
     * Registration form - Multi-step
     */
    public function register($account_type = 'individual') {
        // Validate account type
        if (!in_array($account_type, ['individual', 'organization'])) {
            redirect('partnerregistration');
        }

        // Load giving types and frequencies with error handling
        $giving_types = $this->Type_model->getAll();
        $giving_frequencies = $this->Frequency_model->getAll();

        // Check if data exists
        if (empty($giving_types)) {
            log_message('error', 'Partner Registration: No active giving types found in database');
            show_error('System configuration error: No giving types available. Please contact the administrator.', 500, 'Configuration Error');
            return;
        }

        if (empty($giving_frequencies)) {
            log_message('error', 'Partner Registration: No active giving frequencies found in database');
            show_error('System configuration error: No giving frequencies available. Please contact the administrator.', 500, 'Configuration Error');
            return;
        }

        $this->data['title'] = 'Partner Registration';
        $this->data['page'] = 'registration_form';
        $this->data['account_type'] = $account_type;
        $this->data['giving_types'] = $giving_types;
        $this->data['giving_frequencies'] = $giving_frequencies;

        $this->load->view('themes/' . $this->data['theme'] . '/partnerregistration/register', $this->data);
    }

    /**
     * Check if email/phone belongs to existing student or staff
     */
    public function checkExisting() {
        $email = $this->input->post('email');
        $phone = $this->input->post('phone');

        $response = [
            'exists' => false,
            'type' => null,
            'name' => null,
            'admission_no' => null,
            'student_id' => null,
            'staff_id' => null
        ];

        // Check students
        if ($email) {
            $student = $this->db->where('email', $email)
                                ->or_where('guardian_email', $email)
                                ->or_where('father_email', $email)
                                ->or_where('mother_email', $email)
                                ->get('students')
                                ->row();

            if ($student) {
                $response['exists'] = true;
                $response['type'] = 'student';
                $response['name'] = $student->firstname . ' ' . $student->lastname;
                $response['admission_no'] = $student->admission_no;
                $response['student_id'] = $student->id;
            }
        }

        // Check staff if not found in students
        if (!$response['exists'] && $email) {
            $staff = $this->db->where('email', $email)
                             ->get('staff')
                             ->row();

            if ($staff) {
                $response['exists'] = true;
                $response['type'] = 'staff';
                $response['name'] = $staff->name;
                $response['staff_id'] = $staff->id;
            }
        }

        // Check by phone
        if (!$response['exists'] && $phone) {
            $student = $this->db->where('mobileno', $phone)
                                ->or_where('guardian_phone', $phone)
                                ->or_where('father_phone', $phone)
                                ->or_where('mother_phone', $phone)
                                ->get('students')
                                ->row();

            if ($student) {
                $response['exists'] = true;
                $response['type'] = 'student';
                $response['name'] = $student->firstname . ' ' . $student->lastname;
                $response['admission_no'] = $student->admission_no;
                $response['student_id'] = $student->id;
            }
        }

        echo json_encode($response);
    }

    /**
     * Submit registration
     */
    public function submit() {
        // Set validation rules based on account type
        $account_type = $this->input->post('account_type');

        if ($account_type == 'organization') {
            $this->form_validation->set_rules('organization_name', 'Organization Name', 'required|trim');
            $this->form_validation->set_rules('organization_type', 'Organization Type', 'required|trim');
        }

        $this->form_validation->set_rules('firstname', 'First Name', 'required|trim|xss_clean');
        $this->form_validation->set_rules('lastname', 'Last Name', 'required|trim|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim|xss_clean');
        $this->form_validation->set_rules('mobileno', 'Phone Number', 'required|trim|xss_clean');
        $this->form_validation->set_rules('address', 'Address', 'trim|xss_clean');
        $this->form_validation->set_rules('city', 'City', 'trim|xss_clean');
        $this->form_validation->set_rules('country', 'Country', 'trim|xss_clean');
        $this->form_validation->set_rules('giving_type_id', 'Giving Type', 'trim|xss_clean');
        $this->form_validation->set_rules('giving_frequency_id', 'Giving Frequency', 'trim|xss_clean');
        $this->form_validation->set_rules('contribution_amount', 'Contribution Amount', 'trim|numeric|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $errors = validation_errors();
            echo json_encode([
                'status' => 'error',
                'message' => $errors
            ]);
            return;
        }

        // Prepare partner data
        $partner_data = [
            'firstname' => $this->input->post('firstname'),
            'lastname' => $this->input->post('lastname'),
            'email' => $this->input->post('email'),
            'mobileno' => $this->input->post('mobileno'),
            'address' => $this->input->post('address'),
            'city' => $this->input->post('city'),
            'state' => $this->input->post('state'),
            'country' => $this->input->post('country'),
            'zipcode' => $this->input->post('zipcode'),
            'giving_type_id' => $this->input->post('giving_type_id'),
            'giving_frequency_id' => $this->input->post('giving_frequency_id'),
            'contribution_amount' => $this->input->post('contribution_amount'),
            'currency' => $this->input->post('currency', 'USD'),
            'start_date' => date('Y-m-d'),
            'status' => 'inactive', // Pending approval
            'notes' => $this->input->post('notes'),
            'student_id' => $this->input->post('student_id'),
            'is_active' => 1
        ];

        // Add organization fields if applicable
        if ($account_type == 'organization') {
            $partner_data['notes'] = "Organization: " . $this->input->post('organization_name') .
                                    " (Type: " . $this->input->post('organization_type') . ")\n" .
                                    $partner_data['notes'];
        }

        // Generate partner code
        $partner_data['partner_code'] = $this->Partner_model->generatePartnerCode();

        // Insert partner
        $partner_id = $this->Partner_model->add($partner_data);

        if ($partner_id) {
            // Check if user wants to create account
            $create_account = $this->input->post('create_account');

            if ($create_account == '1') {
                $this->createPartnerAccount($partner_id, $partner_data);
            }

            // Send confirmation email
            $this->sendConfirmationEmail($partner_data, $partner_id);

            echo json_encode([
                'status' => 'success',
                'message' => 'Registration successful! Your application is pending approval.',
                'partner_code' => $partner_data['partner_code'],
                'redirect' => base_url('partnerregistration/success/' . $partner_id)
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Registration failed. Please try again.'
            ]);
        }
    }

    /**
     * Create partner portal account (optional)
     */
    private function createPartnerAccount($partner_id, $partner_data) {
        // Generate username and password
        $username = strtolower($partner_data['firstname'] . '_' . $partner_data['partner_code']);
        $password = $this->generateRandomPassword();

        // Check if user table exists for partners
        // This would integrate with your existing user authentication system
        $user_data = [
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'email' => $partner_data['email'],
            'role' => 'partner',
            'partner_id' => $partner_id,
            'is_active' => 0, // Inactive until approved
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Store temporary password for email
        $this->session->set_tempdata('partner_password', $password, 300);

        // Insert into users table (you may need to create this table)
        // $this->db->insert('partner_users', $user_data);
    }

    /**
     * Generate random password
     */
    private function generateRandomPassword($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $password;
    }

    /**
     * Send confirmation email
     */
    private function sendConfirmationEmail($partner_data, $partner_id) {
        $this->load->library('email');

        $config['mailtype'] = 'html';
        $this->email->initialize($config);

        $this->email->from('noreply@rhemazimbabwe.com', 'Rhema Zimbabwe');
        $this->email->to($partner_data['email']);
        $this->email->subject('Partner Registration Confirmation');

        $message = $this->load->view('themes/default/partnerregistration/email/confirmation', [
            'partner_data' => $partner_data,
            'partner_id' => $partner_id,
            'base_url' => base_url()
        ], TRUE);

        $this->email->message($message);
        $this->email->send();
    }

    /**
     * Success page
     */
    public function success($partner_id = null) {
        if (!$partner_id) {
            redirect('partnerregistration');
        }

        $partner = $this->Partner_model->getById($partner_id);

        if (!$partner) {
            redirect('partnerregistration');
        }

        $this->data['title'] = 'Registration Successful';
        $this->data['page'] = 'success';
        $this->data['partner'] = $partner;

        $this->load->view('themes/' . $this->data['theme'] . '/partnerregistration/success', $this->data);
    }

    /**
     * Check registration status
     */
    public function checkStatus() {
        $this->data['title'] = 'Check Registration Status';
        $this->data['page'] = 'check_status';

        $this->load->view('themes/default/partnerregistration/header', $this->data);
        $this->load->view('themes/default/partnerregistration/check_status', $this->data);
        $this->load->view('themes/default/partnerregistration/footer', $this->data);
    }

    /**
     * Get registration status
     */
    public function getStatus() {
        $partner_code = $this->input->post('partner_code');
        $email = $this->input->post('email');

        if (!$partner_code && !$email) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Please provide partner code or email'
            ]);
            return;
        }

        $query = $this->db->select('partner_code, firstname, lastname, email, status, created_at')
                          ->from('partners');

        if ($partner_code) {
            $query->where('partner_code', $partner_code);
        } else {
            $query->where('email', $email);
        }

        $partner = $query->get()->row();

        if ($partner) {
            $status_text = ucfirst($partner->status);
            $status_class = 'info';

            switch ($partner->status) {
                case 'active':
                    $status_text = 'Approved - Active';
                    $status_class = 'success';
                    break;
                case 'inactive':
                    $status_text = 'Pending Approval';
                    $status_class = 'warning';
                    break;
                case 'suspended':
                    $status_text = 'Suspended';
                    $status_class = 'danger';
                    break;
            }

            echo json_encode([
                'status' => 'success',
                'partner' => [
                    'code' => $partner->partner_code,
                    'name' => $partner->firstname . ' ' . $partner->lastname,
                    'email' => $partner->email,
                    'status' => $partner->status,
                    'status_text' => $status_text,
                    'status_class' => $status_class,
                    'created_at' => $partner->created_at
                ]
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Registration not found'
            ]);
        }
    }
}