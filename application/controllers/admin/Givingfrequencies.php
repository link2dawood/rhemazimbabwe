<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Givingfrequencies extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('frequency_model');
    }

    /**
     * Giving Frequencies Management - Main Page
     */
    public function index()
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Partners');
        $this->session->set_userdata('sub_menu', 'admin/givingfrequencies');

        $data['title'] = 'Giving Frequencies';
        $data['giving_frequencies'] = $this->frequency_model->getAll(false);

        $this->load->view('layout/header', $data);
        $this->load->view('admin/givingfrequencies/index', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * Add/Edit Giving Frequency
     */
    public function save()
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_add') && !$this->rbac->hasPrivilege('partners', 'can_edit')) {
            echo json_output(array('status' => 'error', 'message' => 'Access denied'));
            return;
        }

        $id = $this->input->post('id');
        $name = $this->input->post('name');
        $code = $this->input->post('code');
        $days_interval = $this->input->post('days_interval');
        $description = $this->input->post('description');
        $is_active = $this->input->post('is_active') ? 1 : 0;
        $sort_order = $this->input->post('sort_order') ? $this->input->post('sort_order') : 0;

        if (empty($name)) {
            echo json_output(array('status' => 'error', 'message' => 'Name is required'));
            return;
        }

        $data = array(
            'name' => $name,
            'code' => $code,
            'days_interval' => $days_interval,
            'description' => $description,
            'is_active' => $is_active,
            'sort_order' => $sort_order
        );

        if ($id) {
            // Update existing
            if ($this->frequency_model->update($id, $data)) {
                echo json_output(array('status' => 'success', 'message' => 'Giving frequency updated successfully'));
            } else {
                echo json_output(array('status' => 'error', 'message' => 'Failed to update giving frequency'));
            }
        } else {
            // Add new
            if ($this->frequency_model->add($data)) {
                echo json_output(array('status' => 'success', 'message' => 'Giving frequency added successfully'));
            } else {
                echo json_output(array('status' => 'error', 'message' => 'Failed to add giving frequency'));
            }
        }
    }

    /**
     * Delete Giving Frequency
     */
    public function delete()
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_delete')) {
            echo json_output(array('status' => 'error', 'message' => 'Access denied'));
            return;
        }

        $id = $this->input->post('id');

        // Check if frequency is being used
        $usage_count = $this->frequency_model->getUsageCount($id);
        
        if ($usage_count > 0) {
            echo json_output(array('status' => 'error', 'message' => 'Cannot delete giving frequency - it is being used by ' . $usage_count . ' partner(s)'));
            return;
        }

        if ($this->frequency_model->delete($id)) {
            echo json_output(array('status' => 'success', 'message' => 'Giving frequency deleted successfully'));
        } else {
            echo json_output(array('status' => 'error', 'message' => 'Failed to delete giving frequency'));
        }
    }

    /**
     * Toggle Frequency Active Status
     */
    public function toggle_status()
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_edit')) {
            echo json_output(array('status' => 'error', 'message' => 'Access denied'));
            return;
        }

        $id = $this->input->post('id');

        if ($this->frequency_model->toggleStatus($id)) {
            echo json_output(array('status' => 'success', 'message' => 'Status updated successfully'));
        } else {
            echo json_output(array('status' => 'error', 'message' => 'Failed to update status'));
        }
    }

    /**
     * Get Frequency Data (for AJAX)
     */
    public function get()
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_view')) {
            echo json_output(array('status' => 'error', 'message' => 'Access denied'));
            return;
        }

        $id = $this->input->get('id');
        
        if ($id) {
            $frequency = $this->frequency_model->getById($id);
            echo json_output(array('status' => 'success', 'data' => $frequency));
        } else {
            $frequencies = $this->frequency_model->getAll(false);
            echo json_output(array('status' => 'success', 'data' => $frequencies));
        }
    }
}

