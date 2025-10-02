<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Partnerreports extends Admin_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('Partner_model');
        $this->load->model('Contribution_model');
        $this->load->model('Type_model');
        $this->load->model('Frequency_model');
        $this->load->helper('url');
        $this->load->library('datatables');
    }

    /**
     * Reports index page - shows all available reports
     */
    public function index() {
        if (!$this->rbac->hasPrivilege('partner_reports', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Partners');
        $this->session->set_userdata('sub_menu', 'partner_reports');

        $data['title'] = 'Partner Reports';

        $this->load->view('layout/header', $data);
        $this->load->view('admin/partnerreports/index', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * Partner Information Report
     * Shows comprehensive list of all partners with their details
     */
    public function partner_information() {
        if (!$this->rbac->hasPrivilege('partner_reports', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Partners');
        $this->session->set_userdata('sub_menu', 'partner_reports');

        $data['title'] = 'Partner Information Report';
        $data['giving_types'] = $this->Type_model->getAll();
        $data['giving_frequencies'] = $this->Frequency_model->getAll();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/partnerreports/partner_information', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * Get partner information data for DataTable
     */
    public function getPartnerInformationData() {
        if (!$this->rbac->hasPrivilege('partner_reports', 'can_view')) {
            access_denied();
        }

        $filters = [
            'status' => $this->input->post('status'),
            'giving_type_id' => $this->input->post('giving_type_id'),
            'giving_frequency_id' => $this->input->post('giving_frequency_id'),
            'date_from' => $this->input->post('date_from'),
            'date_to' => $this->input->post('date_to')
        ];

        $partners = $this->Partner_model->getAll($filters);
        $result = [];

        foreach ($partners as $partner) {
            $total_contributions = $this->Contribution_model->getTotalByPartner($partner->id);

            $result[] = [
                $partner->partner_code,
                $partner->firstname . ' ' . $partner->lastname,
                $partner->email,
                $partner->mobileno,
                $partner->type_name ? $partner->type_name : '-',
                $partner->frequency_name ? $partner->frequency_name : '-',
                $partner->currency . ' ' . number_format($partner->contribution_amount, 2),
                $partner->currency . ' ' . number_format($total_contributions, 2),
                $partner->start_date ? date($this->customlib->getSchoolDateFormat(), strtotime($partner->start_date)) : '-',
                ucfirst($partner->status)
            ];
        }

        echo json_encode(['data' => $result]);
    }

    /**
     * Giving Collection By Type Report
     * Shows contributions grouped by giving types
     */
    public function giving_collection_by_type() {
        if (!$this->rbac->hasPrivilege('partner_reports', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Partners');
        $this->session->set_userdata('sub_menu', 'partner_reports');

        $data['title'] = 'Giving Collection By Type Report';
        $data['giving_types'] = $this->Type_model->getAll();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/partnerreports/giving_collection_by_type', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * Get giving collection by type data
     */
    public function getGivingCollectionByTypeData() {
        if (!$this->rbac->hasPrivilege('partner_reports', 'can_view')) {
            access_denied();
        }

        $filters = [
            'giving_type_id' => $this->input->post('giving_type_id'),
            'date_from' => $this->input->post('date_from'),
            'date_to' => $this->input->post('date_to'),
            'status' => 'completed'
        ];

        // Get all contributions grouped by type
        $contributions = $this->Contribution_model->getAll($filters);

        // Group by type
        $grouped = [];
        foreach ($contributions as $contribution) {
            $type_name = $contribution->type_name ? $contribution->type_name : 'No Type';

            if (!isset($grouped[$type_name])) {
                $grouped[$type_name] = [
                    'type' => $type_name,
                    'count' => 0,
                    'total_amount' => 0,
                    'partners' => []
                ];
            }

            $grouped[$type_name]['count']++;
            $grouped[$type_name]['total_amount'] += $contribution->amount;

            if (!in_array($contribution->partner_id, $grouped[$type_name]['partners'])) {
                $grouped[$type_name]['partners'][] = $contribution->partner_id;
            }
        }

        $result = [];
        foreach ($grouped as $type_name => $data) {
            $result[] = [
                $type_name,
                count($data['partners']),
                $data['count'],
                'USD ' . number_format($data['total_amount'], 2)
            ];
        }

        echo json_encode(['data' => $result]);
    }

    /**
     * Partner Statement Report
     * Shows detailed transaction history for a specific partner
     */
    public function partner_statement() {
        if (!$this->rbac->hasPrivilege('partner_reports', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Partners');
        $this->session->set_userdata('sub_menu', 'partner_reports');

        $data['title'] = 'Partner Statement Report';
        $data['partners'] = $this->Partner_model->getAll(['status' => 'active']);

        $this->load->view('layout/header', $data);
        $this->load->view('admin/partnerreports/partner_statement', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * Get partner statement data
     */
    public function getPartnerStatementData() {
        if (!$this->rbac->hasPrivilege('partner_reports', 'can_view')) {
            access_denied();
        }

        $partner_id = $this->input->post('partner_id');
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');

        if (!$partner_id) {
            echo json_encode(['data' => [], 'summary' => []]);
            return;
        }

        $partner = $this->Partner_model->getById($partner_id);

        $filters = [
            'partner_id' => $partner_id,
            'date_from' => $date_from,
            'date_to' => $date_to
        ];

        $contributions = $this->Contribution_model->getAll($filters);

        $result = [];
        $total_amount = 0;
        $completed_amount = 0;
        $pending_amount = 0;

        foreach ($contributions as $contribution) {
            $result[] = [
                date($this->customlib->getSchoolDateFormat(), strtotime($contribution->contribution_date)),
                $contribution->receipt_no,
                $contribution->notes ? $contribution->notes : '-',
                ucfirst(str_replace('_', ' ', $contribution->payment_method)),
                $contribution->currency . ' ' . number_format($contribution->amount, 2),
                '<span class="label label-' . ($contribution->status == 'completed' ? 'success' : 'warning') . '">' . ucfirst($contribution->status) . '</span>'
            ];

            $total_amount += $contribution->amount;
            if ($contribution->status == 'completed') {
                $completed_amount += $contribution->amount;
            } else {
                $pending_amount += $contribution->amount;
            }
        }

        $summary = [
            'partner_name' => $partner->firstname . ' ' . $partner->lastname,
            'partner_code' => $partner->partner_code,
            'total_transactions' => count($contributions),
            'total_amount' => number_format($total_amount, 2),
            'completed_amount' => number_format($completed_amount, 2),
            'pending_amount' => number_format($pending_amount, 2),
            'currency' => $partner->currency
        ];

        echo json_encode(['data' => $result, 'summary' => $summary]);
    }

    /**
     * Balance Giving Report with Remark
     * Shows partners with outstanding/expected contributions
     */
    public function balance_giving_report() {
        if (!$this->rbac->hasPrivilege('partner_reports', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Partners');
        $this->session->set_userdata('sub_menu', 'partner_reports');

        $data['title'] = 'Balance Giving Report';
        $data['giving_types'] = $this->Type_model->getAll();
        $data['giving_frequencies'] = $this->Frequency_model->getAll();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/partnerreports/balance_giving_report', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * Get balance giving report data
     */
    public function getBalanceGivingData() {
        if (!$this->rbac->hasPrivilege('partner_reports', 'can_view')) {
            access_denied();
        }

        $filters = [
            'status' => 'active',
            'giving_type_id' => $this->input->post('giving_type_id'),
            'giving_frequency_id' => $this->input->post('giving_frequency_id')
        ];

        $partners = $this->Partner_model->getAll($filters);
        $result = [];

        foreach ($partners as $partner) {
            // Calculate expected amount based on frequency and start date
            $expected_amount = $this->calculateExpectedAmount($partner);
            $total_contributed = $this->Contribution_model->getTotalByPartner($partner->id);
            $balance = $expected_amount - $total_contributed;

            // Only show partners with balance
            if ($balance > 0) {
                $remark = $this->generateRemark($partner, $balance, $expected_amount);

                $result[] = [
                    $partner->partner_code,
                    $partner->firstname . ' ' . $partner->lastname,
                    $partner->email,
                    $partner->mobileno,
                    $partner->type_name ? $partner->type_name : '-',
                    $partner->frequency_name ? $partner->frequency_name : '-',
                    $partner->currency . ' ' . number_format($expected_amount, 2),
                    $partner->currency . ' ' . number_format($total_contributed, 2),
                    '<span class="text-danger"><strong>' . $partner->currency . ' ' . number_format($balance, 2) . '</strong></span>',
                    $remark
                ];
            }
        }

        echo json_encode(['data' => $result]);
    }

    /**
     * Calculate expected amount based on frequency and period
     */
    private function calculateExpectedAmount($partner) {
        if (!$partner->start_date || !$partner->contribution_amount) {
            return 0;
        }

        $start_date = new DateTime($partner->start_date);
        $current_date = new DateTime();

        // If end date is set and passed, use end date
        if ($partner->end_date) {
            $end_date = new DateTime($partner->end_date);
            if ($end_date < $current_date) {
                $current_date = $end_date;
            }
        }

        $interval = $start_date->diff($current_date);
        $days = $interval->days;

        // Get frequency days interval
        $frequency = $this->Frequency_model->getById($partner->giving_frequency_id);

        if (!$frequency || !$frequency->days_interval) {
            // One-time giving
            return $partner->contribution_amount;
        }

        // Calculate number of periods
        $periods = floor($days / $frequency->days_interval);

        return $periods * $partner->contribution_amount;
    }

    /**
     * Generate remark based on balance and partner status
     */
    private function generateRemark($partner, $balance, $expected_amount) {
        $percentage = $expected_amount > 0 ? ($balance / $expected_amount) * 100 : 0;

        if ($percentage >= 75) {
            return '<span class="label label-danger">Critical - ' . number_format($percentage, 0) . '% Outstanding</span>';
        } elseif ($percentage >= 50) {
            return '<span class="label label-warning">High - ' . number_format($percentage, 0) . '% Outstanding</span>';
        } elseif ($percentage >= 25) {
            return '<span class="label label-info">Moderate - ' . number_format($percentage, 0) . '% Outstanding</span>';
        } else {
            return '<span class="label label-success">Low - ' . number_format($percentage, 0) . '% Outstanding</span>';
        }
    }

    /**
     * Export report to PDF
     */
    public function exportPdf($report_type) {
        if (!$this->rbac->hasPrivilege('partner_reports', 'can_view')) {
            access_denied();
        }

        $this->load->library('pdf');

        $data['report_type'] = $report_type;
        $data['generated_date'] = date($this->customlib->getSchoolDateFormat());

        switch ($report_type) {
            case 'partner_information':
                $data['title'] = 'Partner Information Report';
                $data['partners'] = $this->Partner_model->getAll([]);
                $view = 'admin/partnerreports/pdf/partner_information_pdf';
                break;

            case 'giving_collection_by_type':
                $data['title'] = 'Giving Collection By Type Report';
                // Add data processing
                $view = 'admin/partnerreports/pdf/giving_collection_pdf';
                break;

            case 'partner_statement':
                $partner_id = $this->input->get('partner_id');
                $data['title'] = 'Partner Statement Report';
                $data['partner'] = $this->Partner_model->getById($partner_id);
                $data['contributions'] = $this->Contribution_model->getAll(['partner_id' => $partner_id]);
                $view = 'admin/partnerreports/pdf/partner_statement_pdf';
                break;

            case 'balance_giving':
                $data['title'] = 'Balance Giving Report';
                // Add data processing
                $view = 'admin/partnerreports/pdf/balance_giving_pdf';
                break;

            default:
                show_404();
                return;
        }

        $this->pdf->load_view($view, $data);
        $this->pdf->render();
        $this->pdf->stream($report_type . "_" . date('Y-m-d') . ".pdf");
    }
}