<!-- Profile Content -->
<div class="row">
    <!-- Profile Information -->
    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-user"></i> Profile Information</h3>
            </div>
            <form id="profileForm">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="firstname">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="firstname" name="firstname" 
                                       value="<?php echo $partner['firstname']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lastname">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="lastname" name="lastname" 
                                       value="<?php echo $partner['lastname']; ?>" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo $partner['email']; ?>" required readonly>
                                <small class="text-muted">Email cannot be changed</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mobileno">Mobile Number</label>
                                <input type="text" class="form-control" id="mobileno" name="mobileno" 
                                       value="<?php echo $partner['mobileno']; ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3"><?php echo $partner['address']; ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" class="form-control" id="city" name="city" 
                                       value="<?php echo $partner['city']; ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="state">State/Province</label>
                                <input type="text" class="form-control" id="state" name="state" 
                                       value="<?php echo $partner['state']; ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="zip_code">Postal Code</label>
                                <input type="text" class="form-control" id="zip_code" name="zip_code" 
                                       value="<?php echo $partner['zip_code']; ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="country">Country</label>
                        <input type="text" class="form-control" id="country" name="country" 
                               value="<?php echo $partner['country']; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="notes">Additional Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"><?php echo $partner['notes']; ?></textarea>
                    </div>
                </div>
                
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Update Profile
                    </button>
                    <a href="<?php echo base_url('partnerdashboard'); ?>" class="btn btn-default">
                        <i class="fa fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Account Information -->
    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-info-circle"></i> Account Information</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <td><strong>Partner Code:</strong></td>
                            <td><?php echo $partner['partner_code']; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Account Type:</strong></td>
                            <td><span class="label label-info"><?php echo ucfirst($partner['account_type']); ?></span></td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                <span class="label label-<?php echo $partner['status'] == 'active' ? 'success' : 'warning'; ?>">
                                    <?php echo ucfirst($partner['status']); ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Member Since:</strong></td>
                            <td><?php echo date('M d, Y', strtotime($partner['created_at'])); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Last Login:</strong></td>
                            <td><?php echo $partner['last_login'] ? date('M d, Y H:i', strtotime($partner['last_login'])) : 'Never'; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Email Verified:</strong></td>
                            <td>
                                <span class="label label-<?php echo $partner['is_email_verified'] ? 'success' : 'warning'; ?>">
                                    <?php echo $partner['is_email_verified'] ? 'Yes' : 'No'; ?>
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-bolt"></i> Quick Actions</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <a href="<?php echo base_url('partnerdashboard/giving-settings'); ?>" class="btn btn-app btn-block">
                            <i class="fa fa-cogs"></i> Giving Settings
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="<?php echo base_url('partnerdashboard/change-password'); ?>" class="btn btn-app btn-block">
                            <i class="fa fa-lock"></i> Change Password
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <a href="<?php echo base_url('partnerdashboard/contributions'); ?>" class="btn btn-app btn-block">
                            <i class="fa fa-money"></i> View Contributions
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="<?php echo base_url('partnerdashboard'); ?>" class="btn btn-app btn-block">
                            <i class="fa fa-dashboard"></i> Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Profile form submission
    $('#profileForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        // Show loading state
        submitBtn.html('<i class="fa fa-spinner fa-spin"></i> Updating...');
        submitBtn.prop('disabled', true);
        
        fetch('<?php echo base_url('partnerdashboard/update-profile'); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                // Show success message
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success alert-dismissible';
                alertDiv.innerHTML = `
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-check"></i> Success!</h4>
                    ${data.message}
                `;
                document.querySelector('.content').insertBefore(alertDiv, document.querySelector('.content').firstChild);
                
                // Auto-hide after 3 seconds
                setTimeout(() => {
                    $('.alert').fadeOut('slow');
                }, 3000);
            } else {
                // Show error message
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger alert-dismissible';
                alertDiv.innerHTML = `
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-ban"></i> Error!</h4>
                    ${data.message}
                `;
                document.querySelector('.content').insertBefore(alertDiv, document.querySelector('.content').firstChild);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger alert-dismissible';
            alertDiv.innerHTML = `
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-ban"></i> Error!</h4>
                An error occurred while updating your profile. Please try again.
            `;
            document.querySelector('.content').insertBefore(alertDiv, document.querySelector('.content').firstChild);
        })
        .finally(() => {
            // Restore button state
            submitBtn.html(originalText);
            submitBtn.prop('disabled', false);
        });
    });
});
</script>
