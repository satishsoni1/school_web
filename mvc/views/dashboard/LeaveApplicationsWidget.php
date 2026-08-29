      <div class="box ppg-widget-box">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-calendar-times-o"></i> Pending Leave Applications</h3>
          <div class="box-tools pull-right">
            <a href="<?=base_url('leaveapplication/index')?>" class="btn btn-xs btn-default">View All</a>
          </div>
        </div>
        <div class="box-body" style="padding: 0px;">
          <?php if(customCompute($pendingLeaveApplications)) { ?>
            <table class="table table-hover">
              <tbody>
                <?php foreach ($pendingLeaveApplications as $leave) {
                    $applicantName = $leave->aname ?: ($leave->tname ?: ($leave->sname ?: ($leave->pname ?: $leave->uname)));
                ?>
                  <tr>
                    <td style="width:40px;"><i class="fa fa-user-circle-o ppg-widget-icon"></i></td>
                    <td>
                      <strong><?=$applicantName ?: 'Unknown'?></strong><br>
                      <span class="text-muted" style="font-size:12px;"><?=isset($leave->from_date) ? date('d M', strtotime($leave->from_date)) : ''?><?=isset($leave->to_date) ? ' – '.date('d M', strtotime($leave->to_date)) : ''?></span>
                    </td>
                    <td style="width:90px; text-align:right;">
                      <a href="<?=base_url('leaveapplication/view/'.$leave->leaveapplicationID)?>" class="btn btn-xs btn-primary">Review</a>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          <?php } else { ?>
            <div class="text-center text-muted" style="padding: 30px 10px;">
              <i class="fa fa-check-circle" style="font-size:28px; opacity:0.4;"></i>
              <p style="margin-top:8px;">No pending leave applications.</p>
            </div>
          <?php } ?>
        </div>
      </div>
