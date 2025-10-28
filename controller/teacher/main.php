<?php 
require "query.php";

//teacher_submit
if(isset($_POST['filterQuarter'])){
   $filterquarter = (isset($_POST['filterQuarter'])  ? $_POST['filterQuarter'] : 1);
   $data['teacher_submitgrades']['perquarter']['value'] = [$school_year, $section, $grade, $filterquarter];  
}



//teacher_viewstudgrades
else if(isset($_POST['filterQuarter_tvsg'])){
    $data['teacher_viewstudentgrades']['perquarters']['value'] = [$enroll,  $_POST['filterQuarter_tvsg']];     
}


//teacher_submitgrade
else if(isset($_POST['btn_Excel'])){
   unset($_SESSION['studentData']);
  
   $handled = ['School year', 'Section',  'grade Level'];
   $studentData  = import("studentGrade",$handled,   headersf10());
   //['Filipino', 'English', 'Music', 'Arts']

   $studentData['Details']['grade level'] = $grade;
   $studentData['Details']['section'] = $section;
   $studentData['Details']['school year'] = $school_year;

   $studentData['Handled']['type'] = 'student_Grades';
   $_SESSION['studentData'] = $studentData; 
}

else if(isset($_POST['btnUpload'])){
   if (isset($_SESSION['studentData'])) {
      list($success, $result) =  db_gradesStudents($_SESSION['studentData']);
      $studentData = $result;
      unset($_SESSION['studentData']);
   } else {
      unset($_SESSION['studentData']);
   }
}






//teacher_calendar
else if(isset($_POST['btnSaveEvent'])){ 
   $end = (isset($_POST['ev_edate']) &&  $_POST['ev_edate'] !== '') ? $_POST['ev_edate'] : $_POST['ev_sdate'];
   $data['teachercalendar']['addevent']['value'] = [$teacher, $_POST['ev_title'], $_POST['ev_description'], $_POST['ev_type'], $_POST['ev_sdate'], $end, $_POST['ev_stime'], $_POST['ev_etime'], 'Pending'];
   insert($data['teachercalendar']['addevent']);
   echo alert("<script>showalert('primary', '<strong>Activity Added </strong> <br/> <br/>  The new activity titled \' <b>{$_POST['ev_title']}</b> \' has been included in the schedule.');</script>");
}

else if(isset($_POST['btnSave'])){ 
   $end = (isset($_POST['ev_edate']) && $_POST['ev_edate'] !== '') ? $_POST['ev_edate'] : $_POST['ev_sdate'];
   $data['teachercalendar']['editevent']['value'] = [$_POST['ev_title'], $_POST['ev_description'],  $_POST['ev_sdate'], $end, $_POST['ev_stime'], $_POST['ev_etime'], $_POST['btnSave']];
   insert($data['teachercalendar']['editevent']);
   echo alert("<script>showalert('primary', '<strong>Activity Updated </strong> <br/> <br/>  \' <b>{$_POST['ev_title']}</b> \' has been successfully updated.');</script>");
}

else if (isset($_POST['btnDelete'])) { 
      $data['teachercalendar']['statusevent']['value'] = ['Deleted', $_POST['btnDelete']];
      insert($data['teachercalendar']['statusevent']);
      echo alert("<script>showalert('primary', '<strong>Activity Status</strong> <br/> <br/>  The activity status has been successfully set to <b>Deleted</b>');</script>");
}




?>