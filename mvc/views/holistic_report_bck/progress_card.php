<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Holistic Progress Card – <?= $student['student_name'] ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;600;700;800&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/report.css') ?>">
</head>
<body>

<!-- ── Print Controls ───────────────────────────────────────── -->
<div class="print-controls no-print">
    <div class="print-bar">
        <div class="print-brand">Holistic Progress Card</div>
        <div class="print-actions">
            <a href="<?= base_url('holisticstudent/edit/' . $student['id']) ?>" class="btn-print-action btn-edit">✏️ Edit</a>
            <a href="<?= base_url('holisticstudent') ?>" class="btn-print-action btn-back">← All Students</a>
            <button onclick="window.print()" class="btn-print-action btn-print">🖨 Print / Save PDF</button>
        </div>
    </div>
</div>

<?php
// Helper: get grade symbol
function gradeIcon($grade) {
    $icons = ['Sky' => '☁️', 'Mountain' => '⛰️', 'Stream' => '🌊'];
    return $icons[$grade] ?? '–';
}
function gradeClass($grade) {
    $classes = ['Sky' => 'grade-sky', 'Mountain' => 'grade-mountain', 'Stream' => 'grade-stream'];
    return $classes[$grade] ?? '';
}

// Index activities by term
$act_t1 = array_filter($activities, fn($a) => $a['term'] === 'Term1');
$act_t2 = array_filter($activities, fn($a) => $a['term'] === 'Term2');

// All CG definitions
$all_cgs = [
    'CG1'  => ['Physical Development',             'Develops habits that keep him/her healthy and safe',              ['nutritious_food'=>'Shows a liking for nutritious food','basic_hygiene'=>'Practices basic self-care and hygiene','unsafe_situation'=>'Understands unsafe situation and asks for help']],
    'CG2'  => ['Physical Development',             'Develops sharpness in sensorial perceptions',                     ['diff_shapes'=>'Differentiates between shapes, colours','sense_touch'=>'Develops discrimination in the sense of touch','sensorial_int'=>'Begins integrating sensorial perceptions']],
    'CG3'  => ['Physical Development',             'Develops a fit and flexible body',                                ['balance_coord'=>'Shows balance, coordination and flexibility','precision_ctrl'=>'Shows precision and control with hands and fingers','strength_endur'=>'Shows strength and endurance in carrying, walking, running']],
    'CG4'  => ['Socio-Emotional & Ethical',        'Develops emotional intelligence',                                  ['recognizes_self'=>'Starts recognizing self as individual in family','diff_emotions'=>'Recognises different emotions and regulates them','interacts_comfy'=>'Interacts comfortably with other children and adults','kindness'=>'Shows kindness and helpfulness to others']],
    'CG5'  => ['Socio-Emotional & Ethical',        "Develops positive attitude towards productive work and 'Seva'",   ['willingness'=>'Demonstrates willingness in age appropriate physical work']],
    'CG6'  => ['Socio-Emotional & Ethical',        'Develops positive regard for the natural environment',             ['care_nature'=>'Shows care and joy in engaging with all life forms']],
    'CG7'  => ['Cognitive Development',            'Makes sense of world through observation and logical thinking',    ['observes_objects'=>'Observes and understands different objects and relationships','cause_effect'=>'Understands cause and effect relationship in nature']],
    'CG8'  => ['Cognitive Development',            'Develops mathematical understanding',                              ['sorts_objects'=>'Sorts objects into groups based on properties','identifies_patterns'=>'Identifies and extends patterns','counts_99'=>'Counts up to 99 forwards and backwards','addition_sub'=>'Performs addition and subtraction of 2-digit numbers','geometric_shapes'=>'Recognises and classifies geometric shapes','math_problems'=>'Formulates and solves simple mathematical problems']],
    'CG9'  => ['Language & Literacy',              'Develops effective communication skills in two languages',          ['songs_rhymes'=>'Listens to and appreciates songs, rhymes and poems','converses_fluently'=>'Converses fluently and holds meaningful conversation','oral_instructions'=>'Understands oral instructions for complex tasks','narrated_stories'=>'Comprehends narrated stories and identifies characters']],
    'CG10' => ['Language & Literacy',              'Develops fluency in reading and writing in Language 1',            ['recognises_letters'=>'Recognises all letters of the script (L1)','reads_passages'=>'Reads stories and passages with accuracy and fluency','reads_short_stories'=>'Reads short stories and comprehends meaning','reads_poems'=>'Reads short poems and appreciates word choices','reads_news'=>'Reads and comprehends short news items and instructions']],
    'CG11' => ['Language & Literacy',              'Begins to read and write in Language 2',                          ['recognises_l2'=>'Recognises frequently occurring letters of script (L2)']],
    'CG12' => ['Aesthetic & Cultural',             'Develops ability and sensibilities in visual & performing arts',   ['explores_materials'=>'Explores materials to create 2D and 3D artworks','plays_body'=>'Explores voice, body and objects for music, role-play, dance','innovates_arts'=>'Innovates and works imaginatively through the arts']],
    'CG13' => ['Positive Learning Habits',         'Develops habits of learning to engage actively in formal learning','attention_action'=>'Acquires skills to plan, focus and direct activities','observation_wonder'=>'Observes minute details, wonders and explores using senses'],
];

