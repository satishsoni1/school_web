<?php
$isEdit = !empty($cert);
// Posted value (failed validation) > saved certificate > pre-fill (student / defaults).
$val = function ($key) use ($cert, $prefill) {
    $posted = $this->input->post($key);
    if ($posted !== null) {
        return $posted;
    }
    if ($cert) {
        if (strpos($key, 'f_') === 0) {
            $field = substr($key, 2);
            return isset($cert->data[$field]) ? $cert->data[$field] : '';
        }
        return isset($cert->$key) ? $cert->$key : '';
    }
    $field = strpos($key, 'f_') === 0 ? substr($key, 2) : $key;
    return isset($prefill[$field]) ? $prefill[$field] : '';
};
$studentID = (int) $val('studentID');

// Class filter: classes that have students, in class order; preselect the chosen student's class.
$classes = array();
$studentClass = 0;
$studentList = array();
foreach ($students as $s) {
    $cid = (int) $s->classesID;
    if (!isset($classes[$cid])) {
        $classes[$cid] = $s->classes ?: 'No class';
    }
    if ((int) $s->studentID === $studentID) {
        $studentClass = $cid;
    }
    $studentList[] = array('id' => (int) $s->studentID, 'c' => $cid, 'n' => $s->name, 'r' => $s->roll);
}
?>
<style>
    .sc-panel { border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 16px; background: #fff; }
    .sc-panel > .sc-panel-head { padding: 10px 16px; border-bottom: 1px solid #e2e8f0; background: #f8fafc; border-radius: 8px 8px 0 0; font-weight: 600; color: #0f172a; }
    .sc-panel > .sc-panel-head .sc-step { display: inline-flex; width: 22px; height: 22px; margin-right: 8px; border-radius: 50%; background: #2f7d6d; color: #fff; font-size: 12px; align-items: center; justify-content: center; }
    .sc-panel > .sc-panel-body { padding: 16px 16px 2px; }
    .sc-panel .help-block { font-size: 12px; margin-bottom: 0; }
    .sc-ref-prefix { background: #f1f5f9; font-weight: 600; }
    .sc-actions { position: sticky; bottom: 0; z-index: 5; padding: 12px 0; background: #fff; border-top: 1px solid #e2e8f0; }
    .sc-side { border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; background: #f8fafc; }
    .sc-side h4 { margin: 0 0 6px; font-size: 15px; font-weight: 600; }
    .sc-side ul { padding-left: 18px; margin: 8px 0 0; color: #475569; font-size: 13px; line-height: 1.6; }
    .select2-container { width: 100% !important; }
</style>

<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa <?=isset($def['icon']) ? $def['icon'] : 'fa-certificate'?>"></i> <?=$isEdit ? 'Edit' : 'Generate'?> — <?=html_escape($def['label'])?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url('studentcertificate/index')?>">Certificates</a></li>
            <li class="active"><?=$isEdit ? 'Edit' : 'Generate'?></li>
        </ol>
    </div>

    <div class="box-body">
        <?php if ($errors) { ?>
            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?=implode('<br>', array_map('html_escape', $errors))?></div>
        <?php } ?>

        <div class="row">
            <form method="post" id="cert-form">
            <div class="col-md-8">

                <div class="sc-panel">
                    <div class="sc-panel-head"><span class="sc-step">1</span>Student</div>
                    <div class="sc-panel-body">
                        <div class="row">
                            <div class="col-sm-5 form-group">
                                <label>Class</label>
                                <select id="cert-class" class="form-control">
                                    <option value="">All classes</option>
                                    <?php foreach ($classes as $cid => $cname) { ?>
                                        <option value="<?=$cid?>" <?=$studentClass === $cid ? 'selected' : ''?>><?=html_escape($cname)?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-7 form-group">
                                <label>Student</label>
                                <select class="form-control" name="studentID" id="cert-student"></select>
                                <p class="help-block">Type to search. Choosing a student fills the details below.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="sc-panel">
                    <div class="sc-panel-head"><span class="sc-step">2</span>Reference</div>
                    <div class="sc-panel-body">
                        <div class="row">
                            <div class="col-sm-4 form-group">
                                <label>Ref. No. <span class="text-red">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-addon sc-ref-prefix">PPGMIS/</span>
                                    <input type="number" min="1" class="form-control" name="ref_no" value="<?=html_escape($val('ref_no'))?>" required>
                                </div>
                            </div>
                            <div class="col-sm-4 form-group">
                                <label>Year <span class="text-red">*</span></label>
                                <input type="text" class="form-control" name="ref_year" value="<?=html_escape($val('ref_year'))?>" placeholder="2026-2027" required>
                            </div>
                            <div class="col-sm-4 form-group">
                                <label>Date <span class="text-red">*</span></label>
                                <input type="date" class="form-control" name="cert_date" value="<?=html_escape($val('cert_date'))?>" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="sc-panel">
                    <div class="sc-panel-head"><span class="sc-step">3</span>Certificate details</div>
                    <div class="sc-panel-body">
                        <div class="row">
                        <?php foreach ($def['fields'] as $key => $field) {
                            $name  = 'f_' . $key;
                            $input = isset($field['input']) ? $field['input'] : 'text';
                            $req   = !empty($field['required']);
                            $from  = isset($field['from']) ? $field['from'] : '';
                            $col   = in_array($input, array('select', 'date')) ? 'col-sm-4' : ($input === 'textarea' || $key === 'student_name' || $key === 'to_school' || $key === 'birth_place' ? 'col-sm-12' : 'col-sm-6');
                        ?>
                            <div class="<?=$col?> form-group">
                                <label><?=html_escape($field['label'])?><?=$req ? ' <span class="text-red">*</span>' : ''?></label>
                                <?php if ($input === 'select') { ?>
                                    <select class="form-control" name="<?=$name?>" data-from="<?=$from?>" <?=$req ? 'required' : ''?>>
                                        <?php foreach ($field['options'] as $optValue => $optLabel) { ?>
                                            <option value="<?=html_escape($optValue)?>" <?=(string) $val($name) === (string) $optValue ? 'selected' : ''?>><?=html_escape($optLabel)?></option>
                                        <?php } ?>
                                    </select>
                                <?php } elseif ($input === 'textarea') { ?>
                                    <textarea class="form-control" rows="2" name="<?=$name?>" placeholder="<?=isset($field['placeholder']) ? html_escape($field['placeholder']) : ''?>" <?=$req ? 'required' : ''?>><?=html_escape($val($name))?></textarea>
                                <?php } else { ?>
                                    <input type="<?=$input === 'date' ? 'date' : 'text'?>" class="form-control" name="<?=$name?>" value="<?=html_escape($val($name))?>" data-from="<?=$from?>" placeholder="<?=isset($field['placeholder']) ? html_escape($field['placeholder']) : ''?>" <?=$req ? 'required' : ''?>>
                                <?php } ?>
                                <?php if (isset($field['help'])) { ?><p class="help-block"><?=html_escape($field['help'])?></p><?php } ?>
                            </div>
                        <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="sc-actions">
                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> <?=$isEdit ? 'Update' : 'Generate &amp; Save'?></button>
                    <?php if ($isEdit) { ?>
                        <a href="<?=base_url('studentcertificate/view/' . $cert->id)?>" target="_blank" class="btn btn-primary"><i class="fa fa-print"></i> Print</a>
                    <?php } ?>
                    <a href="<?=base_url('studentcertificate/index')?>" class="btn btn-default">Cancel</a>
                </div>
            </div>
            </form>

            <div class="col-md-4">
                <div class="sc-side">
                    <h4><i class="fa <?=isset($def['icon']) ? $def['icon'] : 'fa-certificate'?>"></i> <?=html_escape($def['label'])?></h4>
                    <div class="text-muted"><?=isset($def['desc']) ? html_escape($def['desc']) : ''?></div>
                    <ul>
                        <li>Pick a class, then search the student by name.</li>
                        <li>Name, class and other saved details fill in automatically. Type anything that is missing.</li>
                        <li>Ref. No. is the next free number for the year. Change it if needed.</li>
                        <li>After saving, use <b>Print</b>. It can print with or without the letterhead.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(function () {
    var students = <?=json_encode($studentList, JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE)?>;
    var selectedID = <?=$studentID?>;
    var $class = $('#cert-class'), $student = $('#cert-student');

    function fillStudents(classID) {
        if ($student.data('select2')) { $student.select2('destroy'); }
        $student.empty().append($('<option>', {value: '', text: classID ? '— Select student —' : '— Select student (or type details below) —'}));
        $.each(students, function (i, s) {
            if (!classID || s.c == classID) {
                $student.append($('<option>', {value: s.id, text: s.n + (s.r ? '  (Roll ' + s.r + ')' : ''), selected: s.id === selectedID}));
            }
        });
        $student.select2();
    }

    $class.select2();
    fillStudents($class.val());

    $class.on('change', function () {
        selectedID = 0;
        fillStudents($(this).val());
    });

    // Picking a student overwrites the fields that come from the student record.
    $student.on('change', function () {
        var id = $(this).val();
        selectedID = parseInt(id, 10) || 0;
        if (!id) { return; }
        $.getJSON('<?=base_url('studentcertificate/student_info')?>', {studentID: id}, function (info) {
            $('#cert-form [data-from]').each(function () {
                var from = $(this).data('from');
                if (from && info[from] !== undefined && info[from] !== null && info[from] !== '') {
                    $(this).val(info[from]);
                }
            });
        });
    });
});
</script>
