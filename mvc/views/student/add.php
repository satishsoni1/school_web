<style>
.switch-container {
    margin-top: 10px;
    display: flex;
    align-items: center;
}
.switch {
  position: relative;
  display: inline-block;
  width: 42px;
  height: 22px;
  margin-bottom: 0;
  vertical-align: middle;
}
.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
  border-radius: 22px;
}
.slider:before {
  position: absolute;
  content: "";
  height: 16px;
  width: 16px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
  border-radius: 50%;
}
.switch input:checked + .slider {
  background-color: #26b99a;
}
.switch input:checked + .slider:before {
  -webkit-transform: translateX(20px);
  -ms-transform: translateX(20px);
  transform: translateX(20px);
}
.switch-text {
  margin-left: 8px;
  font-weight: 500;
  font-size: 13px;
  color: #555;
  cursor: pointer;
  user-select: none;
}
</style>
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-student"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("student/index")?>"><?=$this->lang->line('menu_student')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_student')?></li>
        </ol>
    </div><div class="box-body">
        <form role="form" method="post" enctype="multipart/form-data">
            <div class="row">
                
                <div class="col-md-6">
                    <h4 class="box-title" style="margin-bottom: 15px; padding-bottom: 5px; border-bottom: 1px solid #f4f4f4; color: #555;">Personal Details</h4>
                    
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group <?=form_error('name') ? 'has-error' : ''?>">
                                <label for="name_id"><?=$this->lang->line("student_name")?> <span class="text-red">*</span></label>
                                <input type="text" class="form-control" id="name_id" name="name" value="<?=set_value('name')?>">
                                <span class="text-danger"><?=form_error('name')?></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group <?=form_error('surname') ? 'has-error' : ''?>">
                                <label for="surname">Surname</label>
                                <input type="text" class="form-control" id="surname" name="surname" value="<?=set_value('surname')?>">
                                <span class="text-danger"><?=form_error('surname')?></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group <?=form_error('father_name') ? 'has-error' : ''?>">
                        <label for="father_name">Father's Name</label>
                        <input type="text" class="form-control" id="father_name" name="father_name" value="<?=set_value('father_name')?>">
                        <span class="text-danger"><?=form_error('father_name')?></span>
                        <div class="switch-container">
                            <label class="switch">
                                <input type="checkbox" name="append_father_name" id="append_father_name" value="yes" <?=set_checkbox('append_father_name', 'yes', TRUE)?>>
                                <span class="slider"></span>
                            </label>
                            <label for="append_father_name" class="switch-text">Append Father's Name in Student Name</label>
                        </div>
                    </div>

                    <div class="form-group <?=form_error('mother_name') ? 'has-error' : ''?>">
                        <label for="mother_name">Mother's Name</label>
                        <input type="text" class="form-control" id="mother_name" name="mother_name" value="<?=set_value('mother_name')?>">
                        <span class="text-danger"><?=form_error('mother_name')?></span>
                    </div>

                    <div class="form-group <?=form_error('guardian_name') ? 'has-error' : ''?>">
                        <label for="guardian_name">Guardian Name</label>
                        <input type="text" class="form-control" id="guardian_name" name="guardian_name" value="<?=set_value('guardian_name')?>">
                        <span class="text-danger"><?=form_error('guardian_name')?></span>
                    </div>

                    <div class="form-group <?=form_error('guargianID') ? 'has-error' : ''?>">
                        <label for="guargianID">Linked <?=$this->lang->line("student_guargian")?> <small class="text-muted">(for App login)</small></label>
                        <?php
                            $array = array('0' => $this->lang->line('student_select_guargian'));
                            foreach ($parents as $parent) {
                                $parentsemail = $parent->email ? " (" . $parent->email ." )" : '';
                                $array[$parent->parentsID] = $parent->name.$parentsemail;
                            }
                            echo form_dropdown("guargianID", $array, set_value("guargianID"), "id='guargianID' class='form-control guargianID select2'");
                        ?>
                        <span class="text-danger"><?=form_error('guargianID')?></span>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group <?=form_error('dob') ? 'has-error' : ''?>">
                                <label for="dob"><?=$this->lang->line("student_dob")?></label>
                                <input type="text" class="form-control" id="dob" name="dob" value="<?=set_value('dob')?>">
                                <span class="text-danger"><?=form_error('dob')?></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group <?=form_error('sex') ? 'has-error' : ''?>">
                                <label for="sex"><?=$this->lang->line("student_sex")?></label>
                                <?=form_dropdown("sex", array($this->lang->line('student_sex_male') => $this->lang->line('student_sex_male'), $this->lang->line('student_sex_female') => $this->lang->line('student_sex_female')), set_value("sex"), "id='sex' class='form-control'")?>
                                <span class="text-danger"><?=form_error('sex')?></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group <?=form_error('nationality') ? 'has-error' : ''?>">
                                <label for="nationality">Nationality</label>
                                <input type="text" class="form-control" id="nationality" name="nationality" value="<?=set_value('nationality', 'Indian')?>">
                                <span class="text-danger"><?=form_error('nationality')?></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group <?=form_error('mother_tongue') ? 'has-error' : ''?>">
                                <label for="mother_tongue">Mother Tongue</label>
                                <input type="text" class="form-control" id="mother_tongue" name="mother_tongue" value="<?=set_value('mother_tongue')?>">
                                <span class="text-danger"><?=form_error('mother_tongue')?></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group <?=form_error('religion') ? 'has-error' : ''?>">
                                <label for="religion"><?=$this->lang->line("student_religion")?></label>
                                <input type="text" class="form-control" id="religion" name="religion" value="<?=set_value('religion')?>">
                                <span class="text-danger"><?=form_error('religion')?></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group <?=form_error('caste') ? 'has-error' : ''?>">
                                <label for="caste">Caste</label>
                                <input type="text" class="form-control" id="caste" name="caste" value="<?=set_value('caste')?>">
                                <span class="text-danger"><?=form_error('caste')?></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group <?=form_error('sub_caste') ? 'has-error' : ''?>">
                                <label for="sub_caste">Sub-Caste</label>
                                <input type="text" class="form-control" id="sub_caste" name="sub_caste" value="<?=set_value('sub_caste')?>">
                                <span class="text-danger"><?=form_error('sub_caste')?></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group <?=form_error('photo') ? 'has-error' : ''?>">
                        <label for="photo"><?=$this->lang->line("student_photo")?></label>
                        <div class="input-group image-preview">
                            <input type="text" class="form-control image-preview-filename" disabled="disabled">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                    <span class="fa fa-remove"></span> <?=$this->lang->line('student_clear')?>
                                </button>
                                <div class="btn btn-success image-preview-input">
                                    <span class="fa fa-repeat"></span>
                                    <span class="image-preview-input-title"><?=$this->lang->line('student_file_browse')?></span>
                                    <input type="file" accept="image/png, image/jpeg, image/gif" name="photo"/>
                                </div>
                            </span>
                        </div>
                        <span class="text-danger"><?=form_error('photo')?></span>
                    </div>

                </div> <div class="col-md-6">
                    <h4 class="box-title" style="margin-bottom: 15px; padding-bottom: 5px; border-bottom: 1px solid #f4f4f4; color: #555;">Academic & Logistical Information</h4>
                    
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group <?=form_error('classesID') ? 'has-error' : ''?>">
                                <label for="classesID"><?=$this->lang->line("student_classes")?> <span class="text-red">*</span></label>
                                <?php
                                    $classArray = array(0 => $this->lang->line("student_select_class"));
                                    foreach ($classes as $classa) {
                                        $classArray[$classa->classesID] = $classa->classes;
                                    }
                                    echo form_dropdown("classesID", $classArray, set_value("classesID"), "id='classesID' class='form-control select2'");
                                ?>
                                <span class="text-danger"><?=form_error('classesID')?></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group <?=form_error('admission_date') ? 'has-error' : ''?>">
                                <label for="admission_date"><?=$this->lang->line("student_admission_date")?></label>
                                <input type="text" class="form-control" id="admission_date" name="admission_date" value="<?=set_value('admission_date')?>">
                                <span class="text-danger"><?=form_error('admission_date')?></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group <?=form_error('registerNO') ? 'has-error' : ''?>">
                                <label for="registerNO"><?=$this->lang->line("student_registerNO")?> <span class="text-red">*</span></label>
                                <input type="text" class="form-control" id="registerNO" name="registerNO" value="<?=set_value('registerNO')?>">
                                <span class="text-danger"><?=form_error('registerNO')?></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group <?=form_error('roll') ? 'has-error' : ''?>">
                                <label for="roll"><?=$this->lang->line("student_roll")?></label>
                                <input type="text" class="form-control" id="roll" name="roll" value="<?=set_value('roll')?>">
                                <span class="text-danger"><?=form_error('roll')?></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group <?=form_error('saral_student_id') ? 'has-error' : ''?>">
                                <label for="saral_student_id">Student ID (Saral)</label>
                                <input type="text" class="form-control" id="saral_student_id" name="saral_student_id" value="<?=set_value('saral_student_id')?>">
                                <span class="text-danger"><?=form_error('saral_student_id')?></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group <?=form_error('pen_no') ? 'has-error' : ''?>">
                                <label for="pen_no">PEN No</label>
                                <input type="text" class="form-control" id="pen_no" name="pen_no" value="<?=set_value('pen_no')?>">
                                <span class="text-danger"><?=form_error('pen_no')?></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group <?=form_error('aadhar_or_uid') ? 'has-error' : ''?>">
                                <label for="aadhar_or_uid">UID No / Aadhar No</label>
                                <input type="text" class="form-control" id="aadhar_or_uid" name="aadhar_or_uid" value="<?=set_value('aadhar_or_uid')?>">
                                <span class="text-danger"><?=form_error('aadhar_or_uid')?></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group <?=form_error('bloodgroup') ? 'has-error' : ''?>">
                                <label for="bloodgroup"><?=$this->lang->line("student_bloodgroup")?></label>
                                <?php
                                    $bloodArray = array(
                                        '0' => $this->lang->line('student_select_bloodgroup'),
                                        'A+' => 'A+', 'A-' => 'A-', 'B+' => 'B+', 'B-' => 'B-',
                                        'O+' => 'O+', 'O-' => 'O-', 'AB+' => 'AB+', 'AB-' => 'AB-'
                                    );
                                    echo form_dropdown("bloodgroup", $bloodArray, set_value("bloodgroup"), "id='bloodgroup' class='form-control select2'");
                                ?>
                                <span class="text-danger"><?=form_error('bloodgroup')?></span>
                            </div>
                        </div>
                    </div>

                    <h5 style="margin-top: 20px; font-weight: bold; color: #777;"><i class="fa fa-map-marker"></i> Place of Birth Information</h5>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group <?=form_error('birth_place') ? 'has-error' : ''?>">
                                <label for="birth_place">Village / Town</label>
                                <input type="text" class="form-control" id="birth_place" name="birth_place" value="<?=set_value('birth_place')?>">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group <?=form_error('birth_taluka') ? 'has-error' : ''?>">
                                <label for="birth_taluka">Taluka</label>
                                <input type="text" class="form-control" id="birth_taluka" name="birth_taluka" value="<?=set_value('birth_taluka')?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group <?=form_error('birth_district') ? 'has-error' : ''?>">
                                <label for="birth_district">District</label>
                                <input type="text" class="form-control" id="birth_district" name="birth_district" value="<?=set_value('birth_district')?>">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group <?=form_error('birth_state') ? 'has-error' : ''?>">
                                <label for="birth_state">State</label>
                                <input type="text" class="form-control" id="birth_state" name="birth_state" value="<?=set_value('birth_state')?>">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group <?=form_error('birth_country') ? 'has-error' : ''?>">
                                <label for="birth_country">Country</label>
                                <input type="text" class="form-control" id="birth_country" name="birth_country" value="<?=set_value('birth_country', 'India')?>">
                            </div>
                        </div>
                    </div>

                    <h5 style="margin-top: 15px; font-weight: bold; color: #777;"><i class="fa fa-envelope"></i> Contact Parameters</h5>
                    <div class="form-group <?=form_error('email') ? 'has-error' : ''?>">
                        <label for="email"><?=$this->lang->line("student_email")?></label>
                        <input type="text" class="form-control" id="email" name="email" value="<?=set_value('email')?>">
                        <span class="text-danger"><?=form_error('email')?></span>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group <?=form_error('phone') ? 'has-error' : ''?>">
                                <label for="phone"><?=$this->lang->line("student_phone")?></label>
                                <input type="text" class="form-control" id="phone" name="phone" value="<?=set_value('phone')?>">
                                <span class="text-danger"><?=form_error('phone')?></span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group <?=form_error('emergency_phone') ? 'has-error' : ''?>">
                                <label for="emergency_phone"><?=$this->lang->line("student_emergency_phone")?></label>
                                <input type="text" class="form-control" id="emergency_phone" name="emergency_phone" value="<?=set_value('emergency_phone')?>">
                                <span class="text-danger"><?=form_error('emergency_phone')?></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group <?=form_error('address') ? 'has-error' : ''?>">
                                <label for="address"><?=$this->lang->line("student_address")?></label>
                                <input type="text" class="form-control" id="address" name="address" value="<?=set_value('address')?>">
                                <span class="text-danger"><?=form_error('address')?></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group <?=form_error('state') ? 'has-error' : ''?>">
                                <label for="state"><?=$this->lang->line("student_state")?></label>
                                <input type="text" class="form-control" id="state" name="state" value="<?=set_value('state')?>">
                                <span class="text-danger"><?=form_error('state')?></span>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="country" id="country" value="<?=set_value('country', 'IN')?>">
                    <input type="hidden" id="password" name="password" value="ppgmis">

                </div> </div> <hr style="margin-top: 20px; margin-bottom: 20px; border-top: 1px solid #f4f4f4;">

            <div class="row">
                <div class="col-sm-12 text-right">
                    <input type="submit" class="btn btn-success btn-lg" style="padding: 10px 30px;" value="<?=$this->lang->line("add_student")?>">
                </div>
            </div>
        </form>

        <?php if (isset($siteinfos->note) && $siteinfos->note == 1) { ?>
            <div class="callout callout-danger" style="margin-top: 20px;">
                <p><b>Note:</b> Create teacher and class models before establishing dynamic roster data sets.</p>
            </div>
        <?php } ?>
    </div></div><script type="text/javascript">
