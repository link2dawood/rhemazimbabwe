<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Partner_registration extends Front_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'partner_model',
            'type_model',
            'frequency_model',
            'cms_menu_model',
            'cms_menuitems_model',
            'setting_model',
            'frontcms_setting_model'
        ));
        $this->load->library('form_validation');
        $this->load->helper('url');
    }

    /**
     * Main Partner Registration Page
     */
    public function index()
    {
        $this->data['title'] = 'Become a Partner - Rhema Zimbabwe School';
        
        // Load required menu data
        $menu_list = $this->cms_menu_model->getBySlug('main-menu');
        if ($menu_list) {
            $this->data['main_menus'] = $this->cms_menuitems_model->getMenus($menu_list['id']);
        } else {
            $this->data['main_menus'] = array();
        }
        
        // Load setting data
        $setting_data = $this->setting_model->get();
        $this->data['setting_data'] = $setting_data;
        
        // Provide default giving types and frequencies
        $this->data['giving_types'] = array(
            (object) array('id' => 1, 'name' => 'General Support', 'description' => 'General support for the school'),
            (object) array('id' => 2, 'name' => 'Student Scholarships', 'description' => 'Support for student scholarships'),
            (object) array('id' => 3, 'name' => 'Infrastructure', 'description' => 'Support for school infrastructure')
        );
        
        $this->data['giving_frequencies'] = array(
            (object) array('id' => 1, 'name' => 'One-time', 'days_interval' => null),
            (object) array('id' => 2, 'name' => 'Monthly', 'days_interval' => 30),
            (object) array('id' => 3, 'name' => 'Quarterly', 'days_interval' => 90),
            (object) array('id' => 4, 'name' => 'Annually', 'days_interval' => 365)
        );
        
        $this->data['active_menu'] = 'partner_registration';
        $this->data['page_side_bar'] = false;
        $this->data['page'] = array(
            'title' => 'Become a Partner',
            'meta_title' => 'Partner Registration - Rhema Zimbabwe School',
            'meta_keyword' => 'partner, registration, school, support',
            'meta_description' => 'Join us as a partner to support Rhema Zimbabwe School'
        );
        
        $this->load_theme('pages/partner_registration');
    }
    
    /**
     * Test method to check if basic theme loading works
     */
    public function test()
    {
        // Simple test - just load the view directly
        $this->load->view('themes/shadow_white/pages/partner_registration');
    }
    
    /**
     * Debug method to check theme loading step by step
     */
    public function debug()
    {
        echo "<h1>Debug Information</h1>";
        echo "<p>Theme Path: " . (isset($this->theme_path) ? $this->theme_path : 'NOT SET') . "</p>";
        echo "<p>Front Setting: " . (isset($this->front_setting) ? 'SET' : 'NOT SET') . "</p>";
        echo "<p>School Details: " . (isset($this->school_details) ? 'SET' : 'NOT SET') . "</p>";
        
        // Check database connection
        try {
            $this->db->query("SELECT 1");
            echo "<p>Database Connection: OK</p>";
        } catch (Exception $e) {
            echo "<p>Database Connection: FAILED - " . $e->getMessage() . "</p>";
        }
        
        // Check if front setting is loaded
        if (isset($this->front_setting)) {
            echo "<p>Front Setting Theme: " . $this->front_setting->theme . "</p>";
            echo "<p>Front Setting Active: " . ($this->front_setting->is_active_front_cms ? 'YES' : 'NO') . "</p>";
        }
        
        // Check if theme files exist
        $theme_path = 'themes/shadow_white';
        echo "<p>Layout file exists: " . (file_exists(APPPATH . 'views/' . $theme_path . '/layout.php') ? 'YES' : 'NO') . "</p>";
        echo "<p>Header file exists: " . (file_exists(APPPATH . 'views/' . $theme_path . '/header.php') ? 'YES' : 'NO') . "</p>";
        echo "<p>Footer file exists: " . (file_exists(APPPATH . 'views/' . $theme_path . '/footer.php') ? 'YES' : 'NO') . "</p>";
        echo "<p>Partner registration file exists: " . (file_exists(APPPATH . 'views/' . $theme_path . '/pages/partner_registration.php') ? 'YES' : 'NO') . "</p>";
        
        // Try to load theme with minimal data
        $this->data['title'] = 'Debug Test';
        $this->data['page'] = array(
            'title' => 'Debug Test',
            'meta_title' => 'Debug Test',
            'meta_keyword' => 'debug',
            'meta_description' => 'Debug test page'
        );
        
        try {
            $this->load_theme('pages/partner_registration');
        } catch (Exception $e) {
            echo "<p>Error loading theme: " . $e->getMessage() . "</p>";
        }
    }

    /**
     * Individual Registration Form
     */
    public function individual()
    {
        $this->data['title'] = 'Individual Partner Registration - Rhema Zimbabwe School';
        $this->data['giving_types'] = $this->type_model->getAll();
        $this->data['giving_frequencies'] = $this->frequency_model->getAll();
        $this->data['active_menu'] = 'partner_registration';
        $this->data['page_side_bar'] = false;
        $this->data['page'] = array(
            'title' => 'Individual Partner Registration',
            'meta_title' => 'Individual Partner Registration - Rhema Zimbabwe School',
            'meta_keyword' => 'individual, partner, registration, school, support',
            'meta_description' => 'Register as an individual partner to support Rhema Zimbabwe School'
        );
        
        
        $this->load_theme('pages/partner_registration_individual');
    }

    /**
     * Organization Registration Form
     */
    public function organization()
    {
        $this->data['title'] = 'Organization Partner Registration - Rhema Zimbabwe School';
        $this->data['giving_types'] = $this->type_model->getAll();
        $this->data['giving_frequencies'] = $this->frequency_model->getAll();
        $this->data['active_menu'] = 'partner_registration';
        $this->data['page_side_bar'] = false;
        $this->data['page'] = array(
            'title' => 'Organization Partner Registration',
            'meta_title' => 'Organization Partner Registration - Rhema Zimbabwe School',
            'meta_keyword' => 'organization, partner, registration, school, support',
            'meta_description' => 'Register your organization as a partner to support Rhema Zimbabwe School'
        );
        
        
        $this->load_theme('pages/partner_registration_organization');
    }

    /**
     * Process Individual Registration
     */
    public function process_individual()
    {
        // Validation rules
        $this->form_validation->set_rules('firstname', 'First Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('lastname', 'Last Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
        $this->form_validation->set_rules('mobileno', 'Phone Number', 'trim|required|xss_clean');
        $this->form_validation->set_rules('address', 'Billing Address', 'trim|required|xss_clean');
        $this->form_validation->set_rules('city', 'City', 'trim|required|xss_clean');
        $this->form_validation->set_rules('country', 'Country', 'trim|required|xss_clean');
        $this->form_validation->set_rules('giving_types[]', 'Giving Types', 'required');
        $this->form_validation->set_rules('giving_frequency_id', 'Giving Frequency', 'required');
        
        // Optional account creation
        if ($this->input->post('create_account')) {
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[password]');
        }

        if ($this->form_validation->run() == false) {
            $this->individual();
            return;
        }

        // Prepare partner data
        $partner_data = array(
            'partner_code' => $this->generatePartnerCode(),
            'account_type' => 'individual',
            'firstname' => $this->input->post('firstname'),
            'lastname' => $this->input->post('lastname'),
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
            'status' => 'pending',
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s')
        );

        // Handle account creation
        if ($this->input->post('create_account')) {
            $partner_data['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
            $partner_data['account_creation_status'] = 'completed';
        } else {
            $partner_data['account_creation_status'] = 'skipped';
        }

        // Insert partner
        $partner_id = $this->partner_model->add($partner_data);

        // Check if partner_id is a valid integer (success)
        if (is_numeric($partner_id) && $partner_id > 0) {
            // Save giving types
            $this->saveGivingTypes($partner_id);

            // Send confirmation email
            $this->sendConfirmationEmail($partner_data, $partner_id);

            $this->session->set_flashdata('success', 'Registration submitted successfully! You will receive a confirmation email shortly.');
            redirect('partner_registration/success');
        } elseif (is_array($partner_id) && isset($partner_id['error']) && $partner_id['error'] === true) {
            // Model returned a validation error with specific message
            $this->session->set_flashdata('error', $partner_id['message']);
            $this->individual();
        } else {
            // Unknown error
            log_message('error', 'Partner registration failed with unknown error. Data: ' . json_encode($partner_data));
            $this->session->set_flashdata('error', 'Registration failed due to an unexpected error. Please try again or contact support.');
            $this->individual();
        }
    }

    /**
     * Process Organization Registration
     */
    public function process_organization()
    {
        // Validation rules
        $this->form_validation->set_rules('organization_name', 'Organization Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('organization_type', 'Organization Type', 'trim|required|xss_clean');
        $this->form_validation->set_rules('firstname', 'Contact First Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('lastname', 'Contact Last Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
        $this->form_validation->set_rules('mobileno', 'Phone Number', 'trim|required|xss_clean');
        $this->form_validation->set_rules('address', 'Billing Address', 'trim|required|xss_clean');
        $this->form_validation->set_rules('city', 'City', 'trim|required|xss_clean');
        $this->form_validation->set_rules('country', 'Country', 'trim|required|xss_clean');
        $this->form_validation->set_rules('giving_types[]', 'Giving Types', 'required');
        $this->form_validation->set_rules('giving_frequency_id', 'Giving Frequency', 'required');
        
        // Optional account creation
        if ($this->input->post('create_account')) {
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[password]');
        }

        if ($this->form_validation->run() == false) {
            $this->organization();
            return;
        }

        // Prepare partner data
        $partner_data = array(
            'partner_code' => $this->generatePartnerCode(),
            'account_type' => 'organization',
            'organization_name' => $this->input->post('organization_name'),
            'organization_type' => $this->input->post('organization_type'),
            'firstname' => $this->input->post('firstname'),
            'lastname' => $this->input->post('lastname'),
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
            'status' => 'pending',
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s')
        );

        // Handle account creation
        if ($this->input->post('create_account')) {
            $partner_data['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
            $partner_data['account_creation_status'] = 'completed';
        } else {
            $partner_data['account_creation_status'] = 'skipped';
        }

        // Insert partner
        $partner_id = $this->partner_model->add($partner_data);

        // Check if partner_id is a valid integer (success)
        if (is_numeric($partner_id) && $partner_id > 0) {
            // Save giving types
            $this->saveGivingTypes($partner_id);

            // Send confirmation email
            $this->sendConfirmationEmail($partner_data, $partner_id);

            $this->session->set_flashdata('success', 'Registration submitted successfully! You will receive a confirmation email shortly.');
            redirect('partner_registration/success');
        } elseif (is_array($partner_id) && isset($partner_id['error']) && $partner_id['error'] === true) {
            // Model returned a validation error with specific message
            $this->session->set_flashdata('error', $partner_id['message']);
            $this->organization();
        } else {
            // Unknown error
            log_message('error', 'Partner registration failed with unknown error. Data: ' . json_encode($partner_data));
            $this->session->set_flashdata('error', 'Registration failed due to an unexpected error. Please try again or contact support.');
            $this->organization();
        }
    }

    /**
     * Registration Success Page
     */
    public function success()
    {
        $this->data['title'] = 'Registration Successful - Rhema Zimbabwe School';
        $this->data['active_menu'] = 'partner_registration';
        $this->data['page_side_bar'] = false;
        $this->data['page'] = array(
            'title' => 'Registration Successful',
            'meta_title' => 'Registration Successful - Rhema Zimbabwe School',
            'meta_keyword' => 'registration, successful, partner, school',
            'meta_description' => 'Your partner registration has been submitted successfully'
        );
        
        
        $this->load_theme('pages/partner_registration_success');
    }

    /**
     * Check if user is logged in and redirect to portal
     */
    public function check_login()
    {
        // Check if user is logged in as student/staff
        if ($this->session->userdata('student_id') || $this->session->userdata('staff_id')) {
            $this->session->set_flashdata('info', 'You are already logged in. Please register as a partner through your portal.');
            redirect('user/partner/register');
        }
        
        // Continue with normal registration
        $this->index();
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
     * Send confirmation email
     */
    private function sendConfirmationEmail($partner_data, $partner_id)
    {
        // Email configuration would go here
        // This is a placeholder for email functionality
        $message = "Dear " . $partner_data['firstname'] . ",\n\n";
        $message .= "Thank you for registering as a partner with Rhema Zimbabwe School.\n";
        $message .= "Your partner code is: " . $partner_data['partner_code'] . "\n\n";
        $message .= "We will review your registration and contact you shortly.\n\n";
        $message .= "Best regards,\nRhema Zimbabwe School";
        
        // In a real implementation, you would send this email
        // mail($partner_data['email'], 'Partner Registration Confirmation', $message);
    }
}
