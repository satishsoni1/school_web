      <div class="box ppg-widget-box">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-birthday-cake"></i> Today's Birthdays</h3>
        </div>
        <div class="box-body" style="padding: 0px;">
          <?php if(customCompute($todaysBirthdays)) { ?>
            <table class="table table-hover">
              <tbody>
                <?php foreach ($todaysBirthdays as $person) { ?>
                  <tr>
                    <td style="width:40px;"><i class="fa fa-gift ppg-widget-icon"></i></td>
                    <td>
                      <strong><?=$person['name']?></strong><br>
                      <span class="text-muted" style="font-size:12px;"><?=$person['role']?><?=$person['meta'] ? ' &middot; '.$person['meta'] : ''?></span>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          <?php } else { ?>
            <div class="text-center text-muted" style="padding: 30px 10px;">
              <i class="fa fa-birthday-cake" style="font-size:28px; opacity:0.4;"></i>
              <p style="margin-top:8px;">No birthdays today.</p>
            </div>
          <?php } ?>
        </div>
      </div>
