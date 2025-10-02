<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Partner_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('partner_auth', 'studentmodule_lib', 'customlib'));
        $this->load->model('partner_model');
        $this->load->helper('url');

        // Check if partner is logged in
        if (!$this->partner_auth->is_logged_in()) {
            // Store the intended URL
            $this->session->set_userdata('partner_redirect_url', current_url());

            // Redirect to login
            redirect('partnerportal/login');
        }

        // Get partner data
        $this->partner_data = $this->partner_auth->get_partner_data();

        // Check if account is active
        if ($this->partner_data['status'] != 'active') {
            $this->session->set_flashdata('error', 'Your account is not active. Please contact support.');
            redirect('partnerportal/login');
        }
    }
}
