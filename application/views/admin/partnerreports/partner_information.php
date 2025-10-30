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
                            <button type="button" class="btn btn-primary btn-sm" onclick="window.print()">
                                <i class="fa fa-print"></i> Print
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

                        <!-- Loading Indicator -->
                        <div id="loadingIndicator" style="display:none; text-align:center; padding:20px;">
                            <i class="fa fa-spinner fa-spin fa-3x"></i>
                            <p>Loading data...</p>
                        </div>

                        <!-- Report Table -->
                        <div class="table-responsive" id="reportTableContainer">
                            <table class="table table-striped table-bordered table-hover" id="partnerInfoTable">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('partner_code'); ?></th>
                                        <th><?php echo $this->lang->line('partner_name'); ?></th>
                                        <th><?php echo $this->lang->line('email'); ?></th>
                                        <th><?php echo $this->lang->line('phone'); ?></th>
                                        <th><?php echo $this->lang->line('giving_type'); ?></th>
                                        <th><?php echo $this->lang->line('frequency'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('pledged_amount'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('total_contributed'); ?></th>
                                        <th><?php echo $this->lang->line('start_date'); ?></th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                    </tr>
                                </thead>
                                <tbody id="reportTableBody">
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">
                                            Click "Search" button to load data
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot id="reportTableFooter" style="display:none;">
                                    <tr>
                                        <th colspan="6" class="text-right">Total:</th>
                                        <th class="text-right" id="totalPledged">0.00</th>
                                        <th class="text-right" id="totalContributed">0.00</th>
                                        <th colspan="2"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Record Count -->
                        <div class="row" id="recordInfo" style="display:none;">
                            <div class="col-sm-12">
                                <p class="text-muted">
                                    Showing <span id="recordCount">0</span> record(s)
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
var base_url = '<?php echo base_url() ?>';

$(document).ready(function() {
    // Initialize date pickers
    $('.date').datepicker({
        format: "<?php echo $this->customlib->getSchoolDateFormat() ?>",
        autoclose: true
    });

    // Search button
    $('#searchBtn').click(function() {
        loadReportData();
    });

    // Reset button
    $('#resetBtn').click(function() {
        $('#filterForm')[0].reset();
        $('#reportTableBody').html('<tr><td colspan="10" class="text-center text-muted">Click "Search" button to load data</td></tr>');
        $('#reportTableFooter').hide();
        $('#recordInfo').hide();
    });

    // Load data on page load
    loadReportData();
});

function loadReportData() {
    // Show loading
    $('#loadingIndicator').show();
    $('#reportTableContainer').hide();

    // Get filter values
    var filterData = {
        status: $('#status').val(),
        giving_type_id: $('#giving_type_id').val(),
        giving_frequency_id: $('#giving_frequency_id').val(),
        date_from: $('#date_from').val(),
        date_to: $('#date_to').val()
    };

    // Make AJAX request
    $.ajax({
        url: base_url + "admin/partnerreports/getPartnerInformationData",
        type: "POST",
        data: filterData,
        dataType: 'json',
        success: function(response) {
            $('#loadingIndicator').hide();
            $('#reportTableContainer').show();

            if (response.data && response.data.length > 0) {
                var html = '';
                var totalPledged = 0;
                var totalContributed = 0;

                $.each(response.data, function(index, row) {
                    html += '<tr>';
                    html += '<td>' + row[0] + '</td>'; // Partner Code
                    html += '<td>' + row[1] + '</td>'; // Partner Name
                    html += '<td>' + row[2] + '</td>'; // Email
                    html += '<td>' + row[3] + '</td>'; // Phone
                    html += '<td>' + row[4] + '</td>'; // Giving Type
                    html += '<td>' + row[5] + '</td>'; // Frequency
                    html += '<td class="text-right">' + row[6] + '</td>'; // Pledged Amount
                    html += '<td class="text-right">' + row[7] + '</td>'; // Total Contributed
                    html += '<td>' + row[8] + '</td>'; // Start Date
                    html += '<td>' + row[9] + '</td>'; // Status
                    html += '</tr>';

                    // Calculate totals (extract numeric values from formatted strings)
                    var pledged = parseFloat(row[6].replace(/[^0-9.-]+/g, ""));
                    var contributed = parseFloat(row[7].replace(/[^0-9.-]+/g, ""));
                    totalPledged += pledged || 0;
                    totalContributed += contributed || 0;
                });

                $('#reportTableBody').html(html);

                // Update footer totals
                $('#totalPledged').text(totalPledged.toFixed(2));
                $('#totalContributed').text(totalContributed.toFixed(2));
                $('#reportTableFooter').show();

                // Update record count
                $('#recordCount').text(response.data.length);
                $('#recordInfo').show();
            } else {
                $('#reportTableBody').html('<tr><td colspan="10" class="text-center text-muted">No data available. Try adjusting your filters.</td></tr>');
                $('#reportTableFooter').hide();
                $('#recordInfo').hide();
            }
        },
        error: function(xhr, status, error) {
            $('#loadingIndicator').hide();
            $('#reportTableContainer').show();
            $('#reportTableBody').html('<tr><td colspan="10" class="text-center text-danger">Error loading data: ' + error + '</td></tr>');
            $('#reportTableFooter').hide();
            $('#recordInfo').hide();
        }
    });
}
</script>
