      <div class="box ppg-widget-box">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-comments-o"></i> Recent Complaints</h3>
          <div class="box-tools pull-right">
            <a href="<?=base_url('complain/index')?>" class="btn btn-xs btn-default">View All</a>
          </div>
        </div>
        <div class="box-body" style="padding: 0px;">
          <?php if(customCompute($recentComplaints)) { ?>
            <table class="table table-hover">
              <tbody>
                <?php foreach ($recentComplaints as $complaint) { ?>
                  <tr>
                    <td style="width:40px;"><i class="fa fa-exclamation-circle ppg-widget-icon"></i></td>
                    <td>
                      <strong><?=strip_tags(strlen($complaint->title) > 40 ? substr($complaint->title, 0, 40).'...' : $complaint->title)?></strong><br>
                      <span class="text-muted" style="font-size:12px;"><?=getNameByUsertypeIDAndUserID($complaint->create_usertypeID, $complaint->create_userID)?><?=!empty($complaint->create_date) ? ' &middot; '.date('d M', strtotime($complaint->create_date)) : ''?></span>
                    </td>
                    <td style="width:70px; text-align:right;">
                      <a href="<?=base_url('complain/view/'.$complaint->complainID)?>" class="btn btn-xs btn-primary">View</a>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          <?php } else { ?>
            <div class="text-center text-muted" style="padding: 30px 10px;">
              <i class="fa fa-smile-o" style="font-size:28px; opacity:0.4;"></i>
              <p style="margin-top:8px;">No complaints filed.</p>
            </div>
          <?php } ?>
        </div>
      </div>
