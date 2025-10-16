<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Partner_reports extends Student_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'partner_model',
            'contribution_model',
            'type_model',
            'frequency_model'
        ));
        $this->load->library('Customlib');

        // Set active menu
        $this->session->set_userdata('top_menu', 'partner');
    }

    /**
     * Partner Reports Index for User Portal
     */
    public function index()
    {
        $data = [];
        $data['title'] = 'Partner Reports';
        $data['page'] = 'partner_reports';

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

        $this->load->view('layout/student/header', $data);
        $this->load->view('user/partner_reports/index', $data);
        $this->load->view('layout/student/footer', $data);
    }

    /**
     * Partner Information Report for User Portal
     */
    public function partner_information()
    {
        $data = [];
        $data['title'] = 'My Partner Information';
        $data['page'] = 'partner_information_report';

        $student_data = $this->customlib->getLoggedInUserData();
        $role = $this->customlib->getUserRole();

        $data['role'] = $role;
        $data['student_data'] = $student_data;

        // Get partner records linked to this user
        if ($role == 'student') {
            $student_id = $this->customlib->getStudentSessionUserID();
            $student = $this->student_model->get($student_id);

            $partners = $this->partner_model->getPartnersByStudentOrContact(
                $student_id,
                $student['email'],
                $student['mobileno']
            );
        } elseif ($role == 'parent') {
            $parent_id = $this->customlib->getUsersID();
            $parent = $this->student_model->getParent($parent_id);

            $partners = $this->partner_model->getPartnersByContact(
                $parent['guardian_email'] ?? '',
                $parent['guardian_phone'] ?? ''
            );
        } else {
            $staff_id = $this->customlib->getStaffID();
            $staff = $this->staff_model->get($staff_id);

            $partners = $this->partner_model->getPartnersByStaffOrContact(
                $staff_id,
                $staff['email'] ?? ''
            );
        }

        $data['partners'] = $partners;

        $this->load->view('layout/student/header', $data);
        $this->load->view('user/partner_reports/partner_information', $data);
        $this->load->view('layout/student/footer', $data);
    }

    /**
     * Partner Statement Report for User Portal
     */
    public function partner_statement()
    {
        $data = [];
        $data['title'] = 'My Partner Statement';
        $data['page'] = 'partner_statement_report';

        $student_data = $this->customlib->getLoggedInUserData();
        $role = $this->customlib->getUserRole();

        $data['role'] = $role;
        $data['student_data'] = $student_data;

        // Get date range
        $start_date = $this->input->post('start_date') ?: date('Y-m-01');
        $end_date = $this->input->post('end_date') ?: date('Y-m-t');
        
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        // Get partner records linked to this user
        if ($role == 'student') {
            $student_id = $this->customlib->getStudentSessionUserID();
            $student = $this->student_model->get($student_id);

            $partners = $this->partner_model->getPartnersByStudentOrContact(
                $student_id,
                $student['email'],
                $student['mobileno']
            );
        } elseif ($role == 'parent') {
            $parent_id = $this->customlib->getUsersID();
            $parent = $this->student_model->getParent($parent_id);

            $partners = $this->partner_model->getPartnersByContact(
                $parent['guardian_email'] ?? '',
                $parent['guardian_phone'] ?? ''
            );
        } else {
            $staff_id = $this->customlib->getStaffID();
            $staff = $this->staff_model->get($staff_id);

            $partners = $this->partner_model->getPartnersByStaffOrContact(
                $staff_id,
                $staff['email'] ?? ''
            );
        }

        $data['partners'] = $partners;

        // Get statement data for each partner
        $data['partner_statements'] = [];
        foreach ($partners as $partner) {
            $contributions = $this->contribution_model->getByPartnerIdAndDateRange($partner['id'], $start_date, $end_date);
            $statement_summary = $this->getPartnerStatementSummary($partner, $start_date, $end_date);
            
            $data['partner_statements'][] = [
                'partner' => $partner,
                'contributions' => $contributions,
                'statement_summary' => $statement_summary
            ];
        }

        $this->load->view('layout/student/header', $data);
        $this->load->view('user/partner_reports/partner_statement', $data);
        $this->load->view('layout/student/footer', $data);
    }

    /**
     * Get partner statement summary
     */
    private function getPartnerStatementSummary($partner, $start_date, $end_date)
    {
        // Get contributions in period
        $contributions = $this->contribution_model->getByPartnerIdAndDateRange($partner['id'], $start_date, $end_date);
        $total_contributed = 0;
        foreach ($contributions as $contribution) {
            $total_contributed += $contribution->amount;
        }
        
        // Calculate expected amount based on frequency
        $expected_amount = $this->calculateExpectedAmount($partner, $start_date, $end_date);
        
        // Get opening balance (contributions before start date)
        $opening_balance = $this->contribution_model->getTotalByPartnerAndDateRange($partner['id'], null, $start_date);
        
        // Get closing balance (all contributions up to end date)
        $closing_balance = $this->contribution_model->getTotalByPartnerAndDateRange($partner['id'], null, $end_date);
        
        return array(
            'opening_balance' => $opening_balance,
            'total_contributed' => $total_contributed,
            'expected_amount' => $expected_amount,
            'closing_balance' => $closing_balance,
            'balance_status' => $this->getBalanceStatus($expected_amount, $closing_balance)
        );
    }

    /**
     * Calculate expected amount for partner in date range
     */
    private function calculateExpectedAmount($partner, $start_date, $end_date)
    {
        if (!$partner['contribution_amount'] || !$partner['giving_frequency_id']) {
            return 0;
        }
        
        $frequency = $this->frequency_model->getById($partner['giving_frequency_id']);
        if (!$frequency || !$frequency->days_interval) {
            return $partner['contribution_amount'];
        }
        
        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = $start->diff($end);
        $days = $interval->days + 1;
        
        $periods = floor($days / $frequency->days_interval);
        return $periods * $partner['contribution_amount'];
    }

    /**
     * Get balance status
     */
    private function getBalanceStatus($expected, $actual)
    {
        if ($actual >= $expected) {
            return 'Up to Date';
        } elseif ($actual >= ($expected * 0.75)) {
            return 'Good';
        } elseif ($actual >= ($expected * 0.5)) {
            return 'Behind';
        } else {
            return 'Critical';
        }
    }
}
