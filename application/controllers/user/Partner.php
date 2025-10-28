<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Partner extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Load libraries first
        $this->load->library('Customlib');
        $this->load->database();
        $this->load->helper('custom');
        
        // Load Module_model before module_lib
        $this->load->model('Module_model');
        
        // Load language_model first as setting_model depends on it
        $this->load->model('language_model');
        
        // Load setting_model second as other models depend on it
        $this->load->model('setting_model');
        
        // Load auth library after setting_model (auth depends on setting_model)
        $this->load->library('auth');
        
        // Load module libraries after their dependencies
        $this->load->library('module_lib');
        $this->load->library('studentmodule_lib');
        
        // Load other models
        $this->load->model(array(
            'partner_model',
            'contribution_model',
            'partner_giving_setting_model',
            'type_model',
            'frequency_model',
            'student_model',
            'staff_model',
            'calendar_model',
            'notification_model'
        ));

        // Set active menu
        $this->session->set_userdata('top_menu', 'user_partners');

        // Load language file
        $student_lang = $this->session->userdata('student');
        $language = isset($student_lang['language']['language']) ? $student_lang['language']['language'] : 'English';
        
        // Try to load partners language file, fallback to English if not found
        if (!$this->lang->load('partners', $language)) {
            $this->lang->load('partners', 'English');
        }
    }

    // Partner Dashboard - Allow user to register themselves as a partner
    public function index()
    {
        $student_data = $this->customlib->getLoggedInUserData();
        $role = $this->customlib->getUserRole();

        // Check if user is already a partner
        $existing_partner = null;
        
        if ($role == 'student') {
            $student_id = $this->customlib->getStudentSessionUserID();
            $partners = $this->partner_model->getByStudentId($student_id);
            $existing_partner = !empty($partners) ? $partners[0] : null;
        } elseif ($role == 'staff') {
            $staff_id = $this->customlib->getStaffID();
            $partners = $this->partner_model->getByStaffId($staff_id);
            $existing_partner = !empty($partners) ? $partners[0] : null;
        } elseif ($role == 'parent') {
            $parent_id = $this->customlib->getUsersID();
            $parent = $this->student_model->getParent($parent_id);
            $partners = $this->partner_model->getPartnersByStudentOrContact(
                null, 
                $parent['guardian_email'], 
                $parent['guardian_phone']
            );
            $existing_partner = !empty($partners) ? $partners[0] : null;
        }

        $data = [];
        $data['title'] = $this->lang->line('become_a_partner');
        $data['page'] = 'partner_registration';
        $data['role'] = $role;
        $data['student_data'] = $student_data;
        $data['existing_partner'] = $existing_partner;

        // Get giving types and frequencies
        $data['giving_types'] = $this->type_model->getAll();
        $data['giving_frequencies'] = $this->frequency_model->getAll();

        // Pre-fill data based on user role
        if ($role == 'student') {
            $student_id = $this->customlib->getStudentSessionUserID();
            $student = $this->student_model->get($student_id);
            $data['prefill_data'] = array(
                'firstname' => $student['firstname'] ?? '',
                'lastname' => $student['lastname'] ?? '',
                'email' => $student['email'] ?? '',
                'mobileno' => $student['mobileno'] ?? ''
            );
            $data['user_id'] = $student_id;
            $data['user_type'] = 'student';
        } elseif ($role == 'staff') {
            $staff_id = $this->customlib->getStaffID();
            $staff = $this->staff_model->get($staff_id);
            $data['prefill_data'] = array(
                'firstname' => $staff['name'] ?? '',
                'lastname' => $staff['surname'] ?? '',
                'email' => $staff['email'] ?? '',
                'mobileno' => $staff['contact_no'] ?? ''
            );
            $data['user_id'] = $staff_id;
            $data['user_type'] = 'staff';
        } elseif ($role == 'parent') {
            $parent_id = $this->customlib->getUsersID();
            $parent = $this->student_model->getParent($parent_id);
            $data['prefill_data'] = array(
                'firstname' => $parent['guardian_name'] ?? '',
                'lastname' => $parent['guardian_surname'] ?? '',
                'email' => $parent['guardian_email'] ?? '',
                'mobileno' => $parent['guardian_phone'] ?? ''
            );
            $data['user_id'] = $parent_id;
            $data['user_type'] = 'parent';
        }

        // Make libraries available in views
        $data['module_lib'] = $this->module_lib;
        $data['studentmodule_lib'] = $this->studentmodule_lib;
        $data['auth'] = $this->auth;

        $this->load->view('layout/student/header', $data);
        $this->load->view('user/partner/register_self', $data);
        $this->load->view('layout/student/footer', $data);
    }

    // Process Self Registration
    public function process_self_registration()
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('firstname', 'First Name', 'required|trim');
        $this->form_validation->set_rules('lastname', 'Last Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
        $this->form_validation->set_rules('mobileno', 'Mobile Number', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|trim');
        $this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required|matches[password]|trim');
        $this->form_validation->set_rules('giving_frequency_id', 'Giving Frequency', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('user/partner');
            return;
        }

        $role = $this->input->post('user_type');
        $user_id = $this->input->post('user_id');

        // Generate partner code
        $partner_code = 'P-' . date('Ymd') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);

        // Hash password
        $password = $this->input->post('password');
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare partner data
        $partner_data = array(
            'partner_code' => $partner_code,
            'account_type' => 'individual',
            'firstname' => $this->input->post('firstname'),
            'lastname' => $this->input->post('lastname'),
            'email' => $this->input->post('email'),
            'mobileno' => $this->input->post('mobileno'),
            'password' => $hashed_password, // Add hashed password
            'address' => $this->input->post('address'),
            'city' => $this->input->post('city'),
            'state' => $this->input->post('state'),
            'country' => $this->input->post('country'),
            'zip_code' => $this->input->post('zipcode'),
            'giving_frequency_id' => $this->input->post('giving_frequency_id'),
            'currency' => $this->input->post('currency') ?: 'USD',
            'notes' => $this->input->post('notes'),
            'status' => 'active', // Auto-approve for logged-in users
            'is_active' => 1,
            'created_by' => $user_id,
            'created_at' => date('Y-m-d H:i:s')
        );

        // Set student_id or staff_id based on role
        if ($role == 'student') {
            $partner_data['student_id'] = $user_id;
        } elseif ($role == 'staff') {
            $partner_data['staff_id'] = $user_id;
        }

        // Insert partner
        $partner_id = $this->partner_model->add($partner_data);

        if ($partner_id) {
            // Handle multiple giving types
            $giving_types = $this->input->post('giving_types');
            $giving_amounts = $this->input->post('giving_amounts');

            if (!empty($giving_types) && is_array($giving_types)) {
                foreach ($giving_types as $type_id) {
                    if (isset($giving_amounts[$type_id]) && $giving_amounts[$type_id] > 0) {
                        $giving_setting_data = array(
                            'partner_id' => $partner_id,
                            'giving_type_id' => $type_id,
                            'amount' => $giving_amounts[$type_id],
                            'currency' => $this->input->post('currency') ?: 'USD',
                            'is_active' => 1,
                            'created_at' => date('Y-m-d H:i:s')
                        );
                        $this->partner_giving_setting_model->add($giving_setting_data);
                    }
                }
            }

            $this->session->set_flashdata('success', 'Partner registration successful! Your account has been automatically approved.');
            redirect('user/partner');
        } else {
            $this->session->set_flashdata('error', 'Failed to register as partner. Please try again.');
            redirect('user/partner');
        }
    }

    // Partner Registration - Redirect to new registration system
    public function register()
    {
        $student_data = $this->customlib->getLoggedInUserData();
        $role = $this->customlib->getUserRole();

        // Redirect students and staff to their respective partner registration pages
        if ($role == 'student') {
            redirect(base_url('user/partner_registration/student_register'));
        } elseif ($role == 'staff') {
            redirect(base_url('user/partner_registration/staff_register'));
        }

        // Only parents can register directly from partner portal
        $data = [];
        $data['title'] = $this->lang->line('become_a_partner');
        $data['page'] = 'partner_register';

        $data['role'] = $role;
        $data['student_data'] = $student_data;

        // Get giving types and frequencies
        $data['giving_types'] = $this->type_model->getAll();
        $data['giving_frequencies'] = $this->frequency_model->getAll();

        // Pre-fill data for parents only
        if ($role == 'parent') {
            $parent_id = $this->customlib->getUsersID();
            $parent = $this->student_model->getParent($parent_id);
            $data['prefill_data'] = array(
                'firstname' => $parent['guardian_name'] ?? '',
                'lastname' => $parent['guardian_surname'] ?? '',
                'email' => $parent['guardian_email'] ?? '',
                'mobileno' => $parent['guardian_phone'] ?? ''
            );
        }

        $this->load->view('layout/student/header', $data);
        $this->load->view('user/partner/register', $data);
        $this->load->view('layout/student/footer', $data);
    }

    // Add Partner Form
    public function add()
    {
        $student_data = $this->customlib->getLoggedInUserData();
        $role = $this->customlib->getUserRole();

        $data = [];
        $data['title'] = $this->lang->line('add_partner');
        $data['page'] = 'add_partner';
        $data['role'] = $role;
        $data['student_data'] = $student_data;

        // Get giving types and frequencies
        $data['giving_types'] = $this->type_model->getAll();
        $data['giving_frequencies'] = $this->frequency_model->getAll();

        // Make libraries available in views
        $data['module_lib'] = $this->module_lib;
        $data['studentmodule_lib'] = $this->studentmodule_lib;
        $data['auth'] = $this->auth;

        $this->load->view('layout/student/header', $data);
        $this->load->view('user/partner/add_partner', $data);
        $this->load->view('layout/student/footer', $data);
    }

    // Process Add Partner
    public function process_add()
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('firstname', 'First Name', 'required|trim');
        $this->form_validation->set_rules('lastname', 'Last Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
        $this->form_validation->set_rules('mobileno', 'Mobile Number', 'required|trim');
        $this->form_validation->set_rules('giving_type_id', 'Giving Type', 'required|numeric');
        $this->form_validation->set_rules('giving_frequency_id', 'Giving Frequency', 'required|numeric');
        $this->form_validation->set_rules('contribution_amount', 'Contribution Amount', 'required|numeric|greater_than[0]');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('user/partner/add');
            return;
        }

        $role = $this->customlib->getUserRole();
        $user_id = null;

        // Get user ID based on role
        if ($role == 'student') {
            $user_id = $this->customlib->getStudentSessionUserID();
        } elseif ($role == 'staff') {
            $user_id = $this->customlib->getStaffID();
        } elseif ($role == 'parent') {
            $user_id = $this->customlib->getUsersID();
        }

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
            'currency' => $this->input->post('currency') ?: 'USD',
            'start_date' => $this->input->post('start_date'),
            'end_date' => $this->input->post('end_date'),
            'notes' => $this->input->post('notes'),
            'status' => 'active',
            'is_active' => 1,
            'created_by' => $user_id,
            'created_at' => date('Y-m-d H:i:s')
        );

        // Set student_id or staff_id based on role
        if ($role == 'student') {
            $partner_data['student_id'] = $user_id;
        } elseif ($role == 'staff') {
            $partner_data['staff_id'] = $user_id;
        }

        $partner_id = $this->partner_model->add($partner_data);

        if ($partner_id) {
            $this->session->set_flashdata('success', 'Partner added successfully!');
            redirect('user/partner');
        } else {
            $this->session->set_flashdata('error', 'Failed to add partner. Please try again.');
            redirect('user/partner/add');
        }
    }

    // Add Contribution
    public function add_contribution($partner_id)
    {
        $student_data = $this->customlib->getLoggedInUserData();
        $role = $this->customlib->getUserRole();

        // Check if user can add contribution to this partner
        if (!$this->canManagePartner($partner_id)) {
            $this->session->set_flashdata('error', 'You do not have permission to add contributions to this partner.');
            redirect('user/partner');
            return;
        }

        $data = [];
        $data['title'] = $this->lang->line('add_contribution');
        $data['page'] = 'add_contribution';
        $data['role'] = $role;
        $data['student_data'] = $student_data;
        $data['partner_id'] = $partner_id;
        $data['partner'] = $this->partner_model->get($partner_id);

        // Get giving types
        $data['giving_types'] = $this->type_model->getAll();

        // Make libraries available in views
        $data['module_lib'] = $this->module_lib;
        $data['studentmodule_lib'] = $this->studentmodule_lib;
        $data['auth'] = $this->auth;

        $this->load->view('layout/student/header', $data);
        $this->load->view('user/partner/add_contribution', $data);
        $this->load->view('layout/student/footer', $data);
    }

    // Process Add Contribution
    public function process_add_contribution($partner_id)
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('amount', 'Amount', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('giving_type_id', 'Giving Type', 'required|numeric');
        $this->form_validation->set_rules('contribution_date', 'Contribution Date', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('user/partner/add_contribution/' . $partner_id);
            return;
        }

        // Check if user can add contribution to this partner
        if (!$this->canManagePartner($partner_id)) {
            $this->session->set_flashdata('error', 'You do not have permission to add contributions to this partner.');
            redirect('user/partner');
            return;
        }

        $contribution_data = array(
            'partner_id' => $partner_id,
            'amount' => $this->input->post('amount'),
            'giving_type_id' => $this->input->post('giving_type_id'),
            'contribution_date' => $this->input->post('contribution_date'),
            'payment_method' => $this->input->post('payment_method'),
            'notes' => $this->input->post('notes'),
            'status' => 'completed',
            'created_by' => $this->customlib->getUsersID(),
            'created_at' => date('Y-m-d H:i:s')
        );

        $contribution_id = $this->contribution_model->add($contribution_data);

        if ($contribution_id) {
            $this->session->set_flashdata('success', 'Contribution added successfully!');
            redirect('user/partner/contributions/' . $partner_id);
        } else {
            $this->session->set_flashdata('error', 'Failed to add contribution. Please try again.');
            redirect('user/partner/add_contribution/' . $partner_id);
        }
    }

    // View Contributions for a Partner
    public function contributions($partner_id)
    {
        $student_data = $this->customlib->getLoggedInUserData();
        $role = $this->customlib->getUserRole();

        // Check if user can view contributions for this partner
        if (!$this->canManagePartner($partner_id)) {
            $this->session->set_flashdata('error', 'You do not have permission to view contributions for this partner.');
            redirect('user/partner');
            return;
        }

        $data = [];
        $data['title'] = $this->lang->line('contributions');
        $data['page'] = 'contributions';
        $data['role'] = $role;
        $data['student_data'] = $student_data;
        $data['partner_id'] = $partner_id;
        $data['partner'] = $this->partner_model->get($partner_id);
        $data['contributions'] = $this->contribution_model->getByPartnerId($partner_id);

        // Make libraries available in views
        $data['module_lib'] = $this->module_lib;
        $data['studentmodule_lib'] = $this->studentmodule_lib;
        $data['auth'] = $this->auth;

        $this->load->view('layout/student/header', $data);
        $this->load->view('user/partner/contributions', $data);
        $this->load->view('layout/student/footer', $data);
    }

    // Check if user can manage a partner
    private function canManagePartner($partner_id)
    {
        $role = $this->customlib->getUserRole();
        $partner = $this->partner_model->get($partner_id);

        if (!$partner) {
            return false;
        }

        if ($role == 'student') {
            $student_id = $this->customlib->getStudentSessionUserID();
            return $partner->student_id == $student_id;
        } elseif ($role == 'staff') {
            $staff_id = $this->customlib->getStaffID();
            return $partner->created_by == $staff_id;
        } elseif ($role == 'parent') {
            // Parents can manage partners associated with their children
            $parent_id = $this->customlib->getUsersID();
            $parent = $this->student_model->getParent($parent_id);
            return $partner->email == $parent['guardian_email'] || $partner->mobileno == $parent['guardian_phone'];
        }

        return false;
    }

    // Submit Registration - Redirect to new system
    public function submitRegistration()
    {
        redirect('user/partner_registration');
    }

    // Settings - Redirect to new management system
    public function settings()
    {
        redirect('user/partner_management');
    }

    // Update Settings - Redirect to new management system
    public function updateSettings()
    {
        redirect('user/partner_management');
    }


    // Download Receipt
    public function downloadReceipt($contribution_id)
    {
        $contribution = $this->contribution_model->get($contribution_id);
        
        if (!$contribution) {
            show_404();
        }

        // Check if user has permission to view this receipt
        if (!$this->canViewReceipt($contribution)) {
            $this->session->set_flashdata('error', 'You do not have permission to view this receipt.');
            redirect('user/partner_management');
            return;
        }

        $data['contribution'] = $contribution;
        $data['giving_type'] = $this->type_model->getById($contribution['giving_type_id']);
        
        $this->load->view('user/partner/receipt', $data);
    }

    // Print Receipt
    public function printReceipt($contribution_id)
    {
        $contribution = $this->contribution_model->get($contribution_id);
        
        if (!$contribution) {
            show_404();
        }

        // Check if user has permission to view this receipt
        if (!$this->canViewReceipt($contribution)) {
            $this->session->set_flashdata('error', 'You do not have permission to view this receipt.');
            redirect('user/partner_management');
            return;
        }

        $data['contribution'] = $contribution;
        $data['giving_type'] = $this->type_model->getById($contribution['giving_type_id']);
        
        $this->load->view('user/partner/receipt', $data);
    }

    /**
     * Check if user can view receipt
     */
    private function canViewReceipt($contribution)
    {
        $role = $this->customlib->getUserRole();
        
        if ($role == 'student') {
            $student_id = $this->customlib->getStudentSessionUserID();
            return ($contribution['student_id'] == $student_id);
        } elseif ($role == 'staff') {
            $staff_id = $this->customlib->getStaffID();
            return ($contribution['staff_id'] == $staff_id);
        } elseif ($role == 'admin') {
            return true;
        }
        
        return false;
    }
}