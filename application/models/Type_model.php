<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Type_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all giving types
     * @param bool $active_only
     * @return array
     */
    public function getAll($active_only = true)
    {
        if ($active_only) {
            $this->db->where('is_active', 1);
        }

        $this->db->order_by('sort_order', 'ASC');
        $query = $this->db->get('giving_types');

        return $query->result();
    }

    /**
     * Get type by ID
     * @param int $id
     * @return object|null
     */
    public function getById($id)
    {
        return $this->db->where('id', $id)->get('giving_types')->row();
    }

    /**
     * Alias for getById() for compatibility
     * @param int $id
     * @return array|null
     */
    public function get($id)
    {
        $result = $this->getById($id);
        return $result ? (array)$result : null;
    }

    /**
     * Get type by code
     * @param string $code
     * @return object|null
     */
    public function getByCode($code)
    {
        return $this->db->where('code', $code)->get('giving_types')->row();
    }

    /**
     * Add new type
     * @param array $data
     * @return int|bool
     */
    public function add($data)
    {
        if ($this->db->insert('giving_types', $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * Update type
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('giving_types', $data);
    }

    /**
     * Delete type
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        // Check if type is being used by any partner
        $count = $this->db->where('giving_type_id', $id)->count_all_results('partners');

        if ($count > 0) {
            return false; // Cannot delete if in use
        }

        $this->db->where('id', $id);
        return $this->db->delete('giving_types');
    }

    /**
     * Get types as dropdown options
     * @return array
     */
    public function getDropdown()
    {
        $types = $this->getAll(true);
        $dropdown = array();

        foreach ($types as $type) {
            $dropdown[$type->id] = $type->name;
        }

        return $dropdown;
    }

    /**
     * Get type usage count
     * @param int $id
     * @return int
     */
    public function getUsageCount($id)
    {
        return $this->db->where('giving_type_id', $id)->count_all_results('partners');
    }

    /**
     * Get types with partner counts
     * @return array
     */
    public function getWithCounts()
    {
        $this->db->select('giving_types.*,
                          COUNT(partners.id) as partner_count,
                          IFNULL(SUM(partners.contribution_amount), 0) as total_pledged')
                 ->from('giving_types')
                 ->join('partners', 'partners.giving_type_id = giving_types.id AND partners.is_active = 1', 'left')
                 ->group_by('giving_types.id')
                 ->order_by('giving_types.sort_order', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Toggle type active status
     * @param int $id
     * @return bool
     */
    public function toggleStatus($id)
    {
        $type = $this->getById($id);

        if (!$type) {
            return false;
        }

        $new_status = $type->is_active ? 0 : 1;

        return $this->update($id, array('is_active' => $new_status));
    }

    /**
     * Reorder types
     * @param array $order Array of id => sort_order pairs
     * @return bool
     */
    public function reorder($order)
    {
        $this->db->trans_start();

        foreach ($order as $id => $sort_order) {
            $this->db->where('id', $id);
            $this->db->update('giving_types', array('sort_order' => $sort_order));
        }

        $this->db->trans_complete();

        return $this->db->trans_status();
    }
}