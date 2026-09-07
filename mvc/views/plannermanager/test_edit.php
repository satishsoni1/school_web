<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-pencil"></i> Edit Test Schedule Row</h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url('plannermanager/index')?>">Planner &amp; Test Manager</a></li>
            <li class="active">Edit</li>
        </ol>
    </div>

    <div class="box-body">
        <form class="form-horizontal" method="post" action="<?=base_url('plannermanager/test_edit/'.$schedule->id)?>">
            <div class="form-group">
                <label class="col-sm-2 control-label">Date</label>
                <div class="col-sm-4">
                    <input type="date" class="form-control" name="test_date" value="<?=date('Y-m-d', strtotime($schedule->test_date))?>" required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Class</label>
                <div class="col-sm-4">
                    <select class="form-control" name="classesID" required>
                        <option value="">Select</option>
                        <?php foreach ($classes as $c): ?>
                            <option value="<?=$c->classesID?>" <?=($schedule->classesID == $c->classesID ? 'selected' : '')?>><?=html_escape($c->classes)?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Subject</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="subject" value="<?=html_escape($schedule->subject)?>" required>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-4">
                    <button type="submit" class="btn btn-success">Save</button>
                    <a href="<?=base_url('plannermanager/index')?>" class="btn btn-default">Cancel</a>
                </div>
            </div>
        </form>
        <p class="text-muted col-sm-offset-2">The weekday is filled in automatically from the date.</p>
    </div>
</div>