// Fix CG13 - it's a flat array not nested
$all_cgs['CG13'] = ['Positive Learning Habits', 'Develops habits of learning to engage actively in formal learning', ['attention_action'=>'Acquires skills to plan, focus and direct activities', 'observation_wonder'=>'Observes minute details, wonders and explores using senses']];

$months = ['Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar'];
$att_idx = [];
foreach ($attendance as $a) { $att_idx[$a['month_name']] = $a; }
?>

<div class="pc-wrapper">

<!-- ═══════════════════════════════════════════ PAGE 1 ═══ -->
<div class="pc-page">

    <!-- School Header -->
    <div class="pc-school-header">
        <div class="school-logo-area">
            <div class="school-emblem">🏛</div>
        </div>
        <div class="school-info">
            <p class="school-subtitle">Khalapur Taluka Shikshan Prasarak Mandal’s</p>
            <!-- <div class="school-tagline-hindi">Dream And Excel</div> -->
            <h1 class="school-name">P. P. GaganGiri Maharaj International School</h1>
            <h2 class="school-location">Khopoli</h2>
            
        </div>
        <div class="school-contact">
            <p>CBSE Wing, KTSP Campus, </p>
            <p>Khopoli - 410203, Raigad, Maharashtra.</p>
            <p>📧 principalgiscbse@gmail.com</p>
            <p>📞 +91 84590 38921</p>
        </div>
    </div>

    <div class="pc-card-title">
        <h2>HOLISTIC PROGRESS CARD</h2>
        <p>Session 20__–20__</p>
    </div>

    <!-- Student Info Strip -->
    <div class="student-info-strip">
        <div class="student-photo-box">
            <?php if (!empty($student['photo_self'])): ?>
                <img src="<?= base_url($student['photo_self']) ?>" alt="Student Photo" class="student-photo">
            <?php else: ?>
                <div class="photo-placeholder">👤</div>
            <?php endif; ?>
        </div>
        <div class="student-details">
            <div class="detail-row"><span class="detail-label">Student's Name:</span><span class="detail-value"><?= $student['student_name'] ?></span></div>
            <div class="detail-row"><span class="detail-label">Class:</span><span class="detail-value"><?= $student['class'] ?></span></div>
            <div class="detail-row"><span class="detail-label">Section:</span><span class="detail-value"><?= $student['section'] ?></span></div>
            <div class="detail-row"><span class="detail-label">Address:</span><span class="detail-value"><?= $student['address'] ?></span></div>
            <div class="detail-row"><span class="detail-label">Contact Number:</span><span class="detail-value"><?= $student['contact_number'] ?></span></div>
        </div>
    </div>

    <!-- Two column layout: All About Me + Learner Portfolio -->
    <div class="pc-two-col">
        <div class="pc-col">
            <div class="pc-section-title orange-bg">All About Me</div>

            <div class="about-grid">
                <div class="about-row"><b>My birthday is on</b> <?= $student['birthday'] ?></div>
                <div class="about-row"><b>I am</b> <?= $student['age'] ?> years old</div>
                <div class="about-row"><b>My mother tongue is</b> <?= $student['mother_tongue'] ?></div>
                <div class="about-row"><b>I want to be a/an</b> <?= $student['aspiration'] ?> when I grow up</div>
            </div>

            <div class="interests-box">
                <b>I am interested in:</b>
                <?php
                $int_map = ['dancing'=>'Dancing','reading'=>'Reading','singing'=>'Singing','writing'=>'Writing','paper_craft'=>'Paper Craft','number_games'=>'Number Games & Puzzles','gardening'=>'Gardening','drawing'=>'Drawing & Colouring','listening'=>'Listening to Stories'];
                $active = [];
                foreach ($int_map as $k => $v) {
                    if (!empty($student["interest_{$k}"])) $active[] = $v;
                }
                ?>
                <div class="interest-chips">
                    <?php foreach ($active as $a): ?>
                    <span class="interest-chip">✓ <?= $a ?></span>
                    <?php endforeach; ?>
                    <?php if ($student['interest_other']): ?>
                    <span class="interest-chip">✓ <?= $student['interest_other'] ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="favourites-box">
                <b>My favourite:</b>
                <div class="fav-grid">
                    <span>🎨 <?= $student['fav_colour'] ?: '–' ?></span>
                    <span>🍎 <?= $student['fav_foods'] ?: '–' ?></span>
                    <span>🎮 <?= $student['fav_games'] ?: '–' ?></span>
                    <span>🐾 <?= $student['fav_animal'] ?: '–' ?></span>
                    <span>🌸 <?= $student['fav_flower'] ?: '–' ?></span>
                    <span>🎉 <?= $student['fav_festival'] ?: '–' ?></span>
                </div>
            </div>

            <!-- Attendance -->
            <div class="pc-section-title orange-bg mt-10">Attendance</div>
            <div class="attendance-mini">
                <table class="att-table">
                    <thead>
                        <tr><th>Month</th><?php foreach ($months as $m): ?><th><?= $m ?></th><?php endforeach; ?></tr>
                    </thead>
                    <tbody>
                        <tr><td>Working Days</td><?php foreach ($months as $m): ?><td><?= $att_idx[$m]['working_days'] ?? '' ?></td><?php endforeach; ?></tr>
                        <tr><td>Days Attended</td><?php foreach ($months as $m): ?><td><?= $att_idx[$m]['days_attended'] ?? '' ?></td><?php endforeach; ?></tr>
                        <tr><td>% Attendance</td><?php foreach ($months as $m): ?><td><?= $att_idx[$m]['attendance_percentage'] ?? '' ?></td><?php endforeach; ?></tr>
                    </tbody>
                </table>
            </div>

            <!-- Physical Measurements -->
            <div class="measurements-row">
                <div class="meas-box">
                    <div class="meas-term term1-bg">Term 1</div>
                    <p>Height: <?= $student['t1_height_handspan'] ?> handspan / <?= $student['t1_height_ft'] ?> ft</p>
                    <p>Weight: <?= $student['t1_weight_kg'] ?> kg</p>
                </div>
                <div class="meas-box">
                    <div class="meas-term term2-bg">Term 2</div>
                    <p>Height: <?= $student['t2_height_handspan'] ?> handspan / <?= $student['t2_height_ft'] ?> ft</p>
                    <p>Weight: <?= $student['t2_weight_kg'] ?> kg</p>
                </div>
            </div>
        </div>

        <div class="pc-col">
            <div class="pc-section-title red-bg">Learner's Portfolio</div>
            <div class="portfolio-box">
                <?php if (!empty($student['photo_self'])): ?>
                <img src="<?= base_url($student['photo_self']) ?>" alt="Portfolio" class="portfolio-img">
                <?php else: ?>
                <div class="portfolio-placeholder">🖼 Portfolio Photo</div>
                <?php endif; ?>
            </div>

            <!-- Signature Table -->
            <div class="pc-section-title red-bg mt-10">Signature with Date</div>
            <table class="sig-table">
                <thead><tr><th>Term</th><th>Parents/Guardian</th><th>Class Teacher</th><th>Principal</th></tr></thead>
                <tbody>
                    <?php foreach (['Term1','Term2'] as $term): ?>
                    <tr>
                        <td><?= $term ?></td>
                        <td><?= $signatures[$term]['parent_signature'] ?? '' ?><br><small><?= $signatures[$term]['parent_sign_date'] ?? '' ?></small></td>
                        <td><?= $signatures[$term]['teacher_signature'] ?? '' ?><br><small><?= $signatures[$term]['teacher_sign_date'] ?? '' ?></small></td>
                        <td><?= $signatures[$term]['principal_signature'] ?? '' ?><br><small><?= $signatures[$term]['principal_sign_date'] ?? '' ?></small></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Grade Key -->
            <div class="pc-section-title orange-bg mt-10">Performance Level Descriptors</div>
            <table class="key-table">
                <tbody>
                    <tr><td class="grade-sky key-level">SKY<br><small>(Proficient)</small></td><td>Achieves competency on own & has good conceptual understanding</td></tr>
                    <tr><td class="grade-mountain key-level">MOUNTAIN<br><small>(Progressing)</small></td><td>Achieves competency with occasional support from teachers</td></tr>
                    <tr><td class="grade-stream key-level">STREAM<br><small>(Beginner)</small></td><td>Tries to achieve competency with a lot of support from teachers</td></tr>
                </tbody>
            </table>

            <!-- Glimpse of family -->
            <div class="pc-section-title green-bg mt-10">A Glimpse of My Family</div>
            <div class="family-box">
                <?php if (!empty($student['photo_family'])): ?>
                <img src="<?= base_url($student['photo_family']) ?>" alt="Family" class="portfolio-img">
                <?php else: ?>
                <div class="portfolio-placeholder">👨‍👩‍👧 Family Photo</div>
                <?php endif; ?>
                <p><b>Father:</b> <?= $student['father_name'] ?></p>
                <p><b>Mother:</b> <?= $student['mother_name'] ?></p>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════ PAGE 2 ═══ -->
