<?php 
require "query.php";
$studentData = (isset($studentData) ? $studentData : '');
$_SESSION['Document'] = (isset($_SESSION['Document']) ? $_SESSION['Document'] : '');
$_SESSION['DocuType'] = (isset($_SESSION['DocuType']) ? $_SESSION['DocuType'] : '');
$_SESSION['Student'] = (isset($_SESSION['Student']) ? $_SESSION['Student'] : '');


//registrar_manageenrolled
 if(isset($_POST['btnRemove'])){
     
     if($_POST['manage_reason'] !== 'Section'){
         $data['registrar_manageenrolled']['status']['value'] = [$_POST['manage_reason'], $_POST['manage_remarks'], $_POST['btnRemove']];
         insert($data['registrar_manageenrolled']['status']);
         echo alert("<script>showalert('primary', '<strong>Update Complete</strong> <br/> <br/> The student\'s status has been successfully changed to <b>".$_POST['manage_reason']."<b/>.');</script>");

     }else{
         $data['registrar_manageenrolled']['section']['value'] = [$_POST['manage_section'], $_POST['btnRemove']];
         insert($data['registrar_manageenrolled']['section']);
         echo alert("<script>showalert('primary', '<strong>Transfer Section</strong> <br/> <br/> The student was successfully moved to <b>".$_POST['manage_section']."<b/>.');</script>");
     }
}



//register_viewstudgrades
else if(isset($_POST['filterQuarter_tvsg'])){
    $data['registrarviewstudentsgrades']['perquarters']['value'] = [$enroll,  $_POST['filterQuarter_tvsg']];     
}



//teacher_studentinfo
else if (isset($_POST['btnUploadReq'])) {

    if (isset($_FILES["uploadReq"]) && $_FILES["uploadReq"]["error"] == UPLOAD_ERR_OK) {
         $fileTmpPath = $_FILES["uploadReq"]["tmp_name"];
         $fileName = $_FILES["uploadReq"]["name"];
         $fileData = file_get_contents($fileTmpPath);

         $data['registrarstudentinfo']['insertReq']['value'] =  [$student, $registrar, $_POST['btnUploadReq'], $fileData,];
         insert($data['registrarstudentinfo']['insertReq']);
         echo alert("<script>showalert('primary', '<strong>Requirements </strong> <br/> <br/>  The document has been successfully uploaded. ');</script>");
      }
}


else if (isset($_POST['btnUpdateReq'])) {

    if (isset($_FILES["updateReq"]) && $_FILES["updateReq"]["error"] == UPLOAD_ERR_OK) {
         $fileTmpPath = $_FILES["updateReq"]["tmp_name"];
         $fileName = $_FILES["updateReq"]["name"];
         $fileData = file_get_contents($fileTmpPath);

         $data['registrarstudentinfo']['updateReq']['value'] =  [$fileData, $_POST['btnUpdateReq']];
         insert($data['registrarstudentinfo']['updateReq']);
         echo alert("<script>showalert('primary', '<strong>Requirements </strong> <br/> <br/>  The document has been successfully updated. ');</script>");
   }
}


else if (isset($_POST['btnDownload'])) {
  studentDocuments($_POST['btnDownload']);
}


else if(isset($_POST['UploadTran'])){
   unset($_SESSION['studentData']);
   list($name, $grade) = explode('|', $_POST['UploadTran']);

   $handled = ['School year', 'Section',  'grade Level', $name];
   $studentData  = import("studentGrade",$handled, headersf10());

   if($studentData){
      if (in_array($studentData['Handled']['grade level'], explode(',',  $grade))) {
        
        $data['registrarstudentinfo']['enrollStudentSY']['value'] =  [$studentData['Handled']['grade level'], $studentData['Handled']['school year'],$studentData['Handled']['grade level'], $studentData['Handled']['school year'],$studentData['Handled']['grade level'], $studentData['Handled']['school year'], $student];
        list($checkResult, $checkValue) = select($data['registrarstudentinfo']['enrollStudentSY']);

         if($checkResult){
            $studentData['Details']['grade level'] =  $studentData['Handled']['grade level'];
            $studentData['Details']['section'] =  $studentData['Handled']['section'];
            $studentData['Details']['school year'] =  $studentData['Handled']['school year'];
            $studentData['Handled']['type'] = 'student_Grades';
            $_SESSION['studentData'] = $studentData; 
         }else{
            echo alert("<script>showalert('danger', '<strong>School Year </strong> <br/> <br/> Ensure the school year is correct. Please review and confirm. ');</script>");

         }
            
      } else {
         echo alert("<script>showalert('danger', '<strong>Grade submissions </strong> <br/> <br/>  Grade submissions are allowed for grade levels {$grade} only. ');</script>");
         unset($_SESSION['studentData']);
         return;

      }
   }
}


 else if(isset($_POST['btnUpdate_Grades'])){
      if (isset($_SESSION['studentData'])) {

         $data['registrarstudentinfo']['enrollCheck']['value'] = [$_SESSION['studentData']['Handled']['grade level'], $student];
         list($check, $id) =  select($data['registrarstudentinfo']['enrollCheck']);
         
         if(!$check){
            $data['registrarstudentinfo']['enrollStudent']['value'] = [$student, $registrar, $_SESSION['studentData']['Handled']['grade level'], $_SESSION['studentData']['Handled']['section'], $_SESSION['studentData']['Handled']['school year'], 'Verified'];
            insert($data['registrarstudentinfo']['enrollStudent']);
         }

         list($success, $result) =  db_gradesStudents($_SESSION['studentData']);
         $studentData = $result;
         unset($_SESSION['studentData']);
         
      } else {
         unset($_SESSION['studentData']);
      }

      unset($_POST['btnUpdate_Grades']);
}
   


