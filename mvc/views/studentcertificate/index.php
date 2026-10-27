<?php
$printedID = (int) $this->input->get('printed');
$tabUrl = function ($key) {
    return base_url('studentcertificate/index' . ($key ? '?type=' . $key : '')) . '#saved';
};
?>
<style>
    .sc-cards { display: flex; flex-wrap: wrap; margin: 0 -8px; }
    .sc-card-wrap { padding: 0 8px; margin-bottom: 16px; display: flex; }
    .sc-card { flex: 1; display: flex; flex-direction: column; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; background: #fff; transition: box-shadow .15s, border-color .15s; }
    .sc-card:hover { border-color: #3c8d7f; box-shadow: 0 4px 14px rgba(15, 23, 42, .08); }
    .sc-card .sc-icon { width: 42px; height: 42px; border-radius: 50%; background: #e6f2ef; color: #2f7d6d; display: flex; align-items: center; justify-content: center; font-size: 18px; margin-bottom: 10px; }
    .sc-card h4 { margin: 0 0 6px; font-size: 15px; font-weight: 600; color: #0f172a; }
    .sc-card p { flex: 1; margin: 0 0 14px; color: #64748b; font-size: 13px; line-height: 1.45; }
    .sc-card .sc-foot { display: flex; align-items: center; justify-content: space-between; }
    .sc-card .sc-count { color: #64748b; font-size: 12px; }
    .sc-section { margin: 4px 0 12px; font-size: 13px; font-weight: 600; letter-spacing: .04em; text-transform: uppercase; color: #64748b; }
    .sc-tabs { margin-bottom: 14px; }
    .sc-tabs > li > a { padding: 6px 12px; }
    .sc-tabs .badge { margin-left: 4px; background: #cbd5e1; color: #0f172a; }
    .sc-tabs > li.active .badge { background: #fff; color: #2f7d6d; }
    .sc-ref { font-family: Menlo, Consolas, monospace; font-size: 12.5px; white-space: nowrap; }
    .sc-type { display: inline-block; padding: 2px 8px; border-radius: 10px; background: #e6f2ef; color: #2f7d6d; font-size: 12px; white-space: nowrap; }
    .sc-empty { padding: 30px 10px; text-align: center; color: #64748b; border: 1px dashed #cbd5e1; border-radius: 8px; }
    .sc-actions { white-space: nowrap; }
</style>

<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-certificate"></i> Certificates</h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active">Certificates</li>
        </ol>
    </div>

    <div class="box-body">
        <?php if ($printedID) { ?>
            <div class="alert alert-success">
                <i class="fa fa-check"></i> Certificate saved.
                <a href="<?=base_url('studentcertificate/view/' . $printedID)?>" target="_blank" class="btn btn-default btn-xs" style="margin-left:8px;"><i class="fa fa-print"></i> Print now</a>
            </div>
        <?php } ?>

        <div class="sc-section">Generate a certificate</div>
        <div class="sc-cards">
            <?php foreach ($types as $key => $t) { $n = isset($counts[$key]) ? $counts[$key] : 0; ?>
                <div class="sc-card-wrap col-lg-3 col-sm-6 col-xs-12">
                    <div class="sc-card">
                        <div class="sc-icon"><i class="fa <?=isset($t['icon']) ? $t['icon'] : 'fa-certificate'?>"></i></div>
                        <h4><?=html_escape($t['label'])?></h4>
                        <p><?=isset($t['desc']) ? html_escape($t['desc']) : ''?></p>
                        <div class="sc-foot">
                            <a class="sc-count" href="<?=$tabUrl($key)?>"><?=$n?> saved</a>
                            <a href="<?=base_url('studentcertificate/add/' . $key)?>" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Generate</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="sc-section" id="saved" style="margin-top:8px;">Saved certificates</div>
        <ul class="nav nav-pills sc-tabs">
            <li class="<?=$type === '' ? 'active' : ''?>"><a href="<?=$tabUrl('')?>">All <span class="badge"><?=$total?></span></a></li>
            <?php foreach ($types as $key => $t) { ?>
                <li class="<?=$type === $key ? 'active' : ''?>"><a href="<?=$tabUrl($key)?>"><?=html_escape($t['label'])?> <span class="badge"><?=isset($counts[$key]) ? $counts[$key] : 0?></span></a></li>
            <?php } ?>
        </ul>

        <?php if (!$certificates) { ?>
            <div class="sc-empty">
                <i class="fa fa-file-text-o" style="font-size:28px;"></i><br>
                No certificates <?=$type ? 'of this type ' : ''?>yet. Use <b>Generate</b> on a card above to create one.
            </div>
        <?php } else { ?>
        <div id="hide-table">
            <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                <thead>
                    <tr>
                        <th>Ref. No.</th>
                        <th>Date</th>
                        <?php if (!$type) { ?><th>Type</th><?php } ?>
                        <th>Student</th>
                        <th>Details</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($certificates as $c) {
                    $label = isset($types[$c->type]) ? $types[$c->type]['label'] : $c->type;
                    $d = $c->data;
                    $get = function ($k) use ($d) { return html_escape(isset($d[$k]) ? $d[$k] : ''); };
                ?>
                    <tr>
                        <td data-order="<?=html_escape($c->ref_year) . sprintf('%06d', $c->ref_no)?>"><span class="sc-ref">PPGMIS/<?=(int) $c->ref_no?>/<?=html_escape($c->ref_year)?></span></td>
                        <td data-order="<?=$c->cert_date?>" style="white-space:nowrap;"><?=date('d/m/Y', strtotime($c->cert_date))?></td>
                        <?php if (!$type) { ?><td><span class="sc-type"><?=html_escape($label)?></span></td><?php } ?>
                        <td><b><?=html_escape($c->student_name)?></b></td>
                        <td class="text-muted">
                            <?php if ($c->type === 'lc_request') { ?>
                                <?=$get('grade')?> · To: <?=$get('to_school')?>
                            <?php } elseif (strpos($c->type, 'bonafide_') === 0) { ?>
                                <?=$get('grade')?> · <?=$get('academic_year')?>
                            <?php } elseif ($c->type === 'fee_certificate') { ?>
                                <?=$get('grade')?> · <?=$get('academic_year')?> · &#8377;<?=$get('fee_amount')?>
                            <?php } ?>
                        </td>
                        <td class="sc-actions">
                            <a class="btn btn-success btn-xs" href="<?=base_url('studentcertificate/view/' . $c->id)?>" target="_blank" title="Print"><i class="fa fa-print"></i> Print</a>
                            <a class="btn btn-warning btn-xs" href="<?=base_url('studentcertificate/edit/' . $c->id)?>" title="Edit"><i class="fa fa-edit"></i></a>
                            <a class="btn btn-danger btn-xs" href="<?=base_url('studentcertificate/delete/' . $c->id)?>" onclick="return confirm('Delete this certificate (Ref. <?=(int) $c->ref_no?>)?');" title="Delete"><i class="fa fa-trash-o"></i></a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <?php } ?>
    </div>
</div>
