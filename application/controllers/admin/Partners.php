<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Partners extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'partner_model',
            'contribution_model',
            'frequency_model',
            'type_model',
            'permission_model',
            'reminder_model',
            'note_model',
            'student_model'
        ));
        $this->load->library('datatables');
    }

    /**
     * Partner List - Main Index Page
     */
    public function index()
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Partners');
        $this->session->set_userdata('sub_menu', 'admin/partners');

        $data['title'] = 'Partners';
        $data['giving_types'] = $this->type_model->getAll();
        $data['giving_frequencies'] = $this->frequency_model->getAll();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/partners/partnerlist', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * Get Partners Data for DataTable (AJAX)
     */
    public function getlist()
    {
        $filters = array();

        if ($this->input->post('status')) {
            $filters['status'] = $this->input->post('status');
        }

        if ($this->input->post('giving_type_id')) {
            $filters['giving_type_id'] = $this->input->post('giving_type_id');
        }

        if ($this->input->post('giving_frequency_id')) {
            $filters['giving_frequency_id'] = $this->input->post('giving_frequency_id');
        }

        $partners = $this->partner_model->getAll($filters);

        $data = array();
        foreach ($partners as $partner) {
            $row = array();
            $row[] = $partner->partner_code;
            $row[] = $partner->firstname . ' ' . $partner->lastname;
            $row[] = $partner->email;
            $row[] = $partner->mobileno;
            $row[] = $partner->type_name ? $partner->type_name : '-';
            $row[] = $partner->frequency_name ? $partner->frequency_name : '-';
            $row[] = $partner->currency . ' ' . number_format($partner->contribution_amount, 2);
            $row[] = '<span class="label label-' . ($partner->status == 'active' ? 'success' : ($partner->status == 'inactive' ? 'warning' : 'danger')) . '">' . ucfirst($partner->status) . '</span>';

            // Action buttons
            $action = '<div class="btn-group">';
            if ($this->rbac->hasPrivilege('partners', 'can_view')) {
                $action .= '<a href="' . base_url() . 'admin/partners/show/' . $partner->id . '" class="btn btn-default btn-xs" data-toggle="tooltip" title="View Details"><i class="fa fa-eye"></i></a>';
            }
            if ($this->rbac->hasPrivilege('partners', 'can_edit')) {
                $action .= '<a href="' . base_url() . 'admin/partners/edit/' . $partner->id . '" class="btn btn-default btn-xs" data-toggle="tooltip" title="Edit"><i class="fa fa-pencil"></i></a>';
            }
            if ($this->rbac->hasPrivilege('partners', 'can_delete')) {
                $action .= '<a href="' . base_url() . 'admin/partners/delete/' . $partner->id . '" class="btn btn-default btn-xs" data-toggle="tooltip" title="Delete" onclick="return confirm(\'Are you sure you want to delete this partner?\')"><i class="fa fa-trash"></i></a>';
            }
            $action .= '</div>';

            $row[] = $action;
            $data[] = $row;
        }

        $output = array(
            "draw" => intval($this->input->post('draw')),
            "recordsTotal" => count($partners),
            "recordsFiltered" => count($partners),
            "data" => $data
        );

        echo json_output($output);
    }

    /**
     * Add New Partner
     */
    public function add()
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_add')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Partners');
        $this->session->set_userdata('sub_menu', 'admin/partners');

        $data['title'] = 'Add Partner';
        $data['giving_types'] = $this->type_model->getAll();
        $data['giving_frequencies'] = $this->frequency_model->getAll();

        // Form validation rules
        $this->form_validation->set_rules('firstname', 'First Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('lastname', 'Last Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|xss_clean');
        $this->form_validation->set_rules('mobileno', 'Mobile Number', 'trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/partners/partneradd', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $partner_data = array(
                'firstname' => $this->input->post('firstname'),
                'lastname' => $this->input->post('lastname'),
                'email' => $this->input->post('email'),
                'mobileno' => $this->input->post('mobileno'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'country' => $this->input->post('country'),
                'zipcode' => $this->input->post('zipcode'),
                'giving_frequency_id' => $this->input->post('giving_frequency_id'),
                'giving_type_id' => $this->input->post('giving_type_id'),
                'contribution_amount' => $this->input->post('contribution_amount'),
                'currency' => $this->input->post('currency') ? $this->input->post('currency') : 'USD',
                'start_date' => $this->input->post('start_date'),
                'notes' => $this->input->post('notes'),
                'status' => 'active',
                'is_active' => 1,
                'created_by' => $this->customlib->getStaffID()
            );

            // Handle student link
            if ($this->input->post('student_id')) {
                $partner_data['student_id'] = $this->input->post('student_id');
            }

            // Handle image upload
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $fileInfo = pathinfo($_FILES["file"]["name"]);
                $img_name = 'partner_' . time() . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/partner_images/" . $img_name);
                $partner_data['image'] = 'uploads/partner_images/' . $img_name;
            }

            $insert_id = $this->partner_model->add($partner_data);

            if ($insert_id) {
                $this->session->set_flashdata('msg', '<div class="alert alert-success">Partner added successfully</div>');
                redirect('admin/partners/show/' . $insert_id);
            } else {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger">Failed to add partner</div>');
                redirect('admin/partners/add');
            }
        }
    }

    /**
     * Edit Partner
     */
    public function edit($id = null)
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_edit')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Partners');
        $this->session->set_userdata('sub_menu', 'admin/partners');

        $data['title'] = 'Edit Partner';
        $data['partner'] = $this->partner_model->getById($id);

        if (!$data['partner']) {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger">Partner not found</div>');
            redirect('admin/partners');
        }

        $data['giving_types'] = $this->type_model->getAll();
        $data['giving_frequencies'] = $this->frequency_model->getAll();

        // Form validation rules
        $this->form_validation->set_rules('firstname', 'First Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('lastname', 'Last Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|xss_clean');

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/partners/partneredit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $partner_data = array(
                'firstname' => $this->input->post('firstname'),
                'lastname' => $this->input->post('lastname'),
                'email' => $this->input->post('email'),
                'mobileno' => $this->input->post('mobileno'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'country' => $this->input->post('country'),
                'zipcode' => $this->input->post('zipcode'),
                'giving_frequency_id' => $this->input->post('giving_frequency_id'),
                'giving_type_id' => $this->input->post('giving_type_id'),
                'contribution_amount' => $this->input->post('contribution_amount'),
                'currency' => $this->input->post('currency'),
                'start_date' => $this->input->post('start_date'),
                'end_date' => $this->input->post('end_date'),
                'notes' => $this->input->post('notes'),
                'status' => $this->input->post('status')
            );

            // Handle image upload
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $fileInfo = pathinfo($_FILES["file"]["name"]);
                $img_name = 'partner_' . time() . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/partner_images/" . $img_name);
                $partner_data['image'] = 'uploads/partner_images/' . $img_name;
            }

            if ($this->partner_model->update($id, $partner_data)) {
                $this->session->set_flashdata('msg', '<div class="alert alert-success">Partner updated successfully</div>');
                redirect('admin/partners/show/' . $id);
            } else {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger">Failed to update partner</div>');
                redirect('admin/partners/edit/' . $id);
            }
        }
    }

    /**
     * View Partner Details
     */
    public function show($id = null)
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Partners');
        $this->session->set_userdata('sub_menu', 'admin/partners');

        $data['title'] = 'Partner Details';
        $data['partner'] = $this->partner_model->getById($id);

        if (!$data['partner']) {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger">Partner not found</div>');
            redirect('admin/partners');
        }

        // Get partner contributions
        $data['contributions'] = $this->contribution_model->getByPartnerId($id, 5);
        $data['total_contributions'] = $this->contribution_model->getTotalByPartner($id);

        // Get partner permissions
        $data['permissions'] = $this->permission_model->getByPartnerId($id);

        // Get partner notes
        $data['notes'] = $this->note_model->getByPartnerId($id, true);

        // Get partner reminders
        $data['reminders'] = $this->reminder_model->getByPartnerId($id);

        $this->load->view('layout/header', $data);
        $this->load->view('admin/partners/partnershow', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * Delete Partner
     */
    public function delete($id = null)
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_delete')) {
            access_denied();
        }

        if ($this->partner_model->delete($id)) {
            $this->session->set_flashdata('msg', '<div class="alert alert-success">Partner deleted successfully</div>');
        } else {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger">Failed to delete partner</div>');
        }

        redirect('admin/partners');
    }

    /**
     * Change Partner Status (AJAX)
     */
    public function changestatus()
    {
        $id = $this->input->post('partner_id');
        $status = $this->input->post('status');

        if ($this->partner_model->updateStatus($id, $status)) {
            echo json_output(array('status' => 'success', 'message' => 'Status updated successfully'));
        } else {
            echo json_output(array('status' => 'error', 'message' => 'Failed to update status'));
        }
    }
}