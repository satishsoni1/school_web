<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-pencil"></i> Edit Planner Event</h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url('plannermanager/index')?>">Planner &amp; Test Manager</a></li>
            <li class="active">Edit</li>
        </ol>
    </div>

    <div class="box-body">
        <form class="form-horizontal" method="post" action="<?=base_url('plannermanager/planner_edit/'.$event->id)?>">
            <div class="form-group">
                <label class="col-sm-2 control-label">Date</label>
                <div class="col-sm-4">
                    <input type="date" class="form-control" name="event_date" value="<?=date('Y-m-d', strtotime($event->event_date))?>" required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Title</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="title" value="<?=html_escape($event->title)?>" required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Type</label>
                <div class="col-sm-4">
                    <select class="form-control" name="type">
                        <?php foreach ($plannerTypes as $t): ?>
                            <option value="<?=$t?>" <?=($event->type === $t ? 'selected' : '')?>><?=ucfirst($t)?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Description</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="description" value="<?=html_escape($event->description)?>">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-4">
                    <button type="submit" class="btn btn-success">Save</button>
                    <a href="<?=base_url('plannermanager/index')?>" class="btn btn-default">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
