<style>
    .mark-input { min-width: 60px; max-width: 80px; padding: 4px; }
    .grade-select { min-width: 70px; padding: 4px; }
    
    /* 1. Add max-height to enable vertical scrolling */
    .table-container { 
        max-height: 65vh; /* Takes up 65% of the screen height */
        overflow: auto; /* Enables both X and Y scrolling */
        white-space: nowrap; 
    }
    
    /* 2. Freeze all Header Cells */
    thead th {
        position: -webkit-sticky;
        position: sticky;
        z-index: 10;
        background-color: #fff; /* Solid background prevents text bleeding underneath */
        /* Use inset box-shadow instead of standard borders for cleaner sticky rendering */
        box-shadow: inset 0 -1px 0 #ddd, inset -1px 0 0 #ddd; 
    }
    
    /* 3. First Row Header (Subjects) sticks to the very top */
    thead tr:nth-child(1) th {
        top: 0;
    }
    
    /* Maintain Bootstrap's bg-primary color on the sticky subject headers */
    thead tr:nth-child(1) th.bg-primary {
        background-color: #337ab7; /* Change this hex code if your theme's primary color is different */
        color: white;
    }

    /* 4. Second Row Header (PT1, PT2, etc.) sticks below the first row */
    thead tr:nth-child(2) th {
        /* IMPORTANT: If there is a gap or overlap, adjust this px value to match the exact height of your first row */
        top: 42px; 
    }

    /* 5. Freeze the First Column (Student Name) */
    .sticky-col {
        position: -webkit-sticky;
        position: sticky;
        left: 0;
        background-color: #f9f9f9;
        z-index: 11; /* Keep above scrolling data cells */
        box-shadow: 2px 0px 5px rgba(0,0,0,0.1);
    }
    
    /* 6. Top-Left Corner (Student Name Header) */
    /* Intersection of frozen header & frozen column needs the highest z-index */
    thead th.sticky-col { 
        z-index: 15; 
        background-color: #fff; 
        top: 0; 
    }
</style>

