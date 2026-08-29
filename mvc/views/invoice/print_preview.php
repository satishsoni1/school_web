<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
</head>

<body>
	<div>
	<?php $subtotal = 0;
				$totalsubtotal = 0;
				$i = 1;
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
	<table width="100%" class="table-bordered" cellspacing=0>

<tr>
	<td colspan="4" style="border-bottom: 1px solid #000;">
		<table width="100%">
			<tr>
				<td width="25%">
				</td>
				<td width="50%" style="text-align: center;">
					<span style="font-size:12px">RECEIPT</span>
				</td>
				<td width="25%" class="pull-right">
					<span style="font-size:11px">Student Copy</span>
				</td>
			</tr>
			<tr>
				<td style="width: 12.5%;">
					<?php
					if ($siteinfos->photo) {
						$array = array(
							"src" => base_url('uploads/images/' . $siteinfos->photo),
							'width' => '90px',
							'height' => '90px',
							'style' => 'margin-top:-8px'
						);
						echo img($array);
					}
					?>
				</td>
				<td style="width: 75%;text-align:center">
					<b>
						<span style="font-size:12px">K.T.S.P. Mandal's</span><br />
						<span style="font-size:13px">P.P.GAGANGIRI MAHARAJ INTERNATIONAL SCHOOL,<br />KHOPOLI</span>
					</b>
				</td>
				<td style="width: 12.5%;">
					&nbsp;
				</td>
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
				<td class="pull-left">Name of Student:<?php echo $maininvoice->srname; ?></td>
				<td class="pull-right">Class:<?= $maininvoice->srclasses ?></td>
			</tr>
			<tr>
				<td class="pull-left">Section:<?= $maininvoice->srsection ?></td>
				<td class="pull-right">Date: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/2026

				</td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<th class="bb br"  style="width:25%">Sr.No.</th>
	<th colspan="2"  style="width:50%" class="br bb">Particulars</th>
	<th class="bb"  style="width:25%">Amount</th>
</tr>
<?php $subtotal = 0;
				$totalsubtotal = 0;
				$i = 1;
				if (customCompute($invoices)) {
					foreach ($invoices as $invoice) {
						$discount = 0;
						if ($invoice->discount > 0) {
							$discount = (($invoice->amount / 100) * $invoice->discount);
						}
						$subtotal = ($invoice->amount - $discount);
						$totalsubtotal += $subtotal;  ?>
						<tr>
							<td class="bb br pull-center" style="text-align:center" data-title="<?= $this->lang->line('slno') ?>">
								<?php echo $i; ?>
							</td>

							<td class="bb br" colspan="2" data-title="<?= $this->lang->line('invoice_feetype') ?>">
								<?= isset($feetypes[$invoice->feetypeID]) ? $feetypes[$invoice->feetypeID] : '' ?>
							</td>

							<td class="bb pull-right" data-title="<?= $this->lang->line('invoice_amount') ?>">
								<?= number_format($invoice->amount, 2) ?>
							</td>
						</tr>
				<?php $i++;
					}
				} ?>
  
<tr>
	<th  class="" colspan="2">&nbsp;</th>
	<th class="br pull-right">Discount</th>
	<th class="pull-right"><b><?= number_format($grandtotalandpayment['totalweaver'],0) ?></b></th>
</tr>
<tr>
	<th  class="bt" colspan="2">&nbsp;</th>
	<th class="br bt pull-right">Total</th>
	<th class="pull-right bt"><b><?= number_format($grandtotalandpayment['totalpayment'], 2) ?></b></th>
</tr>
<tr>
	<td colspan="4"  style="border-top: 1px solid #000;" >
		<table width="100%">
			<tr>
				<td style="text-align: left;">
					<!-- Paid By:  $paymenttype -->
				</td>
				<td style="text-align: right;">
					Balance:<b>
					<b><?= number_format(($totalsubtotal - ($grandtotalandpayment['totalpayment'] + $grandtotalandpayment['totalweaver'])), 2) ?></b>					</td>
			</tr>
			<tr><td style="text-align: left;">&nbsp;</td><td style="text-align: left;">&nbsp;</td></tr>
			<tr><td style="text-align: left;">&nbsp;</td><td style="text-align: left;">&nbsp;</td></tr>
			<tr>
				<td style="text-align: left;">
					Signature of Centre Head
				</td>
				<td style="text-align: right;">
					Signature of Student
				</td>
			</tr>
			
		</table>
	</td>
