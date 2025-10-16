<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-eye"></i> Contribution Details</h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo base_url(); ?>admin/partnercontributions">Contributions</a></li>
            <li class="active">Contribution Details</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <?php if ($this->session->flashdata('msg')) {
                    echo $this->session->flashdata('msg');
                } ?>

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Contribution Information</h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('partners', 'can_edit')) { ?>
                                <a href="<?php echo base_url('admin/partnercontributions/edit/' . $contribution->id); ?>" 
                                   class="btn btn-primary btn-sm">
                                    <i class="fa fa-edit"></i> Edit
                                </a>
                            <?php } ?>
                            <a href="<?php echo base_url('admin/partnercontributions'); ?>" class="btn btn-default btn-sm">
                                <i class="fa fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered table-striped">
                                    <tr>
                                        <th width="30%">Receipt Number</th>
                                        <td><?php echo $contribution->receipt_no ? $contribution->receipt_no : 'N/A'; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Contribution Date</th>
                                        <td><?php echo date('d M Y', strtotime($contribution->contribution_date)); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Amount</th>
                                        <td>
                                            <strong class="text-success">
                                                <?php echo $contribution->currency . ' ' . number_format($contribution->amount, 2); ?>
                                            </strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Payment Method</th>
                                        <td><?php echo ucfirst(str_replace('_', ' ', $contribution->payment_method)); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
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
                                    </tr>
                                    <tr>
                                        <th>Transaction ID</th>
                                        <td><?php echo $contribution->transaction_id ? $contribution->transaction_id : 'N/A'; ?></td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <table class="table table-bordered table-striped">
                                    <tr>
                                        <th width="30%">Reference Number</th>
                                        <td><?php echo $contribution->reference_no ? $contribution->reference_no : 'N/A'; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Giving Type</th>
                                        <td><?php echo $contribution->type_name ? $contribution->type_name : 'N/A'; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Giving Frequency</th>
                                        <td><?php echo $contribution->frequency_name ? $contribution->frequency_name : 'N/A'; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Recorded By</th>
                                        <td><?php echo $contribution->recorded_by_name ? $contribution->recorded_by_name : 'N/A'; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Recorded Date</th>
                                        <td><?php echo date('d M Y H:i', strtotime($contribution->created_at)); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Last Updated</th>
                                        <td><?php echo date('d M Y H:i', strtotime($contribution->updated_at)); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <?php if (!empty($contribution->notes)) { ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4><i class="fa fa-sticky-note-o"></i> Notes</h4>
                                    <div class="well">
                                        <?php echo nl2br(htmlspecialchars($contribution->notes)); ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if (!empty($contribution->attachment)) { ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4><i class="fa fa-paperclip"></i> Attachment</h4>
                                    <div class="alert alert-info">
                                        <i class="fa fa-file"></i> 
                                        <a href="<?php echo base_url($contribution->attachment); ?>" target="_blank" class="btn btn-sm btn-info">
                                            <i class="fa fa-download"></i> Download Attachment
                                        </a>
                                        <span class="text-muted"><?php echo basename($contribution->attachment); ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <!-- Partner Information -->
                        <?php if (!empty($partner)) { ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4><i class="fa fa-user"></i> Partner Information</h4>
                                    <div class="box box-info">
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <table class="table table-condensed">
                                                        <tr>
                                                            <th width="30%">Name</th>
                                                            <td><?php echo $partner->firstname . ' ' . $partner->lastname; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Code</th>
                                                            <td><?php echo isset($partner->partner_code) ? $partner->partner_code : 'N/A'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Email</th>
                                                            <td><?php echo isset($partner->email) ? $partner->email : 'N/A'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Phone</th>
                                                            <td><?php echo isset($partner->mobileno) ? $partner->mobileno : 'N/A'; ?></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <table class="table table-condensed">
                                                        <tr>
                                                            <th width="30%">Address</th>
                                                            <td><?php echo isset($partner->address) && $partner->address ? $partner->address : 'N/A'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>City</th>
                                                            <td><?php echo isset($partner->city) && $partner->city ? $partner->city : 'N/A'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Country</th>
                                                            <td><?php echo isset($partner->country) && $partner->country ? $partner->country : 'N/A'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Status</th>
                                                            <td>
                                                                <span class="label label-<?php echo isset($partner->is_active) && $partner->is_active ? 'success' : 'danger'; ?>">
                                                                    <?php echo isset($partner->is_active) && $partner->is_active ? 'Active' : 'Inactive'; ?>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box-footer">
                                    <?php if ($this->rbac->hasPrivilege('partners', 'can_edit')) { ?>
                                        <a href="<?php echo base_url('admin/partnercontributions/edit/' . $contribution->id); ?>" 
                                           class="btn btn-primary">
                                            <i class="fa fa-edit"></i> Edit Contribution
                                        </a>
                                    <?php } ?>
                                    
                                    <?php if ($this->rbac->hasPrivilege('partners', 'can_delete')) { ?>
                                        <a href="<?php echo base_url('admin/partnercontributions/delete/' . $contribution->id); ?>" 
                                           class="btn btn-danger" 
                                           onclick="return confirm('Are you sure you want to delete this contribution?');">
                                            <i class="fa fa-trash"></i> Delete Contribution
                                        </a>
                                    <?php } ?>

                                    <a href="<?php echo base_url('admin/partnercontributions'); ?>" class="btn btn-default">
                                        <i class="fa fa-arrow-left"></i> Back to Contributions
                                    </a>

                                    <?php if ($this->rbac->hasPrivilege('partners', 'can_view') && !empty($partner)) { ?>
                                        <a href="<?php echo base_url('admin/partners/show/' . $partner->id); ?>" class="btn btn-info">
                                            <i class="fa fa-user"></i> View Partner Profile
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
