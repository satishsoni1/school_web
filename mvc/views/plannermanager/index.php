<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-calendar"></i> Planner &amp; Test Manager</h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active">Planner &amp; Test Manager</li>
        </ol>
    </div><!-- /.box-header -->

    <div class="box-body">

        <!-- ================= Academic Planner ================= -->
        <h4 class="page-header"><i class="fa fa-list"></i> Academic Planner</h4>

        <form class="form-inline" method="post" action="<?=base_url('plannermanager/planner_add')?>" style="margin-bottom:15px;">
            <div class="form-group">
                <label>Date</label>
                <input type="date" class="form-control" name="event_date" required>
            </div>
            <div class="form-group">
                <label>Title</label>
                <input type="text" class="form-control" name="title" placeholder="Event title" required>
            </div>
            <div class="form-group">
                <label>Type</label>
                <select class="form-control" name="type">
                    <?php foreach ($plannerTypes as $t): ?>
                        <option value="<?=$t?>"><?=ucfirst($t)?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Description</label>
                <input type="text" class="form-control" name="description" placeholder="Optional">
            </div>
            <button type="submit" class="btn btn-success"><i class="fa fa-plus"></i> Add</button>
        </form>

        <div id="hide-table">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th><th>Date</th><th>Title</th><th>Type</th><th>Description</th><th class="col-md-2">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (customCompute($planner_events)) { $i = 1; foreach ($planner_events as $ev) { ?>
                    <tr>
                        <td><?=$i?></td>
                        <td><?=date('d M Y', strtotime($ev->event_date))?></td>
                        <td><?=html_escape($ev->title)?></td>
                        <td><span class="label label-default"><?=html_escape($ev->type)?></span></td>
                        <td><?=html_escape($ev->description)?></td>
                        <td>
                            <a class="btn btn-primary btn-xs" href="<?=base_url('plannermanager/planner_edit/'.$ev->id)?>"><i class="fa fa-pencil"></i></a>
                            <a class="btn btn-danger btn-xs" href="<?=base_url('plannermanager/planner_delete/'.$ev->id)?>" onclick="return confirm('Delete this event?');"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                <?php $i++; }} else { ?>
                    <tr><td colspan="6" class="text-center">No planner events yet.</td></tr>
                <?php } ?>
            </tbody>
        </table>
        </div>

        <!-- ================= Periodic Test Schedule ================= -->
        <h4 class="page-header" style="margin-top:30px;"><i class="fa fa-clock-o"></i> Periodic Test Schedule</h4>

        <form class="form-inline" method="post" action="<?=base_url('plannermanager/test_add')?>" style="margin-bottom:15px;">
            <div class="form-group">
                <label>Date</label>
                <input type="date" class="form-control" name="test_date" required>
            </div>
            <div class="form-group">
                <label>Class</label>
                <select class="form-control" name="classesID" required>
                    <option value="">Select</option>
                    <?php foreach ($classes as $c): ?>
                        <option value="<?=$c->classesID?>"><?=html_escape($c->classes)?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Subject</label>
                <input type="text" class="form-control" name="subject" placeholder="e.g. ENGLISH" required>
            </div>
            <button type="submit" class="btn btn-success"><i class="fa fa-plus"></i> Add</button>
        </form>

        <div id="hide-table">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr><th>#</th><th>Date</th><th>Day</th><th>Class</th><th>Subject</th><th class="col-md-2">Action</th></tr>
            </thead>
            <tbody>
                <?php if (customCompute($test_schedules)) { $i = 1; foreach ($test_schedules as $s) { ?>
                    <tr>
                        <td><?=$i?></td>
                        <td><?=date('d M Y', strtotime($s->test_date))?></td>
                        <td><?=html_escape($s->day)?></td>
                        <td><?=html_escape($s->class_name)?></td>
                        <td><?=html_escape($s->subject)?></td>
                        <td>
                            <a class="btn btn-primary btn-xs" href="<?=base_url('plannermanager/test_edit/'.$s->id)?>"><i class="fa fa-pencil"></i></a>
                            <a class="btn btn-danger btn-xs" href="<?=base_url('plannermanager/test_delete/'.$s->id)?>" onclick="return confirm('Delete this row?');"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                <?php $i++; }} else { ?>
                    <tr><td colspan="6" class="text-center">No test schedule rows yet.</td></tr>
                <?php } ?>
            </tbody>
        </table>
        </div>

        <!-- ================= Periodic Test Syllabus ================= -->
        <h4 class="page-header" style="margin-top:30px;"><i class="fa fa-book"></i> Periodic Test Syllabus</h4>

        <form class="form-inline" method="post" action="<?=base_url('plannermanager/syllabus_add')?>" style="margin-bottom:15px;">
            <div class="form-group">
                <label>Class</label>
                <select class="form-control" name="classesID" required>
                    <option value="">Select</option>
                    <?php foreach ($classes as $c): ?>
                        <option value="<?=$c->classesID?>"><?=html_escape($c->classes)?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Subject</label>
                <input type="text" class="form-control" name="subject" placeholder="e.g. ENGLISH" required>
            </div>
            <div class="form-group">
                <label>Syllabus</label>
                <input type="text" class="form-control" name="syllabus" placeholder="Chapters / topics" style="min-width:280px;" required>
            </div>
            <button type="submit" class="btn btn-success"><i class="fa fa-plus"></i> Add</button>
        </form>

        <div id="hide-table">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr><th>#</th><th>Class</th><th>Subject</th><th>Syllabus</th><th class="col-md-2">Action</th></tr>
            </thead>
            <tbody>
                <?php if (customCompute($test_syllabus)) { $i = 1; foreach ($test_syllabus as $sy) { ?>
                    <tr>
                        <td><?=$i?></td>
                        <td><?=html_escape($sy->class_name)?></td>
                        <td><?=html_escape($sy->subject)?></td>
                        <td><?=html_escape($sy->syllabus)?></td>
                        <td>
                            <a class="btn btn-primary btn-xs" href="<?=base_url('plannermanager/syllabus_edit/'.$sy->id)?>"><i class="fa fa-pencil"></i></a>
                            <a class="btn btn-danger btn-xs" href="<?=base_url('plannermanager/syllabus_delete/'.$sy->id)?>" onclick="return confirm('Delete this row?');"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                <?php $i++; }} else { ?>
                    <tr><td colspan="5" class="text-center">No syllabus rows yet.</td></tr>
                <?php } ?>
            </tbody>
        </table>
        </div>

    </div>
</div>
