<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Partnercontributions extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'contribution_model',
            'partner_model',
            'type_model',
            'frequency_model'
        ));
    }

    /**
     * Contribution List - Main Index Page
     */
    public function index()
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Partners');
        $this->session->set_userdata('sub_menu', 'admin/partnercontributions');

        // Get filters from POST or use defaults
        $filters = array();

        if ($this->input->post('status')) {
            $filters['status'] = $this->input->post('status');
        }

        if ($this->input->post('payment_method')) {
            $filters['payment_method'] = $this->input->post('payment_method');
        }

        if ($this->input->post('giving_type_id')) {
            $filters['giving_type_id'] = $this->input->post('giving_type_id');
        }

        if ($this->input->post('date_from')) {
            $filters['date_from'] = $this->input->post('date_from');
        }

        if ($this->input->post('date_to')) {
            $filters['date_to'] = $this->input->post('date_to');
        }

        if ($this->input->post('search')) {
            $filters['search'] = $this->input->post('search');
        }

        $data['title'] = 'Partner Contributions';
        $data['contributions'] = $this->contribution_model->getAll($filters);
        $data['giving_types'] = $this->type_model->getAll();
        $data['summary'] = $this->contribution_model->getSummary($filters);

        $this->load->view('layout/header', $data);
        $this->load->view('admin/contributions/contributionlist', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * Add New Contribution
     */
    public function add()
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_add')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Partners');
        $this->session->set_userdata('sub_menu', 'admin/partnercontributions');

        $data['title'] = 'Add Contribution';
        $data['partners'] = $this->partner_model->getAll(array('is_active' => 1));
        $data['giving_types'] = $this->type_model->getAll();
        $data['giving_frequencies'] = $this->frequency_model->getAll();

        // Form validation rules
        $this->form_validation->set_rules('partner_id', 'Partner', 'trim|required|xss_clean');
        $this->form_validation->set_rules('contribution_date', 'Contribution Date', 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('payment_method', 'Payment Method', 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/contributions/contributionadd', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $contribution_data = array(
                'partner_id' => $this->input->post('partner_id'),
                'contribution_date' => $this->input->post('contribution_date'),
                'amount' => $this->input->post('amount'),
                'currency' => $this->input->post('currency') ? $this->input->post('currency') : 'USD',
                'payment_method' => $this->input->post('payment_method'),
                'transaction_id' => $this->input->post('transaction_id'),
                'reference_no' => $this->input->post('reference_no'),
                'giving_type_id' => $this->input->post('giving_type_id'),
                'giving_frequency_id' => $this->input->post('giving_frequency_id'),
                'status' => $this->input->post('status') ? $this->input->post('status') : 'completed',
                'notes' => $this->input->post('notes'),
                'recorded_by' => $this->customlib->getStaffID()
            );

            // Handle attachment upload
            if (isset($_FILES["attachment"]) && !empty($_FILES['attachment']['name'])) {
                $fileInfo = pathinfo($_FILES["attachment"]["name"]);
                $file_name = 'contribution_' . time() . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["attachment"]["tmp_name"], "./uploads/partner_contributions/" . $file_name);
                $contribution_data['attachment'] = 'uploads/partner_contributions/' . $file_name;
            }

            // Enable detailed error logging
            $this->db->db_debug = FALSE;
            
            $insert_id = $this->contribution_model->add($contribution_data);

            // Check if insert was successful (insert_id can be 0 if AUTO_INCREMENT is broken, so check !== false)
            if ($insert_id !== false) {
                // If insert_id is 0 (broken AUTO_INCREMENT), get the last inserted record
                if ($insert_id == 0) {
                    $last_contribution = $this->db->select('id')
                                                   ->where('receipt_no', $contribution_data['receipt_no'])
                                                   ->order_by('created_at', 'DESC')
                                                   ->limit(1)
                                                   ->get('partner_contributions')
                                                   ->row();
                    if ($last_contribution) {
                        $insert_id = $last_contribution->id;
                    }
                }
                
                $this->session->set_flashdata('msg', '<div class="alert alert-success">Contribution added successfully</div>');
                redirect('admin/partnercontributions/show/' . $insert_id);
            } else {
                // Get detailed database error
                $db_error = $this->db->error();
                $error_msg = 'Failed to add contribution';
                
                if (!empty($db_error['message'])) {
                    $error_msg .= ': ' . $db_error['message'];
                    // Log to CodeIgniter log file
                    log_message('error', 'Contribution insert failed: ' . print_r($db_error, true));
                    log_message('error', 'Contribution data: ' . print_r($contribution_data, true));
                }
                
                $this->session->set_flashdata('msg', '<div class="alert alert-danger">' . $error_msg . '</div>');
                redirect('admin/partnercontributions/add');
            }
        }
    }

    /**
     * Edit Contribution
     */
    public function edit($id = null)
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_edit')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Partners');
        $this->session->set_userdata('sub_menu', 'admin/partnercontributions');

        $data['title'] = 'Edit Contribution';
        $data['contribution'] = $this->contribution_model->getById($id);

        if (!$data['contribution']) {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger">Contribution not found</div>');
            redirect('admin/partnercontributions');
        }

        $data['partners'] = $this->partner_model->getAll(array('is_active' => 1));
        $data['giving_types'] = $this->type_model->getAll();
        $data['giving_frequencies'] = $this->frequency_model->getAll();

        // Form validation rules
        $this->form_validation->set_rules('partner_id', 'Partner', 'trim|required|xss_clean');
        $this->form_validation->set_rules('contribution_date', 'Contribution Date', 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('payment_method', 'Payment Method', 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/contributions/contributionedit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $contribution_data = array(
                'partner_id' => $this->input->post('partner_id'),
                'contribution_date' => $this->input->post('contribution_date'),
                'amount' => $this->input->post('amount'),
                'currency' => $this->input->post('currency'),
                'payment_method' => $this->input->post('payment_method'),
                'transaction_id' => $this->input->post('transaction_id'),
                'reference_no' => $this->input->post('reference_no'),
                'giving_type_id' => $this->input->post('giving_type_id'),
                'giving_frequency_id' => $this->input->post('giving_frequency_id'),
                'status' => $this->input->post('status'),
                'notes' => $this->input->post('notes')
            );

            // Handle attachment upload
            if (isset($_FILES["attachment"]) && !empty($_FILES['attachment']['name'])) {
                $fileInfo = pathinfo($_FILES["attachment"]["name"]);
                $file_name = 'contribution_' . time() . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["attachment"]["tmp_name"], "./uploads/partner_contributions/" . $file_name);
                $contribution_data['attachment'] = 'uploads/partner_contributions/' . $file_name;
            }

            if ($this->contribution_model->update($id, $contribution_data)) {
                $this->session->set_flashdata('msg', '<div class="alert alert-success">Contribution updated successfully</div>');
                redirect('admin/partnercontributions/show/' . $id);
            } else {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger">Failed to update contribution</div>');
                redirect('admin/partnercontributions/edit/' . $id);
            }
        }
    }

    /**
     * View Contribution Details
     */
    public function show($id = null)
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Partners');
        $this->session->set_userdata('sub_menu', 'admin/partnercontributions');

        $data['title'] = 'Contribution Details';
        $data['contribution'] = $this->contribution_model->getById($id);

        if (!$data['contribution']) {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger">Contribution not found</div>');
            redirect('admin/partnercontributions');
        }

        // Get partner details
        $data['partner'] = $this->partner_model->getById($data['contribution']->partner_id);

        $this->load->view('layout/header', $data);
        $this->load->view('admin/contributions/contributionshow', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * Delete Contribution
     */
    public function delete($id = null)
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_delete')) {
            access_denied();
        }

        if ($this->contribution_model->delete($id)) {
            $this->session->set_flashdata('msg', '<div class="alert alert-success">Contribution deleted successfully</div>');
        } else {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger">Failed to delete contribution</div>');
        }

        redirect('admin/partnercontributions');
    }

    /**
     * Change Contribution Status (AJAX)
     */
    public function changestatus()
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_edit')) {
            echo json_output(array('status' => 'error', 'message' => 'Access denied'));
            return;
        }

        $id = $this->input->post('contribution_id');
        $status = $this->input->post('status');

        if ($this->contribution_model->updateStatus($id, $status)) {
            echo json_output(array('status' => 'success', 'message' => 'Status updated successfully'));
        } else {
            echo json_output(array('status' => 'error', 'message' => 'Failed to update status'));
        }
    }

    /**
     * Approve Contribution
     */
    public function approve()
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_edit')) {
            echo json_output(array('status' => 'error', 'message' => 'Access denied'));
            return;
        }

        $contribution_id = $this->input->post('contribution_id');

        if (!$contribution_id) {
            echo json_output(array('status' => 'error', 'message' => 'Contribution ID is required'));
            return;
        }

        // Approve contribution
        if ($this->contribution_model->approve($contribution_id, $this->customlib->getStaffID())) {
            echo json_output(array('status' => 'success', 'message' => 'Contribution approved successfully'));
        } else {
            echo json_output(array('status' => 'error', 'message' => 'Failed to approve contribution'));
        }
    }

    /**
     * Get contribution summary (AJAX)
     */
    public function getsummary()
    {
        if (!$this->rbac->hasPrivilege('partners', 'can_view')) {
            echo json_output(array('status' => 'error', 'message' => 'Access denied'));
            return;
        }

        $filters = array();

        if ($this->input->post('date_from')) {
            $filters['date_from'] = $this->input->post('date_from');
        }

        if ($this->input->post('date_to')) {
            $filters['date_to'] = $this->input->post('date_to');
        }

        $summary = $this->contribution_model->getSummary($filters);

        echo json_output(array('status' => 'success', 'data' => $summary));
    }
}
