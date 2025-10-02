<style>
    .partner-login-section {
        padding: 60px 0;
        background: #f8f9fa;
    }

    .login-card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        padding: 40px;
        max-width: 450px;
        margin: 0 auto;
    }

    .login-card h2 {
        color: #667eea;
        margin-bottom: 30px;
        text-align: center;
        font-weight: 600;
    }

    .login-card .form-group {
        margin-bottom: 20px;
    }

    .login-card .form-group label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }

    .login-card .form-control {
        height: 45px;
        border-radius: 5px;
        border: 1px solid #ddd;
    }

    .login-card .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .login-card .btn-login {
        width: 100%;
        height: 45px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: #fff;
        font-size: 16px;
        font-weight: 600;
        border-radius: 5px;
        transition: all 0.3s;
    }

    .login-card .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    .login-card .divider {
        text-align: center;
        margin: 25px 0;
        position: relative;
    }

    .login-card .divider::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: #dee2e6;
    }

    .login-card .divider span {
        background: #fff;
        padding: 0 15px;
        position: relative;
        color: #6c757d;
        font-size: 14px;
    }

    .login-card .footer-links {
        text-align: center;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #dee2e6;
    }

    .login-card .footer-links a {
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
    }

    .login-card .footer-links a:hover {
        text-decoration: underline;
    }
</style>

<section class="partner-login-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="login-card">
                    <h2><i class="fa fa-handshake-o"></i> Partner Login</h2>

                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success">
                            <i class="fa fa-check-circle"></i> <?php echo $this->session->flashdata('success'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger">
                            <i class="fa fa-exclamation-circle"></i> <?php echo $this->session->flashdata('error'); ?>
                        </div>
                    <?php endif; ?>

                    <div id="login-message"></div>

                    <form id="loginForm" method="post" action="<?php echo base_url('partnerportal/do_login'); ?>">
                        <div class="form-group">
                            <label for="username">
                                <i class="fa fa-user"></i> Username or Email
                            </label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter username or email" required autofocus>
                        </div>

                        <div class="form-group">
                            <label for="password">
                                <i class="fa fa-lock"></i> Password
                            </label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                        </div>

                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="remember" value="1"> Remember Me
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-login">
                            <i class="fa fa-sign-in"></i> Login
                        </button>

                        <div class="divider">
                            <span>OR</span>
                        </div>

                        <div class="text-center">
                            <a href="<?php echo base_url('partnerportal/forgot_password'); ?>">
                                <i class="fa fa-question-circle"></i> Forgot Password?
                            </a>
                        </div>
                    </form>

                    <div class="footer-links">
                        <p>
                            Don't have an account?
                            <a href="<?php echo base_url('partnerportal/register'); ?>">Register Here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    var base_url = '<?php echo base_url(); ?>';

    $(document).ready(function() {
        $('#loginForm').submit(function(e) {
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                dataType: 'json',
                beforeSend: function() {
                    $('.btn-login').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Logging in...');
                    $('#login-message').html('');
                },
                success: function(response) {
                    if (response.status) {
                        $('#login-message').html('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + response.message + '</div>');
                        setTimeout(function() {
                            window.location.href = response.redirect;
                        }, 1000);
                    } else {
                        $('#login-message').html('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + response.message + '</div>');
                        $('.btn-login').prop('disabled', false).html('<i class="fa fa-sign-in"></i> Login');
                    }
                },
                error: function() {
                    $('#login-message').html('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> An error occurred. Please try again.</div>');
                    $('.btn-login').prop('disabled', false).html('<i class="fa fa-sign-in"></i> Login');
                }
            });
        });
    });
</script>
