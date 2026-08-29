<div class="well">
    <div class="row">
        <div class="col-sm-6">
            <button class="btn-cs btn-sm-cs" onclick="javascript:printDiv('printablediv')"><span class="fa fa-print"></span> <?=$this->lang->line('print')?> </button>
            
            <?php if(permissionChecker('certificate_template_edit')) { echo btn_sm_edit('certificate_template/edit/'.$certificate_template->certificate_templateID, $this->lang->line('edit')); }
            ?>
        </div>

        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                <li><a href="<?=base_url("certificate_template/index")?>"><?=$this->lang->line('panel_title')?></a></li>
                <li class="active"><?=$this->lang->line('menu_view')?></li>
            </ol>
        </div>
    </div>
</div>


<section class="panel">
    <div class="panel-body bio-graph-info">
        <div id="printablediv" class="box-body">

                <table class="table table-bordered">
  <tr>
    <th colspan="2">K.T.S.P. MANDAL'S P.P. GAGANGIRI MAHARAJ INTERNATIONAL SCHOOL</th>
  </tr>
  <tr>
    <th colspan="2">KHOPOLI (Proposed CBSE)</th>
  </tr>
  <tr>
    <th>CERTIFICATE NUMBER:</th>
    <td></td>
  </tr>
  <tr>
    <th>G.R. NO.:</th>
    <td></td>
  </tr>
  <tr>
    <th colspan="2">LEAVING CERTIFICATE</th>
  </tr>
  <tr>
    <th>STUDENT</th>
    <td></td>
  </tr>
  <tr>
    <th>U.L. D. NO. (AADHAR)</th>
    <td></td>
  </tr>
  <tr>
    <th>NAME OF THE PUPIL:</th>
    <td></td>
  </tr>
  <tr>
    <th>MOTHER'S NAME:</th>
    <td></td>
  </tr>
  <tr>
    <th>NATIONALITY:</th>
    <td></td>
  </tr>
  <tr>
    <th>MOTHER TONGUE:</th>
    <td></td>
  </tr>
  <tr>
    <th>RELIGION:</th>
    <td></td>
  </tr>
  <tr>
    <th>CASTE:</th>
    <td></td>
  </tr>
  <tr>
    <th>SUB CASTE:</th>
    <td></td>
  </tr>
  <tr>
    <th>PLACE OF BIRTH:</th>
    <td></td>
  </tr>
  <tr>
    <th>TOWN/CITY:</th>
    <td></td>
  </tr>
  <tr>
    <th>TALUKA:</th>
    <td></td>
  </tr>
  <tr>
    <th>DISTRICT</th>
    <td></td>
  </tr>
  <tr>
    <th>STATE</th>
    <td></td>
  </tr>
  <tr>
    <th>COUNTRY</th>
    <td></td>
  </tr>
  <tr>
    <th>DATE OF BIRTH:</th>
    <td></td>
  </tr>
  <tr>
    <th>(in words):</th>
    <td></td>
  </tr>
  <tr>
    <th>LAST CLASS & SCHOOL ATTENDED:</th>
    <td></td>
  </tr>
  <tr>
    <th>DATE OF ADMISSION:</th>
    <td></td>
  </tr>
  <tr>
    <th>CLASS:</th>
    <td></td>
  </tr>
  <tr>
    <th>PROGRESS:</th>
    <td></td>
  </tr>
  <tr>
    <th>CONDUCT:</th>
    <td></td>
  </tr> 
  <tr>
    <th>DATE OF LEAVING SCHOOL:</th>
    <td></td>
  </tr>
  <tr>
    <th>STANDARD IN WHICH STUDYING AND SINCE WHEN:</th>
    <td></td>
  </tr>
  <tr>
    <th>REASON FOR LEAVING SCHOOL:</th>
    <td></td>
  </tr>
  <tr>
    <th>REMARKS:</th>
    <td></td>
  </tr>
  <tr>
    <th colspan="2">Certified that the above information is in accordance with the School General Register.</th>
  </tr>
  <tr>
    <th>DATE</th>
    <td></td>
    <th>MONTH</th>
    <td></td>
    <th>YEAR</th>
    <td></td>
  </tr>
  <tr>
    <th>CLASS TEACHER</th>
    <td></td>
    <th>CLERK</th>
    <td></td>
    <th>PRINCIPAL</th>
    <td></td>
  </tr>
</table>

                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">

    function printDiv(divID) {
        //Get the HTML of div
        var divElements = document.getElementById(divID).innerHTML;
        //Get the HTML of whole page
        var oldPage = document.body.innerHTML;

        //Reset the page's HTML with div's HTML only
        document.body.innerHTML =
          "<html><head><title></title></head><body>" +
          divElements + "</body>";

        //Print Page
        window.print();

        //Restore orignal HTML
        document.body.innerHTML = oldPage;
        
        closeWindow();
    }

    function closeWindow() {
        location.reload();
    }
</script>


