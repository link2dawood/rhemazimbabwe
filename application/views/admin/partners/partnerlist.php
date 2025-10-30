<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-handshake-o"></i> <?php echo $this->lang->line('partners'); ?></h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary" id="partnerlist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Partner List</h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('partners', 'can_add')) { ?>
                                <a href="<?php echo base_url() ?>admin/partners/add" class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus"></i> Add Partner
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) {
                            echo $this->session->flashdata('msg');
                        } ?>

                        <!-- Filters -->
                        <form role="form" id="searchform" method="post" action="<?php echo base_url('admin/partners'); ?>">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="form-control" name="status" id="status">
                                            <option value="">All Status</option>
                                            <option value="active" <?php echo $this->input->post('status') == 'active' ? 'selected' : ''; ?>>Active</option>
                                            <option value="inactive" <?php echo $this->input->post('status') == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                            <option value="suspended" <?php echo $this->input->post('status') == 'suspended' ? 'selected' : ''; ?>>Suspended</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
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
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Giving Frequency</label>
                                        <select class="form-control" name="giving_frequency_id" id="giving_frequency_id">
                                            <option value="">All Frequencies</option>
                                            <?php foreach ($giving_frequencies as $frequency) { ?>
                                                <option value="<?php echo $frequency->id ?>" <?php echo $this->input->post('giving_frequency_id') == $frequency->id ? 'selected' : ''; ?>>
                                                    <?php echo $frequency->name ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
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
                                        <th>Partner Code</th>
                                        <th>Partner Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Giving Type</th>
                                        <th>Frequency</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($partners)) {
                                        foreach ($partners as $partner) { ?>
                                            <tr>
                                                <td><?php echo $partner->partner_code; ?></td>
                                                <td>
                                                    <?php
                                                    if ($partner->account_type == 'organization') {
                                                        echo $partner->organization_name . '<br><small class="text-muted">' . $partner->firstname . ' ' . $partner->lastname . '</small>';
                                                    } else {
                                                        echo $partner->firstname . ' ' . $partner->lastname;
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php echo $partner->email; ?></td>
                                                <td><?php echo $partner->mobileno; ?></td>
                                                <td><?php echo $partner->type_name ? $partner->type_name : '-'; ?></td>
                                                <td><?php echo $partner->frequency_name ? $partner->frequency_name : '-'; ?></td>
                                                <td><?php echo $partner->currency . ' ' . number_format($partner->contribution_amount, 2); ?></td>
                                                <td>
                                                    <?php
                                                    $status_class = 'warning';
                                                    if ($partner->status == 'active') {
                                                        $status_class = 'success';
                                                    } elseif ($partner->status == 'suspended') {
                                                        $status_class = 'danger';
                                                    } elseif ($partner->status == 'pending') {
                                                        $status_class = 'warning';
                                                    }
                                                    ?>
                                                    <span class="label label-<?php echo $status_class; ?>">
                                                        <?php echo ucfirst($partner->status); ?>
                                                    </span>
                                                </td>
                                                <td class="text-right">
                                                    <div class="btn-group">
                                                        <?php if ($this->rbac->hasPrivilege('partners', 'can_view')) { ?>
                                                            <a href="<?php echo base_url('admin/partners/show/' . $partner->id); ?>"
                                                               class="btn btn-default btn-xs" data-toggle="tooltip" title="View">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                        <?php } ?>
                                                        <?php if ($this->rbac->hasPrivilege('partners', 'can_edit')) { ?>
                                                            <a href="<?php echo base_url('admin/partners/edit/' . $partner->id); ?>"
                                                               class="btn btn-default btn-xs" data-toggle="tooltip" title="Edit">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                            <a href="<?php echo base_url('admin/partners/permissions/' . $partner->id); ?>"
                                                               class="btn btn-warning btn-xs" data-toggle="tooltip" title="Permissions">
                                                                <i class="fa fa-lock"></i>
                                                            </a>
                                                        <?php } ?>
                                                        <?php if ($this->rbac->hasPrivilege('partners', 'can_delete')) { ?>
                                                            <a href="<?php echo base_url('admin/partners/delete/' . $partner->id); ?>"
                                                               class="btn btn-default btn-xs" data-toggle="tooltip" title="Delete"
                                                               onclick="return confirm('Are you sure you want to delete this partner?');">
                                                                <i class="fa fa-trash"></i>
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
                                                    <i class="fa fa-info-circle"></i> No partners found.
                                                    <?php if ($this->rbac->hasPrivilege('partners', 'can_add')) { ?>
                                                        <a href="<?php echo base_url('admin/partners/add'); ?>" class="btn btn-sm btn-primary">
                                                            <i class="fa fa-plus"></i> Add New Partner
                                                        </a>
                                                    <?php } ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <?php if (!empty($partners)) { ?>
                            <div class="box-footer clearfix">
                                <div class="pull-left">
                                    <p class="text-muted">Total Partners: <strong><?php echo count($partners); ?></strong></p>
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
