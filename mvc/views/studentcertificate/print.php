<?php
$e = function ($v) {
    return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
};
$logo = !empty($siteinfos->photo) ? base_url('uploads/images/' . $siteinfos->photo) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?=$e($type['label'])?> — <?=$e($cert->student_name)?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
<style>
    @page { size: A4; margin: 0; }
    * { box-sizing: border-box; }
    body { margin: 0; background: #e5e7eb; font-family: "Times New Roman", Times, serif; color: #111; }
    .toolbar { text-align: center; padding: 12px; font-family: Arial, sans-serif; font-size: 13px; }
    .toolbar button, .toolbar a { margin: 0 4px; padding: 6px 14px; border: 1px solid #9ca3af; border-radius: 4px; background: #fff; color: #111; text-decoration: none; cursor: pointer; }
    .toolbar button { background: #16a34a; border-color: #16a34a; color: #fff; }
    .page { position: relative; display: flex; flex-direction: column; width: 210mm; min-height: 297mm; margin: 0 auto 20px; padding: 10mm 14mm 8mm; background: #fff; box-shadow: 0 1px 6px rgba(0,0,0,.2); overflow: hidden; }
    .page > * { position: relative; z-index: 1; }
    .watermark { position: absolute !important; z-index: 0 !important; left: 50%; top: 52%; width: 120mm; transform: translate(-50%, -50%); opacity: .08; }
    .head { display: flex; align-items: center; gap: 14px; padding-bottom: 10px; font-family: Poppins, Arial, sans-serif; }
    .head .logo { flex: 0 0 auto; width: 25mm; height: 25mm; object-fit: contain; }
    .head .school { flex: 1; min-width: 0; }
    .head .trust { color: #d4561c; font-size: 13px; font-weight: 500; letter-spacing: .2px; }
    .head h1 { margin: 1px 0 0; color: #3b2f7a; font-size: 25px; font-weight: 700; line-height: 1.12; letter-spacing: .3px; }
    .head h1 span { display: block; font-size: 21.5px; letter-spacing: 1.6px; }
    .head .codes { flex: 0 0 auto; padding-left: 12px; border-left: 2px solid #d4561c; font-size: 11px; line-height: 1.7; color: #334155; white-space: nowrap; }
    .head .codes b { color: #111; font-weight: 600; }
    .head-rule { height: 4px; margin-bottom: 2px; background: #3b2f7a; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .head-rule + .head-rule { height: 1.5px; background: #d4561c; }
    .plain-space { height: 40mm; }
    .refline { display: flex; justify-content: space-between; font-family: Arial, sans-serif; font-weight: bold; font-size: 14px; margin: 10px 0 30px; }
    .photo-box { align-self: flex-end; width: 32mm; height: 38mm; margin: -18px 6mm 0 0; border: 1px solid #999; display: flex; align-items: center; justify-content: center; color: #999; font-family: Arial, sans-serif; font-size: 12px; text-align: center; }
    .photo-box img { width: 100%; height: 100%; object-fit: cover; }
    .foot { margin: auto -14mm -8mm; padding: 6px 14mm; background: #4c3f86; color: #fff; text-align: center; font-family: Arial, sans-serif; font-size: 12.5px; line-height: 1.5; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .body { font-size: 16px; line-height: 2; }
    .body p { margin: 0 0 14px; }
    .fill { font-weight: bold; border-bottom: 1px solid #111; padding: 0 6px; }
    .subject { font-weight: bold; margin: 28px 0 22px !important; }
    .sign { margin-top: 48px; }
    .sign .space { height: 60px; }
    .sign b { display: block; font-family: Arial, sans-serif; line-height: 1.3; }
    @media print {
        body { background: #fff; }
        .toolbar { display: none; }
        .page { width: 210mm; height: 297mm; min-height: 0; margin: 0; box-shadow: none; }
    }
</style>
</head>
<body>
<div class="toolbar">
    <button type="button" onclick="window.print()">Print</button>
    <?php if ($plain) { ?>
        <a href="<?=base_url('studentcertificate/view/' . $cert->id)?>">With letterhead</a>
    <?php } else { ?>
        <a href="<?=base_url('studentcertificate/view/' . $cert->id . '?plain=1')?>">Without letterhead (pre-printed paper)</a>
    <?php } ?>
    <a href="<?=base_url('studentcertificate/edit/' . $cert->id)?>">Edit</a>
</div>

<div class="page">
    <?php if ($plain) { ?>
        <div class="plain-space"></div>
    <?php } else { ?>
        <?php if ($logo) { ?><img class="watermark" src="<?=$e($logo)?>" alt=""><?php } ?>
        <div class="head">
            <?php if ($logo) { ?><img class="logo" src="<?=$e($logo)?>" alt=""><?php } ?>
            <div class="school">
                <div class="trust">Khalapur Taluka Shikshan Prasarak Mandal's</div>
                <h1>P.P. GAGANGIRI MAHARAJ <span>INTERNATIONAL SCHOOL</span></h1>
            </div>
            <div class="codes">
                CBSE Affiliation: <b>1131395</b><br>
                School Code: <b>31384</b><br>
                UDISE No.: <b>27240312802</b>
            </div>
        </div>
        <div class="head-rule"></div>
        <div class="head-rule"></div>
    <?php } ?>

    <div class="refline">
        <span>Ref.No.: PPGMIS/ <?=(int) $cert->ref_no?> /<?=$e(str_replace('-', ' - ', $cert->ref_year))?></span>
        <span>Date: <?=date('d / m / Y', strtotime($cert->cert_date))?></span>
    </div>

    <?php $this->load->view('studentcertificate/types/' . (isset($type['view']) ? $type['view'] : $cert->type), array(
        'd' => $d, 'e' => $e, 'photo' => $photo, 'variant' => isset($type['variant']) ? $type['variant'] : '',
    )); ?>

    <?php if (!$plain) { ?>
        <div class="foot">
            Add. : At - CBSE Wing, K.T.S.P. Campus, Khopoli - 410203, Tal-Khalapur, Dist-Raigad, Maharashtra<br>
            Contact No. : 84590 38921 | Email ID : principalgiscbse@gmail.com | www.ppgmis.com
        </div>
    <?php } ?>
</div>
</body>
</html>
