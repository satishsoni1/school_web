      <div class="box ppg-widget-box">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-id-badge"></i> Staff Attendance Today</h3>
          <div class="box-tools pull-right">
            <a href="<?=base_url('tattendance/index')?>" class="btn btn-xs btn-default">View All</a>
          </div>
        </div>
        <div class="box-body">
          <div class="ppg-attendance-stats">
            <div class="ppg-attendance-stat ppg-stat-present">
              <span class="ppg-stat-count"><?=$staffAttendanceToday['present'] ?? 0?></span>
              <span class="ppg-stat-label">Present</span>
            </div>
            <div class="ppg-attendance-stat ppg-stat-absent">
              <span class="ppg-stat-count"><?=$staffAttendanceToday['absent'] ?? 0?></span>
              <span class="ppg-stat-label">Absent</span>
            </div>
            <div class="ppg-attendance-stat ppg-stat-leave">
              <span class="ppg-stat-count"><?=$staffAttendanceToday['leave'] ?? 0?></span>
              <span class="ppg-stat-label">Leave</span>
            </div>
            <div class="ppg-attendance-stat ppg-stat-unmarked">
              <span class="ppg-stat-count"><?=$staffAttendanceToday['unmarked'] ?? 0?></span>
              <span class="ppg-stat-label">Not Marked</span>
            </div>
          </div>
        </div>
      </div>
