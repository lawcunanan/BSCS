<?php 
require "query.php";

$_SESSION['Document'] = (isset($_SESSION['Document']) ? $_SESSION['Document'] : '');
$_SESSION['DocuType'] = (isset($_SESSION['DocuType']) ? $_SESSION['DocuType'] : 'dpl');
$_SESSION['Student'] = (isset($_SESSION['Student']) ? $_SESSION['Student'] : 'fuck');


//principalmanageclass
 if (isset($_POST["btnSelectasAdviser"])) {
    $data['principalmanageclass']['update']['value'] = [$_POST["btnSelectasAdviser"], $school_year,$section,$grade];
    list($success, $result) = insert($data['principalmanageclass']['update']);

    if($success)
       echo alert("<script>showalert('primary', '<strong>Selected Adviser</strong> <br/> <br/>  Successfully appointed as the adviser for Grade {$grade}, Section {$section} ({$school_year})');</script>");
}



//principalviewstudentsgrades
else if(isset($_POST['filterQuarter_tvsg'])){
    $data['principalviewstudentsgrades']['perquarters']['value'] = [$enroll,  $_POST['filterQuarter_tvsg']];     
}



//principalgenerate
else if(isset($_POST['btn_Generate'])){
  
  $data['principalgenerate']['checkstudentSF10']['value'] =  [$_POST['gen_studentid'], $_POST['gen_studentid']]; 
  list($checkResult1, $checkValue1) = select($data['principalgenerate']['checkstudentSF10']);

  if($checkValue1[0]['missing'] == 'TRO'){ 
        $data['principalgenerate']['checkstudent']['value'] =  [$_POST['gen_studentid'], $_POST['docuType']];
        list($checkResult, $checkValue) = select( $data['principalgenerate']['checkstudent']);
        if(!$checkResult){
               if($_POST['docuType'] !== '3'){
                  $data['principalgenerate']['documentss']['value'] =  [$_POST['gen_studentid'], $_POST['docuType']];
               }else{

                  $data['principalgenerate']['checkstudentgrade']['value'] =  [$_POST['gen_studentid']];
                  list($checkResult, $checkValue) = select( $data['principalgenerate']['checkstudentgrade']);
                           
                  if(!$checkResult){

                     $data['principalgenerate']['checkstudentverify']['value'] =  [$_POST['gen_studentid']]; 
                     list($checkResult, $checkValue) = select( $data['principalgenerate']['checkstudentverify']);
                     if(!$checkResult){
                        $data['principalgenerate']['sf_information']['value'] =  [$_POST['gen_studentid']];
                     }else{
                        echo alert("<script>showalert('danger', '<strong>Document Request </strong> <br/> <br/>  Grades cannot be released. They have not yet been verified</b>. ');</script>");
                        unset($_POST['docuType']);
                        unset($_SESSION['Student']);
                        return;
                     }
                   }else{
                     echo alert("<script>showalert('danger', '<strong>Document Request </strong> <br/> <br/>  Student grades are incomplete. SF10 (Form 137) documents cannot be released.</b>. ');</script>");
                     unset($_POST['docuType']);
                     unset($_SESSION['Student']);
                     return;
                  }
               }
         }else{
            echo alert("<script>showalert('danger', '<strong>Document Request </strong> <br/> <br/> The requested document has already been retrieved on <b>{$checkValue[0]['date']}</b>. ');</script>");
            unset($_POST['docuType']);
            unset($_SESSION['Student']);
            return;
         }
         
         $_SESSION['DocuType'] = $_POST['docuType'];
         $_SESSION['Student'] =  $_POST['gen_studentid'];
  
  }else{
     echo alert("<script>showalert('danger', '<strong>Document Request </strong> <br/> <br/>  Document release is on hold due to incomplete student requirements.</b>. ');</script>");
     unset($_POST['docuType']);
     unset($_SESSION['Student']);
  }
   
}


else if(isset($_POST['btnUpdate'])){
   if(isset($_POST['newtemp']) && $_POST['newtemp'] !== '' && $_POST['newtemp'] !== null){
       $data['principalgenerate']['templatedocumentsinsert']['value'] = ['Template', $_POST['newtemp'], $_POST['certificateContent']];
       insert($data['principalgenerate']['templatedocumentsinsert']);
       echo alert("<script>showalert('primary', '<strong>Insert Template </strong> <br/> <br/> A new template named <b>{$_POST['newtemp']}</b>  has been successfully added.');</script>");

   }else{
       $data['principalgenerate']['templatedocuments']['value'] = [$_POST['certificateContent'], $_POST['btnUpdate']];
       insert($data['principalgenerate']['templatedocuments']);
       echo alert("<script>showalert('primary', '<strong>Update Template </strong> <br/> <br/> The content for the template <b>{$_POST['docuType']}</b> was successfully updated.');</script>");
   }

   unset($_POST['docuType']);
   unset($_POST['btnUpdate']);
}


else if(isset($_POST['btnPrint'])){ 
 $data['principalgenerate']['release']['value'] = [$_SESSION['Student'], $principal,  $_SESSION['DocuType'], $_POST['documentPurpose']];
 insert($data['principalgenerate']['release']);
 unset($_SESSION['DocuType']);
 unset($_SESSION['Student']);
}


