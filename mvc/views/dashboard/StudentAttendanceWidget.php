      <div class="box ppg-widget-box">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-graduation-cap"></i> Student Attendance Today</h3>
          <div class="box-tools pull-right">
            <a href="<?=base_url('sattendance/index')?>" class="btn btn-xs btn-default">View All</a>
          </div>
        </div>
        <div class="box-body">
          <div class="ppg-attendance-stats">
            <div class="ppg-attendance-stat ppg-stat-present">
              <span class="ppg-stat-count"><?=$studentAttendanceToday['present'] ?? 0?></span>
              <span class="ppg-stat-label">Present</span>
            </div>
            <div class="ppg-attendance-stat ppg-stat-absent">
              <span class="ppg-stat-count"><?=$studentAttendanceToday['absent'] ?? 0?></span>
              <span class="ppg-stat-label">Absent</span>
            </div>
            <div class="ppg-attendance-stat ppg-stat-leave">
              <span class="ppg-stat-count"><?=$studentAttendanceToday['leave'] ?? 0?></span>
              <span class="ppg-stat-label">Leave</span>
            </div>
            <div class="ppg-attendance-stat ppg-stat-unmarked">
              <span class="ppg-stat-count"><?=$studentAttendanceToday['unmarked'] ?? 0?></span>
              <span class="ppg-stat-label">Not Marked</span>
            </div>
          </div>
        </div>
      </div>
