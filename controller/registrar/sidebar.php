<aside class="sidebar">
		<div class="sidebar-header">
            <div class="logocon">
			    <img src="../../../model/picture/Logo_1.png" alt="BSCS Logo" />
            </div>
		</div>

        <nav class="sidebar-menu">
            <h3>Home</h3>
            <a href="index.php?registrar=<?php echo $registrar; ?>" <?php echo (isset($cur) && $cur == 'index' ? 'class="active"' : ''); ?>>
                <i class="fas fa-th-large"></i> Dashboard
            </a>

            <h3>Menu</h3>
            <a href="#?registrar=<?php echo $registrar; ?>" <?php echo (isset($cur) && $cur == 'file' ? 'class="active"' : ''); ?>>
                <i class="fas fa-folder"></i> File Directory
            </a>
            <a href="registrar_enroll.php?registrar=<?php echo $registrar; ?>" <?php echo (isset($cur) && $cur == 'enroll' ? 'class="active"' : ''); ?>>
                <i class="fas fa-user-plus"></i> Enroll Class
            </a>
            <a href="registrar_studDirectory.php?registrar=<?php echo $registrar; ?>" <?php echo (isset($cur) && $cur == 'studdirectory' ? 'class="active"' : ''); ?>>
                <i class="fas fa-address-book"></i> Student Directory
            </a>
            <a href="registrar_classArchives.php?registrar=<?php echo $registrar; ?>" <?php echo (isset($cur) && $cur == 'archives' ? 'class="active"' : ''); ?>>
                <i class="fa fa-archive"></i> Class Archives
            </a>
            <a href="registrar_verify.php?registrar=<?php echo $registrar; ?>" <?php echo (isset($cur) && $cur == 'verify' ? 'class="active"' : '') ?>>
                <i class="fas fa-file-alt"></i> View SF10
            </a>
            <a href="registrar_generate.php?registrar=<?php echo $registrar; ?>" <?php echo (isset($cur) && $cur == 'generate' ? 'class="active"' : ''); ?>>
                <i class="fas fa-file-alt"></i> Generate Documents
            </a>
            <a href="registrar_tracker.php?registrar=<?php echo $registrar; ?>" <?php echo (isset($cur) && $cur == 'tracker' ? 'class="active"' : ''); ?>>
                <i class="fas fa-clipboard-list"></i> Document Tracker
            </a>
            <a href="registrar_calendarActivities.php?registrar=<?php echo $registrar; ?>" <?php echo (isset($cur) && $cur == 'calendar' ? 'class="active"' : ''); ?>>
                <i class="fas fa-calendar-alt"></i> Event Calendar
            </a>
            <div class="menu-bottom">
                <a href="../security/index.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </nav>
</aside>
