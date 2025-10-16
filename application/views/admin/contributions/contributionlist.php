<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-money"></i> Partner Contributions</h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Summary Cards -->
                <div class="row">
                    <div class="col-lg-3 col-sm-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-aqua"><i class="fa fa-money"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Contributions</span>
                                <span class="info-box-number">$<?php echo number_format($summary->total_amount, 2); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-green"><i class="fa fa-check"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Completed</span>
                                <span class="info-box-number"><?php echo $summary->completed_count; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-yellow"><i class="fa fa-clock-o"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Pending</span>
                                <span class="info-box-number"><?php echo $summary->pending_count; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-red"><i class="fa fa-hourglass"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Pending Amount</span>
                                <span class="info-box-number">$<?php echo number_format($summary->pending_amount, 2); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Contribution List</h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('partners', 'can_add')) { ?>
                                <a href="<?php echo base_url() ?>admin/partnercontributions/add" class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus"></i> Add Contribution
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) {
                            echo $this->session->flashdata('msg');
                        } ?>

                        <!-- Filters -->
                        <form role="form" id="searchform" method="post" action="<?php echo base_url('admin/partnercontributions'); ?>">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="form-control" name="status" id="status">
                                            <option value="">All Status</option>
                                            <option value="completed" <?php echo $this->input->post('status') == 'completed' ? 'selected' : ''; ?>>Completed</option>
                                            <option value="pending" <?php echo $this->input->post('status') == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="cancelled" <?php echo $this->input->post('status') == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                            <option value="failed" <?php echo $this->input->post('status') == 'failed' ? 'selected' : ''; ?>>Failed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Payment Method</label>
                                        <select class="form-control" name="payment_method" id="payment_method">
                                            <option value="">All Methods</option>
                                            <option value="cash" <?php echo $this->input->post('payment_method') == 'cash' ? 'selected' : ''; ?>>Cash</option>
                                            <option value="bank_transfer" <?php echo $this->input->post('payment_method') == 'bank_transfer' ? 'selected' : ''; ?>>Bank Transfer</option>
                                            <option value="mobile_money" <?php echo $this->input->post('payment_method') == 'mobile_money' ? 'selected' : ''; ?>>Mobile Money</option>
                                            <option value="credit_card" <?php echo $this->input->post('payment_method') == 'credit_card' ? 'selected' : ''; ?>>Credit Card</option>
                                            <option value="debit_card" <?php echo $this->input->post('payment_method') == 'debit_card' ? 'selected' : ''; ?>>Debit Card</option>
                                            <option value="cheque" <?php echo $this->input->post('payment_method') == 'cheque' ? 'selected' : ''; ?>>Cheque</option>
                                            <option value="online" <?php echo $this->input->post('payment_method') == 'online' ? 'selected' : ''; ?>>Online</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Giving Type</label>
                                        <select class="form-control" name="giving_type_id" id="giving_type_id">
                                            <option value="">All Types</option>
                                            <?php foreach ($giving_types as $type) { ?>
                                                <option value="<?php echo $type->id ?>" <?php echo $this->input->post('giving_type_id') == $type->id ? 'selected' : ''; ?>>
                                                    <?php echo $type->name ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>From Date</label>
                                        <input type="date" class="form-control" name="date_from" value="<?php echo $this->input->post('date_from'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>To Date</label>
                                        <input type="date" class="form-control" name="date_to" value="<?php echo $this->input->post('date_to'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fa fa-search"></i> Search
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Receipt No</th>
                                        <th>Date</th>
                                        <th>Partner</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Payment Method</th>
                                        <th>Status</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($contributions)) {
                                        foreach ($contributions as $contribution) { ?>
                                            <tr>
                                                <td><?php echo $contribution->receipt_no ? $contribution->receipt_no : '-'; ?></td>
                                                <td><?php echo date('d M Y', strtotime($contribution->contribution_date)); ?></td>
                                                <td>
                                                    <?php echo $contribution->partner_firstname . ' ' . $contribution->partner_lastname; ?><br>
                                                    <small class="text-muted"><?php echo $contribution->partner_code; ?></small>
                                                </td>
                                                <td><?php echo $contribution->type_name ? $contribution->type_name : '-'; ?></td>
                                                <td><?php echo $contribution->currency . ' ' . number_format($contribution->amount, 2); ?></td>
                                                <td><?php echo ucfirst(str_replace('_', ' ', $contribution->payment_method)); ?></td>
                                                <td>
                                                    <?php
                                                    $status_class = 'warning';
                                                    if ($contribution->status == 'completed') {
                                                        $status_class = 'success';
                                                    } elseif ($contribution->status == 'cancelled' || $contribution->status == 'failed') {
                                                        $status_class = 'danger';
                                                    }
                                                    ?>
                                                    <span class="label label-<?php echo $status_class; ?>">
                                                        <?php echo ucfirst($contribution->status); ?>
                                                    </span>
                                                </td>
                                                <td class="text-right">
                                                    <div class="btn-group">
                                                        <?php if ($this->rbac->hasPrivilege('partners', 'can_view')) { ?>
                                                            <a href="<?php echo base_url('admin/partnercontributions/show/' . $contribution->id); ?>"
                                                               class="btn btn-default btn-xs" data-toggle="tooltip" title="View">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                        <?php } ?>
                                                        <?php if ($this->rbac->hasPrivilege('partners', 'can_edit')) { ?>
                                                            <a href="<?php echo base_url('admin/partnercontributions/edit/' . $contribution->id); ?>"
                                                               class="btn btn-default btn-xs" data-toggle="tooltip" title="Edit">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                        <?php } ?>
                                                        <?php if ($this->rbac->hasPrivilege('partners', 'can_delete')) { ?>
                                                            <a href="<?php echo base_url('admin/partnercontributions/delete/' . $contribution->id); ?>"
                                                               class="btn btn-default btn-xs" data-toggle="tooltip" title="Delete"
                                                               onclick="return confirm('Are you sure you want to delete this contribution?');">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        <?php } ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php }
                                    } else { ?>
                                        <tr>
                                            <td colspan="8" class="text-center">
                                                <div class="alert alert-info">
                                                    <i class="fa fa-info-circle"></i> No contributions found.
                                                    <?php if ($this->rbac->hasPrivilege('partners', 'can_add')) { ?>
                                                        <a href="<?php echo base_url('admin/partnercontributions/add'); ?>" class="btn btn-sm btn-primary">
                                                            <i class="fa fa-plus"></i> Add New Contribution
                                                        </a>
                                                    <?php } ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <?php if (!empty($contributions)) { ?>
                            <div class="box-footer clearfix">
                                <div class="pull-left">
                                    <p class="text-muted">Total Records: <strong><?php echo count($contributions); ?></strong></p>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
