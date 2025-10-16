<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Session_manager extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
    }

    /**
     * Display session management dashboard
     */
    public function index()
    {
        if (!$this->rbac->hasPrivilege('system', 'can_view')) {
            access_denied();
        }

        $data['title'] = 'Session Management';
        $data['active_sessions'] = $this->get_active_sessions();
        $data['session_stats'] = $this->get_session_stats();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/session_manager/index', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * Get active sessions from database
     */
    public function get_active_sessions()
    {
        $this->db->select('id, ip_address, timestamp, data');
        $this->db->from('ci_sessions');
        $this->db->where('timestamp >', time() - 7200); // Last 2 hours
        $this->db->order_by('timestamp', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * Get session statistics
     */
    public function get_session_stats()
    {
        $stats = array();
        
        // Total active sessions
        $this->db->where('timestamp >', time() - 7200);
        $stats['active_sessions'] = $this->db->count_all_results('ci_sessions');
        
        // Sessions in last hour
        $this->db->where('timestamp >', time() - 3600);
        $stats['last_hour'] = $this->db->count_all_results('ci_sessions');
        
        // Sessions in last 24 hours
        $this->db->where('timestamp >', time() - 86400);
        $stats['last_24_hours'] = $this->db->count_all_results('ci_sessions');
        
        // Old sessions (need cleanup)
        $this->db->where('timestamp <', time() - 7200);
        $stats['old_sessions'] = $this->db->count_all_results('ci_sessions');
        
        return $stats;
    }

    /**
     * Clean up old sessions
     */
    public function cleanup()
    {
        if (!$this->rbac->hasPrivilege('system', 'can_delete')) {
            access_denied();
        }

        // Delete sessions older than 2 hours
        $this->db->where('timestamp <', time() - 7200);
        $deleted = $this->db->delete('ci_sessions');
        
        if ($deleted) {
            $this->session->set_flashdata('msg', '<div class="alert alert-success">Old sessions cleaned up successfully!</div>');
        } else {
            $this->session->set_flashdata('msg', '<div class="alert alert-warning">No old sessions found to clean up.</div>');
        }
        
        redirect('admin/session_manager');
    }

    /**
     * Force logout specific session
     */
    public function force_logout($session_id)
    {
        if (!$this->rbac->hasPrivilege('system', 'can_delete')) {
            access_denied();
        }

        $this->db->where('id', $session_id);
        $deleted = $this->db->delete('ci_sessions');
        
        if ($deleted) {
            $this->session->set_flashdata('msg', '<div class="alert alert-success">Session terminated successfully!</div>');
        } else {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger">Failed to terminate session.</div>');
        }
        
        redirect('admin/session_manager');
    }

    /**
     * Get session data for debugging
     */
    public function debug_session($session_id)
    {
        if (!$this->rbac->hasPrivilege('system', 'can_view')) {
            access_denied();
        }

        $this->db->where('id', $session_id);
        $session = $this->db->get('ci_sessions')->row();
        
        if ($session) {
            $data['session'] = $session;
            $data['session_data'] = unserialize($session->data);
            
            $this->load->view('admin/session_manager/debug', $data);
        } else {
            show_404();
        }
    }

    /**
     * AJAX endpoint for session stats
     */
    public function get_stats_ajax()
    {
        $stats = $this->get_session_stats();
        echo json_encode($stats);
    }

    /**
     * AJAX endpoint for active sessions
     */
    public function get_sessions_ajax()
    {
        $sessions = $this->get_active_sessions();
        
        $data = array();
        foreach ($sessions as $session) {
            $session_data = unserialize($session->data);
            $data[] = array(
                'id' => $session->id,
                'ip_address' => $session->ip_address,
                'timestamp' => date('Y-m-d H:i:s', $session->timestamp),
                'user_id' => isset($session_data['user_id']) ? $session_data['user_id'] : 'N/A',
                'username' => isset($session_data['username']) ? $session_data['username'] : 'N/A',
                'role' => isset($session_data['role']) ? $session_data['role'] : 'N/A'
            );
        }
        
        echo json_encode($data);
    }
}
