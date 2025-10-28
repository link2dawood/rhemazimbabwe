<div class="content-wrapper" style="min-height: 946px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-user"></i> My Partner Information
            <small>Complete partner profile details</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>user/user/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo base_url(); ?>user/partner">Partners</a></li>
            <li><a href="<?php echo base_url(); ?>user/partner_reports">Reports</a></li>
            <li class="active">My Information</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <?php if (!empty($partners)): ?>
    <?php foreach ($partners as $partner): ?>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-user"></i> Partner Information
                        <span class="pull-right">
                            <span class="label label-info"><?php echo $partner['partner_code']; ?></span>
                        </span>
                    </h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <!-- Personal Information -->
                        <div class="col-md-6">
                            <h4><i class="fa fa-user text-blue"></i> Personal Information</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Full Name:</th>
                                    <td><strong><?php echo $partner['firstname'] . ' ' . $partner['lastname']; ?></strong></td>
                                </tr>
                                <tr>
                                    <th>Account Type:</th>
                                    <td>
                                        <span class="label label-<?php echo $partner['account_type'] == 'individual' ? 'primary' : 'success'; ?>">
                                            <?php echo ucfirst($partner['account_type']); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php if ($partner['account_type'] == 'organization' && $partner['organization_name']): ?>
                                <tr>
                                    <th>Organization:</th>
                                    <td><strong><?php echo $partner['organization_name']; ?></strong></td>
                                </tr>
                                <tr>
                                    <th>Organization Type:</th>
                                    <td><?php echo $partner['organization_type']; ?></td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <th>Email:</th>
                                    <td><i class="fa fa-envelope"></i> <?php echo $partner['email']; ?></td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td><i class="fa fa-phone"></i> <?php echo $partner['mobileno']; ?></td>
                                </tr>
                                <tr>
                                    <th>Address:</th>
                                    <td>
                                        <i class="fa fa-map-marker"></i> 
                                        <?php echo $partner['address']; ?><br>
                                        <?php echo $partner['city'] . ', ' . $partner['state'] . ', ' . $partner['country']; ?><br>
                                        <?php if ($partner['zip_code']): ?>
                                        Postal Code: <?php echo $partner['zip_code']; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- Giving Information -->
                        <div class="col-md-6">
                            <h4><i class="fa fa-gift text-green"></i> Giving Information</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Giving Type:</th>
                                    <td><?php echo isset($partner['type_name']) && $partner['type_name'] ? $partner['type_name'] : 'Not Set'; ?></td>
                                </tr>
                                <tr>
                                    <th>Frequency:</th>
                                    <td><?php echo isset($partner['frequency_name']) && $partner['frequency_name'] ? $partner['frequency_name'] : 'Not Set'; ?></td>
                                </tr>
                                <tr>
                                    <th>Amount:</th>
                                    <td><strong><?php echo $partner['currency'] . ' ' . number_format($partner['contribution_amount'], 2); ?></strong></td>
                                </tr>
                                <tr>
                                    <th>Start Date:</th>
                                    <td><?php echo $partner['start_date'] ? date('d M Y', strtotime($partner['start_date'])) : 'Not Set'; ?></td>
                                </tr>
                                <tr>
                                    <th>End Date:</th>
                                    <td><?php echo $partner['end_date'] ? date('d M Y', strtotime($partner['end_date'])) : 'Ongoing'; ?></td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="label label-<?php 
                                            echo $partner['status'] == 'active' ? 'success' : 
                                                ($partner['status'] == 'inactive' ? 'warning' : 'danger'); 
                                        ?>">
                                            <?php echo ucfirst($partner['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Registration:</th>
                                    <td><?php echo date('d M Y', strtotime($partner['created_at'])); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Notes Section -->
                    <?php if ($partner['notes']): ?>
                    <div class="row">
                        <div class="col-md-12">
                            <h4><i class="fa fa-sticky-note text-yellow"></i> Notes</h4>
                            <div class="alert alert-info">
                                <?php echo nl2br($partner['notes']); ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Quick Actions -->
                    <div class="row">
                        <div class="col-md-12">
                            <h4><i class="fa fa-cogs text-purple"></i> Quick Actions</h4>
                            <div class="btn-group">
                                <a href="<?php echo base_url(); ?>user/partner_reports/partner_statement" class="btn btn-success">
                                    <i class="fa fa-file-text-o"></i> View Statement
                                </a>
                                <a href="<?php echo base_url(); ?>partnerportal/login" class="btn btn-info">
                                    <i class="fa fa-sign-in"></i> Partner Login
                                </a>
                                <?php if ($partner['status'] == 'active'): ?>
                                <a href="<?php echo base_url(); ?>user/partner/contribute" class="btn btn-primary">
                                    <i class="fa fa-plus"></i> Make Contribution
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-warning">
                <h4><i class="fa fa-warning"></i> No Partner Information Found</h4>
                <p>You don't have any partner accounts associated with your profile. If you believe this is an error, please contact the school administration.</p>
                <a href="<?php echo base_url(); ?>user/partner/register" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Become a Partner
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>
    </section>
</div>

<script type="text/javascript">
$(document).ready(function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
