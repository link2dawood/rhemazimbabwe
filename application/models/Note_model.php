<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Note_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all notes with optional filters
     * @param array $filters
     * @return array
     */
    public function getAll($filters = array())
    {
        $this->db->select('partner_notes.*,
                          partners.firstname as partner_firstname,
                          partners.lastname as partner_lastname,
                          partners.partner_code,
                          staff.name as created_by_name')
                 ->from('partner_notes')
                 ->join('partners', 'partners.id = partner_notes.partner_id')
                 ->join('staff', 'staff.id = partner_notes.created_by', 'left');

        // Apply filters
        if (isset($filters['partner_id'])) {
            $this->db->where('partner_notes.partner_id', $filters['partner_id']);
        }

        if (isset($filters['note_type'])) {
            $this->db->where('partner_notes.note_type', $filters['note_type']);
        }

        if (isset($filters['priority'])) {
            $this->db->where('partner_notes.priority', $filters['priority']);
        }

        if (isset($filters['is_private'])) {
            $this->db->where('partner_notes.is_private', $filters['is_private']);
        }

        if (isset($filters['is_pinned'])) {
            $this->db->where('partner_notes.is_pinned', $filters['is_pinned']);
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $this->db->group_start();
            $this->db->like('partner_notes.title', $search);
            $this->db->or_like('partner_notes.note', $search);
            $this->db->group_end();
        }

        // Order by pinned first, then by creation date
        $this->db->order_by('partner_notes.is_pinned', 'DESC');
        $this->db->order_by('partner_notes.created_at', 'DESC');

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get note by ID
     * @param int $id
     * @return object|null
     */
    public function getById($id)
    {
        $this->db->select('partner_notes.*,
                          partners.firstname as partner_firstname,
                          partners.lastname as partner_lastname,
                          partners.partner_code,
                          created_by_staff.name as created_by_name,
                          updated_by_staff.name as updated_by_name')
                 ->from('partner_notes')
                 ->join('partners', 'partners.id = partner_notes.partner_id')
                 ->join('staff as created_by_staff', 'created_by_staff.id = partner_notes.created_by', 'left')
                 ->join('staff as updated_by_staff', 'updated_by_staff.id = partner_notes.updated_by', 'left')
                 ->where('partner_notes.id', $id);

        $query = $this->db->get();
        return $query->row();
    }

    /**
     * Get notes by partner ID
     * @param int $partner_id
     * @param bool $include_private
     * @return array
     */
    public function getByPartnerId($partner_id, $include_private = true)
    {
        $this->db->select('partner_notes.*,
                          staff.name as created_by_name')
                 ->from('partner_notes')
                 ->join('staff', 'staff.id = partner_notes.created_by', 'left')
                 ->where('partner_notes.partner_id', $partner_id);

        if (!$include_private) {
            $this->db->where('partner_notes.is_private', 0);
        }

        $this->db->order_by('partner_notes.is_pinned', 'DESC');
        $this->db->order_by('partner_notes.created_at', 'DESC');

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Add new note
     * @param array $data
     * @return int|bool
     */
    public function add($data)
    {
        if ($this->db->insert('partner_notes', $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * Update note
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('partner_notes', $data);
    }

    /**
     * Delete note
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('partner_notes');
    }

    /**
     * Toggle pin status
     * @param int $id
     * @return bool
     */
    public function togglePin($id)
    {
        $note = $this->getById($id);

        if (!$note) {
            return false;
        }

        $new_status = $note->is_pinned ? 0 : 1;

        return $this->update($id, array('is_pinned' => $new_status));
    }

    /**
     * Get pinned notes for partner
     * @param int $partner_id
     * @return array
     */
    public function getPinnedByPartner($partner_id)
    {
        $this->db->select('partner_notes.*,
                          staff.name as created_by_name')
                 ->from('partner_notes')
                 ->join('staff', 'staff.id = partner_notes.created_by', 'left')
                 ->where('partner_notes.partner_id', $partner_id)
                 ->where('partner_notes.is_pinned', 1)
                 ->order_by('partner_notes.created_at', 'DESC');

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get recent notes
     * @param int $limit
     * @param bool $private_only
     * @return array
     */
    public function getRecent($limit = 10, $private_only = false)
    {
        $this->db->select('partner_notes.*,
                          partners.firstname as partner_firstname,
                          partners.lastname as partner_lastname,
                          partners.partner_code,
                          staff.name as created_by_name')
                 ->from('partner_notes')
                 ->join('partners', 'partners.id = partner_notes.partner_id')
                 ->join('staff', 'staff.id = partner_notes.created_by', 'left');

        if ($private_only) {
            $this->db->where('partner_notes.is_private', 1);
        }

        $this->db->order_by('partner_notes.created_at', 'DESC');
        $this->db->limit($limit);

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get notes by type
     * @param string $note_type
     * @param int $limit
     * @return array
     */
    public function getByType($note_type, $limit = null)
    {
        $this->db->select('partner_notes.*,
                          partners.firstname as partner_firstname,
                          partners.lastname as partner_lastname,
                          partners.partner_code')
                 ->from('partner_notes')
                 ->join('partners', 'partners.id = partner_notes.partner_id')
                 ->where('partner_notes.note_type', $note_type)
                 ->order_by('partner_notes.created_at', 'DESC');

        if ($limit) {
            $this->db->limit($limit);
        }

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get notes by priority
     * @param string $priority
     * @param int $partner_id
     * @return array
     */
    public function getByPriority($priority, $partner_id = null)
    {
        $this->db->select('partner_notes.*,
                          partners.firstname as partner_firstname,
                          partners.lastname as partner_lastname,
                          partners.partner_code')
                 ->from('partner_notes')
                 ->join('partners', 'partners.id = partner_notes.partner_id')
                 ->where('partner_notes.priority', $priority);

        if ($partner_id) {
            $this->db->where('partner_notes.partner_id', $partner_id);
        }

        $this->db->order_by('partner_notes.created_at', 'DESC');

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get note count by type
     * @return array
     */
    public function getCountByType()
    {
        $this->db->select('note_type, COUNT(*) as count')
                 ->from('partner_notes')
                 ->group_by('note_type');

        $query = $this->db->get();
        $result = array();

        foreach ($query->result() as $row) {
            $result[$row->note_type] = $row->count;
        }

        return $result;
    }

    /**
     * Get high priority notes
     * @param int $limit
     * @return array
     */
    public function getHighPriority($limit = 10)
    {
        $this->db->select('partner_notes.*,
                          partners.firstname as partner_firstname,
                          partners.lastname as partner_lastname,
                          partners.partner_code,
                          staff.name as created_by_name')
                 ->from('partner_notes')
                 ->join('partners', 'partners.id = partner_notes.partner_id')
                 ->join('staff', 'staff.id = partner_notes.created_by', 'left')
                 ->where_in('partner_notes.priority', array('high', 'urgent'))
                 ->order_by('partner_notes.priority', 'DESC')
                 ->order_by('partner_notes.created_at', 'DESC')
                 ->limit($limit);

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Search notes
     * @param string $search_term
     * @param int $partner_id
     * @return array
     */
    public function search($search_term, $partner_id = null)
    {
        $this->db->select('partner_notes.*,
                          partners.firstname as partner_firstname,
                          partners.lastname as partner_lastname,
                          partners.partner_code')
                 ->from('partner_notes')
                 ->join('partners', 'partners.id = partner_notes.partner_id');

        $this->db->group_start();
        $this->db->like('partner_notes.title', $search_term);
        $this->db->or_like('partner_notes.note', $search_term);
        $this->db->group_end();

        if ($partner_id) {
            $this->db->where('partner_notes.partner_id', $partner_id);
        }

        $this->db->order_by('partner_notes.is_pinned', 'DESC');
        $this->db->order_by('partner_notes.created_at', 'DESC');

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Update note priority
     * @param int $id
     * @param string $priority
     * @return bool
     */
    public function updatePriority($id, $priority)
    {
        $valid_priorities = array('low', 'normal', 'high', 'urgent');

        if (!in_array($priority, $valid_priorities)) {
            return false;
        }

        return $this->update($id, array('priority' => $priority));
    }
}