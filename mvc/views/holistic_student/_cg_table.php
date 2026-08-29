<?php
// Helper function to render competency grade tables
function render_cg_tables($cg_groups, $grades, $gradeSelectFn) {
    foreach ($cg_groups as $cg_code => $cg):
?>
<div class="cg-block">
    <h3 class="cg-title"><?= $cg['title'] ?></h3>
    <table class="table cg-table">
        <thead>
            <tr>
                <th rowspan="2">Indicator</th>
                <th colspan="3">Term 1</th>
                <th colspan="3">Term 2</th>
            </tr>
            <tr>
                <th>Stream</th><th>Mountain</th><th>Sky</th>
                <th>Stream</th><th>Mountain</th><th>Sky</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cg['indicators'] as $ind_key => $ind_label):
                $g1 = $grades['Term1'][$cg_code][$ind_key] ?? '';
                $g2 = $grades['Term2'][$cg_code][$ind_key] ?? '';
            ?>
            <tr>
                <td class="indicator-cell"><?= $ind_label ?></td>
                <!-- Term1 radio buttons as column checkboxes -->
                <?php foreach (['Stream','Mountain','Sky'] as $level): ?>
                <td class="grade-cell">
                    <input type="radio" 
                           name="grades[Term1][<?= $cg_code ?>][<?= $ind_key ?>]" 
                           value="<?= $level ?>"
                           <?= ($g1 === $level) ? 'checked' : '' ?>
                           class="grade-radio">
                </td>
                <?php endforeach; ?>
                <!-- Term2 radio buttons -->
                <?php foreach (['Stream','Mountain','Sky'] as $level): ?>
                <td class="grade-cell">
                    <input type="radio" 
                           name="grades[Term2][<?= $cg_code ?>][<?= $ind_key ?>]" 
                           value="<?= $level ?>"
                           <?= ($g2 === $level) ? 'checked' : '' ?>
                           class="grade-radio">
                </td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php
    endforeach;
}