else if(isset($_POST['btnDeleteTemplate'])){
    $data['principalgenerate']['templatedocumentsdelete']['value'] = [$_POST['btnDeleteTemplate']];
    insert($data['principalgenerate']['templatedocumentsdelete']);
    unset($_POST['docuTypedel']);
    echo alert("<script>showalert('danger', '<strong>Delete Template </strong> <br/> <br/> The template has been permanently deleted.');</script>");
}



//principalcalendar
else if(isset($_POST['btnSaveEvent'])){ 
     $title = '';
   if(isset($_POST['ev_title']) && $_POST['ev_title'] !== '' ){
         $title = $_POST['ev_title'];
         $end = (isset($_POST['ev_edate']) &&  $_POST['ev_edate'] !== '') ? $_POST['ev_edate'] : $_POST['ev_sdate'];
         $data['principalcalendar']['addevent']['value'] = [$principal,'Event', $title, $_POST['ev_description'], $_POST['ev_type'], $_POST['ev_sdate'], $end, $_POST['ev_stime'], $_POST['ev_etime'], 'Accepted'];
         insert($data['principalcalendar']['addevent']);
         $title =  $_POST['ev_title'];

         echo alert("<script>showalert('primary', '<strong>Activity Added </strong> <br/> <br/>  The new activity titled \' <b>{$title}</b> \' has been included in the schedule.');</script>");
   }else if(isset($_POST['startedSY'])  && $_POST['startedSY'] !== '' ) {
         $title =  $_POST['startedSY'] .'-'.$_POST['endSY'];
      
         list($checkResult, $checkValue) = select($data['principalcalendar']['submissiondrop']);

         if($checkValue[0]['1st'] === null && $checkValue[0]['2nd'] === null && $checkValue[0]['3rd'] === null && $checkValue[0]['4th'] === null){
              $data['principalcalendar']['addevent']['value'] = [$principal,'SY', $title, 'The '.$title.' school year offers new opportunities for students.', 'School-wide', $_POST['startedDateSY'], $_POST['endDateSY'], 'NA', 'NA', 'Accepted'];
              insert($data['principalcalendar']['addevent']);

              echo alert("<script>showalert('primary', '<strong>Activity Added </strong> <br/> <br/>  The new activity titled \' <b>{$title}</b> \' has been included in the schedule.');</script>");
         }else{
            echo alert("<script>showalert('danger', '<strong>New School Year</strong> <br/> <br/> Ensure all pending quarters are set before proceeding to add a new school year.');</script>");
         }
            
         $title =  'School Year for '. $title;
   
   }else if(isset($_POST['quarterSub'])  && $_POST['quarterSub'] !== '' ) {
         $title =  $_POST['quarterSub'];
         $description = 'The '.$_POST['quarterSub'].' is set for SY '.$_POST['schoolyearSub'].', and its deadline for grade submission is '.$_POST['deadlineSub'].'.';
         $data['principalcalendar']['addevent']['value'] = [$principal,'SY', $title, $description, 'School-wide', $_POST['deadlineSub'], $_POST['deadlineSub'], 'NA', 'NA', 'Accepted'];
         insert($data['principalcalendar']['addevent']);
       
         echo alert("<script>showalert('primary', '<strong>Activity Added </strong> <br/> <br/>  The new activity titled \' <b>{$title}</b> \' has been included in the schedule.');</script>");
   }

}


else if(isset($_POST['btnSave'])){ 
   $end = (isset($_POST['ev_edate']) &&  $_POST['ev_edate'] !== '') ? $_POST['ev_edate'] : $_POST['ev_sdate'];
   $stime= isset($_POST['ev_stime'])  ? $_POST['ev_stime'] : 'NA';
   $etime= isset($_POST['ev_etime'])  ? $_POST['ev_etime'] : 'NA'; 

   $data['principalcalendar']['editevent']['value'] = [$_POST['ev_title'], $_POST['ev_description'],  $_POST['ev_sdate'], $end, $stime, $etime, $_POST['btnSave']];
   insert($data['principalcalendar']['editevent']);
   echo alert("<script>showalert('primary', '<strong>Activity Updated </strong> <br/> <br/>  \' <b>{$_POST['ev_title']}</b> \' has been successfully updated.');</script>");
}


else if (isset($_POST['btnDelete']) || isset($_POST['btnAccept'])) { 

    $status = null; 

    if (isset($_POST['btnDelete'])) {
        $status = ['Deleted', $_POST['btnDelete']];
    } elseif (isset($_POST['btnAccept'])) {
        $status = ['Accepted', $_POST['btnAccept']];
    }

    if ($status !== null) {
        $data['principalcalendar']['statusevent']['value'] = [$status[0], $status[1]];
        insert($data['principalcalendar']['statusevent']);
        echo alert("<script>showalert('primary', '<strong>Activity Status</strong> <br/> <br/>  The activity status has been successfully set to <b>{$status[0]}</b>');</script>");
    }
}


else if (isset($_POST['btnReject'])) { 

   $data['principalcalendar']['statuseventreject']['value'] = ['Rejected', $_POST['rejectreason'], $_POST['btnReject']];
   insert($data['principalcalendar']['statuseventreject']);
   echo alert("<script>showalert('primary', '<strong>Activity Status</strong> <br/> <br/>  The activity status has been successfully set to <b>'Rejected'</b>');</script>");

}

?>