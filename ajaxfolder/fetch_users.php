<?php 
require_once "../config.php";

if (!$mysqli) {
    die("Error: Database connection failed. " . $mysqli->connect_error);
}

$current_time = date('Y-m-d H:i:s');
$time_21_seconds_ago = date('Y-m-d H:i:s', strtotime('-21 seconds'));

$sql = "SELECT id FROM login_details WHERE last_activity BETWEEN '$time_21_seconds_ago' AND '$current_time'";
$result = mysqli_query($mysqli, $sql);

if (!$result) {
    die('Error: ' . mysqli_error($mysqli));
}

$output = '';
while ($row = mysqli_fetch_assoc($result)) {
    $user_id = $row['id'];

    $sql_user = "SELECT User_name FROM users WHERE id='$user_id'";
    $result_user = mysqli_query($mysqli, $sql_user);

    if (!$result_user) {
        die('Error: ' . mysqli_error($mysqli));
    }

    while ($row_user = mysqli_fetch_assoc($result_user)) {
        $user_name = $row_user['User_name'];
        $output .= "<li onclick=\"alert('$user_id : $user_name');\">$user_name</li>";
    }
}

mysqli_close($mysqli);

echo $output;
?>
