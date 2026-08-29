
		<!DOCTYPE html>
		<html lang="en">

		<head>
			<meta charset="UTF-8">
		</head>

		<body>
			<div>
			<?php $totalamount = 0;
		$totalamount = 0;
		$totalweaver = 0;
		$totalfine = 0;
		$totalsubtotal = 0;
		$paymentDate = date('Y-m-d');
		$paymentUserTypeID = 0;
		$paymentUserID = 0;
		$i = 1;
		$payment_type = "";
		if (customCompute($payments)) {
			foreach ($payments as $payment) {
				if ($payment->paymentamount != '' || (isset($weaverandfines[$payment->paymentID]))) {
					$paymentDate = $payment->paymentdate;
					$paymentUserTypeID = $payment->usertypeID;
					$paymentUserID = $payment->userID;
					$subtotal = $payment->paymentamount;
					$payment_type = $payment->payment_type;
					$weaverandfinesAmount = 0;
					$fine = 0;

					if (isset($weaverandfines[$payment->paymentID])) {
						//$subtotal += $weaverandfines[$payment->paymentID]->fine;
						$fine += $weaverandfines[$payment->paymentID]->fine;
						$weaverandfinesAmount += (int)$weaverandfines[$payment->paymentID]->weaver;
					}
					$totalsubtotal += $subtotal;
					//$totalsubtotal += $fine;

					$i++;
				}
			}
		}
		$totalinvoices = 0;
		if (customCompute($invoices)) {
			foreach ($invoices as $invoice) {
				$totalinvoices += $invoice->amount;
			}
		}
		$totalglobalpayments = 0;
		if (customCompute($grandtotalandpayment['totalpayment'])) {
			foreach ($grandtotalandpayment['totalpayment'] as $globalpaymentrow) {
				$totalglobalpayments += $globalpaymentrow;
			}
		}
		$receipt_no = ($globalpayment->payment_receipt_no == null) ? $globalpayment->auto_receipt_no : $globalpayment->payment_receipt_no;
		// $receipt_no = (str_pad((int)$receipt_no, 4, '0', STR_PAD_LEFT));
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
											<span style="font-size:11px">KHALAPUR TALUKA SHIKSHAN PRASARAK MANDAL'S</span><br />
											<span style="font-size:15px">P.P.GAGANGIRI MAHARAJ INTERNATIONAL SCHOOL,<br />KHOPOLI<br/>CBSE AFFILIATION NO: 1131395
											</span>
										</b>
									</td>
									<td style="width: 12.5%;">
										&nbsp;
									</td>
								</tr>
								<tr>
									<td colspan="3" style="text-align:left">
									Payment Receipt No. <?= '<b>'.$receipt_no.'</b>' ?>
									</td>
								</tr>
							</table>
						</td>
					</tr>

					<tr>
						<td colspan="4" style="border-bottom: 1px solid #000;">
							<table width="100%" style="text-align:left;">
								<tr>
									<td class="pull-left">Name of Student:<?php echo $studentrelation->srname; ?></td>
									<td>&nbsp;</td>
									<td class="pull-right">Class:<?= ($schoolyear->schoolyearID!=2)?$classes[$studentrelation->srclassesID]:$studentrelation->srclasses ?></td>
									
									<!-- <td class="pull-right"> -->
										<!-- Section:<?= $studentrelation->srsection ?> -->
									<!-- </td> -->
								</tr>
								<tr>
									<td class="pull-left">Paid By: <?= $paymenttype ?></td>
									<td>&nbsp;</td>
									<td class="pull-right">Date of Payment:<?php if (date("Y", strtotime($globalpayment->payment_date)) > 0 && $globalpayment->payment_date != "null") {
						echo date("d/m/Y", strtotime($globalpayment->payment_date));
					} else {
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/2024";
					}
					?></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<th class="bb br"  style="width:25%">Sr.No.</th>
						<th colspan="2"  style="width:50%" class="br bb">Particulars</th>
						<th class="bb"  style="width:25%">Amount</th>
					</tr>
	          	<?php $message_flag = false; $totalamount = 0; $totalamount = 0; $totalweaver = 0; $totalfine = 0; $totalsubtotal = 0; $paymentDate = date('Y-m-d'); $paymentUserTypeID = 0; $paymentUserID = 0; $i = 1; if(customCompute($payments)) { foreach($payments as $payment) { if($payment->paymentamount != '' || (isset($weaverandfines[$payment->paymentID]))) { $paymentDate = $payment->paymentdate; $paymentUserTypeID = $payment->usertypeID; $paymentUserID = $payment->userID;  ?>
		            <tr>
		                <td data-title="<?=$this->lang->line('slno')?>" style="text-align:center;" class="bb br pull-center">
                            <?php echo $i; ?>
                        </td>
		                
		                <td data-title="<?=$this->lang->line('invoice_feetype')?>" colspan="2" class="bb br">
		                    <?php
                                if(isset($invoices[$payment->invoiceID])) {
                                    if(isset($feetypes[$invoices[$payment->invoiceID]->feetypeID])) {
                                        echo $feetypes[$invoices[$payment->invoiceID]->feetypeID];
										if(str_contains($feetypes[$invoices[$payment->invoiceID]->feetypeID],'Admission')){
											$message_flag = true;
										}
                                    }
                                }
                            ?>
		                </td>
		                
		                <td data-title="<?=$this->lang->line('invoice_amount')?>"  class="bb pull-right">
		                    <?php
                                $totalamount += $payment->paymentamount;
                                echo number_format($payment->paymentamount, 2);
                            ?>
		                </td>
		            </tr>
	          	<?php $i++; } } } ?>
				  <?php if($fine>0) { ?>
				  <tr>
						<th  class="br bb" colspan="2">&nbsp;</th>
						<th class="br bb pull-right">Fine</th>
						<th class="bb pull-right"><b><?=number_format($fine, 2)?></b></th>
					</tr> 
					<?php } ?>	
				  <tr>
						<th  class="br bb" colspan="2">&nbsp;</th>
						<th class="br bb pull-right">Total Amount</th>
						<th class="bb pull-right"><b><?=number_format($totalamount+$fine, 2)?></b></th>
					</tr>
				  <?php if($discount_calculate>0) { ?>
				  <tr>
						<th  class="br bb" colspan="2">&nbsp;</th>
						<th class="br bb pull-right">Discount</th>
						<th class="bb pull-right"><b><?=number_format($discount_calculate+$weaverandfinesAmount, 2)?></b></th>
					</tr> 
					<?php } ?>	

				  	<!-- <tr>
						<th  class="br" colspan="2">&nbsp;</th>
						<th class="br pull-right">Payable Amount</th>
						<th class="pull-right"><b><?=number_format($totalamount-$discount_calculate, 2)?></b></th>
					</tr> -->
					<tr>
						<td colspan="4"  style="border-top: 1px solid #000;" >
							<table width="100%">
								<tr>
									<td style="text-align: left;">
									</td>
									<td style="text-align: center;"></td>
									<td style="text-align: right;">
										Balance:<b><?= number_format($totalinvoices-$totalglobalpayments-$weaverandfinesAmount-$discount_calculate,0) ?>
									</td>
								</tr>
								<tr><td style="text-align: left;">&nbsp;</td><td style="text-align: center;"></td><td style="text-align: left;">&nbsp;</td></tr>
								<tr><td style="text-align: left;">&nbsp;</td><td style="text-align: center;"></td><td style="text-align: left;">&nbsp;</td></tr>
								<tr>
									<td style="text-align: left;width:33%">
									Received By
									</td>
									<td style="text-align: center;width:33%">School stamp</td>
									<td style="text-align: right;width:33%">
										&nbsp;
									</td>
								</tr>
								
							</table>
						</td>
					</tr>
					<?php if($schoolyear->schoolyearID!=2) { ?>
					<tr>
						<td colspan=4  style="border-top: 1px solid #000;text-align:left;padding-left:1rem">
							<h5>Note</h5>
							<ul>
								<?php if($message_flag) { ?>
								<li>Admission fee is non refundable and transferable in any case whatsover</li>
								<?php } ?>
								<li>A fine of ₹50 will be charged for a lost receipt.</li>
								<?php if((int)($totalinvoices-$totalglobalpayments-$weaverandfinesAmount)>0) { ?>
								<li><b>Late Fee:</b> ₹500 per month will be charged if fees are not cleared by the end of September <?= date('Y',strtotime($schoolyear->startingdate))  ?>.</li>
								<?php } ?>
							</ul>
						</td>
					</tr>
					<?php } ?>
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
											<span style="font-size:11px">KHALAPUR TALUKA SHIKSHAN PRASARAK MANDAL'S</span><br />
											<span style="font-size:15px">P.P.GAGANGIRI MAHARAJ INTERNATIONAL SCHOOL,<br />KHOPOLI<br/>CBSE AFFILIATION NO: 1131395
											</span>
										</b>
									</td>
									<td style="width: 12.5%;">
										&nbsp;
									</td>
								</tr>
								<tr>
									<td colspan="3" style="text-align:left">
										Payment Receipt No. <?= '<b>'.$receipt_no.'</b>' ?>
									</td>
								</tr>
							</table>
						</td>
					</tr>

					<tr>
						<td colspan="4" style="border-bottom: 1px solid #000;">
							<table width="100%" style="text-align:left;">
								<tr>
									<td class="pull-left">Name of Student:<?php echo $studentrelation->srname; ?></td>
									<td>&nbsp;</td>
									<td class="pull-right">Class:<?= ($schoolyear->schoolyearID!=2)?$classes[$studentrelation->srclassesID]:$studentrelation->srclasses ?></td>
									<!-- <td class="pull-right">
										Section:<?= $studentrelation->srsection ?>
									</td> -->

								</tr>
								<tr>
								<td class="pull-left">Paid By: <?= $paymenttype ?></td>
