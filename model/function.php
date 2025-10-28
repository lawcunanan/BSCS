<?php 
require "email.php";
require "server.php";
require  "excel/excelhook.php";
date_default_timezone_set('Asia/Manila');
session_start();
function display_all($data = null, $id = null, $output = null){
  list($checkSuccess, $checkResult) = select($data);
  $return_output = "";
  if ($checkSuccess) {
        foreach ($checkResult as $row) {
             if($id != -1)
               $return_output .= $output($row, $id);
            else
               $return_output = $output($row, $id);
        }
  }

   return $return_output;
}

function save_all($data, $table) {
    global $conn;
    
    if (insert($data)) {
        if (isset($_FILES['picture'])) {
            $insertId = $conn->insert_id; 
            $fileName = $table . "_" . $insertId . ".png";
            move_uploaded_file($_FILES['picture']['tmp_name'], "../../../model/picture/" . $fileName);
        }
        return true;
    } 

    return false;
}



function update_all($data, $table) {

    if (insert($data)) {
       
        if (isset($_FILES['picture'])  && $_FILES['picture']['size'] > 0) {
            $fileName = $table . "_" . $data['id'] . ".png";
            $oldFilePath = "../../model/picture/" . $fileName;

            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }

            move_uploaded_file($_FILES['picture']['tmp_name'], "../../../model/picture/" . $fileName);
        }
        return true; 
    } 
        
    return false;
}


function delete_all($data, $table) {
   
    if (insert($data)) {
        $deleteId = $data['id']; 

        $fileName = $table . "_" . $deleteId . ".png";
        $filePath = "../../../model/picture/" . $fileName;

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        return true; 
    } 

    return false; 
}


function average($num1, $num2) { 
    if ($num2 >= 1) {
        return (round(($num1 / $num2), 2));
    } else {
        return null;
    }
}

function modal($heading, $size, $body, $id, $name, $value) {
    $btn = ($name != 'none') ?  "<button
                                    type='submit'
                                    name='btn$name'
                                    class='btn  btn-success'
                                    id='btn$name'
                                    value='$value'
                                >
                                    $name
                                </button>" : "";
    $output = "
        <div class='modal fade' id='$id' >
            <div class='modal-dialog  $size'>
                <form  id='myform' method = 'POST' class ='form' enctype='multipart/form-data'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <div>
                                <h4 class='modal-title'>$heading</h4>
                                <p class='date'></p>
                            </div>
                            <button
                                class='btn-close'
                                data-bs-dismiss='modal'
                                aria-label='Close'
                                onclick='event.preventDefault();'
                            ></button>
                        </div>

                        <!-- Modal body -->
                        $body

                        <!-- Modal footer -->
                        <div class='modal-footer'>
                          {$btn} 
                        </div>
                    </div>
                </form>
            </div>
        </div>";
    return $output;
}


function alert($message){
    return "
        <!DOCTYPE html>
        <script src='/INFORMATIONSYSTEM/model/function.js' ></script>
        <div id = 'alertt'></div>
        $message
    ";
}


function redirect($location){
     header("location: ".$location);
     exit();
}


?>