$( ".select2" ).select2();
$('#dob').datepicker({ startView: 2 });
$('#admission_date').datepicker({ startView: 2 });

$(document).on('click', '#close-preview', function(){
    $('.image-preview').popover('hide');
    $('.image-preview').hover(
        function () { $('.image-preview').popover('show'); $('.content').css('padding-bottom', '100px'); },
        function () { $('.image-preview').popover('hide'); $('.content').css('padding-bottom', '20px'); }
    );
});

$(function() {
    var closebtn = $('<button/>', { type:"button", text: 'x', id: 'close-preview', style: 'font-size: initial;' }).attr("class","close pull-right");
    $('.image-preview').popover({ trigger:'manual', html:true, title: "<strong>Preview</strong>"+$(closebtn)[0].outerHTML, content: "There's no image", placement:'bottom' });
    
    $('.image-preview-clear').click(function(){
        $('.image-preview').attr("data-content","").popover('hide');
        $('.image-preview-filename').val("");
        $('.image-preview-clear').hide();
        $('.image-preview-input input:file').val("");
        $(".image-preview-input-title").text("<?=$this->lang->line('student_file_browse')?>");
    });
    
    $(".image-preview-input input:file").change(function (){
        var img = $('<img/>', { id: 'dynamic', width:250, height:200, overflow:'hidden' });
        var file = this.files[0];
        var reader = new FileReader();
        reader.onload = function (e) {
            $(".image-preview-input-title").text("<?=$this->lang->line('student_file_browse')?>");
            $(".image-preview-clear").show();
            $(".image-preview-filename").val(file.name);
            img.attr('src', e.target.result);
            $(".image-preview").attr("data-content",$(img)[0].outerHTML).popover("show");
            $('.content').css('padding-bottom', '100px');
        }
        reader.readAsDataURL(file);
    });
});
</script>