<div class="pc-page">

    <div class="pc-two-col">
        <!-- Self & Peer Assessment -->
        <div class="pc-col">
            <div class="pc-section-title red-bg">Self Assessment</div>
            <table class="assess-table">
                <thead><tr><th>Activity</th><th>Term 1</th><th>Term 2</th></tr></thead>
                <tbody>
                    <?php
                    $sa_fields = ['activities_enjoy'=>'Activities I enjoy most','activities_difficult'=>'Activities I find difficult','activities_with_friends'=>'Activities I enjoy with friends'];
                    foreach ($sa_fields as $f => $l):
                    ?>
                    <tr>
                        <td><?= $l ?></td>
                        <td><?= $self_assessment['Term1'][$f] ?? '' ?></td>
                        <td><?= $self_assessment['Term2'][$f] ?? '' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="pc-section-title green-bg mt-10">Peer Assessment</div>
            <table class="assess-table">
                <thead><tr><th>Behaviour</th><th>Term 1</th><th>Term 2</th></tr></thead>
                <tbody>
                    <?php
                    $pa_fields = ['help_completing_task'=>'Help in completing task','likes_to_play'=>'Likes to play with others','shares_stationary'=>'Shares stationary'];
                    foreach ($pa_fields as $f => $l):
                        $g1 = $peer_assessment['Term1'][$f] ?? '';
                        $g2 = $peer_assessment['Term2'][$f] ?? '';
                    ?>
                    <tr>
                        <td><?= $l ?></td>
                        <td><span class="grade-badge <?= gradeClass($g1) ?>"><?= $g1 ?: '–' ?></span></td>
                        <td><span class="grade-badge <?= gradeClass($g2) ?>"><?= $g2 ?: '–' ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="pc-section-title orange-bg mt-10">Participated In</div>
            <div class="act-grid">
                <div>
                    <b>Term 1</b>
                    <ol class="act-list">
                        <?php foreach ($act_t1 as $a): ?>
                        <li><?= $a['activity_name'] ?></li>
                        <?php endforeach; ?>
                    </ol>
                </div>
                <div>
                    <b>Term 2</b>
                    <ol class="act-list">
                        <?php foreach ($act_t2 as $a): ?>
                        <li><?= $a['activity_name'] ?></li>
                        <?php endforeach; ?>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Learner's Profile by Teacher + Parent Feedback -->
        <div class="pc-col">
            <div class="pc-section-title blue-bg">Learner's Profile by the Teacher</div>
            <div class="profile-text-box"><?= nl2br($teacher_profile['profile_text'] ?? '') ?></div>

            <div class="pc-section-title red-bg mt-10">Parent's Feedback</div>
            <table class="assess-table">
                <thead><tr><th>Aspect</th><th>Term 1</th><th>Term 2</th></tr></thead>
                <tbody>
                    <?php
                    $pf_fields = ['child_enjoys'=>'My Child enjoys...','child_can_be_supported'=>'My Child can be supported for...','would_like_to_share'=>'I would also like to share...'];
                    foreach ($pf_fields as $f => $l):
                    ?>
                    <tr>
                        <td><?= $l ?></td>
                        <td><?= $parent_feedback['Term1'][$f] ?? '' ?></td>
                        <td><?= $parent_feedback['Term2'][$f] ?? '' ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td>Vaccination completed?</td>
                        <td><?= !empty($parent_feedback['Term1']['vaccination_completed']) ? '✅ Yes' : '–' ?></td>
                        <td><?= !empty($parent_feedback['Term2']['vaccination_completed']) ? '✅ Yes' : '–' ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════ PAGE 3 ═══ -->
