<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Contribution_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all contributions with optional filters
     * @param array $filters
     * @return array
     */
    public function getAll($filters = array())
    {
        $this->db->select('partner_contributions.*,
                          partners.firstname as partner_firstname,
                          partners.lastname as partner_lastname,
                          partners.partner_code,
                          giving_types.name as type_name,
                          giving_frequencies.name as frequency_name')
                 ->from('partner_contributions')
                 ->join('partners', 'partners.id = partner_contributions.partner_id')
                 ->join('giving_types', 'giving_types.id = partner_contributions.giving_type_id', 'left')
                 ->join('giving_frequencies', 'giving_frequencies.id = partner_contributions.giving_frequency_id', 'left');

        // Apply filters
        if (isset($filters['partner_id'])) {
            $this->db->where('partner_contributions.partner_id', $filters['partner_id']);
        }

        if (isset($filters['status'])) {
            $this->db->where('partner_contributions.status', $filters['status']);
        }

        if (isset($filters['payment_method'])) {
            $this->db->where('partner_contributions.payment_method', $filters['payment_method']);
        }

        if (isset($filters['giving_type_id'])) {
            $this->db->where('partner_contributions.giving_type_id', $filters['giving_type_id']);
        }

        if (isset($filters['date_from'])) {
            $this->db->where('partner_contributions.contribution_date >=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $this->db->where('partner_contributions.contribution_date <=', $filters['date_to']);
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $this->db->group_start();
            $this->db->like('partners.firstname', $search);
            $this->db->or_like('partners.lastname', $search);
            $this->db->or_like('partner_contributions.receipt_no', $search);
            $this->db->or_like('partner_contributions.transaction_id', $search);
            $this->db->group_end();
        }

        $this->db->order_by('partner_contributions.contribution_date', 'DESC');

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get contribution by ID
     * @param int $id
     * @return object|null
     */
    public function getById($id)
    {
        $this->db->select('partner_contributions.*,
                          partners.firstname as partner_firstname,
                          partners.lastname as partner_lastname,
                          partners.partner_code,
                          partners.email as partner_email,
                          partners.mobileno as partner_mobile,
                          giving_types.name as type_name,
                          giving_frequencies.name as frequency_name')
                 ->from('partner_contributions')
                 ->join('partners', 'partners.id = partner_contributions.partner_id')
                 ->join('giving_types', 'giving_types.id = partner_contributions.giving_type_id', 'left')
                 ->join('giving_frequencies', 'giving_frequencies.id = partner_contributions.giving_frequency_id', 'left')
                 ->where('partner_contributions.id', $id);

        $query = $this->db->get();
        return $query->row();
    }

    /**
     * Get contributions by partner ID
     * @param int $partner_id
     * @param int $limit
     * @return array
     */
    public function getByPartnerId($partner_id, $limit = null)
    {
        $this->db->select('partner_contributions.*,
                          giving_types.name as type_name,
                          giving_frequencies.name as frequency_name')
                 ->from('partner_contributions')
                 ->join('giving_types', 'giving_types.id = partner_contributions.giving_type_id', 'left')
                 ->join('giving_frequencies', 'giving_frequencies.id = partner_contributions.giving_frequency_id', 'left')
                 ->where('partner_contributions.partner_id', $partner_id)
                 ->order_by('partner_contributions.contribution_date', 'DESC');

        if ($limit) {
            $this->db->limit($limit);
        }

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Add new contribution
     * @param array $data
     * @return int|bool
     */
    public function add($data)
    {
        // Generate receipt number if not provided
        if (empty($data['receipt_no'])) {
            $data['receipt_no'] = $this->generateReceiptNumber();
        }

        if ($this->db->insert('partner_contributions', $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * Update contribution
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('partner_contributions', $data);
    }

    /**
     * Delete contribution
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('partner_contributions');
    }

    /**
     * Generate unique receipt number
     * @return string
     */
    public function generateReceiptNumber()
    {
        do {
            $receipt_no = 'RCT-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $exists = $this->db->where('receipt_no', $receipt_no)->count_all_results('partner_contributions');
        } while ($exists > 0);

        return $receipt_no;
    }

    /**
     * Get total contributions by partner
     * @param int $partner_id
     * @param string $status
     * @return float
     */
    public function getTotalByPartner($partner_id, $status = 'completed')
    {
        $this->db->select_sum('amount');
        $this->db->where('partner_id', $partner_id);
        $this->db->where('status', $status);

        $query = $this->db->get('partner_contributions');
        $result = $query->row();

        return $result->amount ? $result->amount : 0;
    }

    /**
     * Get contributions summary
     * @param array $filters
     * @return object
     */
    public function getSummary($filters = array())
    {
        $this->db->select('COUNT(*) as total_count,
                          SUM(CASE WHEN status = "completed" THEN amount ELSE 0 END) as total_amount,
                          SUM(CASE WHEN status = "pending" THEN amount ELSE 0 END) as pending_amount,
                          SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed_count,
                          SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_count')
                 ->from('partner_contributions');

        // Apply date filters
        if (isset($filters['date_from'])) {
            $this->db->where('contribution_date >=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $this->db->where('contribution_date <=', $filters['date_to']);
        }

        $query = $this->db->get();
        return $query->row();
    }

    /**
     * Get monthly contributions report
     * @param int $year
     * @param int $partner_id
     * @return array
     */
    public function getMonthlyReport($year = null, $partner_id = null)
    {
        if (!$year) {
            $year = date('Y');
        }

        $this->db->select('MONTH(contribution_date) as month,
                          SUM(amount) as total_amount,
                          COUNT(*) as contribution_count')
                 ->from('partner_contributions')
                 ->where('YEAR(contribution_date)', $year)
                 ->where('status', 'completed')
                 ->group_by('MONTH(contribution_date)')
                 ->order_by('month', 'ASC');

        if ($partner_id) {
            $this->db->where('partner_id', $partner_id);
        }

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get contributions by payment method
     * @param array $filters
     * @return array
     */
    public function getByPaymentMethod($filters = array())
    {
        $this->db->select('payment_method,
                          COUNT(*) as count,
                          SUM(amount) as total_amount')
                 ->from('partner_contributions')
                 ->where('status', 'completed')
                 ->group_by('payment_method');

        if (isset($filters['date_from'])) {
            $this->db->where('contribution_date >=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $this->db->where('contribution_date <=', $filters['date_to']);
        }

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get last contribution for partner
     * @param int $partner_id
     * @return object|null
     */
    public function getLastContribution($partner_id)
    {
        $this->db->where('partner_id', $partner_id)
                 ->where('status', 'completed')
                 ->order_by('contribution_date', 'DESC')
                 ->limit(1);

        $query = $this->db->get('partner_contributions');
        return $query->row();
    }

    /**
     * Update contribution status
     * @param int $id
     * @param string $status
     * @return bool
     */
    public function updateStatus($id, $status)
    {
        $valid_statuses = array('pending', 'completed', 'cancelled', 'failed', 'refunded');

        if (!in_array($status, $valid_statuses)) {
            return false;
        }

        return $this->update($id, array('status' => $status));
    }

    /**
     * Approve contribution
     * @param int $id
     * @param int $approved_by
     * @return bool
     */
    public function approve($id, $approved_by)
    {
        $data = array(
            'status' => 'completed',
            'approved_by' => $approved_by,
            'approved_at' => date('Y-m-d H:i:s')
        );

        return $this->update($id, $data);
    }

    /**
     * Get monthly statistics for dashboard
     * @return array
     */
    public function getMonthlyStats()
    {
        $stats = [];

        // Current month total
        $first_day = date('Y-m-01');
        $last_day = date('Y-m-t');

        $month_total = $this->db->select_sum('amount')
                               ->where('status', 'completed')
                               ->where('contribution_date >=', $first_day)
                               ->where('contribution_date <=', $last_day)
                               ->get('partner_contributions')
                               ->row();

        $stats['month_total'] = $month_total->amount ? $month_total->amount : 0;

        // Last 6 months trend
        $monthly_trend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month_start = date('Y-m-01', strtotime("-$i months"));
            $month_end = date('Y-m-t', strtotime("-$i months"));
            $month_name = date('M Y', strtotime($month_start));

            $month_amount = $this->db->select_sum('amount')
                                    ->where('status', 'completed')
                                    ->where('contribution_date >=', $month_start)
                                    ->where('contribution_date <=', $month_end)
                                    ->get('partner_contributions')
                                    ->row();

            $monthly_trend['labels'][] = $month_name;
            $monthly_trend['amounts'][] = $month_amount->amount ? $month_amount->amount : 0;
        }

        $stats['monthly_trend'] = $monthly_trend;

        return $stats;
    }

    /**
     * Get contributions by partner (for portal)
     * @param int $partner_id
     * @return array
     */
    public function getContributionsByPartner($partner_id)
    {
        return $this->db->where('partner_id', $partner_id)
                       ->order_by('contribution_date', 'DESC')
                       ->get('partner_contributions')
                       ->result_array();
    }

    /**
     * Get total contributed by partner (for portal)
     * @param int $partner_id
     * @return float
     */
    public function getTotalContributed($partner_id)
    {
        $result = $this->db->select_sum('amount')
                          ->where('partner_id', $partner_id)
                          ->where('status', 'completed')
                          ->get('partner_contributions')
                          ->row();
        return $result->amount ? $result->amount : 0;
    }

    /**
     * Get year contributed by partner (for portal)
     * @param int $partner_id
     * @param int $year
     * @return float
     */
    public function getYearContributed($partner_id, $year)
    {
        $result = $this->db->select_sum('amount')
                          ->where('partner_id', $partner_id)
                          ->where('status', 'completed')
                          ->where('YEAR(contribution_date)', $year)
                          ->get('partner_contributions')
                          ->row();
        return $result->amount ? $result->amount : 0;
    }

    /**
     * Get simple contribution record (for portal)
     * @param int $id
     * @return array|null
     */
    public function get($id)
    {
        return $this->db->where('id', $id)->get('partner_contributions')->row_array();
    }

    /**
     * Check if contribution exists for a specific date
     * @param int $partner_id
     * @param string $date Date in Y-m-d format
     * @return bool
     */
    public function checkContributionForDate($partner_id, $date)
    {
        $count = $this->db->where('partner_id', $partner_id)
                         ->where('contribution_date', $date)
                         ->where('status', 'completed')
                         ->count_all_results('partner_contributions');
        return $count > 0;
    }
}