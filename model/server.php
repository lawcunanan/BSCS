<?php
require "database.php";

function select($data) {
    global $conn;
   
    $stmt = $conn->prepare($data['query']);
    $stmt->bind_param($data['bind'], ...$data['value']);

    if (!$stmt->execute()) {
        return [false, null];
    }
    
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return [true, $data];
    } else {
        return [false, null];
    }
}

function insert($data){
   global $conn;
 
   $stmt = $conn->prepare($data['query']);
   $stmt->bind_param($data['bind'], ...$data['value']);
    
    if ($stmt->execute()) {
        return  [true, $conn->insert_id]; 
    }
    
    return [false, null];
}

?>