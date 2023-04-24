<?php
session_start();
require_once "../config.php"; // Include your database configuration file

// Check if id parameter is passed from AJAX request
if(isset($_POST['id'])){
    $id = $_POST['id'];
    
    // Prepare and execute the UPDATE statement
    $sql = "UPDATE login_details SET last_activity=? WHERE id=?";
    if ($stmt = $mysqli->prepare($sql)) {
        $new_last_activity_param = date("Y-m-d H:i:s");
        $stmt->bind_param("si", $new_last_activity_param, $id);
        if ($stmt->execute()) {
            $_SESSION["lastseen"] = $new_last_activity_param;
            echo "success"; // Return success response to AJAX request
        } else {
            echo "error"; // Return error response to AJAX request
        }
        $stmt->close();
    } else {
        echo "error"; // Return error response to AJAX request
    }
} else {
    echo "error"; // Return error response to AJAX request
}
?>