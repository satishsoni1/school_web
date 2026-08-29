<div class="row">
    <div class="col-md-12">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-users"></i> Family Students Found</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <?php if(!empty($siblings)) { foreach($siblings as $sib) { ?>
                    <div class="col-md-4">
                        <div class="external-event bg-light-blue ui-draggable ui-draggable-handle" style="cursor: default;">
                            <span style="font-weight:bold; font-size:16px;"><?= $sib->name ?></span><br>
                            Class: <?= $sib->classesID ?> (Roll: <?= $sib->roll ?>)<br>
                            Reg No: <?= $sib->registerNO ?>
                        </div>
                    </div>
                    <?php }} else { echo "<div class='col-md-12'>No siblings found.</div>"; } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-list"></i> Select Invoices to Print</h3>
            </div>
            <div class="box-body">
                
                <form action="<?= base_url('twins/print_action') ?>" method="POST" target="_blank">
                    
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr class="active">
                                <th width="5%" class="text-center">
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th>Invoice No</th>
                                <th>Student Name</th>
                                <th>Class</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($invoices)) { foreach($invoices as $inv) { 
                                // Determine Status Label
                                $status = '<span class="label label-danger">Unpaid</span>';
                                if($inv->maininvoicestatus == 1) $status = '<span class="label label-warning">Partial</span>';
                                if($inv->maininvoicestatus == 2) $status = '<span class="label label-success">Paid</span>';
                            ?>
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" name="maininvoiceID[]" value="<?= $inv->maininvoiceID ?>" class="chk_inv">
                                </td>
                                <td><?= $inv->maininvoiceID ?></td>
                                <td style="font-weight:bold; color:#3c8dbc;"><?= $inv->student_name ?></td>
                                <td><?= $inv->classes ?></td>
                                <td><?= date('d M Y', strtotime($inv->maininvoicedate)) ?></td>
                                <td><?= $status ?></td>
                                <td>
                                    <a href="<?= base_url('invoice/print_preview/'.$inv->maininvoiceID) ?>" class="btn btn-xs btn-default" target="_blank">
                                        <i class="fa fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            <?php }} else { ?>
                                <tr><td colspan="7" class="text-center">No Invoices Found for this family.</td></tr>
                            <?php } ?>
                        </tbody>
                    </table>

                    <hr>
                    
                    <div class="text-right">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fa fa-print"></i> Print Selected Invoices
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<script>
    // "Select All" functionality
    $('#selectAll').click(function(event) {   
        if(this.checked) {
            $('.chk_inv').each(function() { this.checked = true; });
        } else {
            $('.chk_inv').each(function() { this.checked = false; });
        }
    });
</script>