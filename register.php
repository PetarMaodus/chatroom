 <?php 
 require_once("config.php");
 
 $username = $password = $confirm_password = "";
 $username_err = $password_err = $confirm_password_err = "";
 
 if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST['username']))){
        $username_err = "Please enter username.";
    }elseif(!preg_match('/^[a-zA-Z0-9_]+$/',trim($_POST['username']))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else {
        $sql = "SELECT id FROM users WHERE User_name = ?";
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("s",$username_param);
            $username_param = trim($_POST['username']);
            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows == 1){
                    $username_err = "Username already taken.";
                }else{
                    $username = trim($_POST['username']);
                }
            }else{
                 echo "Oops! Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }
    
    if(empty(trim($_POST["password"]))){
        $password_err = "Enter a password.";    
    }elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must be atleast 6 characters.";    
    }else{
        $password = trim($_POST["password"]);
    }
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        $sql = "INSERT INTO users (User_name, User_password) VALUE (?, ?)"; 
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("ss", $username_param , $password_param);
            $username_param = $username;
            $password_param = password_hash($password, PASSWORD_DEFAULT);
            if($stmt->execute()){
                header("Location: login.php");
            } else {
                echo "Something went wrong.";
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
    <title>Sign Up</title>
    
</head>
<body>
    <div >
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div >
                <label>Username</label>
                <input type="text" name="username" value="<?php echo $username; ?>">
                <span ><?php echo $username_err; ?></span>
            </div>    
            <div >
                <label>Password</label>
                <input type="password" name="password" value="<?php echo $password; ?>">
                <span ><?php echo $password_err; ?></span>
            </div>
            <div >
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" value="<?php echo $confirm_password; ?>">
                <span ><?php echo $confirm_password_err; ?></span>
            </div>
            <div >
                <input type="submit" value="Submit">
                <input type="reset" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>