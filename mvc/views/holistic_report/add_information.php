<?php
$existing = isset($existing_data) ? $existing_data : null;

$decode = function($v) {
    if (!$v) return array();
    $arr = json_decode($v, true);
    return is_array($arr) ? $arr : array();
};

$interests = $decode(isset($existing->interests) ? $existing->interests : '');
$health = $decode(isset($existing->health_data) ? $existing->health_data : '');
$self = $decode(isset($existing->self_assessment) ? $existing->self_assessment : '');
$peer = $decode(isset($existing->peer_assessment) ? $existing->peer_assessment : '');
$rubric = $decode(isset($existing->rubrics) ? $existing->rubrics : '');
$parent = $decode(isset($existing->parent_feedback) ? $existing->parent_feedback : '');
$activities = $decode(isset($existing->activities) ? $existing->activities : '');

$get = function($obj, $key, $default = '') {
    return isset($obj->$key) ? $obj->$key : $default;
};

$in = function($arr, $v) {
    return is_array($arr) && in_array($v, $arr) ? 'checked' : '';
};
?>

<div class="row">
    <div class="col-sm-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Add Holistic Information - <?= $student->name; ?></h3>
            </div>

            <form method="post" enctype="multipart/form-data" class="box-body">
                <h4>Basic & Preferences</h4>
                <div class="row">
                    <div class="col-sm-4"><label>Mother Tongue</label><input type="text" name="mother_tongue" class="form-control" value="<?= $get($existing, 'mother_tongue'); ?>"></div>
                    <div class="col-sm-4"><label>Ambition</label><input type="text" name="ambition" class="form-control" value="<?= $get($existing, 'ambition'); ?>"></div>
                    <div class="col-sm-4"><label>Other Interest</label><input type="text" name="other_interest" class="form-control" value="<?= $get($existing, 'other_interest'); ?>"></div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-3"><label>Fav Colour</label><input type="text" name="fav_colour" class="form-control" value="<?= $get($existing, 'fav_colour'); ?>"></div>
                    <div class="col-sm-3"><label>Fav Food</label><input type="text" name="fav_food" class="form-control" value="<?= $get($existing, 'fav_food'); ?>"></div>
                    <div class="col-sm-3"><label>Fav Game</label><input type="text" name="fav_game" class="form-control" value="<?= $get($existing, 'fav_game'); ?>"></div>
                    <div class="col-sm-3"><label>Fav Animal</label><input type="text" name="fav_animal" class="form-control" value="<?= $get($existing, 'fav_animal'); ?>"></div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-3"><label>Fav Flower</label><input type="text" name="fav_flower" class="form-control" value="<?= $get($existing, 'fav_flower'); ?>"></div>
                    <div class="col-sm-3"><label>Fav Festival</label><input type="text" name="fav_festival" class="form-control" value="<?= $get($existing, 'fav_festival'); ?>"></div>
                </div>

                <hr>
                <h4>Interests</h4>
                <?php $opts = array('Reading','Dancing / Singing','Sports / Games','Creative Writing','Gardening','Yoga','Art','Craft','Cooking','Playing an Instrument','Regular Home Chores'); ?>
                <div class="row">
                    <?php foreach($opts as $i => $opt) { ?>
                        <div class="col-sm-3"><label><input type="checkbox" name="interests[]" value="<?= $opt; ?>" <?= $in($interests, $opt); ?>> <?= $opt; ?></label></div>
                    <?php } ?>
                </div>

                <hr>
                <h4>Health Data</h4>
                <div class="row">
                    <div class="col-sm-2"><label>T1 Height Span</label><input type="text" name="t1_height_span" class="form-control" value="<?= isset($health['t1']['span']) ? $health['t1']['span'] : ''; ?>"></div>
                    <div class="col-sm-2"><label>T1 Height Ft/Cm</label><input type="text" name="t1_height_ft" class="form-control" value="<?= isset($health['t1']['ft']) ? $health['t1']['ft'] : ''; ?>"></div>
                    <div class="col-sm-2"><label>T1 Weight</label><input type="text" name="t1_weight" class="form-control" value="<?= isset($health['t1']['weight']) ? $health['t1']['weight'] : ''; ?>"></div>
                    <div class="col-sm-2"><label>T2 Height Span</label><input type="text" name="t2_height_span" class="form-control" value="<?= isset($health['t2']['span']) ? $health['t2']['span'] : ''; ?>"></div>
                    <div class="col-sm-2"><label>T2 Height Ft/Cm</label><input type="text" name="t2_height_ft" class="form-control" value="<?= isset($health['t2']['ft']) ? $health['t2']['ft'] : ''; ?>"></div>
                    <div class="col-sm-2"><label>T2 Weight</label><input type="text" name="t2_weight" class="form-control" value="<?= isset($health['t2']['weight']) ? $health['t2']['weight'] : ''; ?>"></div>
                </div>

                <hr>
                <h4>Self Assessment (Term 1 / Term 2)</h4>
                <div class="row">
                    <?php for($i=0; $i<3; $i++) { ?>
                    <div class="col-sm-6">
                        <label>Self T1 <?= $i+1; ?></label>
                        <input type="text" name="self_t1_<?= $i; ?>" class="form-control" value="<?= isset($self['t1'][$i]) ? $self['t1'][$i] : ''; ?>">
                    </div>
                    <div class="col-sm-6">
                        <label>Self T2 <?= $i+1; ?></label>
                        <input type="text" name="self_t2_<?= $i; ?>" class="form-control" value="<?= isset($self['t2'][$i]) ? $self['t2'][$i] : ''; ?>">
                    </div>
                    <?php } ?>
                </div>

                <hr>
                <h4>Peer Assessment (Term 1 / Term 2)</h4>
                <div class="row">
                    <?php for($i=0; $i<3; $i++) { ?>
                    <div class="col-sm-6">
                        <label>Peer T1 <?= $i+1; ?></label>
                        <input type="text" name="peer_t1_<?= $i; ?>" class="form-control" value="<?= isset($peer['t1'][$i]) ? $peer['t1'][$i] : ''; ?>">
                    </div>
                    <div class="col-sm-6">
                        <label>Peer T2 <?= $i+1; ?></label>
                        <input type="text" name="peer_t2_<?= $i; ?>" class="form-control" value="<?= isset($peer['t2'][$i]) ? $peer['t2'][$i] : ''; ?>">
                    </div>
                    <?php } ?>
                </div>

                <hr>
                <h4>Rubric (Awareness / Sensitivity / Creativity)</h4>
                <div class="row">
                    <div class="col-sm-3"><label>Term 1 Awareness</label><input type="text" name="rubric_t1[awareness]" class="form-control" value="<?= isset($rubric['t1']['awareness']) ? $rubric['t1']['awareness'] : ''; ?>"></div>
                    <div class="col-sm-3"><label>Term 1 Sensitivity</label><input type="text" name="rubric_t1[sensitivity]" class="form-control" value="<?= isset($rubric['t1']['sensitivity']) ? $rubric['t1']['sensitivity'] : ''; ?>"></div>
                    <div class="col-sm-3"><label>Term 1 Creativity</label><input type="text" name="rubric_t1[creativity]" class="form-control" value="<?= isset($rubric['t1']['creativity']) ? $rubric['t1']['creativity'] : ''; ?>"></div>
                    <div class="col-sm-3"><label>Term 2 Awareness</label><input type="text" name="rubric_t2[awareness]" class="form-control" value="<?= isset($rubric['t2']['awareness']) ? $rubric['t2']['awareness'] : ''; ?>"></div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-3"><label>Term 2 Sensitivity</label><input type="text" name="rubric_t2[sensitivity]" class="form-control" value="<?= isset($rubric['t2']['sensitivity']) ? $rubric['t2']['sensitivity'] : ''; ?>"></div>
                    <div class="col-sm-3"><label>Term 2 Creativity</label><input type="text" name="rubric_t2[creativity]" class="form-control" value="<?= isset($rubric['t2']['creativity']) ? $rubric['t2']['creativity'] : ''; ?>"></div>
                </div>

                <hr>
                <h4>Parent Feedback (Term 1 / Term 2)</h4>
                <?php $labels = array('My child enjoys','My child needs support in','Home resources used (books/games/etc.)','Parent comments'); ?>
                <?php for($i=0; $i<4; $i++) { ?>
                    <div class="row">
                        <div class="col-sm-6"><label>T1 - <?= $labels[$i]; ?></label><input type="text" name="parent_t1_<?= $i; ?>" class="form-control" value="<?= isset($parent['t1'][$i]) ? $parent['t1'][$i] : ''; ?>"></div>
                        <div class="col-sm-6"><label>T2 - <?= $labels[$i]; ?></label><input type="text" name="parent_t2_<?= $i; ?>" class="form-control" value="<?= isset($parent['t2'][$i]) ? $parent['t2'][$i] : ''; ?>"></div>
                    </div>
                    <br>
                <?php } ?>

                <h4>Activities</h4>
                <div class="row">
                    <div class="col-sm-6"><label>Activities Term 1 (comma separated)</label><input type="text" name="activities_t1" class="form-control" value="<?= isset($activities['t1']) && is_array($activities['t1']) ? implode(',', $activities['t1']) : ''; ?>"></div>
                    <div class="col-sm-6"><label>Activities Term 2 (comma separated)</label><input type="text" name="activities_t2" class="form-control" value="<?= isset($activities['t2']) && is_array($activities['t2']) ? implode(',', $activities['t2']) : ''; ?>"></div>
                </div>

                <hr>
                <h4>Photo Uploads</h4>
                <div class="row">
                    <div class="col-sm-3">
                        <label>Portfolio Snapshot</label>
                        <input type="file" name="portfolio_snapshot" class="form-control">
                        <?php if($get($existing, 'portfolio_snapshot')) { echo '<small class="text-success">Current: '.$get($existing, 'portfolio_snapshot').'</small>'; } ?>
                    </div>
                    <div class="col-sm-3">
                        <label>Class Group Photo</label>
                        <input type="file" name="class_group_photo" class="form-control">
                        <?php if($get($existing, 'class_group_photo')) { echo '<small class="text-success">Current: '.$get($existing, 'class_group_photo').'</small>'; } ?>
                    </div>
                    <div class="col-sm-3">
                        <label>Activity Highlight 1</label>
                        <input type="file" name="activity_highlight_1" class="form-control">
                        <?php if($get($existing, 'activity_highlight_1')) { echo '<small class="text-success">Current: '.$get($existing, 'activity_highlight_1').'</small>'; } ?>
                    </div>
                    <div class="col-sm-3">
                        <label>Activity Highlight 2</label>
                        <input type="file" name="activity_highlight_2" class="form-control">
                        <?php if($get($existing, 'activity_highlight_2')) { echo '<small class="text-success">Current: '.$get($existing, 'activity_highlight_2').'</small>'; } ?>
                    </div>
                </div>
                <hr>
                <h4>Teacher Observation Notes</h4>
                <textarea name="teacher_profile_remark" class="form-control" rows="6"><?= $get($existing, 'teacher_remarks'); ?></textarea>

                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-success">Save Information</button>
                        <a href="<?= base_url('holisticreport/index'); ?>" class="btn btn-default">Back</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>