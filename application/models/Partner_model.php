<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Partner_model extends MY_Model
{
    protected $current_session;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get current session
     * @return array
     */
    private function getCurrentSession()
    {
        if (!$this->current_session) {
            $this->load->model('setting_model');
            $this->current_session = $this->setting_model->getCurrentSession();
        }
        return $this->current_session;
    }

    /**
     * Get all partners with optional filters
     * @param array $filters
     * @return array
     */
    public function getAll($filters = array())
    {
        $this->db->select('partners.*,
                          giving_frequencies.name as frequency_name,
                          giving_types.name as type_name,
                          students.firstname as student_firstname,
                          students.lastname as student_lastname')
                 ->from('partners')
                 ->join('giving_frequencies', 'giving_frequencies.id = partners.giving_frequency_id', 'left')
                 ->join('giving_types', 'giving_types.id = partners.giving_type_id', 'left')
                 ->join('students', 'students.id = partners.student_id', 'left');

        // Apply filters
        if (isset($filters['is_active'])) {
            $this->db->where('partners.is_active', $filters['is_active']);
        }

        if (isset($filters['status'])) {
            $this->db->where('partners.status', $filters['status']);
        }

        if (isset($filters['giving_type_id'])) {
            $this->db->where('partners.giving_type_id', $filters['giving_type_id']);
        }

        if (isset($filters['giving_frequency_id'])) {
            $this->db->where('partners.giving_frequency_id', $filters['giving_frequency_id']);
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $this->db->group_start();
            $this->db->like('partners.firstname', $search);
            $this->db->or_like('partners.lastname', $search);
            $this->db->or_like('partners.email', $search);
            $this->db->or_like('partners.partner_code', $search);
            $this->db->group_end();
        }

        $this->db->order_by('partners.created_at', 'DESC');

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get partner by ID with relationships
     * @param int $id
     * @return object|null
     */
    public function getById($id)
    {
        $this->db->select('partners.*,
                          giving_frequencies.name as frequency_name,
                          giving_types.name as type_name,
                          students.firstname as student_firstname,
                          students.lastname as student_lastname,
                          students.admission_no')
                 ->from('partners')
                 ->join('giving_frequencies', 'giving_frequencies.id = partners.giving_frequency_id', 'left')
                 ->join('giving_types', 'giving_types.id = partners.giving_type_id', 'left')
                 ->join('students', 'students.id = partners.student_id', 'left')
                 ->where('partners.id', $id);

        $query = $this->db->get();
        return $query->row();
    }

    /**
     * Get partner by partner code
     * @param string $code
     * @return object|null
     */
    public function getByCode($code)
    {
        return $this->db->where('partner_code', $code)->get('partners')->row();
    }

    /**
     * Get partner by student ID
     * @param int $student_id
     * @return object|null
     */
    public function getByStudentId($student_id)
    {
        return $this->db->where('student_id', $student_id)->get('partners')->row();
    }

    /**
     * Add new partner
     * @param array $data
     * @return int|bool
     */
    public function add($data)
    {
        // Generate partner code if not provided
        if (empty($data['partner_code'])) {
            $data['partner_code'] = $this->generatePartnerCode();
        }

        if ($this->db->insert('partners', $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * Update partner
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('partners', $data);
    }

    /**
     * Delete partner
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('partners');
    }

    /**
     * Generate unique partner code
     * @return string
     */
    public function generatePartnerCode()
    {
        do {
            $code = 'PTR-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $exists = $this->db->where('partner_code', $code)->count_all_results('partners');
        } while ($exists > 0);

        return $code;
    }

    /**
     * Get partners count by status
     * @return array
     */
    public function getCountByStatus()
    {
        $this->db->select('status, COUNT(*) as count')
                 ->from('partners')
                 ->group_by('status');

        $query = $this->db->get();
        $result = array();

        foreach ($query->result() as $row) {
            $result[$row->status] = $row->count;
        }

        return $result;
    }

    /**
     * Get active partners count
     * @return int
     */
    public function getActiveCount()
    {
        return $this->db->where('is_active', 1)
                       ->where('status', 'active')
                       ->count_all_results('partners');
    }

    /**
     * Get partners with their total contributions
     * @param array $filters
     * @return array
     */
    public function getWithContributions($filters = array())
    {
        $this->db->select('partners.*,
                          giving_frequencies.name as frequency_name,
                          giving_types.name as type_name,
                          IFNULL(SUM(partner_contributions.amount), 0) as total_contributions,
                          COUNT(partner_contributions.id) as contribution_count,
                          MAX(partner_contributions.contribution_date) as last_contribution_date')
                 ->from('partners')
                 ->join('giving_frequencies', 'giving_frequencies.id = partners.giving_frequency_id', 'left')
                 ->join('giving_types', 'giving_types.id = partners.giving_type_id', 'left')
                 ->join('partner_contributions', 'partner_contributions.partner_id = partners.id AND partner_contributions.status = "completed"', 'left')
                 ->group_by('partners.id');

        // Apply filters
        if (isset($filters['is_active'])) {
            $this->db->where('partners.is_active', $filters['is_active']);
        }

        if (isset($filters['status'])) {
            $this->db->where('partners.status', $filters['status']);
        }

        $this->db->order_by('total_contributions', 'DESC');

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get partners with permissions
     * @param int $partner_id
     * @return array
     */
    public function getPermissions($partner_id)
    {
        $this->db->select('partner_permissions.*, partner_permission_types.description')
                 ->from('partner_permissions')
                 ->join('partner_permission_types', 'partner_permission_types.permission_code = partner_permissions.permission_code', 'left')
                 ->where('partner_permissions.partner_id', $partner_id)
                 ->where('partner_permissions.is_granted', 1);

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Check if partner has specific permission
     * @param int $partner_id
     * @param string $permission_code
     * @return bool
     */
    public function hasPermission($partner_id, $permission_code)
    {
        $count = $this->db->where('partner_id', $partner_id)
                         ->where('permission_code', $permission_code)
                         ->where('is_granted', 1)
                         ->count_all_results('partner_permissions');

        return $count > 0;
    }

    /**
     * Get dashboard statistics
     * @return array
     */
    public function getDashboardStats()
    {
        $stats = [];

        // Total partners
        $stats['total_partners'] = $this->db->where('is_active', 1)->count_all_results('partners');

        // Active partners
        $stats['active_partners'] = $this->db->where('is_active', 1)
                                             ->where('status', 'active')
                                             ->count_all_results('partners');

        // New partners this month
        $first_day = date('Y-m-01');
        $stats['new_this_month'] = $this->db->where('is_active', 1)
                                            ->where('created_at >=', $first_day)
                                            ->count_all_results('partners');

        // Total contributions (all time)
        $total_contrib = $this->db->select_sum('amount')
                                  ->where('status', 'completed')
                                  ->get('partner_contributions')
                                  ->row();
        $stats['total_contributions'] = $total_contrib->amount ? $total_contrib->amount : 0;

        // Monthly expected amount
        $active_partners = $this->db->select('id, giving_frequency_id, contribution_amount, start_date')
                                    ->where('is_active', 1)
                                    ->where('status', 'active')
                                    ->get('partners')
                                    ->result();

        $monthly_expected = 0;
        foreach ($active_partners as $partner) {
            if ($partner->contribution_amount && $partner->giving_frequency_id) {
                $frequency = $this->db->get_where('giving_frequencies', ['id' => $partner->giving_frequency_id])->row();
                if ($frequency && $frequency->days_interval) {
                    // Calculate monthly equivalent
                    $monthly_equivalent = ($partner->contribution_amount * 30) / $frequency->days_interval;
                    $monthly_expected += $monthly_equivalent;
                }
            }
        }
        $stats['monthly_expected'] = $monthly_expected;

        // Partners with outstanding balance
        $stats['partners_with_balance'] = 0;
        $stats['total_outstanding'] = 0;

        foreach ($active_partners as $partner) {
            $expected = $this->calculateExpectedAmount($partner);
            $contributed = $this->db->select_sum('amount')
                                   ->where('partner_id', $partner->id)
                                   ->where('status', 'completed')
                                   ->get('partner_contributions')
                                   ->row()->amount;
            $contributed = $contributed ? $contributed : 0;

            if ($expected > $contributed) {
                $stats['partners_with_balance']++;
                $stats['total_outstanding'] += ($expected - $contributed);
            }
        }

        // Type distribution
        $type_dist = $this->db->select('giving_types.name, COUNT(partners.id) as count')
                             ->from('partners')
                             ->join('giving_types', 'giving_types.id = partners.giving_type_id', 'left')
                             ->where('partners.is_active', 1)
                             ->where('partners.status', 'active')
                             ->group_by('partners.giving_type_id')
                             ->get()
                             ->result();

        $stats['type_distribution'] = [
            'labels' => [],
            'counts' => []
        ];

        foreach ($type_dist as $dist) {
            $stats['type_distribution']['labels'][] = $dist->name ? $dist->name : 'No Type';
            $stats['type_distribution']['counts'][] = $dist->count;
        }

        return $stats;
    }

    /**
     * Calculate expected amount for a partner
     * @param object $partner
     * @return float
     */
    private function calculateExpectedAmount($partner)
    {
        if (!isset($partner->start_date) || !isset($partner->contribution_amount)) {
            return 0;
        }

        $start_date = new DateTime($partner->start_date);
        $current_date = new DateTime();
        $interval = $start_date->diff($current_date);
        $days = $interval->days;

        $frequency = $this->db->get_where('giving_frequencies', ['id' => $partner->giving_frequency_id])->row();

        if (!$frequency || !$frequency->days_interval) {
            return $partner->contribution_amount;
        }

        $periods = floor($days / $frequency->days_interval);
        return $periods * $partner->contribution_amount;
    }

    /**
     * Get partners with upcoming birthdays
     * @param int $days Number of days to look ahead
     * @return array
     */
    public function getUpcomingBirthdays($days = 30)
    {
        // This would require a birthday field in partners table
        // For now, returning empty array as birthday field is not in current schema
        return array();
    }

    /**
     * Update partner status
     * @param int $id
     * @param string $status
     * @return bool
     */
    public function updateStatus($id, $status)
    {
        $valid_statuses = array('active', 'inactive', 'suspended');

        if (!in_array($status, $valid_statuses)) {
            return false;
        }

        return $this->update($id, array('status' => $status));
    }

    /**
     * Get partners by student or contact (for student portal)
     * @param int $student_id
     * @param string $email
     * @param string $phone
     * @return array
     */
    public function getPartnersByStudentOrContact($student_id, $email, $phone)
    {
        $this->db->where('(student_id = ' . $this->db->escape($student_id) . ' OR
                          email = ' . $this->db->escape($email) . ' OR
                          mobileno = ' . $this->db->escape($phone) . ')', NULL, FALSE);
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('partners')->result_array();
    }

    /**
     * Get partners by contact (for parent portal)
     * @param string $email
     * @param string $phone
     * @return array
     */
    public function getPartnersByContact($email, $phone)
    {
        $this->db->where('(email = ' . $this->db->escape($email) . ' OR
                          mobileno = ' . $this->db->escape($phone) . ')', NULL, FALSE);
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('partners')->result_array();
    }

    /**
     * Get partners by staff or contact (for staff portal)
     * @param int $staff_id
     * @param string $email
     * @return array
     */
    public function getPartnersByStaffOrContact($staff_id, $email)
    {
        $this->db->where('(staff_id = ' . $this->db->escape($staff_id) . ' OR
                          email = ' . $this->db->escape($email) . ')', NULL, FALSE);
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('partners')->result_array();
    }

    /**
     * Get partner by partner code (for public lookup)
     * @param string $partner_code
     * @return array|null
     */
    public function getByPartnerCode($partner_code)
    {
        return $this->db->where('partner_code', $partner_code)->get('partners')->row_array();
    }

    /**
     * Get simple partner record (for portal)
     * @param int $id
     * @return array|null
     */
    public function get($id)
    {
        return $this->db->where('id', $id)->get('partners')->row_array();
    }

    /**
     * Search partners by name, email, partner code (for communication module)
     * @param string $keyword
     * @return array
     */
    public function searchPartnerNameLike($keyword)
    {
        $this->db->select('id, firstname, lastname, email, phone, partner_code, organization_name, account_type')
                 ->from('partners')
                 ->where('is_active', 1)
                 ->group_start()
                 ->like('firstname', $keyword)
                 ->or_like('lastname', $keyword)
                 ->or_like('CONCAT(firstname, " ", lastname)', $keyword, false)
                 ->or_like('email', $keyword)
                 ->or_like('partner_code', $keyword)
                 ->or_like('organization_name', $keyword)
                 ->group_end()
                 ->order_by('firstname', 'ASC')
                 ->limit(50);

        return $this->db->get()->result_array();
    }
}