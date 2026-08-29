<?php
$s      = isset($student) ? $student : [];
$mode   = isset($mode) ? $mode : 'add';
$grades = isset($grades) ? $grades : [];
$sa     = isset($self_assessment) ? $self_assessment : [];
$pa     = isset($peer_assessment) ? $peer_assessment : [];
$acts   = isset($activities) ? $activities : [];
$tp     = isset($teacher_profile) ? $teacher_profile : [];
$pf     = isset($parent_feedback) ? $parent_feedback : [];
$sigs   = isset($signatures) ? $signatures : [];
$att    = isset($attendance) ? $attendance : [];

function val($arr, $key, $default = '') {
    return isset($arr[$key]) ? htmlspecialchars($arr[$key]) : $default;
}
function gradeVal($grades, $term, $cg, $indicator) {
    return isset($grades[$term][$cg][$indicator]) ? $grades[$term][$cg][$indicator] : '';
}
function gradeSelect($name, $value, $label = '') {
    $opts = ['Stream','Mountain','Sky'];
    $html = "<select name=\"{$name}\" class=\"grade-select\">";
    $html .= "<option value=\"\">-</option>";
    foreach ($opts as $o) {
        $sel = ($value === $o) ? ' selected' : '';
        $html .= "<option value=\"{$o}\"{$sel}>{$o}</option>";
    }
    $html .= "</select>";
    return $html;
}

$action_url = ($mode === 'edit') 
    ? base_url('holisticstudent/update/' . $s['id']) 
    : base_url('holisticstudent/save');

$months = ['Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar'];
$terms  = ['Term1','Term2'];

// Build activities indexed by term
$act_t1 = [];
$act_t2 = [];
foreach ($acts as $a) {
    if ($a['term'] === 'Term1') $act_t1[] = $a['activity_name'];
    if ($a['term'] === 'Term2') $act_t2[] = $a['activity_name'];
}
// Ensure 10 slots
while (count($act_t1) < 10) $act_t1[] = '';
while (count($act_t2) < 10) $act_t2[] = '';

// Attendance indexed by month
$att_indexed = [];
foreach ($att as $a) {
    $att_indexed[$a['month_name']] = $a;
}
?>

<div class="page-header">
    <h1 class="page-title"><?= $mode === 'edit' ? '✏️ Edit Student' : '➕ Add New Student' ?></h1>
    <a href="<?= base_url('holisticstudent') ?>" class="btn btn-outline">← Back</a>
</div>

<form action="<?= $action_url ?>" method="POST" enctype="multipart/form-data" id="studentForm">

