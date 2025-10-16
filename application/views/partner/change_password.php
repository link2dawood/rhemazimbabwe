<!-- Change Password Content -->
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-lock"></i> Change Password</h3>
            </div>
            <form id="changePasswordForm">
                <div class="box-body">
                    <div class="form-group">
                        <label for="current_password">Current Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password">New Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required minlength="6">
                        <small class="text-muted">Password must be at least 6 characters long</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    
                    <!-- Password Strength Indicator -->
                    <div class="form-group">
                        <label>Password Strength:</label>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar" id="password-strength-bar" role="progressbar" style="width: 0%"></div>
                        </div>
                        <small id="password-strength-text" class="text-muted">Enter a password to see strength</small>
                    </div>
                </div>
                
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Change Password
                    </button>
                    <a href="<?php echo base_url('partnerdashboard/profile'); ?>" class="btn btn-default">
                        <i class="fa fa-arrow-left"></i> Back to Profile
                    </a>
                </div>
            </form>
        </div>
        
        <!-- Password Requirements -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-info-circle"></i> Password Requirements</h3>
            </div>
            <div class="box-body">
                <ul class="list-unstyled">
                    <li><i class="fa fa-check text-success"></i> At least 6 characters long</li>
                    <li><i class="fa fa-check text-success"></i> Mix of letters and numbers recommended</li>
                    <li><i class="fa fa-check text-success"></i> Avoid common words or personal information</li>
                    <li><i class="fa fa-check text-success"></i> Use a unique password for this account</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Password strength checker
    $('#new_password').on('input', function() {
        var password = $(this).val();
        var strength = checkPasswordStrength(password);
        updatePasswordStrengthIndicator(strength);
    });
    
    // Confirm password validation
    $('#confirm_password').on('input', function() {
        var newPassword = $('#new_password').val();
        var confirmPassword = $(this).val();
        
        if (confirmPassword !== '' && newPassword !== confirmPassword) {
            $(this).addClass('is-invalid');
            if (!$(this).next('.invalid-feedback').length) {
                $(this).after('<div class="invalid-feedback">Passwords do not match</div>');
            }
        } else {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        }
    });
    
    // Form submission
    $('#changePasswordForm').on('submit', function(e) {
        e.preventDefault();
        
        var currentPassword = $('#current_password').val();
        var newPassword = $('#new_password').val();
        var confirmPassword = $('#confirm_password').val();
        
        // Client-side validation
        if (newPassword !== confirmPassword) {
            alert('New password and confirm password do not match');
            return;
        }
        
        if (newPassword.length < 6) {
            alert('New password must be at least 6 characters long');
            return;
        }
        
        const formData = new FormData(this);
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        // Show loading state
        submitBtn.html('<i class="fa fa-spinner fa-spin"></i> Changing...');
        submitBtn.prop('disabled', true);
        
        fetch('<?php echo base_url('partnerdashboard/update-password'); ?>', {
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
                
                // Clear form
                $('#changePasswordForm')[0].reset();
                updatePasswordStrengthIndicator({score: 0, text: 'Enter a password to see strength'});
                
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
                An error occurred while changing your password. Please try again.
            `;
            document.querySelector('.content').insertBefore(alertDiv, document.querySelector('.content').firstChild);
        })
        .finally(() => {
            // Restore button state
            submitBtn.html(originalText);
            submitBtn.prop('disabled', false);
        });
    });
    
    // Password strength checker function
    function checkPasswordStrength(password) {
        var score = 0;
        var feedback = [];
        
        if (password.length >= 6) score += 1;
        else feedback.push('At least 6 characters');
        
        if (password.length >= 8) score += 1;
        
        if (/[a-z]/.test(password)) score += 1;
        else feedback.push('Lowercase letter');
        
        if (/[A-Z]/.test(password)) score += 1;
        else feedback.push('Uppercase letter');
        
        if (/[0-9]/.test(password)) score += 1;
        else feedback.push('Number');
        
        if (/[^A-Za-z0-9]/.test(password)) score += 1;
        else feedback.push('Special character');
        
        var strength = {
            score: score,
            text: getStrengthText(score),
            feedback: feedback
        };
        
        return strength;
    }
    
    function getStrengthText(score) {
        if (score <= 1) return 'Very Weak';
        if (score <= 2) return 'Weak';
        if (score <= 3) return 'Fair';
        if (score <= 4) return 'Good';
        if (score <= 5) return 'Strong';
        return 'Very Strong';
    }
    
    function updatePasswordStrengthIndicator(strength) {
        var percentage = (strength.score / 6) * 100;
        var color = 'danger';
        
        if (strength.score >= 4) color = 'success';
        else if (strength.score >= 3) color = 'warning';
        else if (strength.score >= 2) color = 'info';
        
        $('#password-strength-bar')
            .css('width', percentage + '%')
            .removeClass('progress-bar-danger progress-bar-warning progress-bar-info progress-bar-success')
            .addClass('progress-bar-' + color);
        
        $('#password-strength-text')
            .removeClass('text-danger text-warning text-info text-success')
            .addClass('text-' + color)
            .text(strength.text);
    }
});
</script>

<style>
.progress-bar-danger { background-color: #d9534f; }
.progress-bar-warning { background-color: #f0ad4e; }
.progress-bar-info { background-color: #5bc0de; }
.progress-bar-success { background-color: #5cb85c; }

.text-danger { color: #d9534f; }
.text-warning { color: #f0ad4e; }
.text-info { color: #5bc0de; }
.text-success { color: #5cb85c; }

.is-invalid {
    border-color: #d9534f;
}

.invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875rem;
    color: #d9534f;
}
</style>
