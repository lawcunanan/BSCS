<?php 
require "query.php";



if(isset($_POST['btnSaveEvent'])){ 
   $end = (isset($_POST['ev_edate']) &&  $_POST['ev_edate'] !== '') ? $_POST['ev_edate'] : $_POST['ev_sdate'];
   $data['admincalendar']['addevent']['value'] = [$admin, $_POST['ev_title'], $_POST['ev_description'], $_POST['ev_type'], $_POST['ev_sdate'], $end, $_POST['ev_stime'], $_POST['ev_etime'], 'Accepted'];
   insert($data['admincalendar']['addevent']);
   echo alert("<script>showalert('primary', '<strong>Activity Added </strong> <br/> <br/>  The new activity titled \' <b>{$_POST['ev_title']}</b> \' has been included in the schedule.');</script>");
}

else if(isset($_POST['btnSave'])){ 
   $end = (isset($_POST['ev_edate']) &&  $_POST['ev_edate'] !== '') ? $_POST['ev_edate'] : $_POST['ev_sdate'];
   $data['admincalendar']['editevent']['value'] = [$_POST['ev_title'], $_POST['ev_description'],  $_POST['ev_sdate'], $end, $_POST['ev_stime'], $_POST['ev_etime'], $_POST['btnSave']];
   insert($data['admincalendar']['editevent']);
   echo alert("<script>showalert('primary', '<strong>Activity Updated </strong> <br/> <br/>  \' <b>{$_POST['ev_title']}</b> \' has been successfully updated.');</script>");
}

else if (isset($_POST['btnDelete'])) { 
      $data['admincalendar']['statusevent']['value'] = ['Deleted', $_POST['btnDelete']];
      insert($data['admincalendar']['statusevent']);
      echo alert("<script>showalert('primary', '<strong>Activity Status</strong> <br/> <br/>  The activity status has been successfully set to <b>Deleted</b>');</script>");
}



//
else if (isset($_POST['btnAdd'])) { 
    $data['excelheader']['insert']['value'] = [$_POST['headerType'],$_POST['headerName']];
    insert($data['excelheader']['insert']);
    echo alert("<script>showalert('primary', '<strong>Excel Header</strong> <br/> <br/>  A new header has been successfully added.');</script>");
}

else if (isset($_POST['btnUpdate'])) { 
      $data['excelheader']['update']['value'] = [$_POST['headerType'], $_POST['headerType'], $_POST['headerName'], $_POST['headerName'], $_POST['btnUpdate']];
      insert($data['excelheader']['update']);
      echo alert("<script>showalert('primary', '<strong>Excel Header</strong> <br/> <br/>  Header has been updated successfully.');</script>");
}

else if (isset($_POST['btnDelete_Header'])) { 
       $data['excelheader']['delete']['value'] = [$_POST['btnDelete_Header']];
       insert($data['excelheader']['delete']);
       echo alert("<script>showalert('primary', '<strong>Excel Header</strong> <br/> <br/>  Header has been successfully deleted.');</script>");
}


else if (isset($_POST['btnCreate'])) { 
    $data['manageacc']['register']['value'] = [
        $_POST['role'],
        $_POST['fname'],
        $_POST['mname'],
        $_POST['lname'], 
        $_POST['bdate'],
        $_POST['sex'],
        $_POST['email'],
        $_POST['contact'],
        password_hash($_POST['password'], PASSWORD_DEFAULT),
        $_POST['province'],
        $_POST['municipality'],
        $_POST['barangay'],
        $_POST['street'],
    ];  

   
    if($_POST['role'] == 'principal'){
       list($check, $value) =   select($data['manageacc']['checkprincipal']);
       if($check){
         echo alert("<script>showalert('danger', '<strong>Alert</strong> <br/> <br/> A new principal cannot be added while the current principal is still active!');</script>");
         return;
       }
    }

    $data['manageacc']['checkemail']['value'] = [$_POST['email']];
    list($check, $value) =   select($data['manageacc']['checkemail']);
    if(!$check){
         if(save_all($data['manageacc']['register'], "User")){
            echo alert("<script>showalert('success', '<strong>Alert</strong> <br/> <br/> Account has been successfully created!');</script>"); 
         }else{
            echo alert("<script>showalert('danger', '<strong>Alert</strong> <br/> <br/> Please try again.');</script>");
         }
    }else{
         echo alert("<script>showalert('danger', '<strong>Alert</strong> <br/> <br/> This email address is already registered. You cannot use it again!');</script>");
    }

}



else if (isset($_POST['btnSave_Changes'])) { 
    $data['manageacc']['updatepersonal']['value'] = [
        $_POST['fname'],
        $_POST['mname'],
        $_POST['lname'], 
        $_POST['bdate'],
        $_POST['sex'],
        $_POST['contact'],
        $_POST['province'],
        $_POST['municipality'],
        $_POST['barangay'],
        $_POST['street'],
        $_POST['btnSave_Changes']
    ]; 
     $data['manageacc']['updatepersonal']['id']  =  $_POST['btnSave_Changes'];

    if (update_all($data['manageacc']['updatepersonal'], "User")) {
        echo alert("<script>showalert('primary', '<strong>Alert</strong> <br/> <br/> Account has been successfully updated!');</script>"); 
    } else {
        echo alert("<script>showalert('danger', '<strong>Alert</strong> <br/> <br/> Please try again.');</script>");
    }
}


else if (isset($_POST['btnApply_Changes'])) { 
     $data['manageacc']['updateaccount']['value'] = [
        $_POST['role'],
        $_POST['email'],
        password_hash($_POST['password'], PASSWORD_DEFAULT),
        $_POST['btnApply_Changes']
    ]; 


    if($_POST['role'] == 'principal'){
       list($check, $value) =   select($data['manageacc']['checkprincipal']);
       if($check){
         echo alert("<script>showalert('danger', '<strong>Alert</strong> <br/> <br/> A new principal cannot be added while the current principal is still active!');</script>");
         return;
       }
    }

    $data['manageacc']['accountemail']['value'] = [$_POST['email'],  $_POST['btnApply_Changes']];
    list($checkk, $value) =   select($data['manageacc']['accountemail']);
   
    if(!$checkk){
        if (update_all($data['manageacc']['updateaccount'], null)) {
             echo alert("<script>showalert('primary', '<strong>Alert</strong> <br/> <br/> Account has been successfully updated!');</script>"); 
        } else {
             echo alert("<script>showalert('danger', '<strong>Alert</strong> <br/> <br/> Please try again.');</script>");
        }  
   }else{    
      echo alert("<script>showalert('danger', '<strong>Alert</strong> <br/> <br/> This email address is already registered. You cannot use it again!');</script>");
   }

}

else if (isset($_POST['btnDelete_Account'])) { 
     $data['manageacc']['accountdelete']['value'] = [
        $_POST['btnDelete_Account']
    ]; 
    
     insert( $data['manageacc']['accountdelete']);
     echo alert("<script>showalert('primary', '<strong>Alert</strong> <br/> <br/> Account has been successfully deleted!');</script>"); 
}

else if (isset($_POST['saveinformation'])) { 
     $data['schoolinfo']['edit']['value'] = [
        $_POST['sID'],
        $_POST['sname'],
        $_POST['region'],
        $_POST['division'],
        $_POST['district']
    ]; 
    $data['schoolinfo']['edit']['id']  =  1;
    update_all($data['schoolinfo']['edit'], "Logo");
    echo alert("<script>showalert('primary', '<strong>Alert</strong> <br/> <br/>  School information has been updated successfully');</script>"); 
}

?>