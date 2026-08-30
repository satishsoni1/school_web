
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-users"></i> Website Staff</h3>

        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> Dashboard</a></li>
            <li class="active">Website Staff</li>
        </ol>
    </div><!-- /.box-header -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <p class="text-muted">
                    Controls who appears on the public website's <strong>Staff</strong> page &mdash; independent of the
                    real teacher login accounts, so you can add a photo, set the display order, and include
                    non-teaching staff who don't have a teacher account.
                </p>

                <?php
                    if(permissionChecker('websitestaff_add')){
                ?>
                <h5 class="page-header"><a href="<?php echo base_url('websitestaff/add') ?>"><i class="fa fa-plus"></i> Add Staff Member</a></h5>
                <?php } ?>

                <?php
                    $groups = [
                        'teaching' => ['label' => 'Staff', 'icon' => 'fa-graduation-cap'],
                        'non_teaching' => ['label' => 'Non-Teaching Staff', 'icon' => 'fa-briefcase'],
                    ];
                    $byGroup = ['teaching' => [], 'non_teaching' => []];
                    if (customCompute($staffList)) {
                        foreach ($staffList as $s) {
                            $g = ($s->group_type === 'non_teaching') ? 'non_teaching' : 'teaching';
                            $byGroup[$g][] = $s;
                        }
                    }
                ?>

                <?php foreach ($groups as $groupKey => $groupMeta) { ?>
                    <h4 style="margin-top:30px;"><i class="fa <?=$groupMeta['icon']?>"></i> <?=$groupMeta['label']?></h4>
                    <div id="hide-table">
                        <table class="table table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                                <tr>
                                    <th class="col-sm-1">Order</th>
                                    <th class="col-sm-1">Photo</th>
                                    <th class="col-sm-2">Name</th>
                                    <th class="col-sm-2">Designation</th>
                                    <th class="col-sm-1">Status</th>
                                    <th class="col-sm-2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (customCompute($byGroup[$groupKey])) { foreach ($byGroup[$groupKey] as $s) { ?>
                                    <tr>
                                        <td data-title="Order"><?=(int)$s->sort_order?></td>
                                        <td data-title="Photo">
                                            <?php if (!empty($s->photo)) { ?>
                                                <img src="<?=base_url('uploads/gallery/'.$s->photo)?>" alt="" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
                                            <?php } else { ?>
                                                <i class="fa fa-user-circle-o" style="font-size:32px;color:#ccc;"></i>
                                            <?php } ?>
                                        </td>
                                        <td data-title="Name"><?=htmlspecialchars($s->name)?></td>
                                        <td data-title="Designation"><?=htmlspecialchars($s->designation)?></td>
                                        <td data-title="Status">
                                            <?php if ($s->status == 1) { ?>
                                                <span class="label label-success">Shown</span>
                                            <?php } else { ?>
                                                <span class="label label-default">Hidden</span>
                                            <?php } ?>
                                        </td>
                                        <td data-title="Action">
                                            <?php
                                                echo btn_edit('websitestaff/edit/'.$s->website_staffID, 'Edit');
                                                echo btn_delete('websitestaff/delete/'.$s->website_staffID, 'Delete');
                                            ?>
                                        </td>
                                    </tr>
                                <?php } } else { ?>
                                    <tr><td colspan="6" class="text-center text-muted">No entries yet.</td></tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>
</div>
