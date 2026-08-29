<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-bell"></i> Send Test Notification</h3>

        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active">Send Notification</li>
        </ol>
    </div><!-- /.box-header -->

    <div class="box-body">

        <?php if ($result): ?>
            <?php $known = $result['recipientCount'] !== null; ?>
            <div class="alert <?= (!$known || $result['recipientCount'] > 0) ? 'alert-success' : 'alert-warning' ?>">
                <strong><?= (!$known || $result['recipientCount'] > 0) ? 'Sent.' : 'Not sent — no recipients resolved.' ?></strong>
                <?php if ($known): ?>
                    Recipient count: <?= (int) $result['recipientCount'] ?><?php if (!empty($result['notificationID'])): ?>, Notification ID: <?= (int) $result['notificationID'] ?><?php endif; ?>
                <?php else: ?>
                    Broadcast test — no history record kept, recipient count is up to OneSignal ("All" segment).
                <?php endif; ?>
                <?php if ($result['pushResponse']): ?>
                    <hr>
                    <strong>OneSignal raw response</strong> (use this to confirm delivery — look for an "id" field and a non-zero "recipients" count; "errors" means it failed):
                    <pre style="white-space:pre-wrap;word-break:break-all;"><?= htmlspecialchars($result['pushResponse']) ?></pre>
                <?php elseif ($known && $result['recipientCount'] > 0): ?>
                    <hr>
                    <em>No OneSignal response captured — recipients were saved to the in-app notification history, but the push call itself may have failed silently (check mvc/logs / MAMP php_error.log for "OneSignal push failed").</em>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-sm-12">
                <form class="form-horizontal" role="form" method="post">

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Send To</label>
                        <div class="col-sm-4">
                            <select class="form-control" id="target" name="target">
                                <option value="broadcast">Broadcast test (any subscribed device — no login/targeting needed)</option>
                                <option value="all">Everyone (all users, by external_id)</option>
                                <option value="class">A specific class (students + their parents)</option>
                                <option value="user">One specific user</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" id="class-row" style="display:none;">
                        <label class="col-sm-2 control-label">Class</label>
                        <div class="col-sm-4">
                            <select class="form-control" name="classesID">
                                <option value="">Select Class</option>
                                <?php foreach ($classes as $class): ?>
                                    <option value="<?= $class->classesID ?>"><?= $class->classes ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" id="user-type-row" style="display:none;">
                        <label class="col-sm-2 control-label">User Type</label>
                        <div class="col-sm-4">
                            <select class="form-control" id="usertypeID" name="usertypeID">
                                <option value="">Select Type</option>
                                <option value="1">Admin</option>
                                <option value="2">Teacher</option>
                                <option value="3">Student</option>
                                <option value="4">Parent</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" id="user-row" style="display:none;">
                        <label class="col-sm-2 control-label">User</label>
                        <div class="col-sm-4">
                            <select class="form-control" id="userID" name="userID">
                                <option value="">Select Type first</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="title" class="col-sm-2 control-label">Title</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="title" name="title" placeholder="Test Notification">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-2 control-label">Message</label>
                        <div class="col-sm-4">
                            <textarea class="form-control" id="message" name="message" placeholder="This is a test notification."></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-4">
                            <input type="submit" class="btn btn-success" value="Send">
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
function toggleRows() {
    var target = $('#target').val();
    $('#class-row').toggle(target === 'class');
    $('#user-type-row').toggle(target === 'user');
    $('#user-row').toggle(target === 'user');
}
$('#target').on('change', toggleRows);
toggleRows();

$('#usertypeID').on('change', function() {
    var usertypeID = $(this).val();
    if (!usertypeID) {
        $('#userID').html('<option value="">Select Type first</option>');
        return;
    }
    $.ajax({
        type: 'POST',
        url: "<?=base_url('sendnotification/usercall')?>",
        data: "id=" + usertypeID,
        dataType: "html",
        success: function(data) {
            $('#userID').html(data);
        }
    });
});
</script>
