<aside class="sidebar">
		<div class="sidebar-header">
            <div class="logocon">
			    <img src="../../../model/picture/Logo_1.png" alt="BSCS Logo" />
            </div>
		</div>

        <nav class="sidebar-menu">
            <h3>Home</h3>
            <a href="index.php?principal=<?php echo $principal; ?>" <?php echo (isset($cur) && $cur == 'index' ? 'class="active"' : ''); ?>>
                <i class="fas fa-th-large"></i> Dashboard
            </a>

            <h3>Menu</h3>
            <a href="principal_fileDirectory.php?principal=<?php echo $principal; ?>" <?php echo (isset($cur) && $cur == 'file' ? 'class="active"' : ''); ?>>
                <i class="fas fa-folder"></i> File Directory
            </a>
            <a href="principal_viewTeachers.php?principal=<?php echo $principal; ?>" <?php echo (isset($cur) && $cur == 'teacher' ? 'class="active"' : ''); ?>>
                <i class="fa-solid fa-chalkboard-user"></i> View Teachers
            </a>
            <a href="principal_currentClasses.php?principal=<?php echo $principal; ?>" <?php echo (isset($cur) && $cur == 'current' ? 'class="active"' : ''); ?>>
                <i class="fa-solid fa-pen-ruler"></i> Current Enrolled Classes
            </a>
            <a href="principal_studDirectory.php?principal=<?php echo $principal; ?>" <?php echo (isset($cur) && $cur == 'studdirectory' ? 'class="active"' : ''); ?>>
                <i class="fas fa-address-book"></i> Student Directory
            </a>
            <a href="principal_classArchives.php?principal=<?php echo $principal; ?>" <?php echo (isset($cur) && $cur == 'archives' ? 'class="active"' : ''); ?>>
                <i class="fa fa-archive"></i> Class Archives
            </a>
            <a href="principal_generate.php?principal=<?php echo $principal; ?>" <?php echo (isset($cur) && $cur == 'generate' ? 'class="active"' : ''); ?>>
                <i class="fas fa-file-alt"></i> Generate Document
            </a>
            <a href="principal_tracker.php?principal=<?php echo $principal; ?>" <?php echo (isset($cur) && $cur == 'tracker' ? 'class="active"' : ''); ?>>
                <i class="fas fa-clipboard-list"></i> Document Tracker
            </a>
            <a href="principal_calendarActivities.php?principal=<?php echo $principal; ?>" <?php echo (isset($cur) && $cur == 'calendar' ? 'class="active"' : ''); ?>>
                <i class="fas fa-calendar-alt"></i> Event Calendar
            </a>
            <div class="menu-bottom">
                <a href="../security/index.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </nav>
</aside>