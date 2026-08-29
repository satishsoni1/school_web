<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
</head>

<body>
	<div>
		<?php foreach ($student_data as $student_list) { 
			$amount = (float)$student_list->amount;
			?>
			<table width="100%" class="table" style="border:0px">
				<tr>
					<td style="height:1rem"></td>
				</tr>
			</table>
			<table width="100%" class="table" style="border:1px solid #000">

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
							<span style="font-size:15px">BOOK RECEIPT</span>
						</b>
					</td>
				</tr>
				<tr>
					<td style="width:15%">
						Receipt No.
					</td>
					<td style="text-align:left">
						<?= '<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>' ?>
					</td>
					<td>
					</td>
					<td style="width:18%">
						Date:
					</td>
					<td style="width:18%;text-align:left">
					&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/2025
					</td>
				</tr>
				<tr>
					<td colspan=5 style="text-align: left;">
						<b> Name of the Student: </b>
						<span style="width:10px;border-bottom:1px solid #000">
							<?= $student_list->name ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						</span>
					</td>
				</tr>
				<tr>
					<td colspan=5 style="text-align: left;">
						<b> Class: </b>
						<span style="width:10px;border-bottom:1px solid #000">
							<?= $student_list->class ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						</span>
					</td>
				</tr>
				<tr>
					<td colspan=5 style="text-align: left;">
						<b> Amount (in ₹ & Words): </b>
						<span style="width:10px;border-bottom:1px solid #000">
							<?= $student_list->amount ?>/- only. &nbsp; (in Words) <?= AmountInWords($amount) ?> only.
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
				<td style="border-top:#000 solid 1px">School Stamp </td>
				<td></td>
			</tr>
			</table>
			<table width="100%" class="table" style="border:0px">
		<tr>
			<td style="height:1rem"></td>
		</tr>
		<tr>
			<td style="border-top:1px dotted #000;"></td>
		</tr>
		<tr>
			<td style="height:1rem"></td>
		</tr>
		</table>
		<table width="100%" class="table" style="border:1px solid #000">

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
							<span style="font-size:15px">BOOK RECEIPT</span>
						</b>
					</td>
				</tr>
				<tr>
					<td style="width:15%">
						Receipt No.
					</td>
					<td style="text-align:left">
						<?= '<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>' ?>
					</td>
					<td>
					</td>
					<td style="width:18%">
						Date:
					</td>
					<td style="width:18%;text-align:left">
						&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/2025
					</td>
				</tr>
				<tr>
					<td colspan=5 style="text-align: left;">
						<b> Name of the Student: </b>
						<span style="width:10px;border-bottom:1px solid #000">
							<?= $student_list->name ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						</span>
					</td>
				</tr>
				<tr>
					<td colspan=5 style="text-align: left;">
						<b> Class: </b>
						<span style="width:10px;border-bottom:1px solid #000">
							<?= $student_list->class ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						</span>
					</td>
				</tr>
				<tr>
					<td colspan=5 style="text-align: left;">
						<b> Amount (in ₹ & Words): </b>
						<span style="width:10px;border-bottom:1px solid #000">
							<?= $student_list->amount ?>/- only. &nbsp; (in Words) <?= AmountInWords($amount) ?> only.
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
				<td style="border-top:#000 solid 1px">School Stamp </td>
				<td></td>
			</tr>
			</table>
			<p style="page-break-after: always;">&nbsp;</p>
		<?php } ?>
</body>

</html>