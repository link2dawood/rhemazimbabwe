<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Partner extends Student_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'partner_model',
            'contribution_model',
            'giving_type_model',
            'giving_frequency_model'
        ));
        $this->load->library('Customlib');

        // Set active menu
        $this->session->set_userdata('top_menu', 'partner');

        // Load language file
        $this->lang->load('partners', $this->session->userdata('student')['language']['language']);
    }

    // Partner Dashboard
    public function index()
    {
        $data = [];
        $data['title'] = $this->lang->line('partner_dashboard');
        $data['page'] = 'partner_dashboard';

        $student_data = $this->customlib->getLoggedInUserData();
        $role = $this->customlib->getUserRole();

        $data['role'] = $role;
        $data['student_data'] = $student_data;

        // Get partner records linked to this user
        if ($role == 'student') {
            $student_id = $this->customlib->getStudentSessionUserID();
            $student = $this->student_model->get($student_id);

            // Check by student_id, email, or phone
            $partners = $this->partner_model->getPartnersByStudentOrContact(
                $student_id,
                $student['email'],
                $student['mobileno']
            );
        } elseif ($role == 'parent') {
            $parent_id = $this->customlib->getUsersID();
            $parent = $this->student_model->getParent($parent_id);

            // Check by email or phone
            $partners = $this->partner_model->getPartnersByContact(
                $parent['guardian_email'] ?? '',
                $parent['guardian_phone'] ?? ''
            );
        } else {
            // Staff
            $staff_id = $this->customlib->getStaffID();
            $staff = $this->staff_model->get($staff_id);

            // Check by staff_id or email
            $partners = $this->partner_model->getPartnersByStaffOrContact(
                $staff_id,
                $staff['email'] ?? ''
            );
        }

        $data['partners'] = $partners;

        // Get statistics if partner exists
        if (!empty($partners)) {
            $partner_ids = array_column($partners, 'id');
            $data['statistics'] = $this->getPartnerStatistics($partner_ids);
        } else {
            $data['statistics'] = null;
        }

        $this->load->view('layout/student/header', $data);
        $this->load->view('user/partner/dashboard', $data);
        $this->load->view('layout/student/footer', $data);
    }

    // Partner Registration Form
    public function register()
    {
        $data = [];
        $data['title'] = $this->lang->line('become_a_partner');
        $data['page'] = 'partner_register';

        $student_data = $this->customlib->getLoggedInUserData();
        $role = $this->customlib->getUserRole();

        $data['role'] = $role;
        $data['student_data'] = $student_data;

        // Get giving types and frequencies
        $data['giving_types'] = $this->giving_type_model->get();
        $data['giving_frequencies'] = $this->giving_frequency_model->get();

        // Pre-fill data based on role
        if ($role == 'student') {
            $student_id = $this->customlib->getStudentSessionUserID();
            $student = $this->student_model->get($student_id);
            $data['prefill'] = [
                'firstname' => $student['firstname'],
                'lastname' => $student['lastname'],
                'email' => $student['email'],
                'mobileno' => $student['mobileno'],
                'address' => $student['permanent_address'] ?? '',
                'student_id' => $student_id
            ];
        } elseif ($role == 'parent') {
            $parent_id = $this->customlib->getUsersID();
            $parent = $this->student_model->getParent($parent_id);
            $data['prefill'] = [
                'firstname' => $parent['guardian_name'] ?? '',
                'lastname' => '',
                'email' => $parent['guardian_email'] ?? '',
                'mobileno' => $parent['guardian_phone'] ?? '',
                'address' => $parent['guardian_address'] ?? ''
            ];
        } else {
            // Staff
            $staff_id = $this->customlib->getStaffID();
            $staff = $this->staff_model->get($staff_id);
            $data['prefill'] = [
                'firstname' => $staff['name'] ?? '',
                'lastname' => '',
                'email' => $staff['email'] ?? '',
                'mobileno' => $staff['contact_no'] ?? '',
                'address' => $staff['address'] ?? '',
                'staff_id' => $staff_id
            ];
        }

        $this->load->view('layout/student/header', $data);
        $this->load->view('user/partner/register', $data);
        $this->load->view('layout/student/footer', $data);
    }

    // Submit Partner Registration
    public function submitRegistration()
    {
        // Validation rules
        $this->form_validation->set_rules('firstname', $this->lang->line('first_name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('lastname', $this->lang->line('last_name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|required|valid_email|xss_clean');
        $this->form_validation->set_rules('mobileno', $this->lang->line('phone'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('account_type', 'Account Type', 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            echo json_encode(['status' => 'error', 'message' => validation_errors()]);
            return;
        }

        // Prepare data
        $partner_data = [
            'partner_code' => $this->generatePartnerCode(),
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
            'student_id' => $this->input->post('student_id'),
            'staff_id' => $this->input->post('staff_id'),
            'status' => 'inactive', // Pending admin approval
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Insert partner
        $partner_id = $this->partner_model->add($partner_data);

        if ($partner_id) {
            // Send confirmation email
            $this->sendConfirmationEmail($partner_data, $partner_id);

            echo json_encode([
                'status' => 'success',
                'message' => $this->lang->line('partner_added_successfully'),
                'partner_id' => $partner_id,
                'partner_code' => $partner_data['partner_code']
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => $this->lang->line('error_occurred')
            ]);
        }
    }

    // Giving Settings
    public function settings()
    {
        $data = [];
        $data['title'] = $this->lang->line('giving_settings');
        $data['page'] = 'partner_settings';

        $partner_id = $this->input->get('partner_id');

        if (!$partner_id) {
            redirect(base_url('user/partner'));
        }

        // Verify partner belongs to logged-in user
        if (!$this->verifyPartnerOwnership($partner_id)) {
            $this->session->set_flashdata('error', $this->lang->line('unauthorized'));
            redirect(base_url('user/partner'));
        }

        $data['partner'] = $this->partner_model->get($partner_id);
        $data['giving_types'] = $this->giving_type_model->get();
        $data['giving_frequencies'] = $this->giving_frequency_model->get();

        $this->load->view('layout/student/header', $data);
        $this->load->view('user/partner/settings', $data);
        $this->load->view('layout/student/footer', $data);
    }

    // Update Giving Settings
    public function updateSettings()
    {
        $partner_id = $this->input->post('partner_id');

        if (!$this->verifyPartnerOwnership($partner_id)) {
            echo json_encode(['status' => 'error', 'message' => $this->lang->line('unauthorized')]);
            return;
        }

        $update_data = [
            'giving_type_id' => $this->input->post('giving_type_id'),
            'giving_frequency_id' => $this->input->post('giving_frequency_id'),
            'contribution_amount' => $this->input->post('contribution_amount'),
            'currency' => $this->input->post('currency'),
            'notes' => $this->input->post('notes')
        ];

        if ($this->partner_model->update($partner_id, $update_data)) {
            echo json_encode([
                'status' => 'success',
                'message' => $this->lang->line('partner_updated_successfully')
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => $this->lang->line('error_occurred')
            ]);
        }
    }

    // Contribution History
    public function contributions()
    {
        $data = [];
        $data['title'] = $this->lang->line('contribution_history');
        $data['page'] = 'partner_contributions';

        $partner_id = $this->input->get('partner_id');

        if (!$partner_id) {
            redirect(base_url('user/partner'));
        }

        // Verify partner belongs to logged-in user
        if (!$this->verifyPartnerOwnership($partner_id)) {
            $this->session->set_flashdata('error', $this->lang->line('unauthorized'));
            redirect(base_url('user/partner'));
        }

        $data['partner'] = $this->partner_model->get($partner_id);
        $data['contributions'] = $this->contribution_model->getContributionsByPartner($partner_id);
        $data['total_contributed'] = $this->contribution_model->getTotalContributed($partner_id);

        $this->load->view('layout/student/header', $data);
        $this->load->view('user/partner/contributions', $data);
        $this->load->view('layout/student/footer', $data);
    }

    // Download Receipt
    public function downloadReceipt($contribution_id)
    {
        $contribution = $this->contribution_model->get($contribution_id);

        if (!$contribution) {
            $this->session->set_flashdata('error', $this->lang->line('no_record_found'));
            redirect(base_url('user/partner/contributions?partner_id=' . $this->input->get('partner_id')));
        }

        // Verify ownership
        if (!$this->verifyPartnerOwnership($contribution['partner_id'])) {
            $this->session->set_flashdata('error', $this->lang->line('unauthorized'));
            redirect(base_url('user/partner'));
        }

        $data['contribution'] = $contribution;
        $data['partner'] = $this->partner_model->get($contribution['partner_id']);
        $data['school_setting'] = $this->setting_model->getSetting();

        $this->load->view('user/partner/receipt', $data);
    }

    // Print Receipt
    public function printReceipt($contribution_id)
    {
        $this->downloadReceipt($contribution_id);
    }

    // Private Helper Methods
    private function generatePartnerCode()
    {
        // Generate unique partner code: P-YYYYMMDD-XXXX
        $prefix = 'P-' . date('Ymd') . '-';
        $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $code = $prefix . $random;

        // Check if code exists, regenerate if it does
        if ($this->partner_model->getByPartnerCode($code)) {
            return $this->generatePartnerCode();
        }

        return $code;
    }

    private function verifyPartnerOwnership($partner_id)
    {
        $partner = $this->partner_model->get($partner_id);

        if (!$partner) {
            return false;
        }

        $role = $this->customlib->getUserRole();

        if ($role == 'student') {
            $student_id = $this->customlib->getStudentSessionUserID();
            $student = $this->student_model->get($student_id);

            // Check if partner belongs to this student
            return ($partner['student_id'] == $student_id ||
                    $partner['email'] == $student['email'] ||
                    $partner['mobileno'] == $student['mobileno']);
        } elseif ($role == 'parent') {
            $parent_id = $this->customlib->getUsersID();
            $parent = $this->student_model->getParent($parent_id);

            // Check if partner belongs to this parent
            return ($partner['email'] == $parent['guardian_email'] ||
                    $partner['mobileno'] == $parent['guardian_phone']);
        } else {
            // Staff
            $staff_id = $this->customlib->getStaffID();
            $staff = $this->staff_model->get($staff_id);

            // Check if partner belongs to this staff
            return ($partner['staff_id'] == $staff_id ||
                    $partner['email'] == $staff['email']);
        }

        return false;
    }

    private function getPartnerStatistics($partner_ids)
    {
        $stats = [
            'total_partners' => count($partner_ids),
            'active_partners' => 0,
            'total_contributed' => 0,
            'this_year_contributed' => 0,
            'pending_status' => 0
        ];

        foreach ($partner_ids as $partner_id) {
            $partner = $this->partner_model->get($partner_id);

            if ($partner['status'] == 'active') {
                $stats['active_partners']++;
            } elseif ($partner['status'] == 'inactive') {
                $stats['pending_status']++;
            }

            $total = $this->contribution_model->getTotalContributed($partner_id);
            $stats['total_contributed'] += $total;

            $this_year = $this->contribution_model->getYearContributed($partner_id, date('Y'));
            $stats['this_year_contributed'] += $this_year;
        }

        return $stats;
    }

    private function sendConfirmationEmail($partner_data, $partner_id)
    {
        $this->load->library('email');

        $config = [
            'protocol' => 'mail',
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'wordwrap' => TRUE
        ];

        $this->email->initialize($config);
        $this->email->from('noreply@rhemazimbabwe.com', 'Rhema Zimbabwe');
        $this->email->to($partner_data['email']);
        $this->email->subject('Partner Registration Confirmation - Rhema Zimbabwe');

        $partner_data['base_url'] = base_url();
        $message = $this->load->view('user/partner/email/confirmation', ['partner_data' => $partner_data], true);

        $this->email->message($message);
        $this->email->send();
    }
}
