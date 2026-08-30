
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-users"></i> Add Staff Member</h3>

        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> Dashboard</a></li>
            <li><a href="<?=base_url("websitestaff/index")?>">Website Staff</a></li>
            <li class="active">Add</li>
        </ol>
    </div><!-- /.box-header -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">

                <?php if (customCompute($teachers)) { ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Start from a teacher</label>
                    <div class="col-sm-6">
                        <select class="form-control" id="copyFromTeacher">
                            <option value="">&mdash; Add from scratch instead &mdash;</option>
                            <?php foreach ($teachers as $t) { ?>
                                <option value="<?=$t->teacherID?>"
                                    data-name="<?=htmlspecialchars($t->name)?>"
                                    data-designation="<?=htmlspecialchars($t->designation)?>"
                                    data-photo="<?=$t->photo ? base_url('uploads/images/'.$t->photo) : ''?>">
                                    <?=htmlspecialchars($t->name)?><?=$t->designation ? ' — '.htmlspecialchars($t->designation) : ''?>
                                </option>
                            <?php } ?>
                        </select>
                        <p class="help-block">Copies their name, designation and photo in as a starting point — this becomes its own independent website entry, so editing it here never changes the real teacher record.</p>
                    </div>
                </div>
                <hr/>
                <?php } ?>
            </div>
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="source_teacherID" id="source_teacherID" value="">

                    <div class="form-group <?=form_error('name') ? 'has-error' : ''?>">
                        <label for="name" class="col-sm-2 control-label">Name <span class="text-red">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="name" name="name" value="<?=set_value('name')?>">
                        </div>
                        <span class="col-sm-4 control-label"><?php echo form_error('name'); ?></span>
                    </div>

                    <div class="form-group">
                        <label for="designation" class="col-sm-2 control-label">Designation</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="designation" name="designation" value="<?=set_value('designation')?>" placeholder="e.g. TGT, Librarian, Accountant">
                        </div>
                    </div>

                    <div class="form-group <?=form_error('group_type') ? 'has-error' : ''?>">
                        <label class="col-sm-2 control-label">Group <span class="text-red">*</span></label>
                        <div class="col-sm-6">
                            <label class="radio-inline">
                                <input type="radio" name="group_type" value="teaching" id="groupTeaching" checked="checked"> Staff
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="group_type" value="non_teaching" id="groupNonTeaching"> Non-Teaching Staff
                            </label>
                        </div>
                        <span class="col-sm-4 control-label"><?php echo form_error('group_type'); ?></span>
                    </div>

                    <div class="form-group">
                        <label for="sort_order" class="col-sm-2 control-label">Display Order</label>
                        <div class="col-sm-6">
                            <input type="number" class="form-control" id="sort_order" name="sort_order"
                                value="<?=set_value('sort_order', $nextOrderTeaching)?>"
                                data-teaching="<?=$nextOrderTeaching?>" data-non-teaching="<?=$nextOrderNonTeaching?>">
                            <p class="help-block">Lower numbers show first within their group.</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="email" name="email" value="<?=set_value('email')?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone" class="col-sm-2 control-label">Phone</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="phone" name="phone" value="<?=set_value('phone')?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Photo</label>
                        <div class="col-sm-6">
                            <img id="photoPreview" src="" alt="" style="display:none;width:90px;height:90px;border-radius:50%;object-fit:cover;margin-bottom:10px;">
                            <input type="file" name="photo" id="photoInput" accept="image/*">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Show on website</label>
                        <div class="col-sm-6">
                            <input type="checkbox" name="status" value="1" checked="checked">
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

<script type="text/javascript">
    $(document).ready(function() {
        $('#groupTeaching, #groupNonTeaching').change(function() {
            var el = document.getElementById('sort_order');
            var isTeaching = $('#groupTeaching').is(':checked');
            el.value = isTeaching ? el.getAttribute('data-teaching') : el.getAttribute('data-non-teaching');
        });

        $('#copyFromTeacher').change(function() {
            var opt = $(this).find('option:selected');
            var id = $(this).val();
            $('#source_teacherID').val(id);
            if (id) {
                $('#name').val(opt.data('name') || '');
                $('#designation').val(opt.data('designation') || '');
                var photoUrl = opt.data('photo');
                if (photoUrl) {
                    $('#photoPreview').attr('src', photoUrl).show();
                } else {
                    $('#photoPreview').hide();
                }
            }
        });

        $('#photoInput').change(function() {
            var file = this.files[0];
            if (!file) { return; }
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#photoPreview').attr('src', e.target.result).show();
            };
            reader.readAsDataURL(file);
        });
    });
</script>