<!-- ═══════════════════════════════════════════════════════════ -->
<!-- SECTION 1: BASIC INFORMATION                              -->
<!-- ═══════════════════════════════════════════════════════════ -->
<div class="form-section">
    <div class="section-header orange">
        <span class="section-icon">📋</span>
        <h2>Student Basic Information</h2>
    </div>
    <div class="form-grid form-grid-3">
        <div class="form-group">
            <label>Student Name <span class="req">*</span></label>
            <input type="text" name="student_name" value="<?= val($s,'student_name') ?>" required class="form-control">
        </div>
        <div class="form-group">
            <label>Class</label>
            <select name="class" class="form-control">
                <option value="Grade-1" <?= (val($s,'class') === 'Grade-1') ? 'selected' : '' ?>>Grade-1</option>
                <option value="Grade-2" <?= (val($s,'class') === 'Grade-2') ? 'selected' : '' ?>>Grade-2</option>
            </select>
        </div>
        <div class="form-group">
            <label>Section</label>
            <input type="text" name="section" value="<?= val($s,'section') ?>" class="form-control" placeholder="A, B, C...">
        </div>
        <div class="form-group">
            <label>Birthday</label>
            <input type="date" name="birthday" value="<?= val($s,'birthday') ?>" class="form-control">
        </div>
        <div class="form-group">
            <label>Age (years)</label>
            <input type="number" name="age" value="<?= val($s,'age') ?>" class="form-control" min="4" max="12">
        </div>
        <div class="form-group">
            <label>Mother Tongue</label>
            <input type="text" name="mother_tongue" value="<?= val($s,'mother_tongue') ?>" class="form-control">
        </div>
        <div class="form-group form-group-full">
            <label>Address</label>
            <textarea name="address" class="form-control" rows="2"><?= val($s,'address') ?></textarea>
        </div>
        <div class="form-group">
            <label>Contact Number</label>
            <input type="text" name="contact_number" value="<?= val($s,'contact_number') ?>" class="form-control">
        </div>
        <div class="form-group">
            <label>I want to be a/an...</label>
            <input type="text" name="aspiration" value="<?= val($s,'aspiration') ?>" class="form-control" placeholder="when I grow up">
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════ -->
<!-- SECTION 2: INTERESTS & FAVOURITES                         -->
<!-- ═══════════════════════════════════════════════════════════ -->
<div class="form-section">
    <div class="section-header red">
        <span class="section-icon">⭐</span>
        <h2>All About Me – Interests & Favourites</h2>
    </div>
    <div class="form-grid form-grid-2">
        <div>
            <label class="label-heading">I am interested in:</label>
            <div class="checkbox-grid">
                <?php
                $interests = [
                    'dancing'      => 'Dancing',
                    'reading'      => 'Reading',
                    'singing'      => 'Singing',
                    'writing'      => 'Writing',
                    'paper_craft'  => 'Paper Craft',
                    'number_games' => 'Number Games and Puzzles',
                    'gardening'    => 'Gardening',
                    'drawing'      => 'Drawing & Colouring',
                    'listening'    => 'Listening to Stories',
                ];
                foreach ($interests as $key => $label):
                    $checked = (!empty($s["interest_{$key}"])) ? 'checked' : '';
                ?>
                <label class="checkbox-label">
                    <input type="checkbox" name="interest_<?= $key ?>" value="1" <?= $checked ?>>
                    <span><?= $label ?></span>
                </label>
                <?php endforeach; ?>
            </div>
            <div class="form-group" style="margin-top:10px">
                <label>Any other:</label>
                <input type="text" name="interest_other" value="<?= val($s,'interest_other') ?>" class="form-control">
            </div>
        </div>
        <div>
            <label class="label-heading">My Favourite:</label>
            <div class="form-grid form-grid-2">
                <div class="form-group"><label>Colour</label><input type="text" name="fav_colour" value="<?= val($s,'fav_colour') ?>" class="form-control"></div>
                <div class="form-group"><label>Foods</label><input type="text" name="fav_foods" value="<?= val($s,'fav_foods') ?>" class="form-control"></div>
                <div class="form-group"><label>Games</label><input type="text" name="fav_games" value="<?= val($s,'fav_games') ?>" class="form-control"></div>
                <div class="form-group"><label>Animal</label><input type="text" name="fav_animal" value="<?= val($s,'fav_animal') ?>" class="form-control"></div>
                <div class="form-group"><label>Flower</label><input type="text" name="fav_flower" value="<?= val($s,'fav_flower') ?>" class="form-control"></div>
                <div class="form-group"><label>Festival</label><input type="text" name="fav_festival" value="<?= val($s,'fav_festival') ?>" class="form-control"></div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════ -->
