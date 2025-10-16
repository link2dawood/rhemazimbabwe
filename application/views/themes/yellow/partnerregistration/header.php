<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Partner Registration'; ?> - Rhema Zimbabwe</title>

    <!-- Favicon -->
    <link rel="icon" href="<?php echo base_url(); ?>uploads/school_content/admin_small_logo.png" type="image/png">

    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/AdminLTE.min.css">

    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .main-content {
            background: #f5f5f5;
            min-height: calc(100vh - 150px);
        }

        .navbar-custom {
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 0;
        }

        .navbar-custom .navbar-brand {
            font-size: 24px;
            font-weight: bold;
            color: #3c8dbc !important;
        }

        .navbar-custom .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }

        .navbar-custom .navbar-nav > li > a {
            color: #333 !important;
            font-weight: 500;
        }

        .navbar-custom .navbar-nav > li > a:hover {
            color: #3c8dbc !important;
        }

        .footer-custom {
            background: #222;
            color: #fff;
            padding: 30px 0;
            margin-top: 0;
        }

        .footer-custom a {
            color: #3c8dbc;
        }

        .footer-custom a:hover {
            color: #5fa6d3;
            text-decoration: none;
        }
    </style>

    <!-- JavaScript -->
    <script src="<?php echo base_url(); ?>backend/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="<?php echo base_url(); ?>backend/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-custom">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo base_url(); ?>">
                <img src="<?php echo base_url(); ?>uploads/school_content/admin_logo.png" alt="Rhema Zimbabwe" onerror="this.style.display='none'">
                Rhema Zimbabwe
            </a>
        </div>

        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li class="<?php echo ($page == 'registration') ? 'active' : ''; ?>">
                    <a href="<?php echo base_url('partnerregistration'); ?>">
                        <i class="fa fa-home"></i> Home
                    </a>
                </li>
                <li class="<?php echo ($page == 'check_status') ? 'active' : ''; ?>">
                    <a href="<?php echo base_url('partnerregistration/checkStatus'); ?>">
                        <i class="fa fa-search"></i> Check Status
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>">
                        <i class="fa fa-arrow-left"></i> Back to Main Site
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="main-content"