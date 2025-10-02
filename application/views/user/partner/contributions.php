<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-history"></i> <?php echo $this->lang->line('contribution_history'); ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('user/user/dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo $this->lang->line('dashboard'); ?></a></li>
            <li><a href="<?php echo base_url('user/partner'); ?>"><i class="fa fa-handshake-o"></i> <?php echo $this->lang->line('partners'); ?></a></li>
            <li class="active"><?php echo $this->lang->line('contributions'); ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <!-- Partner Summary Card -->
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <h4>
                                    <?php
                                    if ($partner['account_type'] == 'organization') {
                                        echo $partner['organization_name'];
                                    } else {
                                        echo $partner['firstname'] . ' ' . $partner['lastname'];
                                    }
                                    ?>
                                </h4>
                                <p class="text-muted"><?php echo $partner['partner_code']; ?></p>
                            </div>
                            <div class="col-md-3">
                                <div class="info-box bg-aqua">
                                    <span class="info-box-icon"><i class="fa fa-money"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text"><?php echo $this->lang->line('total_contributed'); ?></span>
                                        <span class="info-box-number"><?php echo $partner['currency']; ?> <?php echo number_format($total_contributed, 2); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-box bg-green">
                                    <span class="info-box-icon"><i class="fa fa-check-circle"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text"><?php echo $this->lang->line('total_transactions'); ?></span>
                                        <span class="info-box-number"><?php echo count($contributions); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-box bg-yellow">
                                    <span class="info-box-icon"><i class="fa fa-calendar"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text"><?php echo $this->lang->line('status'); ?></span>
                                        <span class="info-box-number">
                                            <?php
                                            switch ($partner['status']) {
                                                case 'active':
                                                    echo '<span class="label label-success">' . $this->lang->line('active') . '</span>';
                                                    break;
                                                case 'inactive':
                                                    echo '<span class="label label-warning">' . $this->lang->line('pending') . '</span>';
                                                    break;
                                                case 'suspended':
                                                    echo '<span class="label label-danger">' . $this->lang->line('suspended') . '</span>';
                                                    break;
                                            }
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contributions Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-list"></i> <?php echo $this->lang->line('contributions'); ?></h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo base_url('user/partner/settings?partner_id=' . $partner['id']); ?>" class="btn btn-primary btn-sm">
                                <i class="fa fa-cog"></i> <?php echo $this->lang->line('settings'); ?>
                            </a>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php if (empty($contributions)): ?>
                            <div class="alert alert-info text-center">
                                <i class="fa fa-info-circle"></i>
                                <?php echo $this->lang->line('no_record_found'); ?>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="contributionsTable">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('date'); ?></th>
                                            <th><?php echo $this->lang->line('receipt_no'); ?></th>
                                            <th><?php echo $this->lang->line('giving_type'); ?></th>
                                            <th><?php echo $this->lang->line('amount'); ?></th>
                                            <th><?php echo $this->lang->line('payment_method'); ?></th>
                                            <th><?php echo $this->lang->line('transaction_id'); ?></th>
                                            <th><?php echo $this->lang->line('status'); ?></th>
                                            <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($contributions as $contribution): ?>
                                            <tr>
                                                <td><?php echo date('d M, Y', strtotime($contribution['contribution_date'])); ?></td>
                                                <td><strong><?php echo $contribution['receipt_no']; ?></strong></td>
                                                <td>
                                                    <?php
                                                    if ($contribution['giving_type_id']) {
                                                        $giving_type = $this->giving_type_model->get($contribution['giving_type_id']);
                                                        echo $giving_type['name'] ?? '-';
                                                    } else {
                                                        echo '-';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <strong><?php echo $contribution['currency']; ?> <?php echo number_format($contribution['amount'], 2); ?></strong>
                                                </td>
                                                <td>
                                                    <?php
                                                    $method_icons = [
                                                        'Cash' => 'money',
                                                        'Bank Transfer' => 'bank',
                                                        'Mobile Money' => 'mobile',
                                                        'Credit Card' => 'credit-card',
                                                        'Cheque' => 'file-text',
                                                        'Online Payment' => 'globe'
                                                    ];
                                                    $icon = $method_icons[$contribution['payment_method']] ?? 'money';
                                                    ?>
                                                    <i class="fa fa-<?php echo $icon; ?>"></i> <?php echo $contribution['payment_method']; ?>
                                                </td>
                                                <td><?php echo $contribution['transaction_id'] ?? '-'; ?></td>
                                                <td>
                                                    <?php
                                                    switch ($contribution['status']) {
                                                        case 'completed':
                                                            echo '<span class="label label-success">' . $this->lang->line('completed') . '</span>';
                                                            break;
                                                        case 'pending':
                                                            echo '<span class="label label-warning">' . $this->lang->line('pending') . '</span>';
                                                            break;
                                                        case 'failed':
                                                            echo '<span class="label label-danger">' . $this->lang->line('failed') . '</span>';
                                                            break;
                                                        default:
                                                            echo '<span class="label label-default">' . ucfirst($contribution['status']) . '</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-right">
                                                    <div class="btn-group">
                                                        <a href="<?php echo base_url('user/partner/printReceipt/' . $contribution['id'] . '?partner_id=' . $partner['id']); ?>"
                                                           target="_blank"
                                                           class="btn btn-primary btn-xs"
                                                           data-toggle="tooltip"
                                                           title="<?php echo $this->lang->line('print'); ?>">
                                                            <i class="fa fa-print"></i>
                                                        </a>
                                                        <a href="<?php echo base_url('user/partner/downloadReceipt/' . $contribution['id'] . '?partner_id=' . $partner['id']); ?>"
                                                           target="_blank"
                                                           class="btn btn-success btn-xs"
                                                           data-toggle="tooltip"
                                                           title="<?php echo $this->lang->line('download'); ?>">
                                                            <i class="fa fa-download"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-right"><?php echo $this->lang->line('total'); ?>:</th>
                                            <th><strong><?php echo $partner['currency']; ?> <?php echo number_format($total_contributed, 2); ?></strong></th>
                                            <th colspan="4"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="box-footer">
                        <a href="<?php echo base_url('user/partner'); ?>" class="btn btn-default">
                            <i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    $('#contributionsTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copyHtml5',
                text: '<i class="fa fa-copy"></i>',
                titleAttr: 'Copy',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                }
            },
            {
                extend: 'excelHtml5',
                text: '<i class="fa fa-file-excel-o"></i>',
                titleAttr: 'Excel',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fa fa-file-pdf-o"></i>',
                titleAttr: 'PDF',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                }
            },
            {
                extend: 'print',
                text: '<i class="fa fa-print"></i>',
                titleAttr: 'Print',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                }
            }
        ],
        order: [[0, 'desc']],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]]
    });

    $('[data-toggle="tooltip"]').tooltip();
});
</script>
