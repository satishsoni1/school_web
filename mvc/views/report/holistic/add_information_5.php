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

               
               
               function lang_r1_competencies($lang_label) {
                   return [
                       "LANGUAGE R1 ({$lang_label}) - CG1 Develops oral language skills using complex sentence structures to understand and communicate ideas coherently - C1.1 Goals Converses fluently and meaningfully indifferent contexts",
                       "LANGUAGE R1 ({$lang_label}) - CG1 Develops oral language skills using complex sentence structures to understand and communicate ideas coherently - C1.2 Summarises core ideas from material read out in class",
                       "LANGUAGE R1 ({$lang_label}) - CG1 Develops oral language skills using complex sentence structures to understand and communicate ideas coherently - C1.3 Makes oral presentations (show and tell, short welcome notes, anchoring of small events, short speeches, class debates)",
                       "LANGUAGE R1 ({$lang_label}) - CG2 Develops the ability to read with comprehension by gaining a basic understanding of different forms of familiar and unfamiliar texts (such as prose and poetry) - C2.1 Applies varied comprehension strategies (inferring, predicting, visualising) to understand different texts",
                       "LANGUAGE R1 ({$lang_label}) - CG2 Develops the ability to read with comprehension by gaining a basic understanding of different forms of familiar and unfamiliar texts (such as prose and poetry) - C2.2 Understands main ideas and draws essential conclusions from the material read",
                       "LANGUAGE R1 ({$lang_label}) - CG3 Develops the ability to write simple and compound sentence structures to express their understanding and experiences - C3.1 Uses writing strategies, such as sequencing, identifying headings/sub-headings, the beginning, and ending, and forming paragraphs",
                       "LANGUAGE R1 ({$lang_label}) - CG3 Develops the ability to write simple and compound sentence structures to express their understanding and experiences - C3.2 Writes clear and coherent paragraphs that convey their understanding of a given topic/concept or on a reading of a text",
                       "LANGUAGE R1 ({$lang_label}) - CG3 Develops the ability to write simple and compound sentence structures to express their understanding and experiences - C3.3 Creates posters, invites, simple poems, stories, and dialogues with appropriate information and purpose",
                       "LANGUAGE R1 ({$lang_label}) - CG3 Develops the ability to write simple and compound sentence structures to express their understanding and experiences - C3.4 Uses appropriate grammar and structure in their writing",
                       "LANGUAGE R1 ({$lang_label}) - CG4 Acquires a more comprehensive range of words in various contexts (of home and school experience) through different sources - C4.1 Discusses meanings of words and develops vocabulary by listening to and reading a variety of texts",
                       "LANGUAGE R1 ({$lang_label}) - CG4 Acquires a more comprehensive range of words in various contexts (of home and school experience) through different sources - C4.2 Discusses meanings of words and develops vocabulary by listening to and reading a variety of texts or other content areas",
                       "LANGUAGE R1 ({$lang_label}) - CG5 Develops interest and preferences in reading - C5.1 Borrows books from the library regularly to read at home",
                       "LANGUAGE R1 ({$lang_label}) - CG5 Develops interest and preferences in reading - C5.2 Demonstrates interest in reading books from the library",
                   ];
               }
               
               function lang_r2_competencies($lang_label) {
                   return [
                       "LANGUAGE R2 ({$lang_label}) - CG1 Sustains effective communication skills for day-to-day interactions, enhancing their oral ability to express ideas - C1.1 Listens to poems, stories, and conversations and locates important ideas in them",
                       "LANGUAGE R2 ({$lang_label}) - CG1 Sustains effective communication skills for day-to-day interactions, enhancing their oral ability to express ideas - C1.2 Comprehends narrated/read out stories and identifies characters, storyline, and key aspects",
                       "LANGUAGE R2 ({$lang_label}) - CG1 Sustains effective communication skills for day-to-day interactions, enhancing their oral ability to express ideas - C1.3 Converses meaningfully and coherently",
                       "LANGUAGE R2 ({$lang_label}) - CG1 Sustains effective communication skills for day-to-day interactions, enhancing their oral ability to express ideas - C1.4 Makes oral presentations and participates in group discussions",
                       "LANGUAGE R2 ({$lang_label}) - CG2 Develops fluency in reading and the ability to read with comprehension - C2.1 Develops phonological awareness further by blending phonemes/syllables into words and segmenting words into phonemes/syllables",
                       "LANGUAGE R2 ({$lang_label}) - CG2 Develops fluency in reading and the ability to read with comprehension - C2.2 Examines the basic structure of the text and recognises words and sentences in print and basic punctuation marks",
                       "LANGUAGE R2 ({$lang_label}) - CG2 Develops fluency in reading and the ability to read with comprehension - C2.3 Reads stories and passages fluently and accurately with appropriate pauses",
                       "LANGUAGE R2 ({$lang_label}) - CG2 Develops fluency in reading and the ability to read with comprehension - C2.4 Comprehends the meaning of stories, poems, and story posters",
                       "LANGUAGE R2 ({$lang_label}) - CG2 Develops fluency in reading and the ability to read with comprehension - C2.5 Demonstrates interest in picking up and reading a variety of children's books",
                       "LANGUAGE R2 ({$lang_label}) - CG3 Develops the ability to express understanding, experiences, feelings, and ideas in writing - C3.1 Writes a paragraph to express understanding and experiences",
                       "LANGUAGE R2 ({$lang_label}) - CG3 Develops the ability to express understanding, experiences, feelings, and ideas in writing - C3.2 Creates simple posters, invites, and instructions with appropriate information and purpose",
                       "LANGUAGE R2 ({$lang_label}) - CG3 Develops the ability to express understanding, experiences, feelings, and ideas in writing - C3.3 Writes stories, poems, and conversations based on imagination and experiences",
                       "LANGUAGE R2 ({$lang_label}) - CG4 Develops a wide range of vocabulary in various contexts and through different sources - C4.1 Discusses meanings of words and develops vocabulary by listening to and reading a variety of texts or other content areas",
                   ];
               }
               
               function math_competencies() {
                   return [
                       "Mathematics - CG1 Understands numbers (counting numbers and fractions), represents whole numbers using the Indian place value system, understands and carries out the four basic operations with whole numbers, and discovers and recognises patterns in number sequences - C1.1 Represents numbers using the place value structure of the Indian number system, compares whole numbers, and knows and can read the names of very large numbers",
                       "Mathematics - CG1 Understands numbers (counting numbers and fractions), represents whole numbers using the Indian place value system, understands and carries out the four basic operations with whole numbers, and discovers and recognises patterns in number sequences - C1.2 Represents and compares commonly used fractions in daily life (such as ½, ¼, etc.) as parts of unit wholes, as locations on number lines, and as divisions of whole numbers",
                       "Mathematics - CG1 Understands numbers (counting numbers and fractions), represents whole numbers using the Indian place value system, understands and carries out the four basic operations with whole numbers, and discovers and recognises patterns in number sequences - C1.3 Understands and visualises arithmetic operations and the relationships among them, knows addition and multiplication tables at least up to 10x10 (pahade) and applies the four basic operations on whole numbers to solve daily life problems",
                       "Mathematics - CG1 Understands numbers (counting numbers and fractions), represents whole numbers using the Indian place value system, understands and carries out the four basic operations with whole numbers, and discovers and recognises patterns in number sequences - C1.4 Recognises, describes, and extends simple number patterns such as odd numbers, even numbers, square numbers, cubes, powers of 2, powers of 10, and Virahanka-Fibonacci numbers",
                       "Mathematics - CG2 Analyses the characteristics and properties of two and three-dimensional geometric shapes, specifies locations and describes spatial relationships, and recognises and creates shapes that have symmetry - C2.1 Identifies, compares, and analyses attributes of two- and three-dimensional shapes and develops vocabulary to describe their attributes/properties",
                       "Mathematics - CG2 Analyses the characteristics and properties of two and three-dimensional geometric shapes, specifies locations and describes spatial relationships, and recognises and creates shapes that have symmetry - C2.2 Describes location and movement using both common language and mathematical vocabulary; understands the notion of map (najri naksha)",
                       "Mathematics - CG2 Analyses the characteristics and properties of two and three-dimensional geometric shapes, specifies locations and describes spatial relationships, and recognises and creates shapes that have symmetry - C2.3 Recognises and creates symmetry (reflection, rotation) in familiar 2D and 3D shapes",
                       "Mathematics - CG2 Analyses the characteristics and properties of two and three-dimensional geometric shapes, specifies locations and describes spatial relationships, and recognises and creates shapes that have symmetry - C2.4 Discovers, recognises, describes, and extends patterns in 2D and 3D shapes",
                       "Mathematics - CG3 Understands measurable attributes of objects and the units, systems, and processes of such measurement, including those related to distance, length, weight, area, volume, and time using non-standard and standard units - C3.1 Measures in non-standard and standard units and evaluates the need for standard units",
                       "Mathematics - CG3 Understands measurable attributes of objects and the units, systems, and processes of such measurement, including those related to distance, length, weight, area, volume, and time using non-standard and standard units - C3.2 Uses an appropriate unit and tool for the attribute (like length, perimeter, time, weight, volume) being measured",
                       "Mathematics - CG3 Understands measurable attributes of objects and the units, systems, and processes of such measurement, including those related to distance, length, weight, area, volume, and time using non-standard and standard units - C3.3 Carries out simple unit conversions, such as from centimetres to metres, within a system of measurement",
                       "Mathematics - CG3 Understands measurable attributes of objects and the units, systems, and processes of such measurement, including those related to distance, length, weight, area, volume, and time using non-standard and standard units - C3.4 Understands the definition and formula for the area of a square or rectangle as length times breadth",
                       "Mathematics - CG3 Understands measurable attributes of objects and the units, systems, and processes of such measurement, including those related to distance, length, weight, area, volume, and time using non-standard and standard units - C3.5 Devises strategies for estimating the distance, length, time, perimeter (for regular and irregular shapes), area (for regular and irregular shapes), weight and volume and verifies the same using standard units",
                       "Mathematics - CG3 Understands measurable attributes of objects and the units, systems, and processes of such measurement, including those related to distance, length, weight, area, volume, and time using non-standard and standard units - C3.6 Deduces that shapes having equal areas can have different perimeters and shapes having equal perimeters can have different areas",
                       "Mathematics - CG3 Understands measurable attributes of objects and the units, systems, and processes of such measurement, including those related to distance, length, weight, area, volume, and time using non-standard and standard units - C3.7 Evaluates the conservation of attributes like length and volume and solves daily-life problems related to them",
                       "Mathematics - CG4 Develops problem-solving skills with procedural fluency to solve mathematical puzzles as well as daily-life problems, and as a step towards developing computational thinking - C4.1 Solves puzzles and daily-life problems involving one or more operations on whole numbers (including word puzzles and puzzles from 'recreational' areas, such as the construction of magic squares)",
                       "Mathematics - CG4 Develops problem-solving skills with procedural fluency to solve mathematical puzzles as well as daily-life problems, and as a step towards developing computational thinking - C4.2 Learns to systematically count and list all possible permutations or combination given a constraint, in simple situations (e.g., how to make a committee of two people from a group of five people)",
                       "Mathematics - CG4 Develops problem-solving skills with procedural fluency to solve mathematical puzzles as well as daily-life problems, and as a step towards developing computational thinking - C4.3 Selects appropriate methods and tools for computing with whole numbers, such as mental computation, estimation, or paper pencil calculation, in accordance with the context",
                       "Mathematics - CG5 Knows and appreciates the development in India of the decimal place value system that is used around the world today - C5.1 Understand the development of zero in India and the Indian place value system for writing numerals, the history of its transmission to the world, and its modern impact on our lives and in all technology",
                   ];
               }
               
               function world_competencies() {
                   return [
                       "The World Around Us - CG1 Explores and engages with the natural and socio-cultural environment in their surroundings - C1.1 Observes and identifies the natural (insects, plants, birds, animals, geographical features, sun and moon, stars, planets, natural resources) and social (houses, relationships) components in their immediate environment",
                       "The World Around Us - CG1 Explores and engages with the natural and socio-cultural environment in their surroundings - C1.2 Describes relationships (including between humans and animals/nature) and traditions (art forms, celebrations, festivals) in the family and community",
                       "The World Around Us - CG1 Explores and engages with the natural and socio-cultural environment in their surroundings - C1.3 Asks questions and makes predictions about simple patterns (season change, food chain, phases of the moon, movement of stars and planets, shapes of trees, plants, leaves, and flowers, rituals, celebrations) observed in the immediate environment",
                       "The World Around Us - CG1 Explores and engages with the natural and socio-cultural environment in their surroundings - C1.4 Explains the functioning of local institutions (family, school, bank/post office, market, and panchayat) in different forms (story, drawing, tabulating data, reports), and analyses their roles",
                       "The World Around Us - CG1 Explores and engages with the natural and socio-cultural environment in their surroundings - C1.5 Uses local materials to create simple objects (family tree, envelopes, origami animals) on their own for display or use in classroom processes",
                       "The World Around Us - CG2 Understands the interdependence in their environment through observation and experiences, developing the basis for appreciation of the idea of 'Vasudhaiva Kutumbakam' - C2.1 Identifies natural and humanmade systems that support their lives (water supply, water cycle, river flow systems, seasons, life cycle of plants and animals, food, household items, transport, communication, electricity in the home)",
                       "The World Around Us - CG2 Understands the interdependence in their environment through observation and experiences, developing the basis for appreciation of the idea of 'Vasudhaiva Kutumbakam' - C2.2 Describes the relationship between the natural environment and cultural practices in immediate environment (nature of work, food, festivals, traditions)",
                       "The World Around Us - CG2 Understands the interdependence in their environment through observation and experiences, developing the basis for appreciation of the idea of 'Vasudhaiva Kutumbakam' - C2.3 Connects changes in the environment and the lives of family and community, as communicated by elders and through local stories (changes in occupation, food habits, resources, celebrations, communication)",
                       "The World Around Us - CG3 Explains how to ensure the safety of self and others in different (normal as well as emergency) situations - C3.1 Describes the basic safety needs and protection (health and hygiene, food, water, shelter, precautions, awareness of emergency situations, abuse, and unsafe situations) of humans, birds, and animals",
                       "The World Around Us - CG3 Explains how to ensure the safety of self and others in different (normal as well as emergency) situations - C3.2 Discusses how to prepare for emergency situations (smoke, fire, small injuries, burns, electrical safety, unseasonal rains, fallen trees) based on discussions with family and community, or personal experiences",
                       "The World Around Us - CG3 Explains how to ensure the safety of self and others in different (normal as well as emergency) situations - C3.3 Develops simple labels and slogans, and participates in roleplay on safety and protection in the local environment to be displayed/done in school and locality",
                       "The World Around Us - CG4 Develops sensitivity towards social and natural environment - C4.1 Observes and describes diversity among plants, and birds and animals in immediate environment (shape, sounds, food habits, growth, habitat)",
                       "The World Around Us - CG4 Develops sensitivity towards social and natural environment - C4.2 Observes and describes cultural diversity in their immediate environment (food, clothing, games, different seasons, festivals related to harvest and sowing)",
                       "The World Around Us - CG4 Develops sensitivity towards social and natural environment - C4.3 Describes usage of natural resources in their immediate environment",
                       "The World Around Us - CG4 Develops sensitivity towards social and natural environment - C4.4 Demonstrates how natural resources can be shared, maintained, and conserved (trees, use of rainwater, benefits of millets)",
                       "The World Around Us - CG4 Develops sensitivity towards social and natural environment - C4.5 Identifies needs of plants, birds, and animals, and how they can be supported (water, soil, food, care)",
                       "The World Around Us - CG4 Develops sensitivity towards social and natural environment - C4.6 Identifies the needs of people in different situations – in terms of access to resources, equal opportunities, work distribution, and shelter",
                       "The World Around Us - CG4 Develops sensitivity towards social and natural environment - C4.7 Learns about basic social and behavioural norms, values, and dispositions that benefit our social and natural environments and that help our society function smoothly (using dustbins, standing in queues, conserving water, using public transportation, keeping one's environment clean, always helping others in need regardless of background)",
                       "The World Around Us - CG5 Develops the ability to read and interpret simple maps - C5.1 Explains a line drawing of their school, village, and ward",
                       "The World Around Us - CG5 Develops the ability to read and interpret simple maps - C5.2 Draws a sketch of their school, village, and ward using symbols and directions",
                       "The World Around Us - CG5 Develops the ability to read and interpret simple maps - C5.3 Reads simple maps of city, state, and country to identify natural and humanmade features (well, lake, post office, school, hospital) with reference to symbols and directions",
                       "The World Around Us - CG6 Uses data and information from various sources to investigate questions related to their immediate environment - C6.1 Performs simple inquiry related to specific questions independently or in groups",
                       "The World Around Us - CG6 Uses data and information from various sources to investigate questions related to their immediate environment - C6.2 Presents observations and findings through different creative modes (drawing, diagram, poem, play, skit, oral and written expression)",
                       "The World Around Us - CG7 Gains foundational familiarity with basic concepts and methods from the natural sciences (life sciences, physical sciences, and earth and space) - C7.1 Gains familiarity with using the scientific method in investigations, as well as familiarity with other crosscutting concepts such as energy, matter, and systems that apply across the domains of science and engineering",
                       "The World Around Us - CG7 Gains foundational familiarity with basic concepts and methods from the natural sciences (life sciences, physical sciences, and earth and space) - C7.2 Gains familiarity with disciplinary core ideas in the natural sciences, as well as in engineering, technology, and applications of science, which reflect the content that will be learned across subject areas in later Grades",
                   ];
               }
               
               function art_competencies() {
                   return [
                       "Art Education - CG1 Develops an enjoyment of the Arts and exercises their creativity and imagination in Visual and Performing Arts activities - C1.1 Creates and presents a variety of artwork to communicate their ideas and emotions in any of the Visual and Performing Art forms (emphasis on variety in Music, painting, drawing, crafts, Drama, Dance and Movement, and local Art forms)",
                       "Art Education - CG1 Develops an enjoyment of the Arts and exercises their creativity and imagination in Visual and Performing Arts activities - C1.2 Describes the varied materials, tools, and processes used in the Visual and Performing Arts and demonstrates familiarity with some of these in their own artwork [e.g., identifies and names some musical instruments and demonstrates simple beats on a dholak, khanjira, bells, utensils, or one's own body (clapping, tapping, making different sounds using mouth and voice)]",
                       "Art Education - CG1 Develops an enjoyment of the Arts and exercises their creativity and imagination in Visual and Performing Arts activities - C1.3 Creates artworks collaboratively and shares own thoughts and feelings while responding to arts and culture in their surroundings",
                   ];
               }
               
               function pe_competencies() {
                   return [
                       "Physical Education - CG1 Demonstrates the use of basic skills (running, jumping, catching, throwing, hitting, and kicking) to participate in different physical activities / games / sports - C1.1 Practices a combination of movement, motor skills, and manipulative skills (catching, throwing, kicking, hitting a ball towards a target while moving, focusing on visual cues to hit the target)",
                       "Physical Education - CG1 Demonstrates the use of basic skills (running, jumping, catching, throwing, hitting, and kicking) to participate in different physical activities / games / sports - C1.2 Moves purposefully their body to a beat / rhythm / music",
                       "Physical Education - CG1 Demonstrates the use of basic skills (running, jumping, catching, throwing, hitting, and kicking) to participate in different physical activities / games / sports - C1.3 Demonstrates coordination abilities with a partner and objects (e.g., being able to move in coordination with a partner in three-legged race, hand-eye coordination while bowling, throwing)",
                       "Physical Education - CG1 Demonstrates the use of basic skills (running, jumping, catching, throwing, hitting, and kicking) to participate in different physical activities / games / sports - C1.4 Demonstrates basic warm-up exercises and stretching to develop strength and flexibility in the body",
                       "Physical Education - CG2 Develops an awareness of their personal and social behaviour towards themselves and others - C2.1 Demonstrates the ability to play games and activities which require and emphasise teamwork, cooperation, personal responsibility, and communication of ideas",
                       "Physical Education - CG2 Develops an awareness of their personal and social behaviour towards themselves and others - C2.2 Creates group norms and rules of the game/activity before playing and reviews these regularly",
                       "Physical Education - CG2 Develops an awareness of their personal and social behaviour towards themselves and others - C2.3 Exhibits sensitivity to injuries of others and acts empathetically when the other player is physically injured, emotionally stressed, and feeling unwell",
                       "Physical Education - CG2 Develops an awareness of their personal and social behaviour towards themselves and others - C2.4 Practices care and responsibility towards the physical activity material, playground, and facilities",
                       "Physical Education - CG2 Develops an awareness of their personal and social behaviour towards themselves and others - C2.5 Identifies characteristics of safe/unsafe touch in the context of physical activity and describes ways of reporting them",
                       "Physical Education - CG3 Demonstrates mental engagement in physical activity / game situations - C3.1 Explains the concept of some games, their rules, playing positions, and basic moves",
                       "Physical Education - CG3 Demonstrates mental engagement in physical activity / game situations - C3.2 Expresses their emotions and thinking process during the game",
                       "Physical Education - CG4 Develops an understanding of the need to develop themselves and self-assess their progress - C4.1 Sets simple personal goals/targets and records progress (e.g., throwing a ball at 25 m, then 30 m, then 40 m; Jumping 1, 2, 3 feet high/long)",
                   ];
               }
               
               function sel_competencies() {
                   return [
                       "Socio-Emotional Ethical Learning - Has emotional regulation and is able to respond appropriately to various situations",
                       "Socio-Emotional Ethical Learning - Displays empathy for all living beings and the environment",
                       "Socio-Emotional Ethical Learning - Is inclusive and kind in approach towards all cultural, social, religious and ethnic identities",
                       "Socio-Emotional Ethical Learning - Has resilience and displays grit",
                       "Socio-Emotional Ethical Learning - Understands ethical implications in all situations",
                   ];
               }
               
               function plh_competencies() {
                   return [
                       "Positive Learning Habits - Has the mental flexibility to sustain as well as shift attention appropriately",
                       "Positive Learning Habits - Asks interesting and relevant questions",
                       "Positive Learning Habits - Can articulate one's opinions in a coherent and focused manner",
                       "Positive Learning Habits - Has growth mindset-seeks help actively",
                       "Positive Learning Habits - Is able to reflect on work done and take suitable action",
                       "Positive Learning Habits - Follows classroom norms with agency and understanding",
                       "Positive Learning Habits - Has self-control that enables learning in structured",
                   ];
               }
               
               // ─────────────────────────────────────────────────────────────────────────────
               // BUILDER: returns flat ordered competency list for a given class
               // ─────────────────────────────────────────────────────────────────────────────
               function build_comp_list_for_class($class_id) {
                   // classesID 6, 15       → R1-English  +  R2-Hindi  +  R2-Marathi
                   // classesID 7,8,16,20   → R1-English  +  R2-Hindi  +  R2-Marathi  +  R2-Sanskrit
               
                   $three_lang_classes = [6, 15];
                   $four_lang_classes  = [7, 8, 16, 20];
               
                   $raw = [];
               
                   // ── Languages ────────────────────────────────
                   $raw = array_merge($raw, lang_r1_competencies('English'));   // R1 English (always)
                   $raw = array_merge($raw, lang_r2_competencies('Hindi'));     // R2 Hindi   (always)
                   $raw = array_merge($raw, lang_r2_competencies('Marathi'));   // R2 Marathi (always)
               
                   if (in_array($class_id, $four_lang_classes)) {
                       $raw = array_merge($raw, lang_r2_competencies('Sanskrit')); // R2 Sanskrit (4-lang only)
                   }
               
                   // ── Other subjects (same for all classes) ────
                   $raw = array_merge($raw, math_competencies());
                   $raw = array_merge($raw, world_competencies());
                   $raw = array_merge($raw, art_competencies());
                   $raw = array_merge($raw, pe_competencies());
                   $raw = array_merge($raw, sel_competencies());
                   $raw = array_merge($raw, plh_competencies());
               
                   // Auto-index from 1
                   $comp_list = [];
                   $idx = 1;
                   foreach ($raw as $text) {
                       $comp_list[] = [$idx++, $text];
                   }
                
                   return $comp_list;
               }
               
               // ─────────────────────────────────────────────────────────────────────────────
               // GENERATE PER-CLASS ARRAYS
               // ─────────────────────────────────────────────────────────────────────────────
               
            //    $all_class_ids = [6, 7, 8, 15, 16, 20];
               
            //    foreach ($all_class_ids as $class_id) {
            //        $comp_list = build_comp_list_for_class($class_id);
               
            //        $lang_type = in_array($class_id, [6, 15]) ? '3-language' : '4-language';
            //        echo "=== Class ID: {$class_id} ({$lang_type}) | Total competencies: " . count($comp_list) . " ===\n";
            //        echo "  [1]  " . $comp_list[0][1] . "\n";
               
            //        // Show where each language section starts
            //        foreach ($comp_list as $entry) {
            //            if (strpos($entry[1], 'LANGUAGE') !== false && strpos($entry[1], 'C1.1') !== false) {
            //                echo "  [{$entry[0]}]  " . $entry[1] . "\n";
            //            }
            //        }
            //        echo "  [" . end($comp_list)[0] . "]  " . end($comp_list)[1] . "\n\n";
            //    }
               
               // ─────────────────────────────────────────────────────────────────────────────
               // USAGE EXAMPLE: get array for a specific class
               // ─────────────────────────────────────────────────────────────────────────────
               $comp_list = build_comp_list_for_class($classesID); 
               // $comp_list = build_comp_list_for_class(6);   // 3-lang: English + Hindi + Marathi
               // $comp_list = build_comp_list_for_class(7);   // 4-lang: English + Hindi + Marathi + Sanskrit
               // $comp_list = build_comp_list_for_class(15);  // 3-lang: English + Hindi + Marathi
               // $comp_list = build_comp_list_for_class(8);   // 4-lang: English + Hindi + Marathi + Sanskrit
               // $comp_list = build_comp_list_for_class(16);  // 4-lang: English + Hindi + Marathi + Sanskrit
               // $comp_list = build_comp_list_for_class(20);  // 4-lang: English + Hindi + Marathi + Sanskrit
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