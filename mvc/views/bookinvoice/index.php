
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-invoice"></i> Book Receipt</h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> Dashboard</a></li>
            <li class="active">Book Receipt</li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('usertypeID') == 5)) { ?>
                    <?php if(permissionChecker('invoice_add')) { ?>
                        <h5 class="page-header">
                            <a href="<?php echo base_url('bookinvoice/add') ?>">
                                <i class="fa fa-plus"></i> 
                                Add Receipt
                            </a>
                        </h5>
                    <?php } ?>
                <?php } ?>

                <div id="hide-table">
                    <table id="example4" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <!-- <th>S.No.</th> -->
                                <th>Name</th>
                                <th>Class</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(customCompute($student_data)) {$i = 1; foreach($student_data as $student) { ?>
                                <tr>
                                    <!-- <td data-title="S.No.">
                                        <?php echo $i; ?>
                                    </td> -->

                                    <td data-title="Name">
                                        <?php echo $student->name; ?>
                                    </td>

                                     <td data-title="Class">
                                        <?php echo $student->class; ?>
                                    </td>

                                    <td data-title="Amount">
                                        <?= number_format($student->amount) ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('invoice_date')?>">
                                        <?php echo date("d M Y", strtotime($student->created_date)) ; ?>
                                    </td>

                                    <?php if(permissionChecker('bookinvoice_view') || permissionChecker('bookinvoice_edit') || permissionChecker('bookinvoice_delete')) { ?>
                                    <td data-title="<?=$this->lang->line('action')?>">
                                        <a href="<?= base_url('bookinvoice/pdf_preview/'.$student->id) ?>" target="_blank" class="btn btn-success btn-xs mrg" data-placement="top" data-toggle="tooltip" data-original-title="View"><i class="fa fa-check-square-o"></i></a>
                                        <?php if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('usertypeID') == 5)) { ?>
                                            <!-- <?php echo btn_edit('bookinvoice/edit/'.$student->id, $this->lang->line('edit')); ?> -->
                                            <?php echo btn_delete('bookinvoice/delete/'.$student->id, $this->lang->line('delete'));  ?>
                                        <?php } ?>

                                    </td>
                                    <?php } ?>
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function () {
            $('#example4').DataTable({
             order: [[3, 'desc']],
              dom : 'Bfrtip',
              buttons : [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
              ],
              search : false
            });
          });
        </script>