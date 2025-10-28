<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Giving Types Controller
 * Manages CRUD operations for giving types
 *
 * @author Rhema Zimbabwe School
 * @version 1.0
 */
class Givingtypes extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('type_model');
        $this->load->library('form_validation');
        $this->load->helper('json_output');
    }

    /**
     * Display list of all giving types
     */
    public function index()
    {
        // Check permission
        if (!$this->rbac->hasPrivilege('partners', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Partners');
        $this->session->set_userdata('sub_menu', 'admin/givingtypes');

        $data['title'] = 'Giving Types';
        $data['giving_types'] = $this->type_model->getWithCounts();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/givingtypes/index', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * Get giving types data for DataTable (AJAX)
     */
    public function getlist()
    {
        $giving_types = $this->type_model->getWithCounts();

        $data = array();
        foreach ($giving_types as $type) {
            $row = array();
            $row[] = $type->name;
            $row[] = $type->code ? '<span class="label label-info">' . $type->code . '</span>' : '<span class="text-muted">N/A</span>';
            $row[] = substr($type->description, 0, 50) . (strlen($type->description) > 50 ? '...' : '');
            $row[] = '<span class="badge bg-blue">' . $type->partner_count . ' partners</span>';
            $row[] = $type->is_active
                ? '<span class="label label-success">Active</span>'
                : '<span class="label label-danger">Inactive</span>';
            $row[] = $type->sort_order;

            // Action buttons
            $action = '<div class="btn-group">';
            if ($this->rbac->hasPrivilege('partners', 'can_view')) {
                $action .= '<a href="' . base_url() . 'admin/givingtypes/show/' . $type->id . '" class="btn btn-default btn-xs" data-toggle="tooltip" title="View Details"><i class="fa fa-eye"></i></a>';
            }
            if ($this->rbac->hasPrivilege('partners', 'can_edit')) {
                $action .= '<a href="' . base_url() . 'admin/givingtypes/edit/' . $type->id . '" class="btn btn-default btn-xs" data-toggle="tooltip" title="Edit"><i class="fa fa-pencil"></i></a>';
            }
            if ($this->rbac->hasPrivilege('partners', 'can_delete') && $type->partner_count == 0) {
                $action .= '<a href="' . base_url() . 'admin/givingtypes/delete/' . $type->id . '" class="btn btn-danger btn-xs" data-toggle="tooltip" title="Delete" onclick="return confirm(\'Are you sure you want to delete this giving type?\')"><i class="fa fa-trash"></i></a>';
            }
            $action .= '</div>';

            $row[] = $action;
            $data[] = $row;
        }

        $output = array(
            "draw" => intval($this->input->post('draw')),
            "recordsTotal" => count($giving_types),
            "recordsFiltered" => count($giving_types),
            "data" => $data
        );

        echo json_encode($output);
    }

    /**
     * Show add new giving type form
     */
    public function add()
    {
        // Check permission
        if (!$this->rbac->hasPrivilege('partners', 'can_add')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Partners');
        $this->session->set_userdata('sub_menu', 'admin/givingtypes');

        $data['title'] = 'Add Giving Type';

        // Set validation rules
        $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('code', 'Code', 'trim|xss_clean');
        $this->form_validation->set_rules('description', 'Description', 'trim|xss_clean');
        $this->form_validation->set_rules('sort_order', 'Sort Order', 'trim|numeric|xss_clean');

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/givingtypes/add', $data);
            $this->load->view('layout/footer', $data);
        } else {
            // Check if name already exists
            $existing_name = $this->db->where('name', $this->input->post('name'))->get('giving_types')->row();
            if ($existing_name) {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger">A giving type with this name already exists.</div>');
                redirect('admin/givingtypes/add');
                return;
            }

            // Check if code already exists (if provided)
            $code = $this->input->post('code');
            if (!empty($code)) {
                $existing_code = $this->db->where('code', $code)->get('giving_types')->row();
                if ($existing_code) {
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger">A giving type with this code already exists.</div>');
                    redirect('admin/givingtypes/add');
                    return;
                }
            }

            $giving_type_data = array(
                'name' => $this->input->post('name'),
                'code' => $code,
                'description' => $this->input->post('description'),
                'is_active' => $this->input->post('is_active') ? 1 : 0,
                'sort_order' => $this->input->post('sort_order') ? $this->input->post('sort_order') : 0
            );

            $insert_id = $this->type_model->add($giving_type_data);

            if ($insert_id) {
                $this->session->set_flashdata('msg', '<div class="alert alert-success">Giving type added successfully</div>');
                redirect('admin/givingtypes/show/' . $insert_id);
            } else {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger">Failed to add giving type</div>');
                redirect('admin/givingtypes/add');
            }
        }
    }

    /**
     * Show edit giving type form
     */
    public function edit($id = null)
    {
        // Check permission
        if (!$this->rbac->hasPrivilege('partners', 'can_edit')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Partners');
        $this->session->set_userdata('sub_menu', 'admin/givingtypes');

        $data['title'] = 'Edit Giving Type';
        $data['giving_type'] = $this->type_model->getById($id);

        if (!$data['giving_type']) {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger">Giving type not found</div>');
            redirect('admin/givingtypes');
        }

        // Set validation rules
        $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('code', 'Code', 'trim|xss_clean');
        $this->form_validation->set_rules('description', 'Description', 'trim|xss_clean');
        $this->form_validation->set_rules('sort_order', 'Sort Order', 'trim|numeric|xss_clean');

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/givingtypes/edit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            // Check if name already exists (excluding current record)
            $existing_name = $this->db->where('name', $this->input->post('name'))
                                      ->where('id !=', $id)
                                      ->get('giving_types')->row();
            if ($existing_name) {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger">A giving type with this name already exists.</div>');
                redirect('admin/givingtypes/edit/' . $id);
                return;
            }

            // Check if code already exists (if provided, excluding current record)
            $code = $this->input->post('code');
            if (!empty($code)) {
                $existing_code = $this->db->where('code', $code)
                                          ->where('id !=', $id)
                                          ->get('giving_types')->row();
                if ($existing_code) {
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger">A giving type with this code already exists.</div>');
                    redirect('admin/givingtypes/edit/' . $id);
                    return;
                }
            }

            $giving_type_data = array(
                'name' => $this->input->post('name'),
                'code' => $code,
                'description' => $this->input->post('description'),
                'is_active' => $this->input->post('is_active') ? 1 : 0,
                'sort_order' => $this->input->post('sort_order') ? $this->input->post('sort_order') : 0
            );

            if ($this->type_model->update($id, $giving_type_data)) {
                $this->session->set_flashdata('msg', '<div class="alert alert-success">Giving type updated successfully</div>');
                redirect('admin/givingtypes/show/' . $id);
            } else {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger">Failed to update giving type</div>');
                redirect('admin/givingtypes/edit/' . $id);
            }
        }
    }

    /**
     * Show giving type details
     */
    public function show($id = null)
    {
        // Check permission
        if (!$this->rbac->hasPrivilege('partners', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Partners');
        $this->session->set_userdata('sub_menu', 'admin/givingtypes');

        $data['title'] = 'Giving Type Details';
        $data['giving_type'] = $this->type_model->getById($id);

        if (!$data['giving_type']) {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger">Giving type not found</div>');
            redirect('admin/givingtypes');
        }

        // Get usage count
        $data['usage_count'] = $this->type_model->getUsageCount($id);

        // Get partners using this giving type
        $this->load->model('partner_model');
        $data['partners'] = $this->partner_model->getAll(array('giving_type_id' => $id));

        $this->load->view('layout/header', $data);
        $this->load->view('admin/givingtypes/show', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * Delete giving type
     */
    public function delete($id = null)
    {
        // Check permission
        if (!$this->rbac->hasPrivilege('partners', 'can_delete')) {
            access_denied();
        }

        // Check if giving type is being used
        $usage_count = $this->type_model->getUsageCount($id);
        if ($usage_count > 0) {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger">Cannot delete this giving type. It is currently being used by ' . $usage_count . ' partner(s).</div>');
            redirect('admin/givingtypes');
            return;
        }

        if ($this->type_model->delete($id)) {
            $this->session->set_flashdata('msg', '<div class="alert alert-success">Giving type deleted successfully</div>');
        } else {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger">Failed to delete giving type</div>');
        }

        redirect('admin/givingtypes');
    }

    /**
     * Toggle active status (AJAX)
     */
    public function toggle_status($id = null)
    {
        // Check permission
        if (!$this->rbac->hasPrivilege('partners', 'can_edit')) {
            echo json_encode(array('status' => 'error', 'message' => 'Access denied'));
            return;
        }

        if ($this->type_model->toggleStatus($id)) {
            echo json_encode(array('status' => 'success', 'message' => 'Status updated successfully'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Failed to update status'));
        }
    }

    /**
     * Update sort order (AJAX)
     */
    public function update_sort_order()
    {
        // Check permission
        if (!$this->rbac->hasPrivilege('partners', 'can_edit')) {
            echo json_encode(array('status' => 'error', 'message' => 'Access denied'));
            return;
        }

        $order_data = $this->input->post('order');

        if ($this->type_model->reorder($order_data)) {
            echo json_encode(array('status' => 'success', 'message' => 'Sort order updated successfully'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Failed to update sort order'));
        }
    }
}
