 <?php 
 session_start();
 if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header('location: login.php');
    exit;
 }
 require_once('config.php');
 $new_password = $confirm_password = "";
 $new_password_err = $confirm_password_err = "";
 
 if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(empty(trim($_POST['new_password']))){
        $new_password_err = "Please enter the new password.";
    } elseif(strlen(trim($_POST['new_password'])) < 6){
        $new_password_err = "Password must be atleast 6 characters.";
    } else{
        $new_password = trim($_POST['new_password']);
    }
    if(empty(trim($_POST['confirm_password']))){
        $confirm_password_err = "Please confirm the password.";
    } else{
        $confirm_password = trim($_POST['confirm_password']);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    
    if(empty($new_password_err) && empty($confirm_password_err)){
        $sql =  "UPDATE users SET User_password = ? WHERE id = ?" ;

        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("si",$password_param , $id_param);
            $password_param = password_hash($new_password , PASSWORD_DEFAULT);
            $id_param = $_SESSION['id'];

            if($stmt->execute()){
                session_destroy();
                header("Location: login.php");
                exit();
            }else{
                echo " Please try again later.";
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
    <title>Reset Password</title> </head>
<body>
    <div >
        <h2>Reset Password</h2>
        <p>Please fill out this form to reset your password.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div >
                <label>New Password</label>
                <input type="password" name="new_password" value="<?php echo $new_password; ?>">
                <span><?php echo $new_password_err; ?></span>
            </div>
            <div >
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" >
                <span ><?php echo $confirm_password_err; ?></span>
            </div>
            <div >
                <input type="submit" value="Submit">
                <a href="welcome.php">Cancel</a>
            </div>
        </form>
    </div>    
</body>
</html>