<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-flask"></i> Class 9 All Students Bulk Mark Input</h3>
    </div>
    <div class="box-body">
        <form method="POST" action="<?=base_url('mark/add_class9_marks')?>">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Class 9</label>
                        <?php
                            $array = array("0" => "Select Class");
                            foreach ($classes as $classa) { $array[$classa->classesID] = $classa->classes; }
                            echo form_dropdown("classesID", $array, set_value("classesID", $set_classes), "id='classesID' class='form-control select2' required");
                        ?>
                    </div>
                </div>
                <!-- <div class="col-md-6">
                    <div class="form-group">
                        <label>Section</label>
                        <?php
                            $array = array("0" => "Select Section");
                            foreach ($sections as $section) { $array[$section->sectionID] = $section->section; }
                            echo form_dropdown("sectionID", $array, set_value("sectionID", $set_section), "id='sectionID' class='form-control select2' required");
                        ?>
                    </div>
                </div> -->
            </div>
            <button type="submit" class="btn btn-success">Load Grid</button>
        </form>

        <?php if(customCompute($subjects) && customCompute($students)) { ?>
        <hr>
        <form method="POST" action="<?=base_url('mark/add_class9_marks')?>">
            <input type="hidden" name="classesID" value="<?=$set_classes?>">
            <!-- <input type="hidden" name="sectionID" value="<?=$set_section?>"> -->
            <input type="hidden" name="save_marks" value="1">
            
            <div class="table-container">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th rowspan="2" class="sticky-col" style="vertical-align: middle;">Student Name</th>
                            <?php foreach($subjects as $subject) { 
                                // Determine colspan based on subject type
                                $colspan = 1;
                                if ($subject->type == 1 && strpos(strtolower($subject->subject), 'information technology') === false) { $colspan = 7; }
                                elseif (strpos(strtolower($subject->subject), 'information technology') !== false) { $colspan = 2; }
                            ?>
                                <th colspan="<?=$colspan?>" class="text-center bg-primary" style="border-left: 2px solid #000;">
                                    <?=$subject->subject?>
                                </th>
                            <?php } ?>
                        </tr>
                        <tr>
                            <?php foreach($subjects as $subject) { 
                                if ($subject->type == 1 && strpos(strtolower($subject->subject), 'information technology') === false) { ?>
                                    <th class="text-center" style="border-left: 2px solid #000;">PT1(30)</th>
                                    <th class="text-center">PT2(80)</th>
                                    <th class="text-center">PT3(30)</th>
                                    <th class="text-center">NB(5)</th>
                                    <th class="text-center">PF(5)</th>
                                    <th class="text-center">SE(5)</th>
                                    <th class="text-center">Ann(80)</th>
                            <?php } elseif (strpos(strtolower($subject->subject), 'information technology') !== false) { ?>
                                    <th class="text-center" style="border-left: 2px solid #000;">Th(50)</th>
                                    <th class="text-center">Pr(50)</th>
                            <?php } else { ?>
                                    <th class="text-center" style="border-left: 2px solid #000;">Grade</th>
                            <?php } } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($students as $student) { ?>
                        <tr>
                            <td class="sticky-col font-weight-bold">
                                <?=(isset($student->srname) ? $student->srname : $student->name)?> <br>
                                <small class="text-muted">Roll: <?=$student->srroll?></small>
                            </td>
                            
                            <?php foreach($subjects as $subject) { 
                                // Get existing mark if any
                                $m = isset($existing_marks[$student->studentID][$subject->subjectID]) ? $existing_marks[$student->studentID][$subject->subjectID] : null;
                                
                                // Base Input Name Array: marks[studentID][subjectID][field]
                                $base_name = "marks[{$student->studentID}][{$subject->subjectID}]";

                                if ($subject->type == 1 && strpos(strtolower($subject->subject), 'information technology') === false) { ?>
        
                                    <td style="border-left: 2px solid #000;"><input type="text" data-max="30" name="<?=$base_name?>[pt1]" value="<?=$m ? $m->pt1 : ''?>" class="form-control mark-input absent-validate"></td>
                                    <td><input type="text" data-max="80" name="<?=$base_name?>[pt2]" value="<?=$m ? $m->pt2 : ''?>" class="form-control mark-input absent-validate"></td>
                                    <td><input type="text" data-max="30" name="<?=$base_name?>[pt3]" value="<?=$m ? $m->pt3 : ''?>" class="form-control mark-input absent-validate"></td>
                                    <td><input type="text" data-max="5" name="<?=$base_name?>[notebook]" value="<?=$m ? $m->notebook : ''?>" class="form-control mark-input absent-validate"></td>
                                    <td><input type="text" data-max="5" name="<?=$base_name?>[portfolio]" value="<?=$m ? $m->portfolio : ''?>" class="form-control mark-input absent-validate"></td>
                                    <td><input type="text" data-max="5" name="<?=$base_name?>[enrichment]" value="<?=$m ? $m->enrichment : ''?>" class="form-control mark-input absent-validate"></td>
                                    <td><input type="text" data-max="80" name="<?=$base_name?>[annual]" value="<?=$m ? $m->annual : ''?>" class="form-control mark-input absent-validate"></td>
                                
                                <?php } elseif (strpos(strtolower($subject->subject), 'information technology') !== false) { ?>
                                        
                                    <td style="border-left: 2px solid #000;"><input type="text" data-max="50" name="<?=$base_name?>[it_theory]" value="<?=$m ? $m->it_theory : ''?>" class="form-control mark-input absent-validate"></td>
                                    <td><input type="text" data-max="50" name="<?=$base_name?>[it_practical]" value="<?=$m ? $m->it_practical : ''?>" class="form-control mark-input absent-validate"></td>
                                
                                <?php } else {
                                    $g = $m ? $m->co_scholastic_grade : ''; ?>
                                    
                                    <td style="border-left: 2px solid #000;">
                                        <select name="<?=$base_name?>[co_scholastic_grade]" class="form-control grade-select">
                                            <option value="">--</option>
                                            <option value="A" <?=($g=='A')?'selected':''?>>A</option>
                                            <option value="B" <?=($g=='B')?'selected':''?>>B</option>
                                            <option value="C" <?=($g=='C')?'selected':''?>>C</option>
                                            <option value="D" <?=($g=='D')?'selected':''?>>D</option>
                                            <option value="E" <?=($g=='E')?'selected':''?>>E</option>
                                        </select>
                                    </td>

                            <?php } } ?>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <button type="submit" class="btn btn-primary btn-lg mt-3"><i class="fa fa-save"></i> Save All Marks & Grades</button>
        </form>
        <?php } elseif($_POST) { ?>
            <div class="alert alert-warning mt-3">No students found for the selected Class/Section, or subjects are not mapped.</div>
        <?php } ?>
    </div>
</div>

<script>
    // Existing Section Call Script
   /* $('#classesID').change(function() {
        var classesID = $(this).val();
        if(classesID == 0) {
            $('#sectionID').html('<option value="0">Select Section</option>');
        } else {
            $.ajax({ type: 'POST', url: "<?=base_url('mark/sectioncall')?>", data: "id=" + classesID, dataType: "html",
                success: function(data) { $('#sectionID').html(data); }
            });
        }
    });*/

    // NEW: Validation for Marks and "AB"
    $(document).on('blur', '.absent-validate', function() {
        var val = $(this).val().trim().toUpperCase();
        var max = parseFloat($(this).attr('data-max'));

        // If empty, do nothing
        if (val === '') {
            $(this).val('');
            return;
        }

        // If "AB", set to uppercase and accept
        if (val === 'AB') {
            $(this).val('AB');
            return;
        }
        if (val === 'NA') {
            $(this).val('NA');
            return;
        }

        // Otherwise, validate as a number
        var num = parseFloat(val);
        if (isNaN(num) || num < 0 || num > max) {
            alert('Invalid input! Please enter a number between 0 and ' + max + ', or type "AB" for absent.');
            $(this).val(''); // Clear the invalid input
            $(this).focus();
        } else {
            $(this).val(num); // Clean up formatting (e.g., " 05 " becomes "5")
        }
    });
</script>