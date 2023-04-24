<?php 
require_once "../config.php";

// Check if database connection is established
if (!$mysqli) {
    die("Error: Database connection failed. " . $mysqli->connect_error);
}

// Get current time and time 50 seconds ago
$current_time = date('Y-m-d H:i:s');
$time_21_seconds_ago = date('Y-m-d H:i:s', strtotime('-21 seconds'));

// Fetch user ids from the login_details table based on the last_activity
$sql = "SELECT id FROM login_details WHERE last_activity BETWEEN '$time_21_seconds_ago' AND '$current_time'";
$result = mysqli_query($mysqli, $sql);

// Check if query was successful
if (!$result) {
    die('Error: ' . mysqli_error($mysqli));
}

// Fetch user names from the users table based on the user ids
$output = '';
while ($row = mysqli_fetch_assoc($result)) {
    $user_id = $row['id'];

    // Fetch user name from the users table based on the user id
    $sql_user = "SELECT User_name FROM users WHERE id='$user_id'";
    $result_user = mysqli_query($mysqli, $sql_user);

    // Check if query was successful
    if (!$result_user) {
        die('Error: ' . mysqli_error($mysqli));
    }

    // Fetch user name and generate HTML output
    while ($row_user = mysqli_fetch_assoc($result_user)) {
        $user_name = $row_user['User_name'];
        $output .= "<li onclick=\"alert('$user_id : $user_name');\">$user_name</li>";
    }
}

// Close database connection
mysqli_close($mysqli);

// Send response to the client
echo $output;
?>
