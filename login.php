<?php 
session_start();
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("Location: welcome.php");
    exit();
}
require_once("config.php");

$username = $password = "";
$username_err = $password_err = $login_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["username"]))){
        $username_err = "Enter username.";
    } else {
        $username = trim($_POST["username"]);
    }
    if(empty(trim($_POST["password"]))){
        $password_err = "Enter password.";
    } else{
        $password = trim($_POST["password"]);
    }
    if(empty($username_err) && empty($password_err)){
        $sql = "SELECT id, User_name, User_password, User_win FROM users WHERE User_name = ?";
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("s", $username_param);
            $username_param = $username;
            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows == 1){
                    $stmt->bind_result($id , $username, $password_hash, $wins);
                    if($stmt->fetch()){
                        if(password_verify($password, $password_hash)){
                            session_start();
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["win"] = $wins;
                            $sqltwo = "SELECT login_id FROM login_details WHERE id = ?";
                            //
                            if($stmttwo = $mysqli->prepare($sqltwo)){
                                $stmttwo->bind_param("i",$id_param);
                                $id_param = $id;
                                if($stmttwo->execute()){
                                    $stmttwo->store_result();
                                    if($stmttwo->num_rows == 0){
                                        $sqlthree = "INSERT INTO login_details (id, last_activity) VALUE (?, ?)"; 
                                        if($stmtthree = $mysqli->prepare($sqlthree)){
                                            $stmtthree->bind_param("is", $new_id_param , $last_activity_param);
                                            $new_id_param = $id;
                                            $last_activity_param = date("Y-m-d H:i:s");
                                            if($stmtthree->execute()){
                                                $_SESSION["lastseen"] = $last_activity_param;
                                                header("Location: welcome.php");
                                            } else {
                                                echo "Something went wrong.";
                                            }
                                            $stmtthree->close();
                                        }
                                    }elseif($stmttwo->num_rows == 1){
                                        
                                        $sqlfour = "UPDATE login_details SET last_activity=? WHERE id=?";
                                        if ($stmtfour = $mysqli->prepare($sqlfour)) {
                                            $stmtfour->bind_param("si", $new_last_activity_param, $new_new_id_param);
                                            $new_new_id_param = $id;
                                            $new_last_activity_param = date("Y-m-d H:i:s");
                                            if ($stmtfour->execute()) {
                                                $_SESSION["lastseen"] = $new_last_activity_param;
                                                header("Location: welcome.php");
                                            } else {
                                                echo "Something went wrong.";
                                            }
                                            $stmtfour->close();
                                        }
                                    }
                                }else{
                                     echo "Oops!cant store login time error.";
                                }
                                $stmttwo->close();
                            }
                            //
                           
                        } else {
                            $login_err = "Invalid username or password.";
                        }
                    }
                    
                } else{
                    $login_err = "Invalid username or password.";
                }
            } else{
                echo "try again later.";
            }
            $stmt->close();
        }
    }
    $mysqli->close();
}
?>  
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title> 
</head>
<body>
    <div >
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>

        <?php 
        if(!empty($login_err)){
            echo '<div>' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div >
                <label>Username</label>
                <input type="text" name="username" value="<?php echo $username; ?>">
                <span ><?php echo $username_err; ?></span>
            </div>    
            <div >
                <label>Password</label>
                <input type="password" name="password" >
                <span ><?php echo $password_err; ?></span>
            </div>
            <div >
                <input type="submit" value="Login">
            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form>
    </div>
</body>
</html>