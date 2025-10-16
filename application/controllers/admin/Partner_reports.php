<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Partner_reports extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'partner_model',
            'contribution_model',
            'type_model',
            'frequency_model',
            'permission_model',
            'partner_giving_setting_model'
        ));
        $this->load->library('pdf');
    }

    /**
     * Partner Reports Index
     */
    public function index()
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Partners');
        $this->session->set_userdata('sub_menu', 'admin/partner_reports');

        $data['title'] = 'Partner Reports';
        $data['giving_types'] = $this->type_model->getAll();
        $data['giving_frequencies'] = $this->frequency_model->getAll();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/partner_reports/index', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * Partner Information Report
     */
    public function partner_information()
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Partners');
        $this->session->set_userdata('sub_menu', 'admin/partner_reports');

        $data['title'] = 'Partner Information Report';
        
        // Get filters
        $filters = array();
        if ($this->input->post('status')) {
            $filters['status'] = $this->input->post('status');
        }
        if ($this->input->post('giving_type_id')) {
            $filters['giving_type_id'] = $this->input->post('giving_type_id');
        }
        if ($this->input->post('giving_frequency_id')) {
            $filters['giving_frequency_id'] = $this->input->post('giving_frequency_id');
        }
        if ($this->input->post('account_type')) {
            $filters['account_type'] = $this->input->post('account_type');
        }

        $data['filters'] = $filters;
        $data['partners'] = $this->partner_model->getAll($filters);
        $data['giving_types'] = $this->type_model->getAll();
        $data['giving_frequencies'] = $this->frequency_model->getAll();

        // Generate PDF if requested
        if ($this->input->post('generate_pdf')) {
            $this->generatePartnerInformationPDF($data['partners'], $filters);
            return;
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/partner_reports/partner_information', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * Giving Collection By Type Report
     */
    public function giving_collection_by_type()
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Partners');
        $this->session->set_userdata('sub_menu', 'admin/partner_reports');

        $data['title'] = 'Giving Collection By Type Report';
        
        // Get date range
        $start_date = $this->input->post('start_date') ?: date('Y-m-01');
        $end_date = $this->input->post('end_date') ?: date('Y-m-t');
        
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        
        // Get collection data by type
        $data['collections'] = $this->getCollectionByType($start_date, $end_date);
        $data['total_collections'] = $this->getTotalCollections($start_date, $end_date);
        $data['giving_types'] = $this->type_model->getAll();

        // Generate PDF if requested
        if ($this->input->post('generate_pdf')) {
            $this->generateCollectionByTypePDF($data['collections'], $data['total_collections'], $start_date, $end_date);
            return;
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/partner_reports/giving_collection_by_type', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * Partner Statement Report
     */
    public function partner_statement()
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Partners');
        $this->session->set_userdata('sub_menu', 'admin/partner_reports');

        $data['title'] = 'Partner Statement Report';
        
        $partner_id = $this->input->post('partner_id');
        $start_date = $this->input->post('start_date') ?: date('Y-m-01');
        $end_date = $this->input->post('end_date') ?: date('Y-m-t');
        
        $data['partner_id'] = $partner_id;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        
        if ($partner_id) {
            $data['partner'] = $this->partner_model->getById($partner_id);
            $data['contributions'] = $this->contribution_model->getByPartnerIdAndDateRange($partner_id, $start_date, $end_date);
            $data['statement_summary'] = $this->getPartnerStatementSummary($partner_id, $start_date, $end_date);
        }
        
        $data['partners'] = $this->partner_model->getAll(array('status' => 'active'));

        // Generate PDF if requested
        if ($this->input->post('generate_pdf') && $partner_id) {
            $this->generatePartnerStatementPDF($data['partner'], $data['contributions'], $data['statement_summary'], $start_date, $end_date);
            return;
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/partner_reports/partner_statement', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * Balance Giving Report with Remark
     */
    public function balance_giving_report()
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Partners');
        $this->session->set_userdata('sub_menu', 'admin/partner_reports');

        $data['title'] = 'Balance Giving Report with Remark';
        
        // Get filters
        $filters = array();
        if ($this->input->post('status')) {
            $filters['status'] = $this->input->post('status');
        }
        if ($this->input->post('balance_status')) {
            $filters['balance_status'] = $this->input->post('balance_status');
        }
        
        $data['filters'] = $filters;
        $data['balance_data'] = $this->getBalanceGivingData($filters);
        $data['summary_stats'] = $this->getBalanceSummaryStats($filters);

        // Generate PDF if requested
        if ($this->input->post('generate_pdf')) {
            $this->generateBalanceGivingPDF($data['balance_data'], $data['summary_stats'], $filters);
            return;
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/partner_reports/balance_giving_report', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * Get collection data by giving type
     */
    private function getCollectionByType($start_date, $end_date)
    {
        $this->db->select('giving_types.name as type_name, 
                          giving_types.code as type_code,
                          COUNT(partner_contributions.id) as contribution_count,
                          SUM(partner_contributions.amount) as total_amount,
                          AVG(partner_contributions.amount) as average_amount,
                          partner_contributions.currency')
                 ->from('partner_contributions')
                 ->join('giving_types', 'giving_types.id = partner_contributions.giving_type_id', 'left')
                 ->where('partner_contributions.contribution_date >=', $start_date)
                 ->where('partner_contributions.contribution_date <=', $end_date)
                 ->where('partner_contributions.status', 'completed')
                 ->group_by('partner_contributions.giving_type_id, partner_contributions.currency')
                 ->order_by('total_amount', 'DESC');

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get total collections for period
     */
    private function getTotalCollections($start_date, $end_date)
    {
        $this->db->select('SUM(amount) as total_amount, currency, COUNT(*) as total_count')
                 ->from('partner_contributions')
                 ->where('contribution_date >=', $start_date)
                 ->where('contribution_date <=', $end_date)
                 ->where('status', 'completed')
                 ->group_by('currency');

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get partner statement summary
     */
    private function getPartnerStatementSummary($partner_id, $start_date, $end_date)
    {
        $partner = $this->partner_model->getById($partner_id);
        
        // Get contributions in period
        $contributions = $this->contribution_model->getByPartnerIdAndDateRange($partner_id, $start_date, $end_date);
        $total_contributed = 0;
        foreach ($contributions as $contribution) {
            $total_contributed += $contribution->amount;
        }
        
        // Calculate expected amount based on frequency
        $expected_amount = $this->calculateExpectedAmount($partner, $start_date, $end_date);
        
        // Get opening balance (contributions before start date)
        $opening_balance = $this->contribution_model->getTotalByPartnerAndDateRange($partner_id, null, $start_date);
        
        // Get closing balance (all contributions up to end date)
        $closing_balance = $this->contribution_model->getTotalByPartnerAndDateRange($partner_id, null, $end_date);
        
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
        if (!$partner->contribution_amount || !$partner->giving_frequency_id) {
            return 0;
        }
        
        $frequency = $this->frequency_model->getById($partner->giving_frequency_id);
        if (!$frequency || !$frequency->days_interval) {
            return $partner->contribution_amount;
        }
        
        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = $start->diff($end);
        $days = $interval->days + 1;
        
        $periods = floor($days / $frequency->days_interval);
        return $periods * $partner->contribution_amount;
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

    /**
     * Get balance giving data
     */
    private function getBalanceGivingData($filters)
    {
        $this->db->select('partners.*,
                          giving_types.name as type_name,
                          giving_frequencies.name as frequency_name,
                          partners.contribution_amount as expected_amount,
                          COALESCE(SUM(partner_contributions.amount), 0) as actual_amount,
                          (partners.contribution_amount - COALESCE(SUM(partner_contributions.amount), 0)) as balance,
                          partners.currency')
                 ->from('partners')
                 ->join('giving_types', 'giving_types.id = partners.giving_type_id', 'left')
                 ->join('giving_frequencies', 'giving_frequencies.id = partners.giving_frequency_id', 'left')
                 ->join('partner_contributions', 'partner_contributions.partner_id = partners.id AND partner_contributions.status = "completed"', 'left')
                 ->where('partners.is_active', 1)
                 ->group_by('partners.id');

        if (isset($filters['status'])) {
            $this->db->where('partners.status', $filters['status']);
        }

        $query = $this->db->get();
        $results = $query->result();
        
        // Add balance status and remarks
        foreach ($results as $result) {
            $result->balance_status = $this->getBalanceStatus($result->expected_amount, $result->actual_amount);
            $result->remark = $this->getBalanceRemark($result->balance_status, $result->balance);
        }
        
        // Filter by balance status if specified
        if (isset($filters['balance_status'])) {
            $results = array_filter($results, function($item) use ($filters) {
                return $item->balance_status === $filters['balance_status'];
            });
        }
        
        return $results;
    }

    /**
     * Get balance remark
     */
    private function getBalanceRemark($status, $balance)
    {
        switch ($status) {
            case 'Up to Date':
                return 'Partner is up to date with contributions.';
            case 'Good':
                return 'Partner is slightly behind but within acceptable range.';
            case 'Behind':
                return 'Partner is behind on contributions. Follow up recommended.';
            case 'Critical':
                return 'Partner is significantly behind. Immediate action required.';
            default:
                return 'Status unclear.';
        }
    }

    /**
     * Get balance summary statistics
     */
    private function getBalanceSummaryStats($filters)
    {
        $balance_data = $this->getBalanceGivingData($filters);
        
        $stats = array(
            'total_partners' => count($balance_data),
            'up_to_date' => 0,
            'good' => 0,
            'behind' => 0,
            'critical' => 0,
            'total_expected' => 0,
            'total_actual' => 0,
            'total_balance' => 0
        );
        
        foreach ($balance_data as $data) {
            $stats['total_expected'] += $data->expected_amount;
            $stats['total_actual'] += $data->actual_amount;
            $stats['total_balance'] += $data->balance;
            
            switch ($data->balance_status) {
                case 'Up to Date':
                    $stats['up_to_date']++;
                    break;
                case 'Good':
                    $stats['good']++;
                    break;
                case 'Behind':
                    $stats['behind']++;
                    break;
                case 'Critical':
                    $stats['critical']++;
                    break;
            }
        }
        
        return $stats;
    }

    /**
     * Generate Partner Information PDF
     */
    private function generatePartnerInformationPDF($partners, $filters)
    {
        $this->load->library('pdf');
        
        $html = $this->load->view('admin/partner_reports/pdf/partner_information', array(
            'partners' => $partners,
            'filters' => $filters,
            'generated_date' => date('Y-m-d H:i:s')
        ), true);
        
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'landscape');
        $this->pdf->render();
        
        $filename = 'Partner_Information_Report_' . date('Y-m-d') . '.pdf';
        $this->pdf->stream($filename);
    }

    /**
     * Generate Collection By Type PDF
     */
    private function generateCollectionByTypePDF($collections, $total_collections, $start_date, $end_date)
    {
        $this->load->library('pdf');
        
        $html = $this->load->view('admin/partner_reports/pdf/giving_collection_by_type', array(
            'collections' => $collections,
            'total_collections' => $total_collections,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'generated_date' => date('Y-m-d H:i:s')
        ), true);
        
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'landscape');
        $this->pdf->render();
        
        $filename = 'Giving_Collection_By_Type_Report_' . date('Y-m-d') . '.pdf';
        $this->pdf->stream($filename);
    }

    /**
     * Generate Partner Statement PDF
     */
    private function generatePartnerStatementPDF($partner, $contributions, $statement_summary, $start_date, $end_date)
    {
        $this->load->library('pdf');
        
        $html = $this->load->view('admin/partner_reports/pdf/partner_statement', array(
            'partner' => $partner,
            'contributions' => $contributions,
            'statement_summary' => $statement_summary,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'generated_date' => date('Y-m-d H:i:s')
        ), true);
        
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->render();
        
        $filename = 'Partner_Statement_' . $partner->partner_code . '_' . date('Y-m-d') . '.pdf';
        $this->pdf->stream($filename);
    }

    /**
     * Generate Balance Giving PDF
     */
    private function generateBalanceGivingPDF($balance_data, $summary_stats, $filters)
    {
        $this->load->library('pdf');
        
        $html = $this->load->view('admin/partner_reports/pdf/balance_giving_report', array(
            'balance_data' => $balance_data,
            'summary_stats' => $summary_stats,
            'filters' => $filters,
            'generated_date' => date('Y-m-d H:i:s')
        ), true);
        
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'landscape');
        $this->pdf->render();
        
        $filename = 'Balance_Giving_Report_' . date('Y-m-d') . '.pdf';
        $this->pdf->stream($filename);
    }
}