<!-- SECTION 3: FAMILY INFO                                    -->
<!-- ═══════════════════════════════════════════════════════════ -->
<div class="form-section">
    <div class="section-header green">
        <span class="section-icon">👨‍👩‍👧</span>
        <h2>Family Information</h2>
    </div>
    <div class="form-grid form-grid-2">
        <div class="form-group">
            <label>Father's Name</label>
            <input type="text" name="father_name" value="<?= val($s,'father_name') ?>" class="form-control">
        </div>
        <div class="form-group">
            <label>Mother's Name</label>
            <input type="text" name="mother_name" value="<?= val($s,'mother_name') ?>" class="form-control">
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════ -->
<!-- SECTION 4: PHOTOS                                          -->
<!-- ═══════════════════════════════════════════════════════════ -->
<div class="form-section">
    <div class="section-header purple">
        <span class="section-icon">📸</span>
        <h2>Photos</h2>
    </div>
    <div class="form-grid form-grid-2">
        <div class="form-group">
            <label>A Glimpse of Myself (Student Photo)</label>
            <?php if (!empty($s['photo_self'])): ?>
                <img src="<?= base_url($s['photo_self']) ?>" class="preview-img" alt="Self Photo">
            <?php endif; ?>
            <input type="file" name="photo_self" class="form-control" accept="image/*">
        </div>
        <div class="form-group">
            <label>A Glimpse of My Family (Family Photo)</label>
            <?php if (!empty($s['photo_family'])): ?>
                <img src="<?= base_url($s['photo_family']) ?>" class="preview-img" alt="Family Photo">
            <?php endif; ?>
            <input type="file" name="photo_family" class="form-control" accept="image/*">
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════ -->
<!-- SECTION 5: PHYSICAL MEASUREMENTS                          -->
<!-- ═══════════════════════════════════════════════════════════ -->
<div class="form-section">
    <div class="section-header blue">
        <span class="section-icon">📏</span>
        <h2>Physical Measurements</h2>
    </div>
    <div class="form-grid form-grid-2">
        <div class="measurement-box">
            <h3 class="term-label term1">Term 1</h3>
            <div class="form-grid form-grid-2">
                <div class="form-group"><label>Height (handspan)</label><input type="text" name="t1_height_handspan" value="<?= val($s,'t1_height_handspan') ?>" class="form-control"></div>
                <div class="form-group"><label>Height (ft)</label><input type="text" name="t1_height_ft" value="<?= val($s,'t1_height_ft') ?>" class="form-control"></div>
                <div class="form-group"><label>Weight (kg)</label><input type="text" name="t1_weight_kg" value="<?= val($s,'t1_weight_kg') ?>" class="form-control"></div>
            </div>
        </div>
        <div class="measurement-box">
            <h3 class="term-label term2">Term 2</h3>
            <div class="form-grid form-grid-2">
                <div class="form-group"><label>Height (handspan)</label><input type="text" name="t2_height_handspan" value="<?= val($s,'t2_height_handspan') ?>" class="form-control"></div>
                <div class="form-group"><label>Height (ft)</label><input type="text" name="t2_height_ft" value="<?= val($s,'t2_height_ft') ?>" class="form-control"></div>
                <div class="form-group"><label>Weight (kg)</label><input type="text" name="t2_weight_kg" value="<?= val($s,'t2_weight_kg') ?>" class="form-control"></div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════ -->
<!-- SECTION 6: ATTENDANCE                                      -->
<!-- ═══════════════════════════════════════════════════════════ -->
<div class="form-section">
    <div class="section-header orange">
        <span class="section-icon">📅</span>
        <h2>Attendance Record</h2>
    </div>
    <div class="table-responsive">
        <table class="table attendance-table">
            <thead>
                <tr>
                    <th>Months</th>
                    <?php foreach($months as $m): ?><th><?= $m ?></th><?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $att_rows = ['working_days' => 'No. of Working Days', 'days_attended' => 'No. of Days Attended', 'attendance_percentage' => '% of Attendance', 'low_attendance_reason' => 'If attendance is low – reason'];
                foreach ($att_rows as $field => $label):
                ?>
                <tr>
                    <td><?= $label ?></td>
                    <?php foreach($months as $m):
                        $val = isset($att_indexed[$m][$field]) ? $att_indexed[$m][$field] : '';
                    ?>
                    <td><input type="text" name="attendance[<?= $m ?>][<?= $field ?>]" value="<?= htmlspecialchars($val) ?>" class="att-input"></td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════ -->
