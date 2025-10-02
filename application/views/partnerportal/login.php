<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - Rhema Zimbabwe</title>

    <!-- Favicon -->
    <link rel="icon" href="<?php echo base_url(); ?>uploads/school_content/admin_small_logo.png" type="image/png">

    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/font-awesome.min.css">

    <style>
        body {
            font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            max-width: 400px;
            width: 100%;
        }

        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 30px;
            text-align: center;
        }

        .login-header img {
            max-height: 60px;
            margin-bottom: 15px;
        }

        .login-header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }

        .login-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 14px;
        }

        .login-body {
            padding: 40px 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            height: 45px;
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 10px 15px;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .input-group-addon {
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-right: none;
            padding: 10px 15px;
        }

        .btn-login {
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

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .checkbox label {
            font-weight: normal;
            font-size: 14px;
        }

        .login-footer {
            padding: 20px 30px;
            background: #f8f9fa;
            text-align: center;
            border-top: 1px solid #dee2e6;
        }

        .login-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        .alert {
            border-radius: 5px;
            padding: 12px 15px;
            margin-bottom: 20px;
        }

        .divider {
            text-align: center;
            margin: 25px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #dee2e6;
        }

        .divider span {
            background: #fff;
            padding: 0 15px;
            position: relative;
            color: #6c757d;
            font-size: 14px;
        }

        @media (max-width: 576px) {
            .login-container {
                margin: 20px;
            }

            .login-body {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Header -->
        <div class="login-header">
            <?php if (file_exists(FCPATH . 'uploads/school_content/admin_logo.png')): ?>
                <img src="<?php echo base_url('uploads/school_content/admin_logo.png'); ?>" alt="Rhema Zimbabwe">
            <?php endif; ?>
            <h2>Partner Portal</h2>
            <p>Welcome Back!</p>
        </div>

        <!-- Body -->
        <div class="login-body">
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
        </div>

        <!-- Footer -->
        <div class="login-footer">
            <p style="margin: 0;">
                Don't have an account?
                <a href="<?php echo base_url('partnerportal/register'); ?>">Register Here</a>
            </p>
            <p style="margin: 10px 0 0 0; font-size: 12px; color: #6c757d;">
                <a href="<?php echo base_url(); ?>" style="color: #6c757d;">
                    <i class="fa fa-arrow-left"></i> Back to Main Site
                </a>
            </p>
        </div>
    </div>

    <!-- Scripts -->
    <script src="<?php echo base_url(); ?>backend/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="<?php echo base_url(); ?>backend/bootstrap/js/bootstrap.min.js"></script>

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
</body>
</html>