</tr>
<tr>
	<td colspan=4  style="border-top: 1px solid #000;text-align:center;">
		All above mention amount once paid are non refundable in any case whatsoever
	</td>
</tr>
</table>
<table width="100%" class="table" style="border:0px">
			<tr>
				<td style="height:2rem"></td>
			</tr>
			<tr>
				<td style="border-top:1px dotted #000;"></td>
			</tr>
			<tr>
				<td style="height:2rem"></td>
			</tr>
		</table>
<table width="100%" class="table-bordered" cellspacing=0>

<tr>
	<td colspan="4" style="border-bottom: 1px solid #000;">
		<table width="100%">
			<tr>
				<td width="25%">
				</td>
				<td width="50%" style="text-align: center;">
					<span style="font-size:12px">RECEIPT</span>
				</td>
				<td width="25%" class="pull-right">
					<span style="font-size:11px">Office Copy</span>
				</td>
			</tr>
			<tr>
				<td style="width: 12.5%;">
					<?php
					if ($siteinfos->photo) {
						$array = array(
							"src" => base_url('uploads/images/' . $siteinfos->photo),
							'width' => '90px',
							'height' => '90px',
							'style' => 'margin-top:-8px'
						);
						echo img($array);
					}
					?>
				</td>
				<td style="width: 75%;text-align:center">
					<b>
						<span style="font-size:12px">K.T.S.P. Mandal's</span><br />
						<span style="font-size:13px">P.P.GAGANGIRI MAHARAJ INTERNATIONAL SCHOOL,<br />KHOPOLI</span>
					</b>
				</td>
				<td style="width: 12.5%;">
					&nbsp;
				</td>
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
				<td class="pull-left">Name of Student:<?php echo $maininvoice->srname; ?></td>
				<td class="pull-right">Class:<?= $maininvoice->srclasses ?></td>
			</tr>
			<tr>
				<td class="pull-left">Section:<?= $maininvoice->srsection ?></td>
				<td class="pull-right">Date: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/2026

				</td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<th class="bb br"  style="width:25%">Sr.No.</th>
	<th colspan="2"  style="width:50%" class="br bb">Particulars</th>
	<th class="bb"  style="width:25%">Amount</th>
</tr>
<?php $subtotal = 0;
				$totalsubtotal = 0;
				$i = 1;
				if (customCompute($invoices)) {
					foreach ($invoices as $invoice) {
						$discount = 0;
						if ($invoice->discount > 0) {
							$discount = (($invoice->amount / 100) * $invoice->discount);
						}
						$subtotal = ($invoice->amount - $discount);
						$totalsubtotal += $subtotal;  ?>
						<tr>
							<td class="bb br" style="text-align:center" data-title="<?= $this->lang->line('slno') ?>">
								<?php echo $i; ?>
							</td>

							<td class="bb br" colspan="2" data-title="<?= $this->lang->line('invoice_feetype') ?>">
								<?= isset($feetypes[$invoice->feetypeID]) ? $feetypes[$invoice->feetypeID] : '' ?>
							</td>

							<td class="bb pull-right" data-title="<?= $this->lang->line('invoice_amount') ?>">
								<?= number_format($invoice->amount, 2) ?>
							</td>
						</tr>
				<?php $i++;
					}
				} ?>
  <tr>
	<th  class="" colspan="2">&nbsp;</th>
	<th class="br pull-right">Discount</th>
	<th class="pull-right"><b><?= number_format($grandtotalandpayment['totalweaver'],0) ?></b></th>
</tr>
<tr>
	<th  class="bt" colspan="2">&nbsp;</th>
	<th class="br bt pull-right">Total</th>
	<th class="pull-right bt"><b><?= number_format($grandtotalandpayment['totalpayment'], 2) ?></b></th>
</tr>
<tr>
	<td colspan="4"  style="border-top: 1px solid #000;" >
		<table width="100%">
			<tr>
				<td style="text-align: left;">
					<!-- Paid By: paymenttype -->
				</td>
				<td style="text-align: right;">
					Balance:<b>
					<b><?= number_format(($totalsubtotal - ($grandtotalandpayment['totalpayment'] + $grandtotalandpayment['totalweaver'])), 2) ?></b>					</td>
			</tr>
			<tr><td style="text-align: left;">&nbsp;</td><td style="text-align: left;">&nbsp;</td></tr>
			<tr><td style="text-align: left;">&nbsp;</td><td style="text-align: left;">&nbsp;</td></tr>
			<tr>
				<td style="text-align: left;">
					Signature of Centre Head
				</td>
				<td style="text-align: right;">
					Signature of Student
				</td>
			</tr>
			
		</table>
	</td>