<td>&nbsp;</td>
									<td class="pull-right">Date of Payment:<?php if (date("Y", strtotime($globalpayment->payment_date)) > 0 && $globalpayment->payment_date != "null") {
						echo date("d/m/Y", strtotime($globalpayment->payment_date));
					} else {
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/2024";
					}
					?></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<th class="bb br"  style="width:25%">Sr.No.</th>
						<th colspan="2"  style="width:50%" class="br bb">Particulars</th>
						<th class="bb"  style="width:25%">Amount</th>
					</tr>
	          	<?php $totalamount = 0; $totalamount = 0; $totalweaver = 0; $totalfine = 0; $totalsubtotal = 0; $paymentDate = date('Y-m-d'); $paymentUserTypeID = 0; $paymentUserID = 0; $i = 1; if(customCompute($payments)) { foreach($payments as $payment) { if($payment->paymentamount != '' || (isset($weaverandfines[$payment->paymentID]))) { $paymentDate = $payment->paymentdate; $paymentUserTypeID = $payment->usertypeID; $paymentUserID = $payment->userID;  ?>
		            <tr>
		                <td data-title="<?=$this->lang->line('slno')?>" style="text-align:center;" class="bb br">
                            <?php echo $i; ?>
                        </td>
		                
		                <td data-title="<?=$this->lang->line('invoice_feetype')?>" colspan="2" class="bb br">
		                    <?php
                                if(isset($invoices[$payment->invoiceID])) {
                                    if(isset($feetypes[$invoices[$payment->invoiceID]->feetypeID])) {
                                        echo $feetypes[$invoices[$payment->invoiceID]->feetypeID];
                                    }
                                }
                            ?>
		                </td>
		                
		                <td data-title="<?=$this->lang->line('invoice_amount')?>"  class="bb pull-right">
		                    <?php
                                $totalamount += $payment->paymentamount;
                                echo number_format($payment->paymentamount, 2);
                            ?>
		                </td>
		            </tr>
	          	<?php $i++; } } } ?>
				  <?php if($fine>0) { ?>
				  <tr>
						<th  class="br bb" colspan="2">&nbsp;</th>
						<th class="br bb pull-right">Fine</th>
						<th class="bb pull-right"><b><?=number_format($fine, 2)?></b></th>
					</tr> 
					<?php } ?>	
				  <tr>
						<th  class="br bb " colspan="2">&nbsp;</th>
						<th class="br bb pull-right">Total Amount</th>
						<th class="bb pull-right"><b><?=number_format($totalamount+$fine, 2)?></b></th>
					</tr>
				  <?php if($discount_calculate>0) { ?>
				  <tr>
						<th  class="br bb" colspan="2">&nbsp;</th>
						<th class="br bb pull-right">Discount</th>
						<th class="bb pull-right"><b><?=number_format($discount_calculate+$weaverandfinesAmount, 2)?></b></th>
					</tr> 
					<?php } ?>	
				  	<!-- <tr>
						<th  class="br" colspan="2">&nbsp;</th>
						<th class="br pull-right">Payable Amount</th>
						<th class="pull-right"><b><?=number_format($totalamount-$discount_calculate, 2)?></b></th>
					</tr> -->
				  	
					<tr>
						<td colspan="4"  style="border-top: 1px solid #000;" >
							<table width="100%">
								<tr>
									<td style="text-align: left;">
									</td>
									<td>&nbsp;</td>
									<td style="text-align: right;">
										Balance:<b><?= number_format($totalinvoices-$totalglobalpayments-$weaverandfinesAmount-$discount_calculate,0) ?>
									</td>
								</tr>
								<tr><td style="text-align: left;">&nbsp;</td><td>&nbsp;</td><td style="text-align: left;">&nbsp;</td></tr>
								<tr><td style="text-align: left;">&nbsp;</td><td>&nbsp;</td><td style="text-align: left;">&nbsp;</td></tr>
								<tr>
									<td style="text-align: left;width:33%">
									Received By
									</td>
									<td style="text-align: center;width:33%">School stamp</td>
									<td style="text-align: right;">
									&nbsp;
									</td>
								</tr>
								
							</table>
						</td>
					</tr>
					<?php if($schoolyear->schoolyearID!=2) { ?>
					<tr>
						<td colspan=4  style="border-top: 1px solid #000;text-align:left;padding-left:1rem">
							<h5>Note</h5>
							<ul>
								<?php if($message_flag) { ?>
								<li>Admission fee is non refundable and transferable in any case whatsover</li>
								<?php } ?>
								<li>A fine of ₹50 will be charged for a lost receipt.</li>
								<?php if((int)($totalinvoices-$totalglobalpayments-$weaverandfinesAmount)>0) { ?>
								<li><b>Late Fee:</b> ₹500 per month will be charged if fees are not cleared by the end of September <?= date('Y',strtotime($schoolyear->startingdate))  ?>.</li>
								<?php } ?>
							</ul>	
						</td>
					</tr>
					<?php } ?>
				</table>
			</div>
	<!-- <div>
		<?php $totalamount = 0;
		$totalamount = 0;
		$totalweaver = 0;
		$totalfine = 0;
		$totalsubtotal = 0;
		$paymentDate = date('Y-m-d');
		$paymentUserTypeID = 0;
		$paymentUserID = 0;
		$i = 1;
		if (customCompute($payments)) {
			foreach ($payments as $payment) {
				if ($payment->paymentamount != '' || (isset($weaverandfines[$payment->paymentID]))) {
					$paymentDate = $payment->paymentdate;
					$paymentUserTypeID = $payment->usertypeID;
					$paymentUserID = $payment->userID;
					$subtotal = $payment->paymentamount;
					$weaverandfinesAmount = 0;
					if (isset($weaverandfines[$payment->paymentID])) {
						$subtotal += $weaverandfines[$payment->paymentID]->fine;
						$weaverandfinesAmount += (int)$weaverandfines[$payment->paymentID]->weaver;
					}
					$totalsubtotal += $subtotal;

					$i++;
				}
			}
		}
		$totalinvoices = 0;
		if (customCompute($invoices)) {
			foreach ($invoices as $invoice) {
				$totalinvoices += $invoice->amount;
			}
		}
		$totalglobalpayments = 0;
		if (customCompute($grandtotalandpayment['totalpayment'])) {
			foreach ($grandtotalandpayment['totalpayment'] as $globalpaymentrow) {
				$totalglobalpayments += $globalpaymentrow;
			}
		}
		?>
		<table width="100%" class="table" style="border:1px solid #000">

			<tr>
				<td colspan=5 class="pull-right">
					<span style="font-size:11px">Student Copy</span>
				</td>
			</tr>
			<tr>
				<td colspan="5">
					<table width="100%">
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
									<span style="font-size:12px">KHALAPUR TALUKA SHIKSHAN PRASARAK MANDAL'S</span><br />
									<span style="font-size:13px">P.P.GAGANGIRI MAHARAJ INTERNATIONAL SCHOOL,<br />KHOPOLI</span>
									<br />
									<br />
									<span style="font-size:12px">RECEIPT</span>
								</b>
							</td>
							<td style="width: 12.5%;">
								&nbsp;
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="width:25%">
					Payment Receipt No.
				</td>
				<td style="text-align:left">
					<?= '<b>' . ($globalpayment->payment_receipt_no == null) ? $globalpayment->globalpaymentID : $globalpayment->payment_receipt_no . '</b>' ?>
				</td>
				<td>
				</td>
				<td style="width:18%">
					Date:
				</td>
				<td style="width:18%;text-align:left">
					<?php if (date("Y", strtotime($globalpayment->payment_date)) > 0 && $globalpayment->payment_date != "null") {
						echo date("d/m/Y", strtotime($globalpayment->payment_date));
					} else {
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/2024";
					}
					?>
				</td>
			</tr>
			<tr>
				<td colspan=5 style="text-align: left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Received Rs.
					<span style="width:10px;border-bottom:1px solid #000">
						<b>
							<?= number_format($totalsubtotal, 0) ?>
						</b>
					</span>
					(In Words)
					<span style="width:10px;border-bottom:1px solid #000"><?= AmountInWords($totalsubtotal) ?></span>
					via <span style="width:10px;border-bottom:1px solid #000"><?= $paymenttype ?></span>
				
					from Mr./Mrs. <span style="width:10px;border-bottom:1px solid #000"><?= ($student->father_name != null) ? $student->father_name : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" ?></span>
					for admission in <span style="width:10px;border-bottom:1px solid #000"><?= $studentrelation->srclasses ?></span> for the year <?= $schoolyear->schoolyear ?> Student's Name
					<span style="width:10px;border-bottom:1px solid #000"><?php echo $studentrelation->srname; ?></span>
					Balance Amount
					<span style="width:10px;border-bottom:1px solid #000">
						<b><?= $totalinvoices - $totalglobalpayments - $weaverandfinesAmount > 0 ? number_format($totalinvoices - $totalglobalpayments - $weaverandfinesAmount, 0) : "NILL" ?></b>
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
				<td style="height:5rem"></td>
			</tr>
			<tr>
				<td style="border-top:1px dotted #000;"></td>
			</tr>
			<tr>
				<td style="height:5rem"></td>
			</tr>
		</table>
		<table width="100%" class="table" style="border:1px solid #000">

			<tr>
				<td colspan=5 class="pull-right">
					<span style="font-size:11px">Office Copy</span>
				</td>
			</tr>
			<tr>
				<td colspan="5">
					<table width="100%">
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
									<span style="font-size:12px">KHALAPUR TALUKA SHIKSHAN PRASARAK MANDAL'S</span><br />
									<span style="font-size:13px">P.P.GAGANGIRI MAHARAJ INTERNATIONAL SCHOOL,<br />KHOPOLI</span>
									<br />
									<br />
									<span style="font-size:12px">RECEIPT</span>
								</b>
							</td>
							<td style="width: 12.5%;">
								&nbsp;
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="width:25%">
					Payment Receipt No.
				</td>
				<td style="text-align:left">
					<?= '<b>' . ($globalpayment->payment_receipt_no == null) ? $globalpayment->globalpaymentID : $globalpayment->payment_receipt_no . '</b>' ?>
				</td>
				<td>
				</td>
				<td style="width:18%">
					Date:
				</td>
				<td style="width:18%;text-align:left">
					<?php if (date("Y", strtotime($globalpayment->payment_date)) > 0 && $globalpayment->payment_date != "null") {
						echo date("d/m/Y", strtotime($globalpayment->payment_date));
					} else {
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/2024";
					}
					?>


				</td>
			</tr>
			<tr>
				<td colspan=5 style="text-align: left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Received Rs.
					<span style="width:10px;border-bottom:1px solid #000">
						<b>
							<?= number_format($totalsubtotal, 0) ?>
						</b>
					</span>
					(In Words)
					<span style="width:10px;border-bottom:1px solid #000"><?= AmountInWords($totalsubtotal) ?></span>
					via <span style="width:10px;border-bottom:1px solid #000"><?= $paymenttype ?></span>
					from Mr./Mrs. <span style="width:10px;border-bottom:1px solid #000"><?= ($student->father_name != null) ? $student->father_name : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" ?></span>
					for admission in <span style="width:10px;border-bottom:1px solid #000"><?= $studentrelation->srclasses ?></span> for the year <?= $schoolyear->schoolyear ?> Student's Name
					<span style="width:10px;border-bottom:1px solid #000"><?php echo $studentrelation->srname; ?></span>
					Balance Amount
					<span style="width:10px;border-bottom:1px solid #000">
						<b><?= $totalinvoices - $totalglobalpayments - $weaverandfinesAmount > 0 ? number_format($totalinvoices - $totalglobalpayments - $weaverandfinesAmount, 0) : "NILL" ?></b>
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

	</div> -->
</body>

</html>