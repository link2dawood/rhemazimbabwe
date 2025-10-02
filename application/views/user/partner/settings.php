<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-cog"></i> <?php echo $this->lang->line('giving_settings'); ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('user/user/dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo $this->lang->line('dashboard'); ?></a></li>
            <li><a href="<?php echo base_url('user/partner'); ?>"><i class="fa fa-handshake-o"></i> <?php echo $this->lang->line('partners'); ?></a></li>
            <li class="active"><?php echo $this->lang->line('settings'); ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <!-- Partner Info Card -->
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('partner_information'); ?></h3>
                    </div>
                    <div class="box-body box-profile">
                        <h3 class="profile-username text-center">
                            <?php
                            if ($partner['account_type'] == 'organization') {
                                echo $partner['organization_name'];
                            } else {
                                echo $partner['firstname'] . ' ' . $partner['lastname'];
                            }
                            ?>
                        </h3>
                        <p class="text-muted text-center"><?php echo $partner['partner_code']; ?></p>

                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b><?php echo $this->lang->line('account_type'); ?></b>
                                <span class="pull-right">
                                    <?php
                                    echo ($partner['account_type'] == 'individual')
                                        ? '<span class="label label-info">' . $this->lang->line('individual_partner') . '</span>'
                                        : '<span class="label label-primary">' . $this->lang->line('organization_partner') . '</span>';
                                    ?>
                                </span>
                            </li>
                            <li class="list-group-item">
                                <b><?php echo $this->lang->line('status'); ?></b>
                                <span class="pull-right">
                                    <?php
                                    $status_class = '';
                                    $status_text = '';
                                    switch ($partner['status']) {
                                        case 'active':
                                            $status_class = 'success';
                                            $status_text = $this->lang->line('active');
                                            break;
                                        case 'inactive':
                                            $status_class = 'warning';
                                            $status_text = $this->lang->line('pending');
                                            break;
                                        case 'suspended':
                                            $status_class = 'danger';
                                            $status_text = $this->lang->line('suspended');
                                            break;
                                    }
                                    ?>
                                    <span class="label label-<?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                                </span>
                            </li>
                            <li class="list-group-item">
                                <b><?php echo $this->lang->line('email'); ?></b>
                                <span class="pull-right"><?php echo $partner['email']; ?></span>
                            </li>
                            <li class="list-group-item">
                                <b><?php echo $this->lang->line('phone'); ?></b>
                                <span class="pull-right"><?php echo $partner['mobileno']; ?></span>
                            </li>
                        </ul>

                        <a href="<?php echo base_url('user/partner/contributions?partner_id=' . $partner['id']); ?>" class="btn btn-primary btn-block">
                            <i class="fa fa-history"></i> <?php echo $this->lang->line('view_contributions'); ?>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Settings Form -->
            <div class="col-md-8">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('giving_settings'); ?></h3>
                    </div>
                    <form id="settingsForm" method="post" action="<?php echo base_url('user/partner/updateSettings'); ?>">
                        <?php echo $this->customlib->getCSRF(); ?>
                        <input type="hidden" name="partner_id" value="<?php echo $partner['id']; ?>">

                        <div class="box-body">
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> <?php echo $this->lang->line('update_giving_preferences'); ?>
                            </div>

                            <div class="form-group">
                                <label><?php echo $this->lang->line('giving_type'); ?></label>
                                <select class="form-control" name="giving_type_id">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php foreach ($giving_types as $type): ?>
                                        <option value="<?php echo $type['id']; ?>"
                                                <?php echo ($partner['giving_type_id'] == $type['id']) ? 'selected' : ''; ?>>
                                            <?php echo $type['name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label><?php echo $this->lang->line('giving_frequency'); ?></label>
                                <select class="form-control" name="giving_frequency_id">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php foreach ($giving_frequencies as $freq): ?>
                                        <option value="<?php echo $freq['id']; ?>"
                                                <?php echo ($partner['giving_frequency_id'] == $freq['id']) ? 'selected' : ''; ?>>
                                            <?php echo $freq['name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('contribution_amount'); ?></label>
                                        <input type="number" class="form-control" name="contribution_amount"
                                               value="<?php echo $partner['contribution_amount']; ?>"
                                               step="0.01" min="0">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('currency'); ?></label>
                                        <select class="form-control" name="currency">
                                            <option value="USD" <?php echo ($partner['currency'] == 'USD') ? 'selected' : ''; ?>>USD - US Dollar</option>
                                            <option value="ZWL" <?php echo ($partner['currency'] == 'ZWL') ? 'selected' : ''; ?>>ZWL - Zimbabwe Dollar</option>
                                            <option value="ZAR" <?php echo ($partner['currency'] == 'ZAR') ? 'selected' : ''; ?>>ZAR - South African Rand</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?php echo $this->lang->line('notes'); ?></label>
                                <textarea class="form-control" name="notes" rows="4"><?php echo $partner['notes']; ?></textarea>
                            </div>
                        </div>

                        <div class="box-footer">
                            <a href="<?php echo base_url('user/partner'); ?>" class="btn btn-default">
                                <i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back'); ?>
                            </a>
                            <button type="submit" class="btn btn-success pull-right">
                                <i class="fa fa-save"></i> <?php echo $this->lang->line('save'); ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    $('#settingsForm').submit(function(e) {
        e.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            dataType: 'json',
            beforeSend: function() {
                $('button[type="submit"]').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> <?php echo $this->lang->line('saving'); ?>...');
            },
            success: function(response) {
                if (response.status == 'success') {
                    successMsg(response.message);
                    setTimeout(function() {
                        window.location.reload();
                    }, 1500);
                } else {
                    errorMsg(response.message);
                    $('button[type="submit"]').prop('disabled', false).html('<i class="fa fa-save"></i> <?php echo $this->lang->line('save'); ?>');
                }
            },
            error: function() {
                errorMsg('<?php echo $this->lang->line('error_occurred'); ?>');
                $('button[type="submit"]').prop('disabled', false).html('<i class="fa fa-save"></i> <?php echo $this->lang->line('save'); ?>');
            }
        });
    });
});
</script>
