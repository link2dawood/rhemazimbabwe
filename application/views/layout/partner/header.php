<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo isset($title) ? $title : 'Partner Portal'; ?> - <?php echo $this->customlib->getSchoolName(); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo base_url('uploads/school_content/admin_small_logo/'.$this->setting_model->getAdminsmalllogo()); ?>" type="image/x-icon">
    
    <!-- Bootstrap CSS -->
    <link href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo base_url(); ?>backend/dist/css/font-awesome.min.css" rel="stylesheet">
    <!-- Ionicons -->
    <link href="<?php echo base_url(); ?>backend/dist/css/ionicons.min.css" rel="stylesheet">
    <!-- AdminLTE CSS -->
    <link href="<?php echo base_url(); ?>backend/dist/css/AdminLTE.min.css" rel="stylesheet">
    <!-- AdminLTE Skins -->
    <link href="<?php echo base_url(); ?>backend/dist/css/skins/_all-skins.min.css" rel="stylesheet">
    <!-- Custom Partner Portal CSS -->
    <link href="<?php echo base_url(); ?>backend/dist/css/partner-portal.css" rel="stylesheet">
    <!-- Additional CSS -->
    <link href="<?php echo base_url(); ?>backend/dist/css/custom_style.css" rel="stylesheet">
    
    <script type="text/javascript">
        var base_url = "<?php echo base_url() ?>";
    </script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <!-- Main Header -->
        <header class="main-header">
            <!-- Logo -->
            <a href="<?php echo base_url('partnerdashboard'); ?>" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><b>P</b>P</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b>Partner</b>Portal</span>
            </a>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- User Account Menu -->
                        <li class="dropdown user user-menu">
                            <!-- Menu Toggle Button -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <!-- The user image in the navbar-->
                                <img src="<?php echo base_url('backend/images/avatar5.png'); ?>" class="user-image" alt="User Image">
                                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                <span class="hidden-xs"><?php echo $partner['firstname'] . ' ' . $partner['lastname']; ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- The user image in the menu -->
                                <li class="user-header">
                                    <img src="<?php echo base_url('backend/images/avatar5.png'); ?>" class="img-circle" alt="User Image">
                                    <p>
                                        <?php echo $partner['firstname'] . ' ' . $partner['lastname']; ?>
                                        <small><?php echo $partner['email']; ?></small>
                                        <small>Partner Code: <?php echo $partner['partner_code']; ?></small>
                                    </p>
                                </li>
                                <!-- Menu Body -->
                                <li class="user-body">
                                    <div class="row">
                                        <div class="col-xs-4 text-center">
                                            <a href="<?php echo base_url('partnerdashboard/contributions'); ?>">Contributions</a>
                                        </div>
                                        <div class="col-xs-4 text-center">
                                            <a href="<?php echo base_url('partnerdashboard/profile'); ?>">Profile</a>
                                        </div>
                                        <div class="col-xs-4 text-center">
                                            <a href="<?php echo base_url('partnerdashboard/change-password'); ?>">Password</a>
                                        </div>
                                    </div>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="<?php echo base_url('partnerdashboard/profile'); ?>" class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="<?php echo base_url('partnerportal/logout'); ?>" class="btn btn-default btn-flat">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="<?php echo base_url('backend/images/avatar5.png'); ?>" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p><?php echo $partner['firstname'] . ' ' . $partner['lastname']; ?></p>
                        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                    </div>
                </div>
                
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="header">PARTNER PORTAL</li>
                    
                    <li class="<?php echo (isset($active_menu) && $active_menu == 'dashboard') ? 'active' : ''; ?>">
                        <a href="<?php echo base_url('partnerdashboard'); ?>">
                            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                        </a>
                    </li>
                    
                    <li class="<?php echo (isset($active_menu) && $active_menu == 'contributions') ? 'active' : ''; ?>">
                        <a href="<?php echo base_url('partnerdashboard/contributions'); ?>">
                            <i class="fa fa-money"></i> <span>My Contributions</span>
                        </a>
                    </li>
                    
                    <li class="<?php echo (isset($active_menu) && $active_menu == 'giving_settings') ? 'active' : ''; ?>">
                        <a href="<?php echo base_url('partnerdashboard/giving-settings'); ?>">
                            <i class="fa fa-cogs"></i> <span>Giving Settings</span>
                        </a>
                    </li>
                    
                    <li class="<?php echo (isset($active_menu) && $active_menu == 'profile') ? 'active' : ''; ?>">
                        <a href="<?php echo base_url('partnerdashboard/profile'); ?>">
                            <i class="fa fa-user"></i> <span>My Profile</span>
                        </a>
                    </li>
                    
                    <li class="<?php echo (isset($active_menu) && $active_menu == 'change_password') ? 'active' : ''; ?>">
                        <a href="<?php echo base_url('partnerdashboard/change-password'); ?>">
                            <i class="fa fa-lock"></i> <span>Change Password</span>
                        </a>
                    </li>

                    <?php
                    // Show permission-based menu items
                    if (isset($partner_permissions) && !empty($partner_permissions)):
                    ?>
                    <li class="header">ADDITIONAL RESOURCES</li>

                    <?php
                    // Define permission-based menu items
                    $permission_menus = array(
                        'library_access' => array(
                            'label' => 'Library',
                            'icon' => 'fa-book',
                            'url' => 'partnerdashboard/library'
                        ),
                        'online_courses' => array(
                            'label' => 'Online Courses',
                            'icon' => 'fa-graduation-cap',
                            'url' => 'partnerdashboard/courses'
                        ),
                        'download_centre' => array(
                            'label' => 'Download Centre',
                            'icon' => 'fa-download',
                            'url' => 'partnerdashboard/downloads'
                        ),
                        'gmeet_access' => array(
                            'label' => 'GMeet',
                            'icon' => 'fa-video-camera',
                            'url' => 'partnerdashboard/gmeet'
                        ),
                        'zoom_access' => array(
                            'label' => 'Zoom',
                            'icon' => 'fa-video-camera',
                            'url' => 'partnerdashboard/zoom'
                        )
                    );

                    // Display menu items for granted permissions
                    foreach ($permission_menus as $permission_code => $menu_item):
                        if (in_array($permission_code, $partner_permissions)):
                    ?>
                    <li class="<?php echo (isset($active_menu) && $active_menu == $permission_code) ? 'active' : ''; ?>">
                        <a href="<?php echo base_url($menu_item['url']); ?>">
                            <i class="fa <?php echo $menu_item['icon']; ?>"></i> <span><?php echo $menu_item['label']; ?></span>
                        </a>
                    </li>
                    <?php
                        endif;
                    endforeach;
                    ?>

                    <?php endif; ?>

                    <li class="header">EXTERNAL LINKS</li>
                    
                    <li>
                        <a href="<?php echo base_url(); ?>" target="_blank">
                            <i class="fa fa-external-link"></i> <span>Main Website</span>
                        </a>
                    </li>
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    <?php echo isset($page_title) ? $page_title : 'Dashboard'; ?>
                    <small><?php echo isset($page_description) ? $page_description : 'Partner Portal'; ?></small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo base_url('partnerdashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                    <?php if (isset($breadcrumb) && is_array($breadcrumb)): ?>
                        <?php foreach ($breadcrumb as $item): ?>
                            <li class="active"><?php echo $item; ?></li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-check"></i> Success!</h4>
                        <?php echo $this->session->flashdata('success'); ?>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-ban"></i> Error!</h4>
                        <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php endif; ?>
