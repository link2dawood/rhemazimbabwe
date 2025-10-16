<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .dashboard-card {
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border-radius: 10px;
            transition: transform 0.3s ease;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .partner-card {
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        .partner-card:hover {
            border-color: #007bff;
            box-shadow: 0 4px 12px rgba(0,123,255,0.15);
        }
        .btn-custom {
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="h3 mb-0">
                        <i class="fas fa-handshake text-primary me-2"></i>
                        Partner Management
                    </h1>
                    <div>
                        <a href="<?php echo base_url('user/partner_management/add'); ?>" class="btn btn-primary btn-custom">
                            <i class="fas fa-plus me-2"></i>Add Partner
                        </a>
                        <a href="<?php echo base_url('user/partner'); ?>" class="btn btn-outline-secondary btn-custom">
                            <i class="fas fa-arrow-left me-2"></i>Back to Partners
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo $this->session->flashdata('success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php echo $this->session->flashdata('error'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0"><?php echo count($partners); ?></h3>
                            <p class="mb-0">Total Partners</p>
                        </div>
                        <i class="fas fa-users fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0"><?php echo count(array_filter($partners, function($p) { return $p['status'] == 'active'; })); ?></h3>
                            <p class="mb-0">Active Partners</p>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0"><?php echo count(array_filter($partners, function($p) { return $p['account_type'] == 'individual'; })); ?></h3>
                            <p class="mb-0">Individuals</p>
                        </div>
                        <i class="fas fa-user fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0"><?php echo count(array_filter($partners, function($p) { return $p['account_type'] == 'organization'; })); ?></h3>
                            <p class="mb-0">Organizations</p>
                        </div>
                        <i class="fas fa-building fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Partners List -->
        <div class="row">
            <div class="col-12">
                <div class="dashboard-card">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>
                            Partners List
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($partners)): ?>
                            <div class="text-center py-5">
                                <i class="fas fa-handshake fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Partners Found</h5>
                                <p class="text-muted">Start by adding your first partner.</p>
                                <a href="<?php echo base_url('user/partner_management/add'); ?>" class="btn btn-primary btn-custom">
                                    <i class="fas fa-plus me-2"></i>Add Partner
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="row">
                                <?php foreach ($partners as $partner): ?>
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="partner-card">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div>
                                                    <h6 class="mb-1">
                                                        <?php echo $partner['firstname'] . ' ' . $partner['lastname']; ?>
                                                        <?php if ($partner['account_type'] == 'organization' && $partner['organization_name']): ?>
                                                            <br><small class="text-muted"><?php echo $partner['organization_name']; ?></small>
                                                        <?php endif; ?>
                                                    </h6>
                                                    <span class="badge bg-<?php echo $partner['status'] == 'active' ? 'success' : 'warning'; ?>">
                                                        <?php echo ucfirst($partner['status']); ?>
                                                    </span>
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="<?php echo base_url('user/partner_management/edit/' . $partner['id']); ?>">
                                                            <i class="fas fa-edit me-2"></i>Edit
                                                        </a></li>
                                                        <li><a class="dropdown-item" href="<?php echo base_url('user/partner/contributions?partner_id=' . $partner['id']); ?>">
                                                            <i class="fas fa-history me-2"></i>Contributions
                                                        </a></li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li><a class="dropdown-item text-danger" href="<?php echo base_url('user/partner_management/delete/' . $partner['id']); ?>" 
                                                               onclick="return confirm('Are you sure you want to delete this partner?')">
                                                            <i class="fas fa-trash me-2"></i>Delete
                                                        </a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <div class="row text-muted small">
                                                    <div class="col-6">
                                                        <i class="fas fa-envelope me-1"></i>
                                                        <?php echo $partner['email']; ?>
                                                    </div>
                                                    <div class="col-6">
                                                        <i class="fas fa-phone me-1"></i>
                                                        <?php echo $partner['mobileno']; ?>
                                                    </div>
                                                </div>
                                                <?php if ($partner['city']): ?>
                                                    <div class="text-muted small mt-1">
                                                        <i class="fas fa-map-marker-alt me-1"></i>
                                                        <?php echo $partner['city'] . ', ' . $partner['country']; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <small class="text-muted">Partner Code:</small><br>
                                                    <code><?php echo $partner['partner_code']; ?></code>
                                                </div>
                                                <div class="text-end">
                                                    <small class="text-muted">Amount:</small><br>
                                                    <strong><?php echo $partner['currency'] . ' ' . number_format($partner['contribution_amount'], 2); ?></strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
