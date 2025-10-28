<aside class="sidebar">
        <div class="sidebar-header">
            <div class="logocon">
			   <img src="../../../model/picture/Logo_1.png" alt="BSCS Logo" />
            </div>
		</div>
        <nav class="sidebar-menu">
            <h3>Home</h3>
            <a href="index.php?admin=<?php echo $admin; ?>" ><i class="fas fa-th-large" <?php echo (isset($cur) && $cur == 'index' ? 'class="active"' : '') ?>
            ></i> Dashboard</a>
            <h3>Menu</h3>
            
            <a href="admin_fileDirectory.php"?admin=<?php echo $admin; ?> 
            ><i class="fas fa-folder"></i> File Directory</a>
            <a href="admin_schoolInfo.php?admin=<?php echo  $admin; ?>" <?php echo (isset($cur) && $cur == 'school' ? 'class="active"' : '') ?> >
            <i class="fa-solid fa-school"></i> School Information</a>
            <a href="admin_manageAccounts.php?admin=<?php echo $admin; ?>"  <?php echo (isset($cur) && $cur == 'manage' ? 'class="active"' : '') ?>
            ><i class="fas fa-users-cog"></i> Manage Accounts</a>
            <a href="admin_manageHeaders.php?admin=<?php echo $admin; ?>" <?php echo (isset($cur) && $cur == 'headers' ? 'class="active"' : '') ?>
            ><i class="fa-solid fa-table"></i> Manage Excel Headers</a>
            <a href="admin_calendarActivities.php?admin=<?php echo $admin; ?>" <?php echo (isset($cur) && $cur == 'activities' ? 'class="active"' : '') ?>
            ><i class="fas fa-calendar-alt"></i> Event Calendar</a>
            <div class="menu-bottom">
                <a href="../security/index.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </nav>
</aside>