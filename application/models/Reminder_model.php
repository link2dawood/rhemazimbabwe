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
     * Get all reminders with optional filters
     * @param array $filters
     * @return array
     */
    public function getAll($filters = array())
    {
        $this->db->select('partner_reminders.*,
                          partners.firstname as partner_firstname,
                          partners.lastname as partner_lastname,
                          partners.partner_code,
                          partners.email as partner_email,
                          partners.mobileno as partner_mobile')
                 ->from('partner_reminders')
                 ->join('partners', 'partners.id = partner_reminders.partner_id');

        // Apply filters
        if (isset($filters['partner_id'])) {
            $this->db->where('partner_reminders.partner_id', $filters['partner_id']);
        }

        if (isset($filters['status'])) {
            $this->db->where('partner_reminders.status', $filters['status']);
        }

        if (isset($filters['reminder_type'])) {
            $this->db->where('partner_reminders.reminder_type', $filters['reminder_type']);
        }

        if (isset($filters['date_from'])) {
            $this->db->where('partner_reminders.reminder_date >=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $this->db->where('partner_reminders.reminder_date <=', $filters['date_to']);
        }

        $this->db->order_by('partner_reminders.reminder_date', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get reminder by ID
     * @param int $id
     * @return object|null
     */
    public function getById($id)
    {
        $this->db->select('partner_reminders.*,
                          partners.firstname as partner_firstname,
                          partners.lastname as partner_lastname,
                          partners.partner_code,
                          partners.email as partner_email,
                          partners.mobileno as partner_mobile')
                 ->from('partner_reminders')
                 ->join('partners', 'partners.id = partner_reminders.partner_id')
                 ->where('partner_reminders.id', $id);

        $query = $this->db->get();
        return $query->row();
    }

    /**
     * Get reminders by partner ID
     * @param int $partner_id
     * @param string $status
     * @return array
     */
    public function getByPartnerId($partner_id, $status = null)
    {
        $this->db->where('partner_id', $partner_id);

        if ($status) {
            $this->db->where('status', $status);
        }

        $this->db->order_by('reminder_date', 'DESC');

        $query = $this->db->get('partner_reminders');
        return $query->result();
    }

    /**
     * Get pending reminders for today
     * @return array
     */
    public function getPendingForToday()
    {
        $today = date('Y-m-d');

        $this->db->select('partner_reminders.*,
                          partners.firstname as partner_firstname,
                          partners.lastname as partner_lastname,
                          partners.partner_code,
                          partners.email as partner_email,
                          partners.mobileno as partner_mobile')
                 ->from('partner_reminders')
                 ->join('partners', 'partners.id = partner_reminders.partner_id')
                 ->where('partner_reminders.reminder_date', $today)
                 ->where('partner_reminders.status', 'pending')
                 ->order_by('partner_reminders.reminder_time', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get overdue reminders
     * @return array
     */
    public function getOverdue()
    {
        $today = date('Y-m-d');

        $this->db->select('partner_reminders.*,
                          partners.firstname as partner_firstname,
                          partners.lastname as partner_lastname,
                          partners.partner_code')
                 ->from('partner_reminders')
                 ->join('partners', 'partners.id = partner_reminders.partner_id')
                 ->where('partner_reminders.reminder_date <', $today)
                 ->where('partner_reminders.status', 'pending')
                 ->order_by('partner_reminders.reminder_date', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Add new reminder
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
     * Mark reminder as sent
     * @param int $id
     * @return bool
     */
    public function markAsSent($id)
    {
        $data = array(
            'status' => 'sent',
            'sent_at' => date('Y-m-d H:i:s')
        );

        // Check if recurring
        $reminder = $this->getById($id);

        if ($reminder && $reminder->is_recurring && $reminder->next_reminder_date) {
            $data['reminder_date'] = $reminder->next_reminder_date;
            $data['next_reminder_date'] = $this->calculateNextReminderDate($reminder->next_reminder_date, $reminder->recurrence_pattern);
            $data['status'] = 'pending';
        }

        return $this->update($id, $data);
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
     * Cancel reminder
     * @param int $id
     * @return bool
     */
    public function cancel($id)
    {
        return $this->update($id, array('status' => 'cancelled'));
    }

    /**
     * Calculate next reminder date based on recurrence pattern
     * @param string $current_date
     * @param string $pattern
     * @return string|null
     */
    public function calculateNextReminderDate($current_date, $pattern)
    {
        if (!$pattern) {
            return null;
        }

        switch ($pattern) {
            case 'daily':
                return date('Y-m-d', strtotime($current_date . ' + 1 day'));

            case 'weekly':
                return date('Y-m-d', strtotime($current_date . ' + 1 week'));

            case 'monthly':
                return date('Y-m-d', strtotime($current_date . ' + 1 month'));

            case 'yearly':
                return date('Y-m-d', strtotime($current_date . ' + 1 year'));

            default:
                return null;
        }
    }

    /**
     * Create contribution reminder for partner
     * @param int $partner_id
     * @param string $reminder_date
     * @param int $created_by
     * @return int|bool
     */
    public function createContributionReminder($partner_id, $reminder_date, $created_by)
    {
        $data = array(
            'partner_id' => $partner_id,
            'reminder_type' => 'contribution_due',
            'reminder_date' => $reminder_date,
            'title' => 'Contribution Due Reminder',
            'message' => 'This is a friendly reminder that your contribution is due.',
            'send_via' => 'email',
            'status' => 'pending',
            'is_recurring' => 1,
            'recurrence_pattern' => 'monthly',
            'next_reminder_date' => $this->calculateNextReminderDate($reminder_date, 'monthly'),
            'created_by' => $created_by
        );

        return $this->add($data);
    }

    /**
     * Get reminder count by status
     * @return array
     */
    public function getCountByStatus()
    {
        $this->db->select('status, COUNT(*) as count')
                 ->from('partner_reminders')
                 ->group_by('status');

        $query = $this->db->get();
        $result = array();

        foreach ($query->result() as $row) {
            $result[$row->status] = $row->count;
        }

        return $result;
    }

    /**
     * Get upcoming reminders
     * @param int $days
     * @return array
     */
    public function getUpcoming($days = 7)
    {
        $today = date('Y-m-d');
        $future_date = date('Y-m-d', strtotime("+{$days} days"));

        $this->db->select('partner_reminders.*,
                          partners.firstname as partner_firstname,
                          partners.lastname as partner_lastname,
                          partners.partner_code')
                 ->from('partner_reminders')
                 ->join('partners', 'partners.id = partner_reminders.partner_id')
                 ->where('partner_reminders.reminder_date >=', $today)
                 ->where('partner_reminders.reminder_date <=', $future_date)
                 ->where('partner_reminders.status', 'pending')
                 ->order_by('partner_reminders.reminder_date', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * ========================================
     * AUTOMATED REMINDER GENERATION METHODS
     * ========================================
     */

    /**
     * Generate contribution reminders for all active partners
     * Called by cron job daily
     * @return array Statistics about generated reminders
     */
    public function generateContributionReminders()
    {
        $this->load->model('partner_model');
        $this->load->model('contribution_model');

        $stats = array(
            'total_checked' => 0,
            'reminders_created' => 0,
            'errors' => array()
        );

        // Get all active partners
        $partners = $this->partner_model->getAll(array('is_active' => 1, 'status' => 'active'));

        foreach ($partners as $partner) {
            $stats['total_checked']++;

            try {
                // Calculate next expected contribution date based on frequency
                $next_contribution_date = $this->calculateNextContributionDate($partner);

                if (!$next_contribution_date) {
                    continue; // Skip if can't calculate
                }

                // Check if reminder already exists for this date
                $existing_reminder = $this->db->where('partner_id', $partner['id'])
                                             ->where('reminder_type', 'contribution')
                                             ->where('reminder_date', $next_contribution_date)
                                             ->where('status !=', 'cancelled')
                                             ->get('partner_reminders')
                                             ->row();

                if ($existing_reminder) {
                    continue; // Reminder already exists
                }

                // Check if contribution was already made
                $contribution_exists = $this->contribution_model->checkContributionForDate(
                    $partner['id'],
                    $next_contribution_date
                );

                if ($contribution_exists) {
                    continue; // Contribution already made
                }

                // Create reminder (send 3 days before due date)
                $reminder_date = date('Y-m-d', strtotime($next_contribution_date . ' -3 days'));

                if ($reminder_date <= date('Y-m-d')) {
                    $reminder_date = date('Y-m-d'); // Send today if already past reminder date
                }

                $reminder_data = array(
                    'partner_id' => $partner['id'],
                    'reminder_type' => 'contribution',
                    'reminder_date' => $reminder_date,
                    'message' => $this->generateReminderMessage($partner),
                    'status' => 'pending',
                    'send_email' => 1,
                    'send_sms' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => 0 // System generated
                );

                $this->add($reminder_data);
                $stats['reminders_created']++;

            } catch (Exception $e) {
                $stats['errors'][] = "Partner ID {$partner['id']}: " . $e->getMessage();
            }
        }

        return $stats;
    }

    /**
     * Calculate next expected contribution date for partner
     * @param array $partner Partner data
     * @return string|null Next contribution date (Y-m-d) or null
     */
    private function calculateNextContributionDate($partner)
    {
        $this->load->model('contribution_model');
        $this->load->model('frequency_model');

        // Get last contribution
        $last_contribution = $this->contribution_model->getLastContribution($partner['id']);

        $start_date = $last_contribution ? $last_contribution['contribution_date'] : $partner['start_date'];

        if (!$start_date) {
            return null;
        }

        // Get frequency details
        $frequency = $this->frequency_model->get($partner['giving_frequency_id']);

        if (!$frequency) {
            return null;
        }

        // Calculate next date based on frequency
        switch ($frequency['interval_type']) {
            case 'daily':
                $next_date = date('Y-m-d', strtotime($start_date . ' +' . $frequency['interval_value'] . ' days'));
                break;

            case 'weekly':
                $next_date = date('Y-m-d', strtotime($start_date . ' +' . $frequency['interval_value'] . ' weeks'));
                break;

            case 'monthly':
                $next_date = date('Y-m-d', strtotime($start_date . ' +' . $frequency['interval_value'] . ' months'));
                break;

            case 'quarterly':
                $next_date = date('Y-m-d', strtotime($start_date . ' +' . ($frequency['interval_value'] * 3) . ' months'));
                break;

            case 'yearly':
                $next_date = date('Y-m-d', strtotime($start_date . ' +' . $frequency['interval_value'] . ' years'));
                break;

            default:
                return null;
        }

        // Only return if next date is in the future
        return ($next_date > date('Y-m-d')) ? $next_date : null;
    }

    /**
     * Generate reminder message for partner
     * @param array $partner Partner data
     * @return string Reminder message
     */
    private function generateReminderMessage($partner)
    {
        $this->load->model('type_model');
        $this->load->model('frequency_model');

        $giving_type = $this->type_model->get($partner['giving_type_id']);
        $frequency = $this->frequency_model->get($partner['giving_frequency_id']);

        $message = "Dear {$partner['firstname']}, this is a friendly reminder about your ";
        $message .= $frequency ? $frequency['name'] : '';
        $message .= " contribution to our school. ";
        $message .= "Amount: {$partner['currency']} {$partner['contribution_amount']}. ";
        $message .= "Thank you for your continued support!";

        return $message;
    }

    /**
     * Process due reminders (send emails/SMS)
     * Called by cron job
     * @return array Statistics about processed reminders
     */
    public function processDueReminders()
    {
        $this->load->library('email');
        $this->load->library('smsgateway');
        $this->load->model('partner_model');
        $this->load->model('messages_model');
        $this->load->model('setting_model');

        $stats = array(
            'total_processed' => 0,
            'emails_sent' => 0,
            'sms_sent' => 0,
            'failed' => 0,
            'errors' => array()
        );

        // Get reminders due today
        $today = date('Y-m-d');
        $due_reminders = $this->db->select('partner_reminders.*,
                                           partners.firstname, partners.lastname,
                                           partners.email, partners.phone,
                                           partners.partner_code, partners.currency,
                                           partners.contribution_amount, partners.giving_type_id,
                                           partners.giving_frequency_id')
                                  ->from('partner_reminders')
                                  ->join('partners', 'partners.id = partner_reminders.partner_id')
                                  ->where('partner_reminders.reminder_date <=', $today)
                                  ->where('partner_reminders.status', 'pending')
                                  ->get()
                                  ->result_array();

        foreach ($due_reminders as $reminder) {
            $stats['total_processed']++;

            try {
                $email_sent = false;
                $sms_sent = false;

                // Send Email
                if ($reminder['send_email'] && $reminder['email']) {
                    $email_sent = $this->sendReminderEmail($reminder);
                    if ($email_sent) {
                        $stats['emails_sent']++;
                    }
                }

                // Send SMS
                if ($reminder['send_sms'] && $reminder['phone']) {
                    $sms_sent = $this->sendReminderSMS($reminder);
                    if ($sms_sent) {
                        $stats['sms_sent']++;
                    }
                }

                // Update reminder status
                if ($email_sent || $sms_sent) {
                    $this->db->where('id', $reminder['id'])
                            ->update('partner_reminders', array(
                                'status' => 'sent',
                                'sent_at' => date('Y-m-d H:i:s'),
                                'email_sent' => $email_sent ? 1 : 0,
                                'sms_sent' => $sms_sent ? 1 : 0
                            ));
                } else {
                    $stats['failed']++;
                    $this->db->where('id', $reminder['id'])
                            ->update('partner_reminders', array('status' => 'failed'));
                }

            } catch (Exception $e) {
                $stats['errors'][] = "Reminder ID {$reminder['id']}: " . $e->getMessage();
                $stats['failed']++;
            }
        }

        return $stats;
    }

    /**
     * Send reminder email to partner
     * @param array $reminder Reminder data with partner info
     * @return bool Success
     */
    private function sendReminderEmail($reminder)
    {
        // This method should integrate with the email system
        // Implementation depends on your email configuration
        return true; // Placeholder
    }

    /**
     * Send reminder SMS to partner
     * @param array $reminder Reminder data with partner info
     * @return bool Success
     */
    private function sendReminderSMS($reminder)
    {
        // This method should integrate with the SMS gateway
        // Implementation depends on your SMS configuration
        return true; // Placeholder
    }
}