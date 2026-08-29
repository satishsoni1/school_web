<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>

<?php if (isset($collection) && !empty($collection)) { 
    foreach ($collection as $dataItem) {
        // Extract variables for this specific invoice iteration
        $maininvoice = $dataItem['maininvoice'];
        $invoices = $dataItem['invoices'];
        $grandtotalandpayment = $dataItem['grandtotalandpayment'];
        $student = $dataItem['student'];
        
        // Calculate Totals for this invoice
        $subtotal = 0;
        $totalsubtotal = 0;
        if (customCompute($invoices)) {
            foreach ($invoices as $invoice) {
                $discount = 0;
                if ($invoice->discount > 0) {
                    $discount = (($invoice->amount / 100) * $invoice->discount);
                }
                $subtotal = ($invoice->amount - $discount);
                $totalsubtotal += $subtotal;
            }
        }
?>

    <div class="invoice-wrapper">
        <table width="100%" class="table-bordered" cellspacing=0>
            <tr>
                <td colspan="4" style="border-bottom: 1px solid #000;">
                    <table width="100%">
                        <tr>
                            <td width="25%"></td>
                            <td width="50%" style="text-align: center;">
                                <span style="font-size:12px">RECEIPT</span>
                            </td>
                            <td width="25%" class="pull-right">
                                <span style="font-size:11px">Student Copy</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 12.5%;">
                                <?php if ($siteinfos->photo) {
                                    $array = array(
                                        "src" => base_url('uploads/images/' . $siteinfos->photo),
                                        'width' => '90px',
                                        'height' => '90px',
                                        'style' => 'margin-top:-8px'
                                    );
                                    echo img($array);
                                } ?>
                            </td>
                            <td style="width: 75%;text-align:center">
                                <b>
                                    <span style="font-size:12px">K.T.S.P. Mandal's</span><br />
                                    <span style="font-size:13px">P.P.GAGANGIRI MAHARAJ INTERNATIONAL SCHOOL,<br />KHOPOLI</span>
                                </b>
                            </td>
                            <td style="width: 12.5%;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align:left">
                                Receipt No. <?= $maininvoice->maininvoiceID ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td colspan="4" style="border-bottom: 1px solid #000;">
                    <table width="100%" style="text-align:left;">
                        <tr>
                            <td class="pull-left">Name of Student: <?php echo $maininvoice->srname; ?></td>
                            <td class="pull-right">Class: <?= $maininvoice->srclasses ?></td>
                        </tr>
                        <tr>
                            <td class="pull-left">Section: <?= $maininvoice->srsection ?></td>
                            <td class="pull-right">Date: <?= date('d/m/Y') ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <th class="bb br" style="width:10%">Sr.No.</th>
                <th colspan="2" style="width:65%" class="br bb">Particulars</th>
                <th class="bb" style="width:25%">Amount</th>
            </tr>
            
            <?php 
            $i = 1;
            if (customCompute($invoices)) {
                foreach ($invoices as $invoice) { ?>
                    <tr>
                        <td class="bb br pull-center" style="text-align:center"><?php echo $i; ?></td>
                        <td class="bb br" colspan="2">
                            <?= isset($feetypes[$invoice->feetypeID]) ? $feetypes[$invoice->feetypeID] : '' ?>
                        </td>
                        <td class="bb pull-right">
                            <?= number_format($invoice->amount, 2) ?>
                        </td>
                    </tr>
            <?php $i++; } } ?>

            <tr>
                <th class="" colspan="2">&nbsp;</th>
                <th class="br pull-right">Discount</th>
                <th class="pull-right"><b><?= number_format($grandtotalandpayment['totalweaver'], 0) ?></b></th>
            </tr>
            <tr>
                <th class="bt" colspan="2">&nbsp;</th>
                <th class="br bt pull-right">Total</th>
                <th class="pull-right bt"><b><?= number_format($grandtotalandpayment['totalpayment'], 2) ?></b></th>
            </tr>
            <tr>
                <td colspan="4" style="border-top: 1px solid #000;">
                    <table width="100%">
                        <tr>
                            <td style="text-align: left;"></td>
                            <td style="text-align: right;">
                                Balance: <b><?= number_format(($totalsubtotal - ($grandtotalandpayment['totalpayment'] + $grandtotalandpayment['totalweaver'])), 2) ?></b>
                            </td>
                        </tr>
                        <tr><td colspan="2" style="height:2rem">&nbsp;</td></tr>
                        <tr>
                            <td style="text-align: left;">Signature of Centre Head</td>
                            <td style="text-align: right;">Signature of Student</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="border-top: 1px solid #000;text-align:center;">
                    All above mention amount once paid are non refundable in any case whatsoever
                </td>
            </tr>
        </table>

        <table width="100%" class="table" style="border:0px">
            <tr><td style="height:1rem"></td></tr>
            <tr><td style="border-top:1px dotted #000;"></td></tr>
            <tr><td style="height:1rem"></td></tr>
        </table>

        <table width="100%" class="table-bordered" cellspacing=0>
            <tr>
                <td colspan="4" style="border-bottom: 1px solid #000;">
                    <table width="100%">
                        <tr>
                            <td width="25%"></td>
                            <td width="50%" style="text-align: center;">
                                <span style="font-size:12px">RECEIPT</span>
                            </td>
                            <td width="25%" class="pull-right">
                                <span style="font-size:11px">Office Copy</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 12.5%;">
                                <?php if ($siteinfos->photo) {
                                    $array = array(
                                        "src" => base_url('uploads/images/' . $siteinfos->photo),
                                        'width' => '90px',
                                        'height' => '90px',
                                        'style' => 'margin-top:-8px'
                                    );
                                    echo img($array);
                                } ?>
                            </td>
                            <td style="width: 75%;text-align:center">
                                <b>
                                    <span style="font-size:12px">K.T.S.P. Mandal's</span><br />
                                    <span style="font-size:13px">P.P.GAGANGIRI MAHARAJ INTERNATIONAL SCHOOL,<br />KHOPOLI</span>
                                </b>
                            </td>
                            <td style="width: 12.5%;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align:left">
                                Receipt No. <?= $maininvoice->maininvoiceID ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td colspan="4" style="border-bottom: 1px solid #000;">
                    <table width="100%" style="text-align:left;">
                        <tr>
                            <td class="pull-left">Name of Student: <?php echo $maininvoice->srname; ?></td>
                            <td class="pull-right">Class: <?= $maininvoice->srclasses ?></td>
                        </tr>
                        <tr>
                            <td class="pull-left">Section: <?= $maininvoice->srsection ?></td>
                            <td class="pull-right">Date: <?= date('d/m/Y') ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <th class="bb br" style="width:10%">Sr.No.</th>
                <th colspan="2" style="width:65%" class="br bb">Particulars</th>
                <th class="bb" style="width:25%">Amount</th>
            </tr>
            
            <?php 
            $i = 1;
            if (customCompute($invoices)) {
                foreach ($invoices as $invoice) { ?>
                    <tr>
                        <td class="bb br pull-center" style="text-align:center"><?php echo $i; ?></td>
                        <td class="bb br" colspan="2">
                            <?= isset($feetypes[$invoice->feetypeID]) ? $feetypes[$invoice->feetypeID] : '' ?>
                        </td>
                        <td class="bb pull-right">
                            <?= number_format($invoice->amount, 2) ?>
                        </td>
                    </tr>
            <?php $i++; } } ?>

            <tr>
                <th class="" colspan="2">&nbsp;</th>
                <th class="br pull-right">Discount</th>
                <th class="pull-right"><b><?= number_format($grandtotalandpayment['totalweaver'], 0) ?></b></th>
            </tr>
            <tr>
                <th class="bt" colspan="2">&nbsp;</th>
                <th class="br bt pull-right">Total</th>
                <th class="pull-right bt"><b><?= number_format($grandtotalandpayment['totalpayment'], 2) ?></b></th>
            </tr>
            <tr>
                <td colspan="4" style="border-top: 1px solid #000;">
                    <table width="100%">
                        <tr>
                            <td style="text-align: left;"></td>
                            <td style="text-align: right;">
                                Balance: <b><?= number_format(($totalsubtotal - ($grandtotalandpayment['totalpayment'] + $grandtotalandpayment['totalweaver'])), 2) ?></b>
                            </td>
                        </tr>
                        <tr><td colspan="2" style="height:2rem">&nbsp;</td></tr>
                        <tr>
                            <td style="text-align: left;">Signature of Centre Head</td>
                            <td style="text-align: right;">Signature of Student</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="border-top: 1px solid #000;text-align:center;">
                    All above mention amount once paid are non refundable in any case whatsoever
                </td>
            </tr>
        </table>
    </div>

<?php 
    } // End foreach collection
} 
?>
    
</body>
</html>