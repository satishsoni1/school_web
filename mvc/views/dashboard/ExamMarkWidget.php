      <div class="box ppg-widget-box">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-pencil-square-o"></i> Upcoming Exams</h3>
          <div class="box-tools pull-right">
            <a href="<?=base_url('examschedule/index')?>" class="btn btn-xs btn-default">View All</a>
          </div>
        </div>
        <div class="box-body" style="padding: 0px;">
          <?php if(customCompute($upcomingExams)) { ?>
            <table class="table table-hover">
              <tbody>
                <?php foreach ($upcomingExams as $schedule) { ?>
                  <tr>
                    <td style="width:52px; text-align:center;">
                      <div class="ppg-date-chip">
                        <span class="ppg-date-day"><?=date('d', strtotime($schedule->edate))?></span>
                        <span class="ppg-date-month"><?=date('M', strtotime($schedule->edate))?></span>
                      </div>
                    </td>
                    <td>
                      <strong><?=$schedule->exam?></strong><br>
                      <span class="text-muted" style="font-size:12px;"><?=$schedule->subject?><?=!empty($schedule->classes) ? ' &middot; '.$schedule->classes : ''?></span>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          <?php } else { ?>
            <div class="text-center text-muted" style="padding: 30px 10px;">
              <i class="fa fa-calendar-o" style="font-size:28px; opacity:0.4;"></i>
              <p style="margin-top:8px;">No upcoming exams scheduled.</p>
            </div>
          <?php } ?>
        </div>
        <div class="box-footer ppg-widget-footer">
          <i class="fa fa-check-square-o"></i> <?=number_format($marksEnteredCount ?? 0)?> marks entered this year
          &nbsp;&middot;&nbsp;
          <a href="<?=base_url('mark/index')?>">Go to Mark entry</a>
        </div>
      </div>
