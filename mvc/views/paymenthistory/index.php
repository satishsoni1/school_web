
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-payment"></i> <?=$this->lang->line('panel_title')?></h3>

        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_paymenthistory')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                 <h5 class="page-header">
                            <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12 pull-right drop-marg">
                                <?php
                                    $array = array("0" => $this->lang->line("student_select_class"));
                                    if(customCompute($classes)) {
                                        foreach ($classes as $classa) {
                                            $array[$classa->classesID] = $classa->classes;
                                        }
                                    }
                                    echo form_dropdown("classesID", $array, set_value("classesID", $set), "id='classesID' class='form-control select2'");
                                ?>
                            </div>
                    </h5>
                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('slno')?></th>
                                <th><?=$this->lang->line('paymenthistory_student')?></th>
                                <th><?=$this->lang->line('paymenthistory_classes')?></th>
                                <th><?=$this->lang->line('paymenthistory_feetype')?></th>
                                <th><?=$this->lang->line('paymenthistory_method')?></th>
                                <th><?=$this->lang->line('paymenthistory_amount')?></th>
                                <th><?=$this->lang->line('paymenthistory_date')?></th>
                                <th>Receipt NO</th>
                                <?php if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('usertypeID') == 5)) { ?>
                                    <?php if(permissionChecker('paymenthistory_edit') || permissionChecker('paymenthistory_delete')) { ?>
                                        <th><?=$this->lang->line('action')?></th>
                                    <?php } ?>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(customCompute($payments)) {$i = 1; foreach($payments as $payment) { if($payment->paymentamount != '') { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('paymenthistory_student')?>">
                                        <?php echo $payment->srname; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('paymenthistory_classes')?>">
                                        <?php echo $payment->srclasses; ?>
                                    </td>
                                   
                                    <td data-title="<?=$this->lang->line('paymenthistory_feetype')?>">
                                        <?php echo $payment->feetype; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('paymenthistory_method')?>">
                                        <?php echo $payment->paymenttype; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('paymenthistory_amount')?>">
                                        <?php echo $payment->paymentamount; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('paymenthistory_date')?>">
                                        <?php echo ($payment->payment_date == NULL || $payment->payment_date == "0000-00-00")?date("d M Y", strtotime($payment->paymentdate)):date("d M Y", strtotime($payment->payment_date));  ?>
                                    </td>
                                    <td data-title="Receipt No">
                                        <?php echo $payment->payment_receipt_no;  ?>
                                    </td>
                                    <?php if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('usertypeID') == 5)) { ?>
                                        <?php if(permissionChecker('paymenthistory_edit') || permissionChecker('paymenthistory_delete')) { ?>
                                            <td data-title="<?=$this->lang->line('action')?>">
                                                <?php if($payment->paymenttype != 'Paypal' && $payment->paymenttype != 'Stripe' && $payment->paymenttype != 'PayUmoney') { ?>
                                                    <?php echo btn_edit('paymenthistory/edit/'.$payment->paymentID, $this->lang->line('edit')) ?>
                                                    <?php echo btn_delete('paymenthistory/delete/'.$payment->paymentID, $this->lang->line('delete')) ?>
                                                <?php } ?>
                                            </td>
                                        <?php } ?>
                                    <?php } ?>
                                </tr>
                            <?php $i++; } } } ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(".select2").select2();

    $('#classesID').change(function() {
        var classesID = $(this).val();
        if(classesID == 0) {
            $('#hide-table').hide();
            $('.nav-tabs-custom').hide();
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('paymenthistory/payment_list')?>",
                data: "id=" + classesID,
                dataType: "html",
                success: function(data) {
                    window.location.href = data;
                }
            });
        }
    });
    </script>