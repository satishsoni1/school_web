<style type="text/css">
    .mainmeritstagereport {
        margin: 0px;
        overflow: hidden;
        border: 1px solid #ddd;
        max-width: 794px;
        margin: 0px auto;
        padding: 12px;
    }

    .terminal-headers {
        border-bottom: 1px solid #ddd;
        overflow: hidden;
        padding-bottom: 10px;
        vertical-align: middle;
        margin-bottom: 15px;
    }

    .terminal-logo {
        float: left;
    }

    .terminal-headers img {
        width: 60px;
        height: 60px;
    }

    .school-name h2 {
        padding-top: 7px;
        padding-left: 20px;
        font-weight: bold;
        float: left;
    }

    .terminal-infos {
        width: 100%;
        overflow: hidden;
    }

    .terminal-infos h5 {
        font-weight: bold;
    }

    .school_address {
        width: 40%;
        float: left;
    }

    .mandatory_subjects {
        width: 30%;
        float: left;
        padding-left: 15px;
    }

    .optinal_subjects {
        width: 30%;
        float: left;
        padding-left: 15px;
    }

    table {
        width: 100%;
    }

    table tr,
    table td,
    table th {
        border: 1px solid #ddd;
        padding: 3px;
        text-align: center;
    }

    .school_info p {
        margin: 1px;
        font-size: 14px;
    }

    .merit_info {
        margin-top: 15px;
    }

    .merit_info p {
        margin: 1px;
        font-size: 14px;
    }

    .school_info h3,
    .merit_info h3,
    .caption_table {
        font-weight: bold;
        line-height: 18px;
        margin: 5px 0px;
        font-size: 18px;
    }

    .terminal-contents {
        width: 100%;
        overflow: hidden;
        margin-top: 12px;
    }

    .terminal-contents table {
        width: 100%;
    }

    .terminal-contents table tr,
    .terminal-contents table td,
    .terminal-contents table th {
        border: 1px solid #ddd;
        padding: 4px;
        text-align: center;
        font-size: 12px;
    }

    @media print {
        .mainmeritstagereport {
            border: 0px solid #ddd;
            padding: 0px 20px;
        }
    }

    @media screen and (max-width: 480px) {

        .school_address {
            width: 100%;
        }

        .mandatory_subjects {
            width: 100%;
            padding-left: 0px;
            margin-top: 10px;
        }

        .optinal_subjects {
            width: 100%;
            padding-left: 0px;
            margin-top: 10px;
        }

        table tr,
        table td,
        table th {
            border: 1px solid #ddd;
            padding: 3px;
            text-align: center;
        }

        .school_info h3,
        .merit_info h3,
        .caption_table {
            text-align: left;
        }

        .school-name h2 {
            padding-left: 0px;
            float: none;
        }
    }
</style>
<table>
    <thead>
        <tr>
            <th rowspan="2"><?= $this->lang->line('meritstagereport_slno') ?></th>
            <th rowspan="2"><?= $this->lang->line('meritstagereport_name') ?></th>
            <th rowspan="2">Class</th>
            <th rowspan="2"><?= $this->lang->line('meritstagereport_position') ?></th>
            <th rowspan="2"><?= $this->lang->line('meritstagereport_total_marks') ?></th>
            <th colspan="<?= $mandatory_column ?>"><?= $this->lang->line('meritstagereport_mandatory_subjects') ?></th>

            <?php if ($optionalSubjectStatus) { ?>
                <th colspan="<?= $optional_column ?>"><?= $this->lang->line('meritstagereport_optional_subjects') ?></th>
            <?php } ?>
        </tr>
        <tr>
            <?php if (customCompute($subjects)) {
                foreach ($subjects as $subject) {
                    if ($subject->type == 1) { ?>
                        <th><?= substr($subject->subject, 0, 3) ?></th>
            <?php }
                }
            } ?>
            <?php if ($optionalSubjectStatus) { ?>
                <?php if (customCompute($subjects)) {
                    foreach ($subjects as $subject) {
                        if ($subject->type != 1) { ?>
                            <th><?= substr($subject->subject, 0, 3) ?></th>
                <?php }
                    }
                } ?>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php $i = 0;
        if (customCompute($studentPosition['studentClassPositionArray'])) {
            foreach ($studentPosition['studentClassPositionArray'] as $studentID => $student) {
                if (isset($studentLists[$studentID])) {
                    $i++;
        ?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><?= $studentLists[$studentID]->srname ?></td>
                        <td><?= $studentLists[$studentID]->srclasses ?></td>
                        <td>
                            <?php
                            if (isset($studentPosition['studentClassPositionArray'][$studentID])) {
                                echo addOrdinalNumberSuffix((int)array_search($studentID, array_keys($studentPosition['studentClassPositionArray'])) + 1);
                            }
                            ?>
                        </td>
                        <td><?= isset($studentPosition[$studentID]['totalSubjectMark']) ? ini_round($studentPosition[$studentID]['totalSubjectMark']) : 0 ?></td>
                        <?php if (customCompute($subjects)) {
                            foreach ($subjects as $subject) {
                                if ($subject->type == 1) { ?>
                                    <td><?= isset($studentPosition[$studentID]['subjectMark'][$subject->subjectID]) ? $studentPosition[$studentID]['subjectMark'][$subject->subjectID] : '0' ?></td>
                        <?php }
                            }
                        } ?>

                        <?php if ($optionalSubjectStatus) { ?>
                            <?php if (customCompute($subjects)) {
                                foreach ($subjects as $subject) {
                                    if ($subject->type != 1) { ?>
                                        <td>
                                            <?php
                                            if ($studentLists[$studentID]->sroptionalsubjectID == $subject->subjectID) {
                                                echo isset($studentPosition[$studentID]['subjectMark'][$subject->subjectID]) ? $studentPosition[$studentID]['subjectMark'][$subject->subjectID] : 0;
                                            }
                                            ?>
                                        </td>
                            <?php }
                                }
                            } ?>
                        <?php } ?>
                    </tr>
            <?php }
            }
        } else { ?>
            <tr>
                <td style="font-weight: bold" colspan="<?= ($mandatory_column + $optional_column + 7) ?>"><?= $this->lang->line('meritstagereport_data_not_found') ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>