<!-- Dashboard Content -->
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3><?php echo isset($statistics['total_contributions']) ? $statistics['total_contributions'] : '0'; ?></h3>
                <p>Total Contributions</p>
            </div>
            <div class="icon">
                <i class="fa fa-money"></i>
            </div>
            <a href="<?php echo base_url('partnerdashboard/contributions'); ?>" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
            <div class="inner">
                <h3><?php echo isset($statistics['total_amount']) ? '$' . number_format($statistics['total_amount'], 2) : '$0.00'; ?></h3>
                <p>Total Amount</p>
            </div>
            <div class="icon">
                <i class="fa fa-dollar"></i>
            </div>
            <a href="<?php echo base_url('partnerdashboard/contributions'); ?>" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3><?php echo isset($statistics['this_year_amount']) ? '$' . number_format($statistics['this_year_amount'], 2) : '$0.00'; ?></h3>
                <p>This Year</p>
            </div>
            <div class="icon">
                <i class="fa fa-calendar"></i>
            </div>
            <a href="<?php echo base_url('partnerdashboard/contributions'); ?>" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-red">
            <div class="inner">
                <h3><?php echo isset($statistics['last_contribution']) ? date('M d', strtotime($statistics['last_contribution'])) : 'Never'; ?></h3>
                <p>Last Contribution</p>
            </div>
            <div class="icon">
                <i class="fa fa-clock-o"></i>
            </div>
            <a href="<?php echo base_url('partnerdashboard/contributions'); ?>" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Partner Information -->
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Partner Information</h3>
            </div>
            <div class="box-body">
                <div class="text-center">
                    <img class="profile-user-img img-responsive img-circle" src="<?php echo base_url('backend/images/avatar5.png'); ?>" alt="User profile picture">
                    <h3 class="profile-username text-center"><?php echo $partner['firstname'] . ' ' . $partner['lastname']; ?></h3>
                    <p class="text-muted text-center">Partner Code: <?php echo $partner['partner_code']; ?></p>
                </div>
                
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <b>Email</b> <a class="pull-right"><?php echo $partner['email']; ?></a>
                    </li>
                    <li class="list-group-item">
                        <b>Phone</b> <a class="pull-right"><?php echo $partner['mobileno']; ?></a>
                    </li>
                    <li class="list-group-item">
                        <b>Account Type</b> <a class="pull-right"><?php echo ucfirst($partner['account_type']); ?></a>
                    </li>
                    <li class="list-group-item">
                        <b>Status</b> <a class="pull-right"><span class="label label-success"><?php echo ucfirst($partner['status']); ?></span></a>
                    </li>
                    <li class="list-group-item">
                        <b>Member Since</b> <a class="pull-right"><?php echo date('M d, Y', strtotime($partner['created_at'])); ?></a>
                    </li>
                </ul>
                
                <a href="<?php echo base_url('partnerdashboard/profile'); ?>" class="btn btn-primary btn-block"><b>Update Profile</b></a>
            </div>
        </div>
    </div>
    
    <!-- Recent Contributions -->
    <div class="col-md-8">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Recent Contributions</h3>
                <div class="box-tools pull-right">
                    <a href="<?php echo base_url('partnerdashboard/contributions'); ?>" class="btn btn-sm btn-info">View All</a>
                </div>
            </div>
            <div class="box-body">
                <?php if (!empty($recent_contributions)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_contributions as $contribution): ?>
                                <tr>
                                    <td><?php echo date('M d, Y', strtotime($contribution['contribution_date'])); ?></td>
                                    <td><?php echo !empty($contribution['giving_type_name']) ? $contribution['giving_type_name'] : 'General'; ?></td>
                                    <td>$<?php echo number_format($contribution['amount'], 2); ?></td>
                                    <td>
                                        <span class="label label-<?php echo $contribution['status'] == 'completed' ? 'success' : 'warning'; ?>">
                                            <?php echo ucfirst($contribution['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?php echo base_url('partnerdashboard/receipt/' . $contribution['id']); ?>" class="btn btn-xs btn-info">
                                            <i class="fa fa-download"></i> Receipt
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center">
                        <i class="fa fa-money fa-3x text-muted"></i>
                        <h4 class="text-muted">No contributions yet</h4>
                        <p class="text-muted">Your contribution history will appear here once you make your first contribution.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-md-12">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Quick Actions</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-2">
                        <a href="#" data-toggle="modal" data-target="#addContributionModal" class="btn btn-app">
                            <span class="badge bg-green">New</span>
                            <i class="fa fa-plus-circle"></i> Add Contribution
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="<?php echo base_url('partnerdashboard/giving-settings'); ?>" class="btn btn-app">
                            <i class="fa fa-cogs"></i> Giving Settings
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="<?php echo base_url('partnerdashboard/contributions'); ?>" class="btn btn-app">
                            <i class="fa fa-money"></i> View Contributions
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="<?php echo base_url('partnerdashboard/profile'); ?>" class="btn btn-app">
                            <i class="fa fa-user"></i> Update Profile
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="<?php echo base_url('partnerdashboard/change-password'); ?>" class="btn btn-app">
                            <i class="fa fa-lock"></i> Change Password
                        </a>
                    </div>
                </div>
                
                <!-- Test Data Button (for development/testing) -->
                <?php if (empty($recent_contributions)): ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <h4><i class="icon fa fa-info"></i> No Contributions Yet</h4>
                            <p>You haven't made any contributions yet. To test the dashboard with sample data, you can add some sample contributions.</p>
                            <a href="<?php echo base_url('partnerdashboard/add-sample-contributions'); ?>" class="btn btn-info">
                                <i class="fa fa-plus"></i> Add Sample Contributions (Test)
                            </a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Add Contribution Modal -->
<div class="modal fade" id="addContributionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="addContributionForm">
                <div class="modal-header bg-green">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Add New Contribution</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Giving Type <span class="text-danger">*</span></label>
                        <select class="form-control" name="giving_type_id" required>
                            <option value="">Select Giving Type</option>
                            <?php foreach ($giving_types as $type): ?>
                                <option value="<?php echo $type->id; ?>"><?php echo $type->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Amount <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="number" class="form-control" name="amount" step="0.01" min="0.01" required placeholder="0.00">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Currency</label>
                                <select class="form-control" name="currency">
                                    <option value="USD" selected>USD ($)</option>
                                    <option value="ZWL">ZWL (Z$)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Contribution Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="contribution_date" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Payment Method <span class="text-danger">*</span></label>
                        <select class="form-control" name="payment_method" required>
                            <option value="">Select Payment Method</option>
                            <option value="cash">Cash</option>
                            <option value="check">Check</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="debit_card">Debit Card</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="mobile_money">Mobile Money</option>
                            <option value="online">Online Payment</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Transaction ID / Reference Number</label>
                        <input type="text" class="form-control" name="transaction_id" placeholder="Optional">
                    </div>
                    
                    <div class="form-group">
                        <label>Notes</label>
                        <textarea class="form-control" name="notes" rows="3" placeholder="Any additional notes (optional)"></textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> Your contribution will be submitted for review by the administrator.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check"></i> Submit Contribution
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Handle contribution form submission
    $('#addContributionForm').submit(function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.html();
        
        // Disable button and show loading
        submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Submitting...');
        
        $.ajax({
            url: '<?php echo base_url("partnerdashboard/add_contribution"); ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    // Success
                    successMsg(response.message);
                    $('#addContributionModal').modal('hide');
                    $('#addContributionForm')[0].reset();
                    
                    // Reload page after 2 seconds
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                } else {
                    // Error
                    errorMsg(response.message);
                }
            },
            error: function() {
                errorMsg('An error occurred. Please try again.');
            },
            complete: function() {
                // Re-enable button
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
});
</script>
