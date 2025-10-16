<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-users"></i> <?php echo $this->lang->line('partners'); ?></h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary" id="partnerlist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('partner_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('partners', 'can_add')) { ?>
                                <a href="<?php echo base_url() ?>admin/partners/add" class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_partner'); ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-controls">
                            <?php if ($this->session->flashdata('msg')) {
                                echo $this->session->flashdata('msg');
                                $this->session->unset_userdata('msg');
                            } ?>
                        </div>

                        <!-- Filters -->
                        <div class="row">
                            <div class="col-md-12">
                                <form role="form" id="searchform" method="post">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('status'); ?></label>
                                            <select class="form-control" name="status" id="status">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
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
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
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
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php foreach ($giving_frequencies as $frequency) { ?>
                                                    <option value="<?php echo $frequency->id ?>"><?php echo $frequency->name ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <button type="button" class="btn btn-primary btn-block" id="search_btn">
                                                <i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="mailbox-messages table-responsive">
                            <table class="table table-striped table-bordered table-hover ajaxlist" id="partnerDataTable" data-export-title="<?php echo $this->lang->line('partner_list'); ?>">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('partner_code'); ?></th>
                                        <th><?php echo $this->lang->line('partner_name'); ?></th>
                                        <th><?php echo $this->lang->line('email'); ?></th>
                                        <th><?php echo $this->lang->line('phone'); ?></th>
                                        <th><?php echo $this->lang->line('giving_type'); ?></th>
                                        <th><?php echo $this->lang->line('frequency'); ?></th>
                                        <th><?php echo $this->lang->line('contribution_amount'); ?></th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <th class="text-right no-print"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    var base_url = '<?php echo base_url() ?>';

    $(document).ready(function() {
        initDatatable('partnerDataTable', 'admin/partners/getlist');
    });

    $('#search_btn').click(function() {
        $('#partnerDataTable').DataTable().ajax.reload();
    });

    function initDatatable(tablename, path) {
        $('#' + tablename).DataTable({
            "processing": true,
            "serverSide": false,
            "ajax": {
                "url": base_url + path,
                "type": "POST",
                "data": function(d) {
                    d.status = $('#status').val();
                    d.giving_type_id = $('#giving_type_id').val();
                    d.giving_frequency_id = $('#giving_frequency_id').val();
                }
            },
            "columns": [
                {"data": 0},
                {"data": 1},
                {"data": 2},
                {"data": 3},
                {"data": 4},
                {"data": 5},
                {"data": 6},
                {"data": 7},
                {"data": 8}
            ],
            "responsive": true,
            "autoWidth": false,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copyHtml5',
                    text: '<i class="fa fa-files-o"></i>',
                    titleAttr: 'Copy',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ':visible:not(.no-print)'
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o"></i>',
                    titleAttr: 'Excel',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ':visible:not(.no-print)'
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="fa fa-file-text-o"></i>',
                    titleAttr: 'CSV',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ':visible:not(.no-print)'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fa fa-file-pdf-o"></i>',
                    titleAttr: 'PDF',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ':visible:not(.no-print)'
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i>',
                    titleAttr: 'Print',
                    title: $('.download_label').html(),
                    customize: function(win) {
                        $(win.document.body).find('table').addClass('display').css('font-size', '14px');
                        $(win.document.body).find('tr:nth-child(odd) td').each(function() {
                            $(this).css('background-color', '#D0D0D0');
                        });
                        $(win.document.body).find('h1').css('text-align', 'center');
                    },
                    exportOptions: {
                        columns: ':visible:not(.no-print)'
                    }
                },
                {
                    extend: 'colvis',
                    text: '<i class="fa fa-columns"></i>',
                    titleAttr: 'Columns',
                    title: $('.download_label').html(),
                    postfixButtons: ['colvisRestore']
                },
            ]
        });
    }
</script>