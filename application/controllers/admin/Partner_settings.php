<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Partner_settings extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'partner_model',
            'type_model',
            'frequency_model',
            'permission_model',
            'reminder_model',
            'partner_giving_setting_model'
        ));
        $this->load->library('datatables');
    }

    /**
     * Partner Settings Index - Main Settings Page
     */
    public function index()
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Partners');
        $this->session->set_userdata('sub_menu', 'admin/partner_settings');

        $data['title'] = 'Partner Settings';
        
        // Get all settings data
        $data['giving_types'] = $this->type_model->getAll(false); // Get all including inactive
        $data['giving_frequencies'] = $this->frequency_model->getAll(false); // Get all including inactive
        $data['permission_types'] = $this->permission_model->getAllPermissionTypes();
        $data['reminder_templates'] = $this->reminder_model->getAllTemplates();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/partner_settings/index', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * Giving Types Management
     */
    public function giving_types()
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_edit')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Partners');
        $this->session->set_userdata('sub_menu', 'admin/partner_settings/giving_types');

        $data['title'] = 'Giving Types Settings';
        $data['giving_types'] = $this->type_model->getAll(false);

        $this->load->view('layout/header', $data);
        $this->load->view('admin/partner_settings/giving_types', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * Add/Edit Giving Type
     */
    public function save_giving_type()
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_edit')) {
            echo json_output(array('status' => 'error', 'message' => 'Access denied'));
            return;
        }

        $id = $this->input->post('id');
        $name = $this->input->post('name');
        $code = $this->input->post('code');
        $description = $this->input->post('description');
        $is_active = $this->input->post('is_active') ? 1 : 0;

        if (empty($name)) {
            echo json_output(array('status' => 'error', 'message' => 'Name is required'));
            return;
        }

        $data = array(
            'name' => $name,
            'code' => $code,
            'description' => $description,
            'is_active' => $is_active
        );

        if ($id) {
            // Update existing
            if ($this->type_model->update($id, $data)) {
                echo json_output(array('status' => 'success', 'message' => 'Giving type updated successfully'));
            } else {
                echo json_output(array('status' => 'error', 'message' => 'Failed to update giving type'));
            }
        } else {
            // Add new
            if ($this->type_model->add($data)) {
                echo json_output(array('status' => 'success', 'message' => 'Giving type added successfully'));
            } else {
                echo json_output(array('status' => 'error', 'message' => 'Failed to add giving type'));
            }
        }
    }

    /**
     * Delete Giving Type
     */
    public function delete_giving_type()
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_delete')) {
            echo json_output(array('status' => 'error', 'message' => 'Access denied'));
            return;
        }

        $id = $this->input->post('id');

        if ($this->type_model->delete($id)) {
            echo json_output(array('status' => 'success', 'message' => 'Giving type deleted successfully'));
        } else {
            echo json_output(array('status' => 'error', 'message' => 'Cannot delete giving type - it may be in use'));
        }
    }

    /**
     * Giving Frequencies Management
     */
    public function giving_frequencies()
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_edit')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Partners');
        $this->session->set_userdata('sub_menu', 'admin/partner_settings/giving_frequencies');

        $data['title'] = 'Giving Frequencies Settings';
        $data['giving_frequencies'] = $this->frequency_model->getAll(false);

        $this->load->view('layout/header', $data);
        $this->load->view('admin/partner_settings/giving_frequencies', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * Add/Edit Giving Frequency
     */
    public function save_giving_frequency()
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_edit')) {
            echo json_output(array('status' => 'error', 'message' => 'Access denied'));
            return;
        }

        $id = $this->input->post('id');
        $name = $this->input->post('name');
        $code = $this->input->post('code');
        $days_interval = $this->input->post('days_interval');
        $description = $this->input->post('description');
        $is_active = $this->input->post('is_active') ? 1 : 0;

        if (empty($name)) {
            echo json_output(array('status' => 'error', 'message' => 'Name is required'));
            return;
        }

        $data = array(
            'name' => $name,
            'code' => $code,
            'days_interval' => $days_interval,
            'description' => $description,
            'is_active' => $is_active
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
    public function delete_giving_frequency()
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_delete')) {
            echo json_output(array('status' => 'error', 'message' => 'Access denied'));
            return;
        }

        $id = $this->input->post('id');

        if ($this->frequency_model->delete($id)) {
            echo json_output(array('status' => 'success', 'message' => 'Giving frequency deleted successfully'));
        } else {
            echo json_output(array('status' => 'error', 'message' => 'Cannot delete giving frequency - it may be in use'));
        }
    }

    /**
     * Permission Types Management
     */
    public function permissions()
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_edit')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Partners');
        $this->session->set_userdata('sub_menu', 'admin/partner_settings/permissions');

        $data['title'] = 'Permission Types Settings';
        $data['permission_types'] = $this->permission_model->getAllPermissionTypes();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/partner_settings/permissions', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * Add/Edit Permission Type
     */
    public function save_permission_type()
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_edit')) {
            echo json_output(array('status' => 'error', 'message' => 'Access denied'));
            return;
        }

        $id = $this->input->post('id');
        $permission_code = $this->input->post('permission_code');
        $permission_name = $this->input->post('permission_name');
        $description = $this->input->post('description');
        $is_active = $this->input->post('is_active') ? 1 : 0;

        if (empty($permission_code) || empty($permission_name)) {
            echo json_output(array('status' => 'error', 'message' => 'Permission code and name are required'));
            return;
        }

        $data = array(
            'permission_code' => $permission_code,
            'permission_name' => $permission_name,
            'description' => $description,
            'is_active' => $is_active
        );

        if ($id) {
            // Update existing
            if ($this->permission_model->updatePermissionType($id, $data)) {
                echo json_output(array('status' => 'success', 'message' => 'Permission type updated successfully'));
            } else {
                echo json_output(array('status' => 'error', 'message' => 'Failed to update permission type'));
            }
        } else {
            // Add new
            if ($this->permission_model->addPermissionType($data)) {
                echo json_output(array('status' => 'success', 'message' => 'Permission type added successfully'));
            } else {
                echo json_output(array('status' => 'error', 'message' => 'Failed to add permission type'));
            }
        }
    }

    /**
     * Delete Permission Type
     */
    public function delete_permission_type()
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_delete')) {
            echo json_output(array('status' => 'error', 'message' => 'Access denied'));
            return;
        }

        $id = $this->input->post('id');

        if ($this->permission_model->deletePermissionType($id)) {
            echo json_output(array('status' => 'success', 'message' => 'Permission type deleted successfully'));
        } else {
            echo json_output(array('status' => 'error', 'message' => 'Cannot delete permission type - it may be in use'));
        }
    }

    /**
     * Reminder Settings Management
     */
    public function reminders()
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_edit')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Partners');
        $this->session->set_userdata('sub_menu', 'admin/partner_settings/reminders');

        $data['title'] = 'Reminder Settings';
        $data['reminder_templates'] = $this->reminder_model->getAllTemplates();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/partner_settings/reminders', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * Add/Edit Reminder Template
     */
    public function save_reminder_template()
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_edit')) {
            echo json_output(array('status' => 'error', 'message' => 'Access denied'));
            return;
        }

        $id = $this->input->post('id');
        $template_name = $this->input->post('template_name');
        $reminder_type = $this->input->post('reminder_type');
        $timing = $this->input->post('timing');
        $days_before = $this->input->post('days_before');
        $days_after = $this->input->post('days_after');
        $subject = $this->input->post('subject');
        $message = $this->input->post('message');
        $is_active = $this->input->post('is_active') ? 1 : 0;

        if (empty($template_name) || empty($reminder_type) || empty($timing)) {
            echo json_output(array('status' => 'error', 'message' => 'Template name, type, and timing are required'));
            return;
        }

        $data = array(
            'template_name' => $template_name,
            'reminder_type' => $reminder_type,
            'timing' => $timing,
            'days_before' => $days_before,
            'days_after' => $days_after,
            'subject' => $subject,
            'message' => $message,
            'is_active' => $is_active
        );

        if ($id) {
            // Update existing
            if ($this->reminder_model->updateTemplate($id, $data)) {
                echo json_output(array('status' => 'success', 'message' => 'Reminder template updated successfully'));
            } else {
                echo json_output(array('status' => 'error', 'message' => 'Failed to update reminder template'));
            }
        } else {
            // Add new
            if ($this->reminder_model->addTemplate($data)) {
                echo json_output(array('status' => 'success', 'message' => 'Reminder template added successfully'));
            } else {
                echo json_output(array('status' => 'error', 'message' => 'Failed to add reminder template'));
            }
        }
    }

    /**
     * Delete Reminder Template
     */
    public function delete_reminder_template()
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_delete')) {
            echo json_output(array('status' => 'error', 'message' => 'Access denied'));
            return;
        }

        $id = $this->input->post('id');

        if ($this->reminder_model->deleteTemplate($id)) {
            echo json_output(array('status' => 'success', 'message' => 'Reminder template deleted successfully'));
        } else {
            echo json_output(array('status' => 'error', 'message' => 'Failed to delete reminder template'));
        }
    }

    /**
     * Toggle Active Status for any setting
     */
    public function toggle_status()
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_edit')) {
            echo json_output(array('status' => 'error', 'message' => 'Access denied'));
            return;
        }

        $type = $this->input->post('type');
        $id = $this->input->post('id');

        $result = false;

        switch ($type) {
            case 'giving_type':
                $result = $this->type_model->toggleStatus($id);
                break;
            case 'giving_frequency':
                $result = $this->frequency_model->toggleStatus($id);
                break;
            case 'permission_type':
                $result = $this->permission_model->togglePermissionTypeStatus($id);
                break;
            case 'reminder_template':
                $result = $this->reminder_model->toggleTemplateStatus($id);
                break;
        }

        if ($result) {
            echo json_output(array('status' => 'success', 'message' => 'Status updated successfully'));
        } else {
            echo json_output(array('status' => 'error', 'message' => 'Failed to update status'));
        }
    }

    /**
     * Get Settings Data for AJAX
     */
    public function get_settings_data()
    {
        $type = $this->input->get('type');

        switch ($type) {
            case 'giving_types':
                $data = $this->type_model->getAll(false);
                break;
            case 'giving_frequencies':
                $data = $this->frequency_model->getAll(false);
                break;
            case 'permission_types':
                $data = $this->permission_model->getAllPermissionTypes();
                break;
            case 'reminder_templates':
                $data = $this->reminder_model->getAllTemplates();
                break;
            default:
                $data = array();
        }

        echo json_output($data);
    }
}
