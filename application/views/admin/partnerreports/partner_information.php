<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-users"></i> <?php echo $this->lang->line('partner_information_report'); ?></h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('filter_criteria'); ?></h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-primary btn-sm" id="exportExcel">
                                <i class="fa fa-file-excel-o"></i> <?php echo $this->lang->line('export_to_excel'); ?>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" id="exportPdf">
                                <i class="fa fa-file-pdf-o"></i> <?php echo $this->lang->line('export_to_pdf'); ?>
                            </button>
                        </div>
                    </div>

                    <div class="box-body">
                        <!-- Filters -->
                        <form id="filterForm" class="form-horizontal">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('status'); ?></label>
                                        <select class="form-control" name="status" id="status">
                                            <option value=""><?php echo $this->lang->line('all'); ?></option>
                                            <option value="active"><?php echo $this->lang->line('active'); ?></option>
                                            <option value="inactive"><?php echo $this->lang->line('inactive'); ?></option>
                                            <option value="suspended"><?php echo $this->lang->line('suspended'); ?></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('giving_type'); ?></label>
                                        <select class="form-control" name="giving_type_id" id="giving_type_id">
                                            <option value=""><?php echo $this->lang->line('all'); ?></option>
                                            <?php foreach ($giving_types as $type) { ?>
                                                <option value="<?php echo $type->id ?>"><?php echo $type->name ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('giving_frequency'); ?></label>
                                        <select class="form-control" name="giving_frequency_id" id="giving_frequency_id">
                                            <option value=""><?php echo $this->lang->line('all'); ?></option>
                                            <?php foreach ($giving_frequencies as $frequency) { ?>
                                                <option value="<?php echo $frequency->id ?>"><?php echo $frequency->name ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-primary btn-block" id="searchBtn">
                                            <i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('date_from'); ?></label>
                                        <input type="text" class="form-control date" name="date_from" id="date_from" placeholder="<?php echo $this->lang->line('start_date'); ?>">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('date_to'); ?></label>
                                        <input type="text" class="form-control date" name="date_to" id="date_to" placeholder="<?php echo $this->lang->line('end_date'); ?>">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-default btn-block" id="resetBtn">
                                            <i class="fa fa-refresh"></i> <?php echo $this->lang->line('reset'); ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <hr>

                        <!-- Report Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="partnerInfoTable">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('partner_code'); ?></th>
                                        <th><?php echo $this->lang->line('partner_name'); ?></th>
                                        <th><?php echo $this->lang->line('email'); ?></th>
                                        <th><?php echo $this->lang->line('phone'); ?></th>
                                        <th><?php echo $this->lang->line('giving_type'); ?></th>
                                        <th><?php echo $this->lang->line('frequency'); ?></th>
                                        <th><?php echo $this->lang->line('pledged_amount'); ?></th>
                                        <th><?php echo $this->lang->line('total_contributed'); ?></th>
                                        <th><?php echo $this->lang->line('start_date'); ?></th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="6" class="text-right"><?php echo $this->lang->line('total'); ?>:</th>
                                        <th id="totalPledged"></th>
                                        <th id="totalContributed"></th>
                                        <th colspan="2"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
var base_url = '<?php echo base_url() ?>';
var partnerInfoTable;

$(document).ready(function() {
    // Initialize date pickers
    $('.date').datepicker({
        format: "<?php echo $this->customlib->getSchoolDateFormat() ?>",
        autoclose: true
    });

    // Initialize DataTable
    initDataTable();

    // Search button
    $('#searchBtn').click(function() {
        partnerInfoTable.ajax.reload();
    });

    // Reset button
    $('#resetBtn').click(function() {
        $('#filterForm')[0].reset();
        partnerInfoTable.ajax.reload();
    });

    // Export buttons
    $('#exportExcel').click(function() {
        partnerInfoTable.button('.buttons-excel').trigger();
    });

    $('#exportPdf').click(function() {
        window.location.href = base_url + 'admin/partnerreports/exportPdf/partner_information';
    });
});

function initDataTable() {
    partnerInfoTable = $('#partnerInfoTable').DataTable({
        "processing": true,
        "serverSide": false,
        "ajax": {
            "url": base_url + "admin/partnerreports/getPartnerInformationData",
            "type": "POST",
            "data": function(d) {
                d.status = $('#status').val();
                d.giving_type_id = $('#giving_type_id').val();
                d.giving_frequency_id = $('#giving_frequency_id').val();
                d.date_from = $('#date_from').val();
                d.date_to = $('#date_to').val();
            }
        },
        "columns": [
            {"data": 0},
            {"data": 1},
            {"data": 2},
            {"data": 3},
            {"data": 4},
            {"data": 5},
            {"data": 6, "className": "text-right"},
            {"data": 7, "className": "text-right"},
            {"data": 8},
            {"data": 9}
        ],
        "responsive": true,
        "autoWidth": false,
        "drawCallback": function(settings) {
            // Calculate totals
            var api = this.api();
            // You can add total calculations here if needed
        },
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copyHtml5',
                text: '<i class="fa fa-files-o"></i>',
                titleAttr: 'Copy',
                title: 'Partner Information Report',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'excelHtml5',
                text: '<i class="fa fa-file-excel-o"></i>',
                titleAttr: 'Excel',
                title: 'Partner Information Report',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'csvHtml5',
                text: '<i class="fa fa-file-text-o"></i>',
                titleAttr: 'CSV',
                title: 'Partner Information Report',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fa fa-file-pdf-o"></i>',
                titleAttr: 'PDF',
                title: 'Partner Information Report',
                orientation: 'landscape',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'print',
                text: '<i class="fa fa-print"></i>',
                titleAttr: 'Print',
                title: 'Partner Information Report',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'colvis',
                text: '<i class="fa fa-columns"></i>',
                titleAttr: 'Columns'
            }
        ]
    });
}
</script>