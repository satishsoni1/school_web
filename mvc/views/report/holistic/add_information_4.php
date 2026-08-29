<?php
// ─────────────────────────────────────────────────────────────────────────────
// Decode all JSON columns into PHP arrays (true = associative array, not object)
// Without the second argument 'true', json_decode returns stdClass and all
// form pre-population breaks silently.
// ─────────────────────────────────────────────────────────────────────────────
$health  = (isset($existing_data->health_data)      && $existing_data->health_data      != '') ? json_decode($existing_data->health_data,      true) : array();
$self    = (isset($existing_data->self_assessment)   && $existing_data->self_assessment   != '') ? json_decode($existing_data->self_assessment,   true) : array();
$peer    = (isset($existing_data->peer_assessment)   && $existing_data->peer_assessment   != '') ? json_decode($existing_data->peer_assessment,   true) : array();
$parent  = (isset($existing_data->parent_feedback)   && $existing_data->parent_feedback   != '') ? json_decode($existing_data->parent_feedback,   true) : array();
$comps   = (isset($existing_data->competencies)      && $existing_data->competencies      != '') ? json_decode($existing_data->competencies,      true) : array();
$summary = (isset($existing_data->annual_summary)    && $existing_data->annual_summary    != '') ? json_decode($existing_data->annual_summary,    true) : array();
$feel    = (isset($existing_data->feel_at_school)    && $existing_data->feel_at_school    != '') ? json_decode($existing_data->feel_at_school,    true) : array();

$selected_interests = (isset($existing_data->interests) && $existing_data->interests != '') ? json_decode($existing_data->interests, true) : array();
if (!is_array($selected_interests)) $selected_interests = array();
if (!is_array($health))  $health  = array();
if (!is_array($self))    $self    = array();
if (!is_array($peer))    $peer    = array();
if (!is_array($parent))  $parent  = array();
if (!is_array($comps))   $comps   = array();
if (!is_array($summary)) $summary = array();
if (!is_array($feel))    $feel    = array();

// ─────────────────────────────────────────────────────────────────────────────
// Helper: safely read a value from a decoded array (1 or 2 levels deep)
// ─────────────────────────────────────────────────────────────────────────────
function safe_val($array, $key1, $key2 = null) {
    if (!is_array($array)) return '';
    if ($key2 !== null) {
        return (isset($array[$key1]) && is_array($array[$key1]) && isset($array[$key1][$key2]))
            ? $array[$key1][$key2] : '';
    }
    return isset($array[$key1]) ? $array[$key1] : '';
}

// ─────────────────────────────────────────────────────────────────────────────
// Helper: mark a <select> option as selected when it matches the saved value
// ─────────────────────────────────────────────────────────────────────────────
function select_match($val1, $val2) {
    return ((string)$val1 === (string)$val2 && $val2 !== '') ? 'selected' : '';
}
?>

