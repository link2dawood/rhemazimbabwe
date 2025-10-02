<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Partnerportal extends Front_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('partner_auth');
        $this->load->model(array('partner_model', 'contribution_model', 'type_model', 'frequency_model'));
        $this->load->helper(array('url', 'form'));
        $this->load->library(array('form_validation', 'session', 'module_lib', 'customlib', 'captchalib', 'cart'));

        // Initialize data array
        if (!isset($this->data)) {
            $this->data = array();
        }

        // Load setting data for themes
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
    }

    // Login Page
    public function login()
    {
        // If already logged in, redirect to dashboard
        if ($this->partner_auth->is_logged_in()) {
            redirect('partnerdashboard');
        }

        $this->data['title'] = 'Partner Login';
        $this->data['active_menu'] = 'partner-login';
        $this->data['page'] = array(
            'title' => 'Partner Login',
            'meta_title' => 'Partner Login',
            'meta_keyword' => 'partner, login',
            'meta_description' => 'Partner Portal Login'
        );
        $this->data['page_side_bar'] = false;

        $this->load_theme('partnerportal/login', true);
    }

    // Process Login
    public function do_login()
    {
        $this->form_validation->set_rules('username', 'Username/Email', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => false, 'message' => validation_errors()]);
            return;
        }

        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $remember = $this->input->post('remember') == '1';

        $result = $this->partner_auth->login($username, $password, $remember);

        if ($result['status']) {
            // Check for redirect URL
            $redirect = $this->session->userdata('partner_redirect_url');
            if ($redirect) {
                $this->session->unset_userdata('partner_redirect_url');
                $result['redirect'] = $redirect;
            } else {
                $result['redirect'] = base_url('partnerdashboard');
            }
        }

        echo json_encode($result);
    }

    // Registration Page
    public function register()
    {
        // If already logged in, redirect to dashboard
        if ($this->partner_auth->is_logged_in()) {
            redirect('partnerdashboard');
        }

        $this->data['title'] = 'Partner Registration';
        $this->data['active_menu'] = 'partner-registration';
        $this->data['giving_types'] = $this->type_model->getAll();
        $this->data['giving_frequencies'] = $this->frequency_model->getAll();
        $this->data['page'] = array(
            'title' => 'Partner Registration',
            'meta_title' => 'Partner Registration',
            'meta_keyword' => 'partner, registration',
            'meta_description' => 'Partner Portal Registration'
        );
        $this->data['page_side_bar'] = false;

        $this->load_theme('partnerportal/register', true);
    }

    // Process Registration
    public function do_register()
    {
        $this->form_validation->set_rules('firstname', 'First Name', 'required|trim');
        $this->form_validation->set_rules('lastname', 'Last Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
        $this->form_validation->set_rules('mobileno', 'Phone', 'required|trim');
        $this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric|min_length[4]|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => false, 'message' => validation_errors()]);
            return;
        }

        $data = [
            'partner_code' => $this->partner_model->generatePartnerCode(),
            'account_type' => $this->input->post('account_type'),
            'organization_name' => $this->input->post('organization_name'),
            'organization_type' => $this->input->post('organization_type'),
            'firstname' => $this->input->post('firstname'),
            'lastname' => $this->input->post('lastname'),
            'email' => $this->input->post('email'),
            'mobileno' => $this->input->post('mobileno'),
            'address' => $this->input->post('address'),
            'city' => $this->input->post('city'),
            'state' => $this->input->post('state'),
            'country' => $this->input->post('country') ?? 'Zimbabwe',
            'zip_code' => $this->input->post('zip_code'),
            'giving_type_id' => $this->input->post('giving_type_id'),
            'giving_frequency_id' => $this->input->post('giving_frequency_id'),
            'contribution_amount' => $this->input->post('contribution_amount'),
            'currency' => $this->input->post('currency') ?? 'USD',
            'notes' => $this->input->post('notes'),
            'username' => $this->input->post('username'),
            'password' => $this->input->post('password'),
            'status' => 'inactive' // Pending admin approval
        ];

        $result = $this->partner_auth->register($data);
        echo json_encode($result);
    }

    // Logout
    public function logout()
    {
        $this->partner_auth->logout();
        $this->session->set_flashdata('success', 'You have been logged out successfully');
        redirect('partnerportal/login');
    }

    // Forgot Password
    public function forgot_password()
    {
        $this->data['title'] = 'Forgot Password';
        $this->data['active_menu'] = 'partner-forgot-password';
        $this->data['page'] = array(
            'title' => 'Forgot Password',
            'meta_title' => 'Forgot Password',
            'meta_keyword' => 'partner, forgot password',
            'meta_description' => 'Partner Portal Forgot Password'
        );
        $this->data['page_side_bar'] = false;
        $this->load_theme('partnerportal/forgot_password', true);
    }

    // Process Forgot Password
    public function do_forgot_password()
    {
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => false, 'message' => validation_errors()]);
            return;
        }

        $email = $this->input->post('email');
        $result = $this->partner_auth->request_password_reset($email);
        echo json_encode($result);
    }

    // Reset Password Page
    public function reset_password($token = '')
    {
        if (empty($token)) {
            show_404();
        }

        $this->data['title'] = 'Reset Password';
        $this->data['active_menu'] = 'partner-reset-password';
        $this->data['token'] = $token;
        $this->data['page'] = array(
            'title' => 'Reset Password',
            'meta_title' => 'Reset Password',
            'meta_keyword' => 'partner, reset password',
            'meta_description' => 'Partner Portal Reset Password'
        );
        $this->data['page_side_bar'] = false;
        $this->load_theme('partnerportal/reset_password', true);
    }

    // Process Reset Password
    public function do_reset_password()
    {
        $this->form_validation->set_rules('token', 'Token', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => false, 'message' => validation_errors()]);
            return;
        }

        $token = $this->input->post('token');
        $password = $this->input->post('password');

        $result = $this->partner_auth->reset_password($token, $password);
        echo json_encode($result);
    }

    // Verify Email
    public function verify_email($token = '')
    {
        if (empty($token)) {
            show_404();
        }

        $result = $this->partner_auth->verify_email($token);

        if ($result['status']) {
            $this->session->set_flashdata('success', $result['message']);
        } else {
            $this->session->set_flashdata('error', $result['message']);
        }

        redirect('partnerportal/login');
    }
}
