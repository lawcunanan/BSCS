<aside class="sidebar">
		<div class="sidebar-header">
            <div class="logocon">
			    <img src="../../../model/picture/Logo_1.png" alt="BSCS Logo" />
            </div>
		</div>

        <nav class="sidebar-menu">
            <h3>Home</h3>
            <a href="index.php?teacher=<?php echo $teacher; ?>" <?php echo (isset($cur) && $cur == 'index' ? 'class="active"' : '') ?>>
                <i class="fas fa-th-large"></i> Dashboard
            </a>

            <h3>Menu</h3>
            <a href="#">
                <i class="fas fa-folder"></i> File Directory
            </a>
            <a href="teacher_advisoryClass.php?teacher=<?php echo $teacher; ?>" <?php echo (isset($cur) && $cur == 'advisory' ? 'class="active"' : '') ?>>
                <i class="fas fa-user-plus"></i> Class Advisory
            </a>
            <a href="teacher_handled.php?teacher=<?php echo $teacher; ?>" <?php echo (isset($cur) && $cur == 'handled' ? 'class="active"' : '') ?>>
                <i class="fas fa-chalkboard-teacher"></i> Handled Classes
            </a>
            
            <a href="teacher_calendarActivities.php?teacher=<?php echo $teacher; ?>" <?php echo (isset($cur) && $cur == 'calendar' ? 'class="active"' : '') ?>>
                <i class="fas fa-calendar-alt"></i> Event Calendar
            </a>
            <div class="menu-bottom">
                <a href="../security/index.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </nav>
</aside>
