<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Reminder_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all reminder templates
     * @param bool $active_only
     * @return array
     */
    public function getAllTemplates($active_only = true)
    {
        if ($active_only) {
            $this->db->where('is_active', 1);
        }

        $this->db->order_by('template_name', 'ASC');
        $query = $this->db->get('partner_reminder_templates');

        return $query->result();
    }

    /**
     * Get reminder template by ID
     * @param int $id
     * @return object|null
     */
    public function getTemplateById($id)
    {
        return $this->db->where('id', $id)->get('partner_reminder_templates')->row();
    }

    /**
     * Add new reminder template
     * @param array $data
     * @return int|bool
     */
    public function addTemplate($data)
    {
        if ($this->db->insert('partner_reminder_templates', $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * Update reminder template
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateTemplate($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('partner_reminder_templates', $data);
    }

    /**
     * Delete reminder template
     * @param int $id
     * @return bool
     */
    public function deleteTemplate($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('partner_reminder_templates');
    }

    /**
     * Toggle template active status
     * @param int $id
     * @return bool
     */
    public function toggleTemplateStatus($id)
    {
        $template = $this->getTemplateById($id);

        if (!$template) {
            return false;
        }

        $new_status = $template->is_active ? 0 : 1;

        return $this->updateTemplate($id, array('is_active' => $new_status));
    }

    /**
     * Get reminders for a specific partner
     * @param int $partner_id
     * @param int $limit
     * @return array
     */
    public function getByPartnerId($partner_id, $limit = null)
    {
        $this->db->where('partner_id', $partner_id);
        $this->db->order_by('reminder_date', 'ASC');

        if ($limit) {
            $this->db->limit($limit);
        }

        $query = $this->db->get('partner_reminders');
        return $query->result();
    }

    /**
     * Add reminder for partner
     * @param array $data
     * @return int|bool
     */
    public function add($data)
    {
        if ($this->db->insert('partner_reminders', $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * Update reminder
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('partner_reminders', $data);
    }

    /**
     * Delete reminder
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('partner_reminders');
    }

    /**
     * Toggle reminder status
     * @param int $id
     * @param int $is_active
     * @return bool
     */
    public function toggleStatus($id, $is_active)
    {
        $this->db->where('id', $id);
        return $this->db->update('partner_reminders', array('is_active' => $is_active));
    }

    /**
     * Get pending reminders
     * @param string $date
     * @return array
     */
    public function getPendingReminders($date = null)
    {
        if (!$date) {
            $date = date('Y-m-d');
        }

        $this->db->select('partner_reminders.*, partners.firstname, partners.lastname, partners.email, partners.mobileno')
                 ->from('partner_reminders')
                 ->join('partners', 'partners.id = partner_reminders.partner_id', 'left')
                 ->where('partner_reminders.reminder_date', $date)
                 ->where('partner_reminders.status', 'pending')
                 ->where('partners.is_active', 1)
                 ->where('partners.status', 'active');

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Mark reminder as sent
     * @param int $id
     * @return bool
     */
    public function markAsSent($id)
    {
        return $this->update($id, array(
            'status' => 'sent',
            'sent_at' => date('Y-m-d H:i:s')
        ));
    }

    /**
     * Mark reminder as failed
     * @param int $id
     * @return bool
     */
    public function markAsFailed($id)
    {
        return $this->update($id, array('status' => 'failed'));
    }

    /**
     * Create reminder from template
     * @param int $partner_id
     * @param int $template_id
     * @param string $reminder_date
     * @param int $created_by
     * @return int|bool
     */
    public function createFromTemplate($partner_id, $template_id, $reminder_date, $created_by = null)
    {
        $template = $this->getTemplateById($template_id);

        if (!$template) {
            return false;
        }

        $data = array(
            'partner_id' => $partner_id,
            'title' => $template->template_name,
            'reminder_date' => $reminder_date,
            'reminder_type' => $template->reminder_type,
            'send_via' => 'email',
            'status' => 'pending',
            'created_by' => $created_by
        );

        return $this->add($data);
    }

    /**
     * Get reminder statistics
     * @return array
     */
    public function getReminderStats()
    {
        $stats = array();

        // Total reminders
        $stats['total'] = $this->db->count_all_results('partner_reminders');

        // Pending reminders
        $stats['pending'] = $this->db->where('status', 'pending')->count_all_results('partner_reminders');

        // Sent reminders
        $stats['sent'] = $this->db->where('status', 'sent')->count_all_results('partner_reminders');

        // Failed reminders
        $stats['failed'] = $this->db->where('status', 'failed')->count_all_results('partner_reminders');

        // Today's reminders
        $stats['today'] = $this->db->where('reminder_date', date('Y-m-d'))->count_all_results('partner_reminders');

        return $stats;
    }

    /**
     * Get reminder types dropdown
     * @return array
     */
    public function getReminderTypesDropdown()
    {
        return array(
            'contribution' => 'Contribution Reminder',
            'follow_up' => 'Follow Up',
            'renewal' => 'Renewal',
            'other' => 'Other'
        );
    }

    /**
     * Get timing options dropdown
     * @return array
     */
    public function getTimingOptionsDropdown()
    {
        return array(
            'before' => 'Before Due Date',
            'after' => 'After Due Date'
        );
    }
}