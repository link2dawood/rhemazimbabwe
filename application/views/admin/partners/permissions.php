<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-lock"></i> <?php echo $this->lang->line('partner_permissions'); ?>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>admin/dashboard"><i class="fa fa-dashboard"></i> <?php echo $this->lang->line('dashboard'); ?></a></li>
            <li><a href="<?php echo base_url(); ?>admin/partners"><i class="fa fa-handshake-o"></i> <?php echo $this->lang->line('partners'); ?></a></li>
            <li><a href="<?php echo base_url(); ?>admin/partners/show/<?php echo $partner->id; ?>"><?php echo $partner->firstname . ' ' . $partner->lastname; ?></a></li>
            <li class="active"><?php echo $this->lang->line('permissions'); ?></li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <!-- Partner Info Card -->
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-user"></i> <?php echo $this->lang->line('partner_information'); ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="text-center" style="margin-bottom: 15px;">
                            <?php
                            $image = $partner->photo ? base_url() . $partner->photo : base_url() . 'uploads/partner_images/default.png';
                            ?>
                            <img src="<?php echo $image; ?>" class="img-circle" alt="Partner Photo" style="width: 100px; height: 100px; object-fit: cover;">
                        </div>

                        <table class="table table-striped">
                            <tr>
                                <th style="width: 40%;"><?php echo $this->lang->line('partner_code'); ?></th>
                                <td><span class="label label-info"><?php echo $partner->partner_code; ?></span></td>
                            </tr>
                            <tr>
                                <th><?php echo $this->lang->line('name'); ?></th>
                                <td><?php echo $partner->firstname . ' ' . $partner->lastname; ?></td>
                            </tr>
                            <tr>
                                <th><?php echo $this->lang->line('email'); ?></th>
                                <td><?php echo $partner->email; ?></td>
                            </tr>
                            <tr>
                                <th><?php echo $this->lang->line('phone'); ?></th>
                                <td><?php echo $partner->mobileno; ?></td>
                            </tr>
                            <tr>
                                <th><?php echo $this->lang->line('status'); ?></th>
                                <td>
                                    <span class="label label-<?php echo $partner->status == 'active' ? 'success' : ($partner->status == 'inactive' ? 'warning' : 'danger'); ?>">
                                        <?php echo ucfirst($partner->status); ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th><?php echo $this->lang->line('giving_type'); ?></th>
                                <td><?php echo $partner->type_name ? $partner->type_name : '-'; ?></td>
                            </tr>
                        </table>

                        <div class="text-center" style="margin-top: 15px;">
                            <a href="<?php echo base_url(); ?>admin/partners/show/<?php echo $partner->id; ?>" class="btn btn-default btn-sm">
                                <i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back_to_partner'); ?>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Currently Granted Permissions Card -->
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('current_permissions'); ?></h3>
                    </div>
                    <div class="box-body">
                        <?php if (empty($granted_permissions)): ?>
                            <p class="text-muted text-center">
                                <i class="fa fa-info-circle"></i> <?php echo $this->lang->line('no_permissions_granted'); ?>
                            </p>
                        <?php else: ?>
                            <ul class="list-unstyled">
                                <?php
                                // Default icons based on permission codes
                                $icon_map = array(
                                    'library_access' => 'fa-book',
                                    'online_courses' => 'fa-graduation-cap',
                                    'download_centre' => 'fa-download',
                                    'gmeet_access' => 'fa-video-camera',
                                    'zoom_access' => 'fa-video-camera',
                                    'events_access' => 'fa-calendar'
                                );

                                foreach ($permission_types as $perm_type) {
                                    if (in_array($perm_type->permission_code, $granted_permissions)) {
                                        $icon = isset($icon_map[$perm_type->permission_code]) ? $icon_map[$perm_type->permission_code] : 'fa-check';
                                        ?>
                                        <li style="padding: 5px 0; border-bottom: 1px solid #f4f4f4;">
                                            <i class="fa <?php echo $icon; ?> text-success"></i>
                                            <strong><?php echo $perm_type->permission_name; ?></strong>
                                        </li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                            <div class="text-center text-muted" style="margin-top: 10px; font-size: 12px;">
                                <i class="fa fa-info-circle"></i> <?php echo count($granted_permissions); ?> permission(s) granted
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Permission Assignment Form -->
            <div class="col-md-8">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-edit"></i> <?php echo $this->lang->line('assign_permissions'); ?></h3>
                    </div>

                    <?php echo form_open('admin/partners/permissions/' . $partner->id, array('class' => 'form-horizontal')); ?>

                    <div class="box-body">
                        <?php
                        if ($this->session->flashdata('msg')) {
                            echo $this->session->flashdata('msg');
                        }
                        ?>

                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i>
                            <strong><?php echo $this->lang->line('note'); ?>:</strong>
                            <?php echo $this->lang->line('select_permissions_to_grant'); ?>
                            <br>
                            <small><?php echo $this->lang->line('granted_permissions_will_appear_in_partner_sidebar'); ?></small>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-12">
                                <h4 style="margin-top: 0;">
                                    <i class="fa fa-list"></i> <?php echo $this->lang->line('available_permissions'); ?>
                                </h4>
                            </label>
                        </div>

                        <?php if (empty($permission_types)): ?>
                            <div class="alert alert-warning">
                                <i class="fa fa-exclamation-triangle"></i>
                                <?php echo $this->lang->line('no_permission_types_available'); ?>
                            </div>
                        <?php else: ?>
                            <div class="row">
                                <?php
                                // Default icons based on permission codes
                                $icon_map = array(
                                    'library_access' => 'fa-book',
                                    'online_courses' => 'fa-graduation-cap',
                                    'download_centre' => 'fa-download',
                                    'gmeet_access' => 'fa-video-camera',
                                    'zoom_access' => 'fa-video-camera',
                                    'events_access' => 'fa-calendar'
                                );

                                foreach ($permission_types as $perm_type):
                                    $icon = isset($icon_map[$perm_type->permission_code]) ? $icon_map[$perm_type->permission_code] : 'fa-key';
                                ?>
                                    <div class="col-md-6">
                                        <div class="permission-item" style="border: 1px solid #ddd; border-radius: 4px; padding: 15px; margin-bottom: 15px; background-color: #f9f9f9;">
                                            <div class="checkbox" style="margin: 0;">
                                                <label style="font-weight: normal; display: flex; align-items: flex-start;">
                                                    <input type="checkbox"
                                                           name="permissions[]"
                                                           value="<?php echo $perm_type->permission_code; ?>"
                                                           <?php echo in_array($perm_type->permission_code, $granted_permissions) ? 'checked' : ''; ?>
                                                           style="margin-top: 3px; margin-right: 8px;">
                                                    <div style="flex: 1;">
                                                        <div style="display: flex; align-items: center; margin-bottom: 5px;">
                                                            <i class="fa <?php echo $icon; ?>" style="margin-right: 8px; color: #3c8dbc;"></i>
                                                            <strong style="font-size: 14px;"><?php echo $perm_type->permission_name; ?></strong>
                                                        </div>
                                                        <?php if ($perm_type->description): ?>
                                                            <small class="text-muted" style="display: block; margin-left: 0;">
                                                                <?php echo $perm_type->description; ?>
                                                            </small>
                                                        <?php endif; ?>
                                                        <div style="margin-top: 5px;">
                                                            <span class="label label-default" style="font-size: 10px;">
                                                                <?php echo $perm_type->permission_code; ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div style="border-top: 2px solid #ddd; padding-top: 15px; margin-top: 10px;">
                                        <button type="button" class="btn btn-default btn-sm" onclick="selectAllPermissions()">
                                            <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('select_all'); ?>
                                        </button>
                                        <button type="button" class="btn btn-default btn-sm" onclick="deselectAllPermissions()">
                                            <i class="fa fa-square-o"></i> <?php echo $this->lang->line('deselect_all'); ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <a href="<?php echo base_url(); ?>admin/partners/show/<?php echo $partner->id; ?>" class="btn btn-default">
                                    <i class="fa fa-times"></i> <?php echo $this->lang->line('cancel'); ?>
                                </a>
                                <button type="submit" name="submit" value="1" class="btn btn-primary">
                                    <i class="fa fa-save"></i> <?php echo $this->lang->line('save_permissions'); ?>
                                </button>
                            </div>
                        </div>
                    </div>

                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
function selectAllPermissions() {
    $('input[name="permissions[]"]').prop('checked', true);
}

function deselectAllPermissions() {
    $('input[name="permissions[]"]').prop('checked', false);
}
</script>

<style>
.permission-item:hover {
    background-color: #f0f0f0 !important;
    border-color: #3c8dbc !important;
    transition: all 0.3s;
}

.permission-item input[type="checkbox"]:checked + div {
    color: #00a65a;
}

@media print {
    .box-footer {
        display: none;
    }
}
</style>
