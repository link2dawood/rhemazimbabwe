<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <i class="fa fa-database"></i> Session Management
        <small>Database session monitoring and management</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url(); ?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Session Management</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-blue">
                <div class="inner">
                    <h3 id="active-sessions"><?php echo $session_stats['active_sessions']; ?></h3>
                    <p>Active Sessions</p>
                </div>
                <div class="icon">
                    <i class="fa fa-users"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3 id="last-hour"><?php echo $session_stats['last_hour']; ?></h3>
                    <p>Last Hour</p>
                </div>
                <div class="icon">
                    <i class="fa fa-clock-o"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3 id="last-24-hours"><?php echo $session_stats['last_24_hours']; ?></h3>
                    <p>Last 24 Hours</p>
                </div>
                <div class="icon">
                    <i class="fa fa-calendar"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-red">
                <div class="inner">
                    <h3 id="old-sessions"><?php echo $session_stats['old_sessions']; ?></h3>
                    <p>Old Sessions</p>
                </div>
                <div class="icon">
                    <i class="fa fa-trash"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Session Management Actions -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-cogs"></i> Session Actions</h3>
                </div>
                <div class="box-body">
                    <div class="btn-group">
                        <button type="button" class="btn btn-success" onclick="refreshStats()">
                            <i class="fa fa-refresh"></i> Refresh Stats
                        </button>
                        <a href="<?php echo base_url(); ?>admin/session_manager/cleanup" class="btn btn-warning" onclick="return confirm('Are you sure you want to clean up old sessions?')">
                            <i class="fa fa-trash"></i> Cleanup Old Sessions
                        </a>
                        <button type="button" class="btn btn-info" onclick="refreshSessions()">
                            <i class="fa fa-list"></i> Refresh Session List
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Sessions Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-list"></i> Active Sessions
                        <small class="pull-right">Last Updated: <span id="last-updated"><?php echo date('Y-m-d H:i:s'); ?></span></small>
                    </h3>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="sessions-table">
                            <thead>
                                <tr>
                                    <th>Session ID</th>
                                    <th>IP Address</th>
                                    <th>User ID</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Last Activity</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($active_sessions as $session): ?>
                                <?php 
                                $session_data = unserialize($session->data);
                                $user_id = isset($session_data['user_id']) ? $session_data['user_id'] : 'N/A';
                                $username = isset($session_data['username']) ? $session_data['username'] : 'N/A';
                                $role = isset($session_data['role']) ? $session_data['role'] : 'N/A';
                                ?>
                                <tr>
                                    <td>
                                        <code><?php echo substr($session->id, 0, 20) . '...'; ?></code>
                                    </td>
                                    <td><?php echo $session->ip_address; ?></td>
                                    <td><?php echo $user_id; ?></td>
                                    <td><?php echo $username; ?></td>
                                    <td>
                                        <span class="label label-info"><?php echo ucfirst($role); ?></span>
                                    </td>
                                    <td><?php echo date('Y-m-d H:i:s', $session->timestamp); ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?php echo base_url(); ?>admin/session_manager/debug_session/<?php echo $session->id; ?>" 
                                               class="btn btn-info btn-xs" title="Debug Session" target="_blank">
                                                <i class="fa fa-bug"></i>
                                            </a>
                                            <a href="<?php echo base_url(); ?>admin/session_manager/force_logout/<?php echo $session->id; ?>" 
                                               class="btn btn-danger btn-xs" title="Force Logout" 
                                               onclick="return confirm('Are you sure you want to force logout this session?')">
                                                <i class="fa fa-sign-out"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Session Configuration Info -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-info-circle"></i> Session Configuration</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Current Settings:</h4>
                            <ul>
                                <li><strong>Session Driver:</strong> Database</li>
                                <li><strong>Session Table:</strong> ci_sessions</li>
                                <li><strong>Session Expiration:</strong> 7200 seconds (2 hours)</li>
                                <li><strong>Session Regeneration:</strong> 300 seconds (5 minutes)</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h4>Benefits of Database Sessions:</h4>
                            <ul>
                                <li>✅ Persistent across server restarts</li>
                                <li>✅ Better for load balancing</li>
                                <li>✅ Centralized session management</li>
                                <li>✅ Resolves "No More Classes Found" error</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
$(document).ready(function() {
    // Auto-refresh every 30 seconds
    setInterval(function() {
        refreshStats();
        refreshSessions();
    }, 30000);
});

function refreshStats() {
    $.ajax({
        url: '<?php echo base_url(); ?>admin/session_manager/get_stats_ajax',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            $('#active-sessions').text(data.active_sessions);
            $('#last-hour').text(data.last_hour);
            $('#last-24-hours').text(data.last_24_hours);
            $('#old-sessions').text(data.old_sessions);
            $('#last-updated').text(new Date().toLocaleString());
        },
        error: function() {
            console.log('Error refreshing stats');
        }
    });
}

function refreshSessions() {
    $.ajax({
        url: '<?php echo base_url(); ?>admin/session_manager/get_sessions_ajax',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            var tbody = $('#sessions-table tbody');
            tbody.empty();
            
            $.each(data, function(index, session) {
                var row = '<tr>' +
                    '<td><code>' + session.id.substring(0, 20) + '...</code></td>' +
                    '<td>' + session.ip_address + '</td>' +
                    '<td>' + session.user_id + '</td>' +
                    '<td>' + session.username + '</td>' +
                    '<td><span class="label label-info">' + session.role.charAt(0).toUpperCase() + session.role.slice(1) + '</span></td>' +
                    '<td>' + session.timestamp + '</td>' +
                    '<td>' +
                        '<div class="btn-group">' +
                            '<a href="<?php echo base_url(); ?>admin/session_manager/debug_session/' + session.id + '" class="btn btn-info btn-xs" title="Debug Session" target="_blank"><i class="fa fa-bug"></i></a>' +
                            '<a href="<?php echo base_url(); ?>admin/session_manager/force_logout/' + session.id + '" class="btn btn-danger btn-xs" title="Force Logout" onclick="return confirm(\'Are you sure you want to force logout this session?\')"><i class="fa fa-sign-out"></i></a>' +
                        '</div>' +
                    '</td>' +
                '</tr>';
                tbody.append(row);
            });
            
            $('#last-updated').text(new Date().toLocaleString());
        },
        error: function() {
            console.log('Error refreshing sessions');
        }
    });
}
</script>
