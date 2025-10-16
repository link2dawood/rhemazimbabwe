<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-handshake-o"></i> <?php echo $this->lang->line('partner'); ?> Requests
            <small><?php echo $this->lang->line('pending'); ?> <?php echo $this->lang->line('partner'); ?> Registrations</small>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-list"></i> <?php echo $this->lang->line('pending'); ?> Partner Requests</h3>
                    </div>

                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) { ?>
                            <?php echo $this->session->flashdata('msg'); ?>
                        <?php } ?>

                        <!-- Filters -->
                        <form role="form" method="post" action="<?php echo base_url('admin/partners/requests'); ?>">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Giving Type</label>
                                        <select class="form-control" name="giving_type_id" id="giving_type_id">
                                            <option value="">All Types</option>
                                            <?php foreach ($giving_types as $type) { ?>
                                                <option value="<?php echo $type->id; ?>" <?php echo $this->input->post('giving_type_id') == $type->id ? 'selected' : ''; ?>>
                                                    <?php echo $type->name; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Giving Frequency</label>
                                        <select class="form-control" name="giving_frequency_id" id="giving_frequency_id">
                                            <option value="">All Frequencies</option>
                                            <?php foreach ($giving_frequencies as $frequency) { ?>
                                                <option value="<?php echo $frequency->id; ?>" <?php echo $this->input->post('giving_frequency_id') == $frequency->id ? 'selected' : ''; ?>>
                                                    <?php echo $frequency->name; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>&nbsp;</label><br>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-filter"></i> Filter
                                        </button>
                                        <a href="<?php echo base_url('admin/partners/requests'); ?>" class="btn btn-default">
                                            <i class="fa fa-refresh"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Data Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Partner Code</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Type</th>
                                        <th>Frequency</th>
                                        <th>Amount</th>
                                        <th>Requested On</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($requests)) {
                                        foreach ($requests as $request) { ?>
                                            <tr>
                                                <td><?php echo $request->partner_code; ?></td>
                                                <td>
                                                    <?php
                                                    if ($request->account_type == 'organization') {
                                                        echo $request->organization_name . '<br><small class="text-muted">' . $request->firstname . ' ' . $request->lastname . '</small>';
                                                    } else {
                                                        echo $request->firstname . ' ' . $request->lastname;
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php echo $request->email; ?></td>
                                                <td><?php echo $request->mobileno; ?></td>
                                                <td><?php echo $request->type_name ? $request->type_name : '-'; ?></td>
                                                <td><?php echo $request->frequency_name ? $request->frequency_name : '-'; ?></td>
                                                <td><?php echo $request->currency . ' ' . number_format($request->contribution_amount, 2); ?></td>
                                                <td><?php echo date('d M Y', strtotime($request->created_at)); ?></td>
                                                <td class="text-right">
                                                    <div class="btn-group">
                                                        <?php if ($this->rbac->hasPrivilege('partners', 'can_view')) { ?>
                                                            <a href="<?php echo base_url('admin/partners/show/' . $request->id); ?>"
                                                               class="btn btn-default btn-xs" data-toggle="tooltip" title="View Details">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                        <?php } ?>
                                                        <?php if ($this->rbac->hasPrivilege('partners', 'can_edit')) { ?>
                                                            <a href="javascript:void(0);"
                                                               onclick="approvePartner(<?php echo $request->id; ?>)"
                                                               class="btn btn-success btn-xs" data-toggle="tooltip" title="Approve">
                                                                <i class="fa fa-check"></i>
                                                            </a>
                                                            <a href="javascript:void(0);"
                                                               onclick="rejectPartner(<?php echo $request->id; ?>)"
                                                               class="btn btn-danger btn-xs" data-toggle="tooltip" title="Reject">
                                                                <i class="fa fa-times"></i>
                                                            </a>
                                                        <?php } ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php }
                                    } else { ?>
                                        <tr>
                                            <td colspan="9" class="text-center">
                                                <div class="alert alert-info">
                                                    <i class="fa fa-info-circle"></i> No pending partner requests found.
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <?php if (!empty($requests)) { ?>
                            <div class="box-footer clearfix">
                                <div class="pull-left">
                                    <p class="text-muted">Total Pending Requests: <strong><?php echo count($requests); ?></strong></p>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-check"></i> Approve Partner Request</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to approve this partner registration?</p>
                <div class="form-group">
                    <label>Approval Note (Optional)</label>
                    <textarea class="form-control" id="approve_reason" rows="3" placeholder="Add any notes about this approval..."></textarea>
                </div>
                <input type="hidden" id="approve_partner_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="confirmApprove()">
                    <i class="fa fa-check"></i> Approve
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-times"></i> Reject Partner Request</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to reject this partner registration?</p>
                <div class="form-group">
                    <label>Rejection Reason (Optional)</label>
                    <textarea class="form-control" id="reject_reason" rows="3" placeholder="Provide a reason for rejection..."></textarea>
                </div>
                <input type="hidden" id="reject_partner_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmReject()">
                    <i class="fa fa-times"></i> Reject
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });

    function approvePartner(partnerId) {
        $('#approve_partner_id').val(partnerId);
        $('#approve_reason').val('');
        $('#approveModal').modal('show');
    }

    function rejectPartner(partnerId) {
        $('#reject_partner_id').val(partnerId);
        $('#reject_reason').val('');
        $('#rejectModal').modal('show');
    }

    function confirmApprove() {
        var partnerId = $('#approve_partner_id').val();
        var reason = $('#approve_reason').val();

        $.ajax({
            url: '<?php echo base_url(); ?>admin/partners/approve',
            type: 'POST',
            data: {
                partner_id: partnerId,
                reason: reason
            },
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    $('#approveModal').modal('hide');
                    successMsg(response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    errorMsg(response.message);
                }
            },
            error: function() {
                errorMsg('An error occurred. Please try again.');
            }
        });
    }

    function confirmReject() {
        var partnerId = $('#reject_partner_id').val();
        var reason = $('#reject_reason').val();

        $.ajax({
            url: '<?php echo base_url(); ?>admin/partners/reject',
            type: 'POST',
            data: {
                partner_id: partnerId,
                reason: reason
            },
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    $('#rejectModal').modal('hide');
                    successMsg(response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    errorMsg(response.message);
                }
            },
            error: function() {
                errorMsg('An error occurred. Please try again.');
            }
        });
    }

    function successMsg(msg) {
        var alertHtml = '<div class="alert alert-success alert-dismissible" role="alert">' +
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
            '<span aria-hidden="true">&times;</span></button>' + msg + '</div>';
        $('.box-body').prepend(alertHtml);
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 3000);
    }

    function errorMsg(msg) {
        var alertHtml = '<div class="alert alert-danger alert-dismissible" role="alert">' +
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
            '<span aria-hidden="true">&times;</span></button>' + msg + '</div>';
        $('.box-body').prepend(alertHtml);
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 3000);
    }
</script>