<div class="box">
    <div class="box-header">
        <h3 class="box-title">
            <i class="fa fa-pencil"></i> Holistic Data: <?= htmlspecialchars($student->name, ENT_QUOTES, 'UTF-8'); ?>
        </h3>
        <div class="box-tools pull-right">
            <a href="<?= base_url('holisticreport/index') ?>" class="btn btn-default btn-sm">Back to List</a>
            <?php if (isset($existing_data)): ?>
                <a href="<?= base_url('holisticreport/generate_report_1/' . $student->srstudentID . '/' . $classesID) ?>"
                   target="_blank" class="btn btn-success btn-sm">
                    <i class="fa fa-print"></i> Print Report Card
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="box-body">

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">

            <!-- ══════════════════════════════════════════════════════════════
                 SECTION 1 – GENERAL INFORMATION & HEALTH
            ══════════════════════════════════════════════════════════════════ -->
            <fieldset style="border:1px solid #ddd; padding:15px; margin-bottom:20px;">
                <legend style="width:auto; border:none; font-weight:bold; font-size:18px;">
                    1. General Information &amp; Health
                </legend>

                <div class="row">
                    <div class="col-sm-3 form-group">
                        <label>Ambition</label>
                        <input type="text" class="form-control" name="ambition"
                               value="<?= isset($existing_data->ambition) ? htmlspecialchars($existing_data->ambition, ENT_QUOTES, 'UTF-8') : ''; ?>"
                               placeholder="e.g. Doctor">
                    </div>
                    <div class="col-sm-3 form-group">
                        <label>My best friend is</label>
                        <input type="text" class="form-control" name="best_friend"
                               value="<?= isset($existing_data->best_friend) ? htmlspecialchars($existing_data->best_friend, ENT_QUOTES, 'UTF-8') : ''; ?>">
                    </div>
                    <div class="col-sm-3 form-group">
                        <label>Fav Colour</label>
                        <input type="text" class="form-control" name="fav_colour"
                               value="<?= isset($existing_data->fav_colour) ? htmlspecialchars($existing_data->fav_colour, ENT_QUOTES, 'UTF-8') : ''; ?>">
                    </div>
                    <div class="col-sm-3 form-group">
                        <label>Fav Food</label>
                        <input type="text" class="form-control" name="fav_food"
                               value="<?= isset($existing_data->fav_food) ? htmlspecialchars($existing_data->fav_food, ENT_QUOTES, 'UTF-8') : ''; ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 form-group">
                        <label>My Interests (Select Multiple)</label>
                        <?php $interest_options = ['Reading', 'Drawing', 'Music', 'Dance', 'Rhymes', 'Sports', 'Clay Work', 'Nature']; ?>
                        <select name="interests[]" class="form-control select2" multiple="multiple"
                                data-placeholder="Select Interests">
                            <?php foreach ($interest_options as $opt): ?>
                                <option value="<?= $opt ?>"
                                    <?= in_array($opt, $selected_interests) ? 'selected' : '' ?>>
                                    <?= $opt ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <hr>
                <div class="row">
                    <!-- <div class="col-sm-3 form-group">
                        <label>Term 1 Height (Ft/Cm)</label>
                        <input type="text" class="form-control" name="t1_height_ft"
                               value="<?= htmlspecialchars(safe_val($health, 't1', 'ft'), ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div class="col-sm-3 form-group">
                        <label>Term 1 Weight (Kg)</label>
                        <input type="text" class="form-control" name="t1_weight"
                               value="<?= htmlspecialchars(safe_val($health, 't1', 'weight'), ENT_QUOTES, 'UTF-8'); ?>">
                    </div> -->
                    <div class="col-sm-3 form-group">
                        <label>Term 2 Height (Ft/Cm)</label>
                        <input type="text" class="form-control" name="t2_height_ft"
                               value="<?= htmlspecialchars(safe_val($health, 't2', 'ft'), ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div class="col-sm-3 form-group">
                        <label>Term 2 Weight (Kg)</label>
                        <input type="text" class="form-control" name="t2_weight"
                               value="<?= htmlspecialchars(safe_val($health, 't2', 'weight'), ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                </div>
            </fieldset>

            <!-- ══════════════════════════════════════════════════════════════
                 SECTION 2 – HOW DO I FEEL AT SCHOOL
            ══════════════════════════════════════════════════════════════════ -->
            <fieldset style="border:1px solid #ddd; padding:15px; margin-bottom:20px;">
                <legend style="width:auto; border:none; font-weight:bold; font-size:18px;">
                    How Do I Feel At School?
                </legend>
                <div class="row">
                    <?php
                    $feel_qs = [
                        '1. I can talk about how I feel',
                        '2. I can calm myself down',
                        '3. I can understand how my friends feel',
                        '4. I respect everyone\'s opinions',
                        '5. I can help my friends after a fight',
                        '6. When someone is sad, I can make them feel better',
                        '7. I think I do well at school'
                    ];
                    foreach ($feel_qs as $i => $q):
                        // $feel is a flat indexed array: [0=>'Yes', 1=>'Sometimes', ...]
                        $saved_feel = safe_val($feel, $i);
                    ?>
                    <div class="col-sm-6 form-group">
                        <label><?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?></label>
                        <select name="feel_<?= $i ?>" class="form-control">
                            <option value="">Select Option</option>
                            <option value="Yes"      <?= select_match('Yes',      $saved_feel); ?>>Yes</option>
                            <option value="Sometimes"<?= select_match('Sometimes',$saved_feel); ?>>Sometimes</option>
                            <option value="No"       <?= select_match('No',       $saved_feel); ?>>No</option>
                            <option value="Not sure" <?= select_match('Not sure', $saved_feel); ?>>Not sure</option>
                        </select>
                    </div>
                    <?php endforeach; ?>
                </div>
            </fieldset>

            <!-- ══════════════════════════════════════════════════════════════
                 SECTION 3 – COMPETENCIES (TERM 1 & TERM 2)
            ══════════════════════════════════════════════════════════════════ -->
            <fieldset style="border:1px solid #ddd; padding:15px; margin-bottom:20px;">
                <legend style="width:auto; border:none; font-weight:bold; font-size:18px;">
                    2. Competencies (Term 1 &amp; Term 2)
                </legend>
                <p><em>Select the achievement level for each term.</em></p>

                <?php
             $comp_list = [

[1,'C-1.1 Shows a liking for and understanding of nutritious food and does not waste food'],
[2,'C-1.2 Practices basic self-care and hygiene'],
[3,'C-1.6 Understands unsafe situations and asks for help'],

[4,'C-2.1 Differentiates between shapes, colours and their shades'],
[5,'C-2.4 Develops discrimination in the sense of smell and taste'],
[6,'C-2.5 Begins integrating sensorial perceptions to get a holistic awareness of experiences'],

[7,'C-3.2 Shows balance, coordination and flexibility in various physical activities'],
[8,'C-3.3 Shows precision and control in working with their hands and fingers'],
[9,'C-3.4 Shows strength and endurance in carrying, walking and running'],

[10,'C-4.1 Recognizing self as an individual belonging to family and community'],
[11,'C-4.2 Recognises different emotions and makes deliberate effort to regulate them appropriately'],
[12,'C-4.3 Interacts comfortably with other children and adults'],
[13,'C-4.6 Shows kindness and helpfulness to others (including animals, plants) when they are in need'],

[14,'C-5.1 Demonstrates willingness and participation in age-appropriate physical work towards helping others'],

[15,'C-6.1 Shows care for and joy in engaging with all life forms'],

[16,'C-7.1 Observes and understands different objects and relationships between them'],
[17,'C-7.2 Observes and understands cause and effect relationships in nature by forming simple hypotheses'],

[18,'C-8.1 Sorts objects into groups and sub-groups based on more than one property'],
[19,'C-8.2 Identifies and extends patterns in their surroundings, shapes and numbers'],
[20,'C-8.5 Counts up to 99 both forwards and backwards and in groups of 10s and 20s'],
[21,'C-8.8 Performs addition and subtraction of 2-digit numbers using flexible strategies'],
[22,'C-8.13 Recognises, makes and classifies basic geometric shapes and their properties'],
[23,'C-8.14 Formulates and solves simple mathematical problems related to quantities and measurements'],

[24,'C-9.1 Listens to and appreciates simple songs, rhymes and poems'],
[25,'C-9.3 Converses fluently and can hold a meaningful conversation'],
[26,'C-9.4 Understands oral instructions for a complex task and gives clear oral instructions to others'],
[27,'C-9.5 Comprehends narrated/read-out stories and identifies characters and storyline'],

[28,'C-10.1 Recognises all letters of alphabet of the script and uses this knowledge to read and write words'],
[29,'C-10.2 Reads stories and passages with accuracy and fluency with appropriate pauses and voice modulation'],
[30,'C-10.3 Reads short stories and comprehends their meaning by identifying characters and storyline'],
[31,'C-10.4 Reads short poems and begins to appreciate the poem for its choice of words and imagination'],
[32,'C-10.5 Reads and comprehends short news items, instructions, recipes and publicity material'],

[33,'C-11.1 Recognises frequently occurring letters of the alphabet of the script and reads simple words and sentences'],

[34,'C-12.1 Explores and plays with a variety of materials and tools to create two-dimensional and three-dimensional artworks'],
[35,'C-12.2 Explores and plays with own voice, body, spaces and objects to create music, role-play, dance and movement'],
[36,'C-12.3 Innovates and works imaginatively to express ideas and emotions through the arts'],

[37,'C-13.1 Attention and intentional action – acquires skills to plan, focus attention and direct activities to achieve goals'],
[38,'C-13.3 Observation, curiosity and exploration – observes details, explores using senses and asks questions']

];
                ?>

                <table class="table table-bordered table-condensed">
                    <tr style="background:#f4f4f4;">
                        <th>Competency</th>
                        <th width="200">Term 1</th>
                        <th width="200">Term 2</th>
                    </tr>
                    <?php foreach ($comp_list as $c):
                    
                        // Key = first token e.g. "C-1.1", "C-8.13"
                        $key   = explode(' ', $c[1])[0];
                        $key_second   = $c[0];
                        $t1val = safe_val($comps, $key_second, 't1');
                        $t2val = safe_val($comps, $key_second, 't2');
                    ?>
                    <tr>
                        <td style="vertical-align:middle;"><?= htmlspecialchars($c[1], ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <select class="form-control input-sm" name="competencies[<?= $key_second ?>][t1]">
                                <option value="">Select Level</option>
                                <option value="Beginner"   <?= select_match('Beginner',   $t1val) ?>>Beginner</option>
                                <option value="Progressing"<?= select_match('Progressing',$t1val) ?>>Progressing</option>
                                <option value="Proficient" <?= select_match('Proficient', $t1val) ?>>Proficient</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-control input-sm" name="competencies[<?= $key_second ?>][t2]">
                                <option value="">Select Level</option>
                                <option value="Beginner"   <?= select_match('Beginner',   $t2val) ?>>Beginner</option>
                                <option value="Progressing"<?= select_match('Progressing',$t2val) ?>>Progressing</option>
                                <option value="Proficient" <?= select_match('Proficient', $t2val) ?>>Proficient</option>
                            </select>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </fieldset>

            <!-- ══════════════════════════════════════════════════════════════
                 SECTION 4 – ASSESSMENTS & OBSERVATIONS
            ══════════════════════════════════════════════════════════════════ -->
            <fieldset style="border:1px solid #ddd; padding:15px; margin-bottom:20px;">
                <legend style="width:auto; border:none; font-weight:bold; font-size:18px;">
                    3. Assessments &amp; Observations
                </legend>

                <!-- Self Assessment -->
                <h4 style="color:#2f2d92;">Self Assessment</h4>
                <div class="row">
                    <?php
                    $q_self = ['Enjoyed all activities', 'Complete work independently', 'Followed instructions'];
                    foreach ($q_self as $i => $q):
                        $saved = safe_val($self, 't1', $i);
                    ?>
                    <div class="col-sm-4 form-group">
                        <label><?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?></label>
                        <select name="self_t1_<?= $i ?>" class="form-control">
                            <option value="">Select Option</option>
                            <option value="Yes"       <?= select_match('Yes',      $saved); ?>>Yes</option>
                            <option value="Sometimes" <?= select_match('Sometimes',$saved); ?>>Sometimes</option>
                            <option value="No"        <?= select_match('No',       $saved); ?>>No</option>
                            <option value="Not sure"  <?= select_match('Not sure', $saved); ?>>Not sure</option>
                        </select>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Peer Assessment -->
                <h4 style="color:#2f2d92; margin-top:15px;">Peer Assessment</h4>
                <div class="row">
                    <?php
                    $q_peer = ['Collaborates with friends', 'Shares learning materials', 'Supports team activities'];
                    foreach ($q_peer as $i => $q):
                        $saved = safe_val($peer, 't1', $i);
                    ?>
                    <div class="col-sm-4 form-group">
                        <label><?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?></label>
                        <select name="peer_t1_<?= $i ?>" class="form-control">
                            <option value="">Select Option</option>
                            <option value="Yes"       <?= select_match('Yes',      $saved); ?>>Yes</option>
                            <option value="Sometimes" <?= select_match('Sometimes',$saved); ?>>Sometimes</option>
                            <option value="No"        <?= select_match('No',       $saved); ?>>No</option>
                            <option value="Not sure"  <?= select_match('Not sure', $saved); ?>>Not sure</option>
                        </select>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="row">
                    <div class="col-sm-6 form-group">
                        <label>Self assessment Notes</label>
                        <textarea class="form-control" name="selfNotes" rows="3"><?= isset($existing_data->selfNotes) ? htmlspecialchars($existing_data->selfNotes, ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
                    </div>
                    <div class="col-sm-6 form-group">
                        <label>Peer Assesment Notes</label>
                        <textarea class="form-control" name="peerNotes" rows="3"><?= isset($existing_data->peerNotes) ? htmlspecialchars($existing_data->peerNotes, ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
                    </div>
                </div>

                <!-- Parent & Teacher Feedback -->
                <h4 style="color:#2f2d92; margin-top:15px;">Parent &amp; Teacher Feedback</h4>
                <div class="row">
                    <div class="col-sm-6 form-group">
                        <label>Child enjoys:</label>
                        <input type="text" class="form-control" name="parent_t1_0"
                               value="<?= htmlspecialchars(safe_val($parent, 't1', 0), ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div class="col-sm-6 form-group">
                        <label>Child needs support in:</label>
                        <input type="text" class="form-control" name="parent_t1_1"
                               value="<?= htmlspecialchars(safe_val($parent, 't1', 1), ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div class="col-sm-6 form-group">
                        <label>Home resources used:</label>
                        <input type="text" class="form-control" name="parent_t1_2"
                               value="<?= htmlspecialchars(safe_val($parent, 't1', 2), ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div class="col-sm-6 form-group">
                        <label>Parent comments:</label>
                        <input type="text" class="form-control" name="parent_t1_3"
                               value="<?= htmlspecialchars(safe_val($parent, 't1', 3), ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 form-group">
                        <label>Teacher Notes</label>
                        <textarea class="form-control" name="teacher_remarks" rows="3"><?= isset($existing_data->teacher_remarks) ? htmlspecialchars($existing_data->teacher_remarks, ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
                    </div>
                    <!-- <div class="col-sm-6 form-group">
                        <label>Activity Evidence</label>
                        <textarea class="form-control" name="teacher_evidence" rows="3"><?= isset($existing_data->teacher_evidence) ? htmlspecialchars($existing_data->teacher_evidence, ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
                    </div> -->
                </div>
            </fieldset>

            <!-- ══════════════════════════════════════════════════════════════
                 SECTION 5 – DOMAIN-WISE ANNUAL SUMMARY
            ══════════════════════════════════════════════════════════════════ -->
            <fieldset style="border:1px solid #ddd; padding:15px; margin-bottom:20px;">
                <legend style="width:auto; border:none; font-weight:bold; font-size:18px;">
                    4. Domain-wise Annual Summary
                </legend>
                <div class="row">
                    <?php
                    $domains = [
                        'physical'  => 'Physical Development',
                        'socio'     => 'Socio-Emotional Development',
                        'cognitive' => 'Cognitive Development',
                        'language'  => 'Language & Literacy',
                        'aesthetic' => 'Aesthetic & Cultural',
                        'habits'    => 'Positive Learning Habits',
                    ];
                    foreach ($domains as $dkey => $label):
                        $saved_level = safe_val($summary, $dkey);
                    ?>
                    <div class="col-sm-4 form-group">
                        <label><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></label>
                        <select name="annual_summary[<?= $dkey ?>]" class="form-control">
                            <option value="">Select Level</option>
                            <option value="Beginner"  <?= select_match('Beginner',  $saved_level); ?>>Beginner</option>
                            <option value="Proficient"<?= select_match('Proficient',$saved_level); ?>>Proficient</option>
                            <option value="Advanced"  <?= select_match('Advanced',  $saved_level); ?>>Advanced</option>
                        </select>
                    </div>
                    <?php endforeach; ?>
                </div>
            </fieldset>

            <!-- ══════════════════════════════════════════════════════════════
                 SECTION 6 – IMAGE UPLOADS
            ══════════════════════════════════════════════════════════════════ -->
            <fieldset style="border:1px solid #ddd; padding:15px; margin-bottom:20px;">
                <legend style="width:auto; border:none; font-weight:bold; font-size:18px;">
                    5. Image Uploads
                </legend>
                <div class="row">

                    <div class="col-sm-6 form-group">
                        <label>Portfolio Snapshot <small class="text-muted">(Displays on Page 2)</small></label>
                        <input type="file" class="form-control" name="portfolio_snapshot" accept="image/*">
                        <?php if (!empty($existing_data->portfolio_snapshot)): ?>
                            <small class="text-success" style="display:block; margin-top:5px;">
                                <i class="fa fa-check"></i> Uploaded: <?= htmlspecialchars($existing_data->portfolio_snapshot, ENT_QUOTES, 'UTF-8') ?>
                                &nbsp;<a href="<?= base_url('uploads/holistic_photos/' . $existing_data->portfolio_snapshot) ?>" target="_blank">Preview</a>
                            </small>
                        <?php endif; ?>
                    </div>

                    <div class="col-sm-6 form-group">
                        <label>Class Group Photo <small class="text-muted">(For Gallery)</small></label>
                        <input type="file" class="form-control" name="class_group_photo" accept="image/*">
                        <?php if (!empty($existing_data->class_group_photo)): ?>
                            <small class="text-success" style="display:block; margin-top:5px;">
                                <i class="fa fa-check"></i> Uploaded: <?= htmlspecialchars($existing_data->class_group_photo, ENT_QUOTES, 'UTF-8') ?>
                                &nbsp;<a href="<?= base_url('uploads/holistic_photos/' . $existing_data->class_group_photo) ?>" target="_blank">Preview</a>
                            </small>
                        <?php endif; ?>
                    </div>

                    <div class="col-sm-6 form-group">
                        <label>Activity Highlight 1</label>
                        <input type="file" class="form-control" name="activity_highlight_1" accept="image/*">
                        <?php if (!empty($existing_data->activity_highlight_1)): ?>
                            <small class="text-success" style="display:block; margin-top:5px;">
                                <i class="fa fa-check"></i> Uploaded: <?= htmlspecialchars($existing_data->activity_highlight_1, ENT_QUOTES, 'UTF-8') ?>
                                &nbsp;<a href="<?= base_url('uploads/holistic_photos/' . $existing_data->activity_highlight_1) ?>" target="_blank">Preview</a>
                            </small>
                        <?php endif; ?>
                    </div>

                    <div class="col-sm-6 form-group">
                        <label>Activity Highlight 2</label>
                        <input type="file" class="form-control" name="activity_highlight_2" accept="image/*">
                        <?php if (!empty($existing_data->activity_highlight_2)): ?>
                            <small class="text-success" style="display:block; margin-top:5px;">
                                <i class="fa fa-check"></i> Uploaded: <?= htmlspecialchars($existing_data->activity_highlight_2, ENT_QUOTES, 'UTF-8') ?>
                                &nbsp;<a href="<?= base_url('uploads/holistic_photos/' . $existing_data->activity_highlight_2) ?>" target="_blank">Preview</a>
                            </small>
                        <?php endif; ?>
                    </div>

                </div>
            </fieldset>

            <button type="submit" class="btn btn-primary btn-lg btn-block">
                <i class="fa fa-save"></i> Save Report Card Data
            </button>

        </form>
    </div><!-- /.box-body -->
</div><!-- /.box -->

<script type="text/javascript">
$(document).ready(function () {
    if ($.fn.select2) {
        $('.select2').select2({
            placeholder: "Select Interests",
            allowClear: true
        });
    }
});
</script>