<?php
session_start();
require_once "../config.php";

if(isset($_POST['id'])){
    $id = $_POST['id'];
    
    $sql = "UPDATE login_details SET last_activity=? WHERE id=?";
    if ($stmt = $mysqli->prepare($sql)) {
        $new_last_activity_param = date("Y-m-d H:i:s");
        $stmt->bind_param("si", $new_last_activity_param, $id);
        if ($stmt->execute()) {
            $_SESSION["lastseen"] = $new_last_activity_param;
            echo "success"; 
        } else {
            echo "error"; 
        }
        $stmt->close();
    } else {
        echo "error"; 
    }
} else {
    echo "error"; 
}
?>