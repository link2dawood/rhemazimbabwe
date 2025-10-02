<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Frequency_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all giving frequencies
     * @param bool $active_only
     * @return array
     */
    public function getAll($active_only = true)
    {
        if ($active_only) {
            $this->db->where('is_active', 1);
        }

        $this->db->order_by('sort_order', 'ASC');
        $query = $this->db->get('giving_frequencies');

        return $query->result();
    }

    /**
     * Get frequency by ID
     * @param int $id
     * @return object|null
     */
    public function getById($id)
    {
        return $this->db->where('id', $id)->get('giving_frequencies')->row();
    }

    /**
     * Get frequency by code
     * @param string $code
     * @return object|null
     */
    public function getByCode($code)
    {
        return $this->db->where('code', $code)->get('giving_frequencies')->row();
    }

    /**
     * Add new frequency
     * @param array $data
     * @return int|bool
     */
    public function add($data)
    {
        if ($this->db->insert('giving_frequencies', $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * Update frequency
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('giving_frequencies', $data);
    }

    /**
     * Delete frequency
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        // Check if frequency is being used by any partner
        $count = $this->db->where('giving_frequency_id', $id)->count_all_results('partners');

        if ($count > 0) {
            return false; // Cannot delete if in use
        }

        $this->db->where('id', $id);
        return $this->db->delete('giving_frequencies');
    }

    /**
     * Get frequencies as dropdown options
     * @return array
     */
    public function getDropdown()
    {
        $frequencies = $this->getAll(true);
        $dropdown = array();

        foreach ($frequencies as $frequency) {
            $dropdown[$frequency->id] = $frequency->name;
        }

        return $dropdown;
    }

    /**
     * Get frequency usage count
     * @param int $id
     * @return int
     */
    public function getUsageCount($id)
    {
        return $this->db->where('giving_frequency_id', $id)->count_all_results('partners');
    }

    /**
     * Calculate next contribution date based on frequency
     * @param int $frequency_id
     * @param string $last_date
     * @return string|null
     */
    public function calculateNextDate($frequency_id, $last_date)
    {
        $frequency = $this->getById($frequency_id);

        if (!$frequency || !$frequency->days_interval) {
            return null;
        }

        $next_date = date('Y-m-d', strtotime($last_date . ' + ' . $frequency->days_interval . ' days'));

        return $next_date;
    }

    /**
     * Toggle frequency active status
     * @param int $id
     * @return bool
     */
    public function toggleStatus($id)
    {
        $frequency = $this->getById($id);

        if (!$frequency) {
            return false;
        }

        $new_status = $frequency->is_active ? 0 : 1;

        return $this->update($id, array('is_active' => $new_status));
    }
}