//registrar_generate
else if(isset($_POST['btn_Ge'])){
   $searchGe = (isset($_POST['search_Ge'])  ? $_POST['search_Ge'] : '');
   $data['registrar_generate']['display']['value'] = [$searchGe, $searchGe, $searchGe, $searchGe, $searchGe];
}


else if(isset($_POST['btn_Generate'])){

  $data['registrar_generate']['checkstudentSF10']['value'] =  [$_POST['gen_studentid'], $_POST['gen_studentid']]; 
  list($checkResult1, $checkValue1) = select($data['registrar_generate']['checkstudentSF10']);

  if( $checkValue1[0]['missing'] == 'TRO'){ 
        $data['registrar_generate']['checkstudent']['value'] =  [$_POST['gen_studentid'], $_POST['docuType']];
        list($checkResult, $checkValue) = select( $data['registrar_generate']['checkstudent']);
        if(!$checkResult){
               if($_POST['docuType'] !== '3'){
                  $data['registrar_generate']['documentss']['value'] =  [$_POST['gen_studentid'], $_POST['docuType']];
               }else{

                  $data['registrar_generate']['checkstudentgrade']['value'] =  [$_POST['gen_studentid']];
                  list($checkResult, $checkValue) = select( $data['registrar_generate']['checkstudentgrade']);
                           
                  if(!$checkResult){

                     $data['registrar_generate']['checkstudentverify']['value'] =  [$_POST['gen_studentid']]; 
                     list($checkResult, $checkValue) = select( $data['registrar_generate']['checkstudentverify']);
                     if(!$checkResult){
                        $data['registrar_generate']['sf_information']['value'] =  [$_POST['gen_studentid']];
                     }else{
                        echo alert("<script>showalert('danger', '<strong>Document Request </strong> <br/> <br/>  Grades cannot be released. They have not yet been verified.</b>. ');</script>");
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
       $data['registrar_generate']['templatedocumentsinsert']['value'] = ['Template',$_POST['newtemp'], $_POST['certificateContent']];
       insert($data['registrar_generate']['templatedocumentsinsert']);
       echo alert("<script>showalert('primary', '<strong>Insert Template </strong> <br/> <br/> A new template named <b>{$_POST['newtemp']}</b>  has been successfully added.');</script>");

   }else{
       $data['registrar_generate']['templatedocuments']['value'] = [$_POST['certificateContent'], $_POST['btnUpdate']];
       insert($data['registrar_generate']['templatedocuments']);
       echo alert("<script>showalert('primary', '<strong>Update Template </strong> <br/> <br/> The content for the template <b>{$_POST['docuType']}</b> was successfully updated.');</script>");
   }

   unset($_POST['docuType']);
   unset($_POST['btnUpdate']);
}


else if(isset($_POST['btnDeleteTemplate'])){
    $data['registrar_generate']['templatedocumentsdelete']['value'] = [$_POST['btnDeleteTemplate']];
    insert($data['registrar_generate']['templatedocumentsdelete']);
    unset($_POST['docuTypedel']);
    echo alert("<script>showalert('danger', '<strong>Delete Template </strong> <br/> <br/> The template has been permanently deleted.');</script>");
}


else if(isset($_POST['btnPrint'])){ 
   $data['registrar_generate']['release']['value'] = [ $_SESSION['Student'], $registrar,  $_SESSION['DocuType'], $_POST['documentPurpose']];
   insert($data['registrar_generate']['release']);
   unset($_SESSION['DocuType']);
   unset($_SESSION['Student']);
}




//teacher_generate
else if(isset($_POST['btnSubmitgrade'])){
      unset($_SESSION['studentData']);
      list($enid, $name, $grade, $section, $school_year) = explode('|', $_POST['btnSubmitgrade']);
      $handled = ['School year', 'Section',  'grade Level', $name];
      $studentData  = import("studentGrade",$handled,  headersf10());
      $studentData['Handled']['type'] = 'student_Grades';
      
      $studentData['Details']['grade level'] = $grade;
      $studentData['Details']['section'] = $section;
      $studentData['Details']['school year'] = $school_year;
      $_SESSION['studentData'] = $studentData;   
}

else if(isset($_POST['btnUpdatedGrades'])){
      if (isset($_SESSION['studentData'])) {
         list($success, $result) =  db_gradesStudents($_SESSION['studentData']);
         
         $studentData = $result;
         unset($_SESSION['studentData']);
      } else {
         unset($_SESSION['studentData']);
      }

      unset($_POST['btnUpdatedGrades']);
}

else if(isset($_POST['btn_Preview'])){
      $value = json_decode($_POST['btn_Preview'], true);

      $data['registrar_generate']['checkstudentgrade']['value'] =  [$value[0]];
      list($checkResult, $checkValue) = select($data['registrar_generate']['checkstudentgrade']);
               
      if(!$checkResult){
         $data['registrar_generate']['sf_information']['value'] =  [$value[0]];
      
      }else{
         echo alert("<script>showalert('danger', '<strong>SF10 Preview </strong> <br/> <br/>  Cannot preview SF10. The student does not yet have any grades recorded. ');</script>");
         unset($_POST['btn_Preview']); 
          
      }
}

else if(isset($_POST['completeGrade'])){
   $data['registrar_generate']['verified']['value'] = [$_POST['completeGrade']];
   insert($data['registrar_generate']['verified']);
   echo alert("<script>showalert('primary', '<strong>Verified Grade </strong> <br/> <br/>  The grades for this student are verified and accurate.');</script>");
}






















// 
else if(isset($_POST['btn_Excel'])){
   unset($_SESSION['studentData']);
   $handled = ['School year', 'Section',  'grade Level'];
    $studentData =  import("studentInfo", $handled, headerSF1());
   //"LRN|name|sex|birth date|age|mother tongue|IP|religion|Province|Sex|House #|Barangay|Municipality City|Father|Mother|Guardian|Contact"
    if($studentData['Handled']['school year'] === $_POST['btn_Excel']){
       $studentData['Handled']['type'] = 'preview_Enroll';
       $_SESSION['studentData'] = $studentData; 
    }else{
       echo alert("<script>showalert('danger', '<strong>School Year Mismatch </strong> <br/> <br/> The school year ({$studentData['Handled']['school year']}) you are uploading does not match the current school year ({$_POST['btn_Excel']}).');</script>");
    }
}

else if(isset($_POST['btnUpload'])){
   if (isset($_SESSION['studentData'])) {
      list($success, $result) =  db_enrollStudents($_SESSION['studentData']);
      $studentData = $result;
      unset($_SESSION['studentData']);
   } else {
      unset($_SESSION['studentData']);
   }
}



//registrarcalendar 
else if(isset($_POST['btnSaveEvent'])){ 
   $end = (isset($_POST['ev_edate']) &&  $_POST['ev_edate'] !== '') ? $_POST['ev_edate'] : $_POST['ev_sdate'];
   $data['registrarcalendar']['addevent']['value'] = [$registrar, $_POST['ev_title'], $_POST['ev_description'], $_POST['ev_type'], $_POST['ev_sdate'], $end, $_POST['ev_stime'], $_POST['ev_etime'], 'Pending'];
   insert($data['registrarcalendar']['addevent']);
   echo alert("<script>showalert('primary', '<strong>Activity Added </strong> <br/> <br/>  The new activity titled \' <b>{$_POST['ev_title']}</b> \' has been included in the schedule.');</script>");
}


else if(isset($_POST['btnSave'])){ 
   $end = (isset($_POST['ev_edate']) &&  $_POST['ev_edate'] !== '') ? $_POST['ev_edate'] : $_POST['ev_sdate'];
   $data['registrarcalendar']['editevent']['value'] = [$_POST['ev_title'], $_POST['ev_description'],  $_POST['ev_sdate'], $end, $_POST['ev_stime'], $_POST['ev_etime'], $_POST['btnSave']];
   insert($data['registrarcalendar']['editevent']);
   echo alert("<script>showalert('primary', '<strong>Activity Updated </strong> <br/> <br/>  \' <b>{$_POST['ev_title']}</b> \' has been successfully updated.');</script>");
}


else if (isset($_POST['btnDelete'])) { 
   $data['registrarcalendar']['statusevent']['value'] = ['Deleted', $_POST['btnDelete']];
   insert($data['registrarcalendar']['statusevent']);
   echo alert("<script>showalert('primary', '<strong>Activity Status</strong> <br/> <br/>  The activity status has been successfully set to <b>Deleted</b>');</script>");
   
}


?>