<div class="pc-page">
    <div class="pc-section-title orange-bg">Competency Grades – All Developmental Areas</div>

    <div class="cg-legend">
        <span class="grade-badge grade-sky">SKY = Proficient</span>
        <span class="grade-badge grade-mountain">MOUNTAIN = Progressing</span>
        <span class="grade-badge grade-stream">STREAM = Beginner</span>
    </div>

    <table class="cg-report-table">
        <thead>
            <tr>
                <th>CG</th>
                <th>Indicator</th>
                <th colspan="2">Term 1</th>
                <th colspan="2">Term 2</th>
            </tr>
            <tr class="sub-header">
                <th></th><th></th>
                <th>T1 Grade</th><th>Level</th>
                <th>T2 Grade</th><th>Level</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($all_cgs as $cg_code => $cg_def):
                list($domain, $title, $indicators) = $cg_def;
                $first = true;
            ?>
            <tr class="cg-domain-row">
                <td colspan="6"><strong><?= $domain ?></strong> – <?= $title ?></td>
            </tr>
            <?php foreach ($indicators as $ind_key => $ind_label):
                $g1 = $grades['Term1'][$cg_code][$ind_key] ?? '';
                $g2 = $grades['Term2'][$cg_code][$ind_key] ?? '';
            ?>
            <tr>
                <?php if ($first): ?><td class="cg-code" rowspan="<?= count($indicators) ?>"><?= $cg_code ?></td><?php $first = false; endif; ?>
                <td class="indicator-text"><?= $ind_label ?></td>
                <td class="grade-cell-report"><span class="grade-badge <?= gradeClass($g1) ?>"><?= $g1 ?: '–' ?></span></td>
                <td class="grade-cell-report"><?= gradeIcon($g1) ?></td>
                <td class="grade-cell-report"><span class="grade-badge <?= gradeClass($g2) ?>"><?= $g2 ?: '–' ?></span></td>
                <td class="grade-cell-report"><?= gradeIcon($g2) ?></td>
            </tr>
            <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</div><!-- /.pc-wrapper -->

</body>
</html>
