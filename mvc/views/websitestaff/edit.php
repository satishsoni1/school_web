
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-users"></i> Edit Staff Member</h3>

        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> Dashboard</a></li>
            <li><a href="<?=base_url("websitestaff/index")?>">Website Staff</a></li>
            <li class="active">Edit</li>
        </ol>
    </div><!-- /.box-header -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">

                <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">

                    <div class="form-group <?=form_error('name') ? 'has-error' : ''?>">
                        <label for="name" class="col-sm-2 control-label">Name <span class="text-red">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="name" name="name" value="<?=set_value('name', $staff->name)?>">
                        </div>
                        <span class="col-sm-4 control-label"><?php echo form_error('name'); ?></span>
                    </div>

                    <div class="form-group">
                        <label for="designation" class="col-sm-2 control-label">Designation</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="designation" name="designation" value="<?=set_value('designation', $staff->designation)?>" placeholder="e.g. TGT, Librarian, Accountant">
                        </div>
                    </div>

                    <div class="form-group <?=form_error('group_type') ? 'has-error' : ''?>">
                        <label class="col-sm-2 control-label">Group <span class="text-red">*</span></label>
                        <div class="col-sm-6">
                            <label class="radio-inline">
                                <input type="radio" name="group_type" value="teaching" <?=(set_value('group_type', $staff->group_type) == 'teaching') ? 'checked="checked"' : ''?>> Staff
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="group_type" value="non_teaching" <?=(set_value('group_type', $staff->group_type) == 'non_teaching') ? 'checked="checked"' : ''?>> Non-Teaching Staff
                            </label>
                        </div>
                        <span class="col-sm-4 control-label"><?php echo form_error('group_type'); ?></span>
                    </div>

                    <div class="form-group">
                        <label for="sort_order" class="col-sm-2 control-label">Display Order</label>
                        <div class="col-sm-6">
                            <input type="number" class="form-control" id="sort_order" name="sort_order" value="<?=set_value('sort_order', $staff->sort_order)?>">
                            <p class="help-block">Lower numbers show first within their group.</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="email" name="email" value="<?=set_value('email', $staff->email)?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone" class="col-sm-2 control-label">Phone</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="phone" name="phone" value="<?=set_value('phone', $staff->phone)?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Photo</label>
                        <div class="col-sm-6">
                            <?php if (!empty($staff->photo)) { ?>
                                <div style="margin-bottom:10px;">
                                    <img src="<?=base_url('uploads/gallery/'.$staff->photo)?>" alt="" style="width:90px;height:90px;border-radius:50%;object-fit:cover;display:block;margin-bottom:6px;">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="photo_remove" value="1"> Remove current photo
                                    </label>
                                </div>
                            <?php } ?>
                            <input type="file" name="photo" accept="image/*">
                            <p class="help-block">Choose a new file to replace the current photo.</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Show on website</label>
                        <div class="col-sm-6">
                            <input type="checkbox" name="status" value="1" <?=($staff->status == 1) ? 'checked="checked"' : ''?>>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-6">
                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
                            <a href="<?=base_url('websitestaff/index')?>" class="btn btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