</tr>
<tr>
	<td colspan=4  style="border-top: 1px solid #000;text-align:center;">
		All above mention amount once paid are non refundable in any case whatsoever
	</td>
</tr>
</table>
		<!-- <table width="100%" class="table" style="border:1px solid #000">
		
				<tr>
					<td colspan=5 class="pull-right">
					<span style="font-size:11px">Student Copy</span>
					</td>
				</tr>
			<tr>
				<td>
					<?php
					if ($siteinfos->photo) {
						$array = array(
							"src" => base_url('uploads/images/' . $siteinfos->photo),
							'width' => '100px',
							'height' => '100px',
							'style' => 'margin-top:-8px'
						);
						echo img($array);
					}
					?>
				</td>
				<td colspan=4 style="text-align:center">
					<b>
						<span style="font-size:15px">K.T.S.P. Mandal's</span><br />
						<span style="font-size:17px">P.P.GAGANGIRI MAHARAJ INTERNATIONALSCHOOL,KHOPOLI</span>
						<br />
						<span style="font-size:15px">RECEIPT</span>
					</b>
				</td>
			</tr>
			<tr>
				<td style="width:15%">
					Receipt No.
				</td>
				<td style="text-align:left">
					<?= $maininvoice->maininvoiceID ?>
				</td>
				<td>
				</td>
				<td style="width:18%">
					Date:
				</td>
				<td style="width:18%;text-align:left">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/2024
				</td>
			</tr>
			<tr>
				<td colspan=5 style="text-align: left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Received Rs. 
					<span style="width:10px;border-bottom:1px solid #000">
						<b>
							<?= number_format($grandtotalandpayment['totalpayment'], 2) ?>
						</b>
					</span>
					(In Words) 
					<span style="width:10px;border-bottom:1px solid #000"><?= AmountInWords($grandtotalandpayment['totalpayment']) ?></span> 
					from Mr./Mrs. <span style="width:10px;border-bottom:1px solid #000"><?= ($student->father_name!=null)?$student->father_name:"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" ?></span> 
					for admission in <span style="width:10px;border-bottom:1px solid #000"><?= $maininvoice->srclasses ?></span> 
					(1st/2nd/3rd Installments) for the year <?= $schoolyear->schoolyear ?> Student's Name 
					<span style="width:10px;border-bottom:1px solid #000"><?php echo $maininvoice->srname; ?></span> 
					Balance Amount 
					<span style="width:10px;border-bottom:1px solid #000">
						<b><?= number_format(($totalsubtotal - ($grandtotalandpayment['totalpayment'] + $grandtotalandpayment['totalweaver'])), 2) ?></b>
					</span> 
				</td>
			</tr>
			<tr>
				<td colspan=5>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td colspan=5>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td></td>
				<td style="border-top:#000 solid 1px">Received By</td>
				<td></td>
				<td style="border-top:#000 solid 1px">Principal </td>
				<td></td>
			</tr>
		</table>
		<table width="100%" class="table" style="border:0px">
		<tr>
			<td style="border-top:1px dotted #000"></td>
		</tr>
		</table>
		<table width="100%" class="table" style="border:1px solid #000">
		<?php $subtotal = 0;
				$totalsubtotal = 0;
				$i = 1;
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
				<tr>
					<td colspan=5 class="pull-right">
					<span style="font-size:11px">Office Copy</span>
					</td>
				</tr>
			<tr>
				<td>
					<?php
					if ($siteinfos->photo) {
						$array = array(
							"src" => base_url('uploads/images/' . $siteinfos->photo),
							'width' => '100px',
							'height' => '100px',
							'style' => 'margin-top:-8px'
						);
						echo img($array);
					}
					?>
				</td>
				<td colspan=4 style="text-align:center">
					<b>
						<span style="font-size:15px">K.T.S.P. Mandal's</span><br />
						<span style="font-size:17px">P.P.GAGANGIRI MAHARAJ INTERNATIONALSCHOOL,KHOPOLI</span>
						<br />
						<span style="font-size:15px">RECEIPT</span>
					</b>
				</td>
			</tr>
			<tr>
				<td style="width:15%">
					Receipt No.
				</td>
				<td style="text-align:left">
					<?= $maininvoice->maininvoiceID ?>
				</td>
				<td>
				</td>
				<td style="width:18%">
					Date:
				</td>
				<td style="width:18%;text-align:left">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/2024
				</td>
			</tr>
			<tr>
				<td colspan=5 style="text-align: left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Received Rs. 
					<span style="width:10px;border-bottom:1px solid #000">
						<b>
							<?= number_format($grandtotalandpayment['totalpayment'], 2) ?>
						</b>
					</span>
					(In Words) 
					<span style="width:10px;border-bottom:1px solid #000"><?= AmountInWords($grandtotalandpayment['totalpayment']) ?></span> 
					from Mr./Mrs. <span style="width:10px;border-bottom:1px solid #000"><?= ($student->father_name!=null)?$student->father_name:"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" ?></span> 
					for admission in <span style="width:10px;border-bottom:1px solid #000"><?= $maininvoice->srclasses ?></span> 
					(1st/2nd/3rd Installments) for the year <?= $schoolyear->schoolyear ?> Student's Name 
					<span style="width:10px;border-bottom:1px solid #000"><?php echo $maininvoice->srname; ?></span> 
					Balance Amount 
					<span style="width:10px;border-bottom:1px solid #000">
						<b><?= number_format(($totalsubtotal - ($grandtotalandpayment['totalpayment'] + $grandtotalandpayment['totalweaver'])), 2) ?></b>
					</span> 
				</td>
			</tr>
			<tr>
				<td colspan=5>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td colspan=5>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td></td>
				<td style="border-top:#000 solid 1px">Received By</td>
				<td></td>
				<td style="border-top:#000 solid 1px">Principal </td>
				<td></td>
			</tr>
		</table> -->

		<!--table width="100%">
			<tr>
				<td widht="5%">
					<h2>
						<?php
						if ($siteinfos->photo) {
							$array = array(
								"src" => base_url('uploads/images/' . $siteinfos->photo),
								'width' => '25px',
								'height' => '25px',
								'style' => 'margin-top:-8px'
							);
							echo img($array);
						}
						?>
					</h2>
				</td>
				<td widht="75%">
					<h3 class="top-site-header-title"><?php echo $siteinfos->sname; ?></h3>
				</td>
				<td class="20%">
					<h5 class="top-site-header-create-title"><?php echo $this->lang->line("invoice_create_date") . " : " . date("d M Y"); ?></h5>
				</td>
			</tr>
		</table>
		<br>
		<table width="100%">
			<tr>
				<td width="33%">
					<table>
						<tbody>
							<tr>
								<th class="site-header-title-float"><?php echo $this->lang->line("invoice_from"); ?></th>
							</tr>
							<?php if (customCompute($siteinfos)) { ?>
								<tr>
									<td><?= $siteinfos->sname ?></td>
								</tr>
								<tr>
									<td><?= $siteinfos->address ?></td>
								</tr>
								<tr>
									<td><?= $this->lang->line("invoice_phone") . " : " . $siteinfos->phone ?></td>
								</tr>
								<tr>
									<td><?= $this->lang->line("invoice_email") . " : " . $siteinfos->email ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</td>
				<td width="33%">
					<table>
						<tbody>
							<tr>
								<th class="site-header-title-float"><?php echo $this->lang->line("invoice_to"); ?></th>
							</tr>
							<tr>
								<td><?php echo $maininvoice->srname; ?></td>
							</tr>
							<tr>
								<td><?php echo $this->lang->line("invoice_roll") . " : " . $maininvoice->srroll; ?></td>
							</tr>
							<tr>
								<td><?php echo $this->lang->line("invoice_classesID") . " : " . $maininvoice->srclasses; ?></td>
							</tr>
							<tr>
								<td><?php echo $this->lang->line("student_registerNO") . " : " . $maininvoice->srregisterNO; ?></td>
							</tr>
							<?php if (customCompute($student)) { ?>
								<tr>
									<td><?= $this->lang->line("invoice_email") . " : " . $student->email ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</td>
				<td width="34%" style="vertical-align: text-top;">
					<table>
						<tbody>
							<tr>
								<td>
									<?= $this->lang->line("invoice_invoice") . $maininvoice->maininvoiceID ?>
								</td>
							</tr>
							<tr>
								<td>
									<?php
									$status = $maininvoice->maininvoicestatus;
									$setButton = '';
									if ($status == 0) {
										$status = $this->lang->line('invoice_notpaid');
										$setButton = 'text-red';
									} elseif ($status == 1) {
										$status = $this->lang->line('invoice_partially_paid');
										$setButton = 'text-yellow';
									} elseif ($status == 2) {
										$status = $this->lang->line('invoice_fully_paid');
										$setButton = 'text-green';
									}

									echo $this->lang->line('invoice_status') . " : " . "<span class='" . $setButton . "'>" . $status . "</span>";;
									?>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</table>
		<br>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th><?= $this->lang->line('slno') ?></th>
					<th><?= $this->lang->line('invoice_feetype') ?></th>
					<th><?= $this->lang->line('invoice_amount') ?></th>
					<th><?= $this->lang->line('invoice_discount') ?></th>
					<th><?= $this->lang->line('invoice_subtotal') ?></th>
				</tr>
			</thead>
			<tbody>
				<?php $subtotal = 0;
				$totalsubtotal = 0;
				$i = 1;
				if (customCompute($invoices)) {
					foreach ($invoices as $invoice) {
						$discount = 0;
						if ($invoice->discount > 0) {
							$discount = (($invoice->amount / 100) * $invoice->discount);
						}
						$subtotal = ($invoice->amount - $discount);
						$totalsubtotal += $subtotal;  ?>
						<tr>
							<td data-title="<?= $this->lang->line('slno') ?>">
								<?php echo $i; ?>
							</td>

							<td data-title="<?= $this->lang->line('invoice_feetype') ?>">
								<?= isset($feetypes[$invoice->feetypeID]) ? $feetypes[$invoice->feetypeID] : '' ?>
							</td>

							<td data-title="<?= $this->lang->line('invoice_amount') ?>">
								<?= number_format($invoice->amount, 2) ?>
							</td>

							<td data-title="<?= $this->lang->line('invoice_discount') ?>">
								<?= number_format($discount, 2) ?>
							</td>
							<td data-title="<?= $this->lang->line('invoice_subtotal') ?>">
								<?= number_format($subtotal, 2) ?>
							</td>
						</tr>
				<?php $i++;
					}
				} ?>
			</tbody>
			<tfoot>
				<tr>
					<td class="pull-right" colspan="4"><b><?= $this->lang->line('invoice_totalamount') ?> <?= !empty($siteinfos->currency_code) ? '(' . $siteinfos->currency_code . ')' : '' ?></b></td>
					<td><b><?= number_format($totalsubtotal, 2) ?></b></td>
				</tr>
				<tr>
					<td class="pull-right" colspan="4"><b><?= $this->lang->line('invoice_paid') ?> <?= !empty($siteinfos->currency_code) ? '(' . $siteinfos->currency_code . ')' : '' ?></b></td>
					<td><b><?= number_format($grandtotalandpayment['totalpayment'], 2) ?></b></td>
				</tr>
				<tr>
					<td class="pull-right" colspan="4"><b><?= $this->lang->line('invoice_weaver') ?> <?= !empty($siteinfos->currency_code) ? '(' . $siteinfos->currency_code . ')' : '' ?></b></td>
					<td><b><?= number_format($grandtotalandpayment['totalweaver'], 2) ?></b></td>
				</tr>
				<tr>
					<td class="pull-right" colspan="4"><b><?= $this->lang->line('invoice_balance'); ?> <?= !empty($siteinfos->currency_code) ? '(' . $siteinfos->currency_code . ')' : '' ?></b></td>
					<td><b><?= number_format(($totalsubtotal - ($grandtotalandpayment['totalpayment'] + $grandtotalandpayment['totalweaver'])), 2) ?></b></td>
				</tr>
				<tr>
					<td class="pull-right" colspan="4"><b><?= $this->lang->line('invoice_fine'); ?> <?= !empty($siteinfos->currency_code) ? '(' . $siteinfos->currency_code . ')' : '' ?></b></td>
					<td><b><?= number_format($grandtotalandpayment['totalfine'], 2) ?></b></td>
				</tr>
			</tfoot>
		</table>

		<table width="100%">
			<tr>
				<td width="65%">
				</td>
				<td width="35%">
					<table>
						<tr>
							<td><?= $this->lang->line('invoice_create_by') ?> : <?= $createuser ?></td>
						</tr>
						<tr>
							<td><?= $this->lang->line('invoice_date') ?> : <?= date('d M Y', strtotime($maininvoice->maininvoicecreate_date)) ?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table-->
	</div>
	
</body>

</html>