<!-- SECTION 7: SELF ASSESSMENT                                 -->
<!-- ═══════════════════════════════════════════════════════════ -->
<div class="form-section">
    <div class="section-header red">
        <span class="section-icon">🪞</span>
        <h2>Self Assessment</h2>
        <small>Self-reflection on inter-disciplinary activity (Clay work, drawing, playing a game, colouring, puppet-making, model making etc.)</small>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr><th>Activity</th><th>Term 1</th><th>Term 2</th></tr>
            </thead>
            <tbody>
                <?php
                $sa_fields = [
                    'activities_enjoy'        => 'Activities that I enjoy the most',
                    'activities_difficult'    => 'Activities that I find difficult to do',
                    'activities_with_friends' => 'Activities that I enjoy doing with my friends',
                ];
                foreach ($sa_fields as $field => $label):
                ?>
                <tr>
                    <td><?= $label ?></td>
                    <td><textarea name="self_assessment[Term1][<?= $field ?>]" class="form-control"><?= htmlspecialchars($sa['Term1'][$field] ?? '') ?></textarea></td>
                    <td><textarea name="self_assessment[Term2][<?= $field ?>]" class="form-control"><?= htmlspecialchars($sa['Term2'][$field] ?? '') ?></textarea></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════ -->
<!-- SECTION 8: PEER ASSESSMENT                                 -->
<!-- ═══════════════════════════════════════════════════════════ -->
<div class="form-section">
    <div class="section-header green">
        <span class="section-icon">🤝</span>
        <h2>Peer Assessment</h2>
        <small>Peer feedback from classmate(s) – Collaborative game/activity done in pairs/groups</small>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr><th>Behaviour</th><th>Term 1</th><th>Term 2</th></tr>
            </thead>
            <tbody>
                <?php
                $pa_fields = [
                    'help_completing_task' => 'Help in completing task/activity',
                    'likes_to_play'        => 'Likes to play with other',
                    'shares_stationary'    => 'Shares stationary (crayons/glue/chalk) with classmates',
                ];
                foreach ($pa_fields as $field => $label):
                    $v1 = $pa['Term1'][$field] ?? '';
                    $v2 = $pa['Term2'][$field] ?? '';
                ?>
                <tr>
                    <td><?= $label ?></td>
                    <td><?= gradeSelect("peer_assessment[Term1][{$field}]", $v1) ?></td>
                    <td><?= gradeSelect("peer_assessment[Term2][{$field}]", $v2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════ -->
<!-- SECTION 9: PARTICIPATED IN                                 -->
<!-- ═══════════════════════════════════════════════════════════ -->
<div class="form-section">
    <div class="section-header blue">
        <span class="section-icon">🏆</span>
        <h2>Participated In</h2>
    </div>
    <div class="form-grid form-grid-2">
        <div>
            <h3 class="term-label term1">Term 1</h3>
            <?php for ($i = 0; $i < 10; $i++): ?>
            <div class="form-group activity-row">
                <input type="text" name="activities[Term1][]" value="<?= htmlspecialchars($act_t1[$i]) ?>" class="form-control" placeholder="Activity <?= $i+1 ?>">
            </div>
            <?php endfor; ?>
        </div>
        <div>
            <h3 class="term-label term2">Term 2</h3>
            <?php for ($i = 0; $i < 10; $i++): ?>
            <div class="form-group activity-row">
                <input type="text" name="activities[Term2][]" value="<?= htmlspecialchars($act_t2[$i]) ?>" class="form-control" placeholder="Activity <?= $i+1 ?>">
            </div>
            <?php endfor; ?>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════ -->
<!-- SECTION 10: PHYSICAL DEVELOPMENT (CG1-CG3)               -->
<!-- ═══════════════════════════════════════════════════════════ -->
<div class="form-section">
    <div class="section-header orange">
        <span class="section-icon">🏃</span>
        <h2>Physical Development</h2>
    </div>
    <?php
    $physical = [
        'CG1' => [
            'title' => 'CG1 – Develops habits that keep him/her healthy and safe',
            'indicators' => [
                'nutritious_food' => 'Shows a liking for and understanding of nutritious food and does not waste food',
                'basic_hygiene'   => 'Practices basic self-care and hygiene',
                'unsafe_situation'=> 'Understands unsafe situation and asks for help',
            ]
        ],
        'CG2' => [
            'title' => 'CG2 – Develops sharpness in sensorial perceptions',
            'indicators' => [
                'diff_shapes'    => 'Differentiates between shapes, colours, and their shapes',
                'sense_touch'    => 'Develops discrimination in the sense of touch',
                'sensorial_int'  => 'Begins integrating sensorial perceptions to get a holistic awareness of experiences',
            ]
        ],
        'CG3' => [
            'title' => 'CG3 – Develops a fit and flexible body',
            'indicators' => [
                'balance_coord'  => 'Shows balance, coordination and flexibility in various physical activities',
                'precision_ctrl' => 'Shows precision and control in working with their hands and fingers',
                'strength_endur' => 'Shows strength and endurance in carrying, walking and running',
            ]
        ],
    ];
    include_once(APPPATH . 'views/student/_cg_table.php');
    render_cg_tables($physical, $grades, 'gradeSelect');
    ?>
</div>

<!-- ═══════════════════════════════════════════════════════════ -->
<!-- SECTION 11: SOCIO-EMOTIONAL & ETHICAL DEVELOPMENT (CG4-CG6)-->
<!-- ═══════════════════════════════════════════════════════════ -->
<div class="form-section">
    <div class="section-header red">
        <span class="section-icon">💖</span>
        <h2>Socio-Emotional & Ethical Development</h2>
    </div>
    <?php
    $socio = [
        'CG4' => [
            'title' => 'CG4 – Develops emotional intelligence',
            'indicators' => [
                'recognizes_self'   => 'Starts recognizing \'self\' as an individual belong to a family and community',
                'diff_emotions'     => 'Recognises different emotions and makes deliberate effort to regulate them appropriately',
                'interacts_comfy'   => 'Interacts comfortably with other children and adults',
                'kindness'          => 'Shows kindness and helpfulness to others (including animals, plants) when they are in need',
            ]
        ],
        'CG5' => [
            'title' => 'CG5 – Develops positive attitude towards productive work and service or \'Seva\'',
            'indicators' => [
                'willingness'       => 'Demonstrate willingness and participation in age appropriate physical work towards helping others',
            ]
        ],
        'CG6' => [
            'title' => 'CG6 – Develops a positive regard for the natural environment around them',
            'indicators' => [
                'care_nature'       => 'Shows care for and joy in engaging with all life forms',
            ]
        ],
    ];
    render_cg_tables($socio, $grades, 'gradeSelect');
    ?>
</div>

<!-- ═══════════════════════════════════════════════════════════ -->
<!-- SECTION 12: COGNITIVE DEVELOPMENT (CG7-CG8)               -->
<!-- ═══════════════════════════════════════════════════════════ -->
<div class="form-section">
    <div class="section-header blue">
        <span class="section-icon">🧠</span>
        <h2>Cognitive Development</h2>
    </div>
    <?php
    $cognitive = [
        'CG7' => [
            'title' => 'CG7 – Makes sense of world around through observation and logical thinking',
            'indicators' => [
                'observes_objects'  => 'Observes and understands different objects and relationship between them',
                'cause_effect'      => 'Observes and understands cause and effect of relationship in nature by forming simple hypothesis and uses observations to explain their hypothesis',
            ]
        ],
        'CG8' => [
            'title' => 'CG8 – Develops mathematical understanding and abilities to recognize the world through quantities, shapes and measure',
            'indicators' => [
                'sorts_objects'     => 'Sorts objects into groups and sub-groups based on more than one property',
                'identifies_patterns'=> 'Identifies and extends patterns in their surroundings, shapes and numbers',
                'counts_99'         => 'Counts up to 99 both forwards and backwards and in groups of 10s and 20s',
                'addition_sub'      => 'Performs addition and subtraction of 2-digit numbers fluently using flexible strategies of composition and decomposition',
                'geometric_shapes'  => 'Recognises, makes, and classifies basic geometric shapes and their observable properties, and understands and explains the relative relation of objects in space',
                'math_problems'     => 'Formulates and solves simple mathematical problems related to quantities, shapes, space and measurements',
            ]
        ],
    ];
    render_cg_tables($cognitive, $grades, 'gradeSelect');
    ?>
</div>

<!-- ═══════════════════════════════════════════════════════════ -->
<!-- SECTION 13: LANGUAGE & LITERACY DEVELOPMENT (CG9-CG11)   -->
<!-- ═══════════════════════════════════════════════════════════ -->
<div class="form-section">
    <div class="section-header green">
        <span class="section-icon">📖</span>
        <h2>Language and Literacy Development</h2>
    </div>
    <?php
    $language = [
        'CG9' => [
            'title' => 'CG9 – Develops effective communication skills for day-to-day interaction in two languages',
            'indicators' => [
                'songs_rhymes'      => 'Listens to and appreciates simple songs, rhymes and poems',
                'converses_fluently'=> 'Converses fluently and can hold a meaningful conversation',
                'oral_instructions' => 'Understands oral instructions for a complex task and gives clear oral instructions for the same to others',
                'narrated_stories'  => 'Comprehends narrated/read-out stories and identifies characters, storyline and what the author wants to say',
            ]
        ],
        'CG10' => [
            'title' => 'CG10 – Develops fluency in reading and writing in Language 1',
            'indicators' => [
                'recognises_letters'=> 'Recognises all letters of alphabet of the script (L1) and uses this knowledge to read and write words',
                'reads_passages'    => 'Read stories and passages (in L1) with accuracy and fluency with appropriate pauses and voice modulation',
                'reads_short_stories'=> 'Reads short stories and comprehends its meaning by identifying characters, storyline and what the author wanted to say – on their own (L1)',
                'reads_poems'       => 'Reads short poems and begins to appreciate the poem for its choice of words and imagination',
                'reads_news'        => 'Reads and comprehends meaning of short news items, instructions and recipes, and publicity material',
            ]
        ],
        'CG11' => [
            'title' => 'CG11 – Begins to read and write in Language 2',
            'indicators' => [
                'recognises_l2'     => 'Recognises most frequently occurring letters of the alphabet of the script, and used this knowledge to read and write simple words and sentences',
            ]
        ],
    ];
    render_cg_tables($language, $grades, 'gradeSelect');
    ?>
</div>

<!-- ═══════════════════════════════════════════════════════════ -->
<!-- SECTION 14: AESTHETIC & CULTURAL DEVELOPMENT (CG12)       -->
<!-- ═══════════════════════════════════════════════════════════ -->
<div class="form-section">
    <div class="section-header purple">
        <span class="section-icon">🎨</span>
        <h2>Aesthetic and Cultural Development</h2>
    </div>
    <?php
    $aesthetic = [
        'CG12' => [
            'title' => 'CG12 – Develops ability and sensibilities in visual & performing arts (music, art and dance) and express their emotions through art in meaningful and joyful ways',
            'indicators' => [
                'explores_materials'=> 'Explores and plays with a variety of materials and tools to create two-dimensional and three dimensional artworks in varying sizes',
                'plays_body'        => 'Explores and plays with own voice, body, spaces and a variety of objects to create music, role-play, dance and movement',
                'innovates_arts'    => 'Innovates and works imaginatively to express ideas and emotions through the arts',
            ]
        ],
    ];
    render_cg_tables($aesthetic, $grades, 'gradeSelect');
    ?>
</div>

<!-- ═══════════════════════════════════════════════════════════ -->
<!-- SECTION 15: POSITIVE LEARNING HABITS (CG13)               -->
<!-- ═══════════════════════════════════════════════════════════ -->
<div class="form-section">
    <div class="section-header orange">
        <span class="section-icon">📚</span>
        <h2>Positive Learning Habits</h2>
    </div>
    <?php
    $learning = [
        'CG13' => [
            'title' => 'CG13 – Develops habits of learning that allow him/her to engage actively in formal learning environments like a school classroom',
            'indicators' => [
                'attention_action'  => 'Attention and intentional action: Acquires skills to plan, focus attention and direct activities to achieve specific goals',
                'observation_wonder'=> 'Observation, wonder, curiosity and exploration: Observes minute details of objects, wonders and explores using various senses, tinkers with objects, ask questions',
            ]
        ],
    ];
    render_cg_tables($learning, $grades, 'gradeSelect');
    ?>
</div>

<!-- ═══════════════════════════════════════════════════════════ -->
<!-- SECTION 16: TEACHER'S LEARNER PROFILE                     -->
<!-- ═══════════════════════════════════════════════════════════ -->
<div class="form-section">
    <div class="section-header green">
        <span class="section-icon">👩‍🏫</span>
        <h2>Learner's Profile by the Teacher</h2>
    </div>
    <div class="form-group">
        <textarea name="teacher_profile" class="form-control" rows="6" placeholder="Teacher's observations and profile of the learner..."><?= htmlspecialchars($tp['profile_text'] ?? '') ?></textarea>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════ -->
<!-- SECTION 17: PARENT FEEDBACK                               -->
<!-- ═══════════════════════════════════════════════════════════ -->
<div class="form-section">
    <div class="section-header blue">
        <span class="section-icon">👨‍👩‍👧</span>
        <h2>Parent's Feedback</h2>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr><th>Aspect</th><th>Term 1</th><th>Term 2</th></tr>
            </thead>
            <tbody>
                <?php
                $pf_fields = [
                    'child_enjoys'             => 'My Child enjoys participating in...',
                    'child_can_be_supported'   => 'My Child can be supported for...',
                    'would_like_to_share'      => 'I would also like to share...',
                ];
                foreach ($pf_fields as $field => $label):
                ?>
                <tr>
                    <td><?= $label ?></td>
                    <td><textarea name="parent_feedback[Term1][<?= $field ?>]" class="form-control"><?= htmlspecialchars($pf['Term1'][$field] ?? '') ?></textarea></td>
                    <td><textarea name="parent_feedback[Term2][<?= $field ?>]" class="form-control"><?= htmlspecialchars($pf['Term2'][$field] ?? '') ?></textarea></td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td>Have I completed age appropriate vaccination schedule for my child?</td>
                    <td>
                        <label class="checkbox-label">
                            <input type="checkbox" name="parent_feedback[Term1][vaccination_completed]" value="1" <?= !empty($pf['Term1']['vaccination_completed']) ? 'checked' : '' ?>>
                            <span>Yes</span>
                        </label>
                    </td>
                    <td>
                        <label class="checkbox-label">
                            <input type="checkbox" name="parent_feedback[Term2][vaccination_completed]" value="1" <?= !empty($pf['Term2']['vaccination_completed']) ? 'checked' : '' ?>>
                            <span>Yes</span>
                        </label>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════ -->
<!-- SECTION 18: SIGNATURES                                     -->
<!-- ═══════════════════════════════════════════════════════════ -->
<div class="form-section">
    <div class="section-header red">
        <span class="section-icon">✍️</span>
        <h2>Signatures with Date</h2>
    </div>
    <div class="form-grid form-grid-2">
        <?php foreach ($terms as $term): ?>
        <div class="signature-block">
            <h3 class="term-label <?= strtolower($term) ?>"><?= $term ?></h3>
            <div class="form-grid form-grid-3">
                <?php foreach (['parent_signature'=>'Parents/Guardian', 'teacher_signature'=>'Class Teacher', 'principal_signature'=>'Principal'] as $field => $label): ?>
                <div class="form-group">
                    <label><?= $label ?></label>
                    <input type="text" name="signatures[<?= $term ?>][<?= $field ?>]" value="<?= htmlspecialchars($sigs[$term][$field] ?? '') ?>" class="form-control" placeholder="Name">
                    <input type="date" name="signatures[<?= $term ?>][<?= str_replace('signature','sign_date',$field) ?>]" value="<?= htmlspecialchars($sigs[$term][str_replace('signature','sign_date',$field)] ?? '') ?>" class="form-control mt-5">
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Submit -->
<div class="form-actions">
    <button type="submit" class="btn btn-primary btn-lg">
        <?= $mode === 'edit' ? '💾 Save Changes' : '✅ Add Student' ?>
    </button>
    <a href="<?= base_url('holisticstudent') ?>" class="btn btn-outline btn-lg">Cancel</a>
</div>

</form>
