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
        $this->load->model('cms_page_model');
        $this->load->library('form_validation');
        $this->load->library('email');
        $this->load->library('module_lib');
        $this->load->library('captchalib');
        $this->load->library('cart');
        $this->load->helper(['url', 'form']);

        // Load settings data for theme
        $this->data['setting_data'] = $this->setting_model->get();

        // Load course setting if module exists
        if ($this->module_lib->hasModule('online_course')) {
            $this->load->model('course_model');
            $this->data['course_setting'] = $this->course_model->getOnlineCourseSettings();
        } else {
            // Initialize empty course_setting to avoid undefined variable errors
            $this->data['course_setting'] = (object)array('guest_login' => 0);
        }

        // Load currencies if available
        if ($this->module_lib->hasModule('online_course')) {
            $this->load->model('currency_model');
            $this->data['currencies'] = $this->currency_model->get();
        } else {
            $this->data['currencies'] = array();
        }

        // Set theme from settings or use default
        $this->data['theme'] = $this->data['theme'] ?? 'default';

        // Set active menu for partner section
        $this->data['active_menu'] = 'partners';
    }

    /**
     * Landing page - Account type selection
     */
    public function index() {
        $this->data['title'] = 'Become a Partner';
        $this->data['page'] = array(
            'title' => 'Become a Partner',
            'meta_title' => 'Become a Partner - Support Education',
            'meta_keyword' => 'partner, donate, support, education',
            'meta_description' => 'Join us as a partner and make a difference in students lives'
        );
        $this->data['page_side_bar'] = false;

        $this->load_theme('partnerregistration/index');
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
        $this->data['page'] = array(
            'title' => 'Partner Registration - ' . ucfirst($account_type),
            'meta_title' => 'Partner Registration',
            'meta_keyword' => 'partner registration, donate, support',
            'meta_description' => 'Register as a partner to support our educational mission'
        );
        $this->data['page_side_bar'] = false;
        $this->data['account_type'] = $account_type;
        $this->data['giving_types'] = $giving_types;
        $this->data['giving_frequencies'] = $giving_frequencies;

        $this->load_theme('partnerregistration/register');
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

        // Calculate total contribution amount from multiple types
        $giving_types_selected = $this->input->post('giving_types') ?? [];
        $giving_amounts = $this->input->post('giving_amounts') ?? [];
        $total_contribution = 0;

        foreach ($giving_types_selected as $type_id) {
            if (isset($giving_amounts[$type_id]) && !empty($giving_amounts[$type_id])) {
                $total_contribution += floatval($giving_amounts[$type_id]);
            }
        }

        // Prepare partner data
        $student_id = $this->input->post('student_id');
        $giving_frequency_id = $this->input->post('giving_frequency_id');
        $organization_name = $this->input->post('organization_name');
        $organization_type = $this->input->post('organization_type');

        $partner_data = [
            'account_type' => $account_type,
            'organization_name' => !empty($organization_name) ? $organization_name : NULL,
            'organization_type' => !empty($organization_type) ? $organization_type : NULL,
            'firstname' => $this->input->post('firstname'),
            'lastname' => $this->input->post('lastname'),
            'email' => $this->input->post('email'),
            'mobileno' => $this->input->post('mobileno'),
            'address' => $this->input->post('address') ?: NULL,
            'city' => $this->input->post('city') ?: NULL,
            'state' => $this->input->post('state') ?: NULL,
            'country' => $this->input->post('country') ?: 'Zimbabwe',
            'zip_code' => $this->input->post('zipcode') ?: NULL,
            'giving_frequency_id' => !empty($giving_frequency_id) ? $giving_frequency_id : NULL,
            'contribution_amount' => $total_contribution,
            'total_contribution_amount' => 0,
            'currency' => $this->input->post('currency') ?? 'USD',
            'start_date' => date('Y-m-d'),
            'status' => 'pending', // Pending admin approval
            'notes' => $this->input->post('notes') ?: NULL,
            'student_id' => !empty($student_id) ? $student_id : NULL,
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Generate partner code
        $partner_data['partner_code'] = $this->Partner_model->generatePartnerCode();

        // Check if user wants to create account with password
        $create_account = $this->input->post('create_account');
        $password = $this->input->post('password');

        if ($create_account == '1' && !empty($password)) {
            // Generate username from email or firstname
            $email_parts = explode('@', $partner_data['email']);
            $partner_data['username'] = strtolower($email_parts[0]);

            // Check if username exists, append number if needed
            $username_check = $this->db->where('username', $partner_data['username'])->get('partners')->row();
            if ($username_check) {
                $partner_data['username'] = $partner_data['username'] . rand(100, 999);
            }

            $partner_data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        // Debug: Log the data being submitted
        log_message('debug', 'Partner registration attempt with data: ' . json_encode($partner_data));

        // TEMP DEBUG: Check if required fields are present
        $debug_info = [
            'has_firstname' => isset($partner_data['firstname']),
            'has_lastname' => isset($partner_data['lastname']),
            'has_email' => isset($partner_data['email']),
            'has_mobileno' => isset($partner_data['mobileno']),
            'has_partner_code' => isset($partner_data['partner_code']),
            'partner_code_value' => $partner_data['partner_code'] ?? 'NOT SET',
            'email_value' => $partner_data['email'] ?? 'NOT SET',
            'status_value' => $partner_data['status'] ?? 'NOT SET'
        ];

        // Insert partner
        $partner_id = $this->Partner_model->add($partner_data);

        // Debug: Log the result
        log_message('debug', 'Partner_model->add() result: ' . json_encode($partner_id));

        // Check if partner_id is a valid integer (success)
        if (is_numeric($partner_id) && $partner_id > 0) {
            // Save multiple giving types with amounts
            if (!empty($giving_types_selected)) {
                foreach ($giving_types_selected as $type_id) {
                    if (isset($giving_amounts[$type_id]) && !empty($giving_amounts[$type_id])) {
                        $insert_result = $this->db->insert('partner_giving_types', [
                            'partner_id' => $partner_id,
                            'giving_type_id' => $type_id,
                            'amount' => floatval($giving_amounts[$type_id]),
                            'currency' => $partner_data['currency'],
                            'created_at' => date('Y-m-d H:i:s')
                        ]);

                        // Log error if insert fails but don't stop the process
                        if (!$insert_result) {
                            log_message('error', 'Failed to insert giving type for partner ' . $partner_id . ', type: ' . $type_id);
                        }
                    }
                }
            }

            // Send confirmation email
            $this->sendConfirmationEmail($partner_data, $partner_id);

            echo json_encode([
                'status' => 'success',
                'message' => 'Registration successful! Your application is pending approval.',
                'partner_code' => $partner_data['partner_code'],
                'redirect' => base_url('partnerregistration/success/' . $partner_id)
            ]);
        } elseif (is_array($partner_id) && isset($partner_id['error']) && $partner_id['error'] === true) {
            // Model returned a validation error with specific message
            echo json_encode([
                'status' => 'error',
                'message' => $partner_id['message']
            ]);
        } else {
            // Unknown error
            log_message('error', 'Partner registration failed with unknown error. Data: ' . json_encode($partner_data));
            log_message('error', 'Partner_model->add() returned: ' . print_r($partner_id, true));

            // Get database error for debugging
            $db_error = $this->db->error();

            echo json_encode([
                'status' => 'error',
                'message' => 'Registration failed due to an unexpected error. Please try again or contact support.',
                'debug' => [
                    'result_type' => gettype($partner_id),
                    'result_value' => $partner_id,
                    'db_error' => $db_error,
                    'field_check' => $debug_info,
                    'sample_data' => array_slice($partner_data, 0, 5)
                ]
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
        try {
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

            // Attempt to send email, but don't fail if mail server is not configured
            @$this->email->send();

        } catch (Exception $e) {
            // Log error but don't fail the registration
            log_message('error', 'Partner registration email failed: ' . $e->getMessage());
        }
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
        $this->data['page'] = array(
            'title' => 'Registration Successful',
            'meta_title' => 'Registration Successful',
            'meta_keyword' => 'partner registration success',
            'meta_description' => 'Your partner registration has been submitted successfully'
        );
        $this->data['page_side_bar'] = false;
        $this->data['partner'] = $partner;

        $this->load_theme('partnerregistration/success');
    }

    /**
     * Check registration status
     */
    public function checkStatus() {
        $this->data['title'] = 'Check Registration Status';
        $this->data['page'] = array(
            'title' => 'Check Registration Status',
            'meta_title' => 'Check Partner Registration Status',
            'meta_keyword' => 'partner status, registration check',
            'meta_description' => 'Check your partner registration status'
        );
        $this->data['page_side_bar'] = false;

        $this->load_theme('partnerregistration/check_status');
    }

    /**
     * Debug page for testing registration
     */
    public function debug_test() {
        // Simple test to diagnose issues
        echo "<h1>Partner Registration Diagnostic</h1>";
        echo "<style>body { font-family: Arial; margin: 20px; } .success { color: green; } .error { color: red; } pre { background: #f5f5f5; padding: 10px; }</style>";

        // Test 1: Check database connection
        echo "<h2>1. Database Connection</h2>";
        try {
            $this->db->query("SELECT 1");
            echo "<p class='success'>✓ Database connected</p>";
        } catch (Exception $e) {
            echo "<p class='error'>✗ Database connection failed: " . $e->getMessage() . "</p>";
            return;
        }

        // Test 2: Check giving types
        echo "<h2>2. Giving Types</h2>";
        $giving_types = $this->Type_model->getAll();
        if (empty($giving_types)) {
            echo "<p class='error'>✗ No giving types found!</p>";
        } else {
            echo "<p class='success'>✓ Found " . count($giving_types) . " giving types</p>";
            echo "<pre>" . print_r($giving_types, true) . "</pre>";
        }

        // Test 3: Check giving frequencies
        echo "<h2>3. Giving Frequencies</h2>";
        $giving_frequencies = $this->Frequency_model->getAll();
        if (empty($giving_frequencies)) {
            echo "<p class='error'>✗ No giving frequencies found!</p>";
        } else {
            echo "<p class='success'>✓ Found " . count($giving_frequencies) . " giving frequencies</p>";
            echo "<pre>" . print_r($giving_frequencies, true) . "</pre>";
        }

        // Test 4: Try a registration
        echo "<h2>4. Test Registration</h2>";
        $test_data = [
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => 'debugtest_' . time() . '@example.com',
            'mobileno' => '071' . rand(1000000, 9999999),
            'account_type' => 'individual',
            'status' => 'pending',
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ];

        echo "<p>Testing with data:</p>";
        echo "<pre>" . print_r($test_data, true) . "</pre>";

        $result = $this->Partner_model->add($test_data);

        echo "<p>Result:</p>";
        echo "<pre>Type: " . gettype($result) . "\nValue: " . print_r($result, true) . "</pre>";

        if (is_numeric($result) && $result > 0) {
            echo "<p class='success'>✓ Registration successful! Partner ID: " . $result . "</p>";
            // Clean up
            $this->db->where('id', $result)->delete('partners');
            echo "<p>Test partner deleted.</p>";
        } elseif (is_array($result) && isset($result['error'])) {
            echo "<p class='error'>✗ Validation error: " . $result['message'] . "</p>";
        } else {
            echo "<p class='error'>✗ Unexpected result!</p>";
            $db_error = $this->db->error();
            echo "<p>DB Error:</p><pre>" . print_r($db_error, true) . "</pre>";
        }

        // Test 5: Check recent partners
        echo "<h2>5. Recent Partners</h2>";
        $recent = $this->db->select('id, partner_code, firstname, lastname, email, status, created_at')
                           ->from('partners')
                           ->order_by('created_at', 'DESC')
                           ->limit(5)
                           ->get()
                           ->result();
        if ($recent) {
            echo "<p>Last 5 partners:</p>";
            echo "<pre>" . print_r($recent, true) . "</pre>";
        } else {
            echo "<p>No partners found.</p>";
        }
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
                case 'pending':
                    $status_text = 'Pending Approval';
                    $status_class = 'warning';
                    break;
                case 'active':
                    $status_text = 'Approved - Active';
                    $status_class = 'success';
                    break;
                case 'inactive':
                    $status_text = 'Inactive';
                    $status_class = 'info';
                    break;
                case 'suspended':
                    $status_text = 'Suspended / Rejected';
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