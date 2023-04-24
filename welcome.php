<?php 
session_start();
 
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
</head>
<body>
    <h1 >Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
    <h2><?php echo $_SESSION["win"];?></h2>
    <h2><?php echo $_SESSION["lastseen"];?></h2>
    <p>
        <a href="reset-password.php" >Reset Your Password</a>
        <a href="logout.php" >Sign Out of Your Account</a>
    </p>
    <ul id="userNames"></ul>
    <script>
        
        $(document).ready(function(){
                setInterval(function(){
                    $.ajax({
                        url: "ajaxfolder/update_login_details.php",
                        method: "POST",
                        data: {id: "<?php echo $_SESSION["id"]; ?>"},
                        success: function(response){
                            if(response === "success"){
                                <?php $_SESSION["lastseen"] = date("Y-m-d H:i:s"); ?>
                            } else {
                                console.log("Something went wrong.");
                            }
                        },
                        error: function(){
                            console.log("Error occurred during AJAX request.");
                        }
                    });
                }, 10000); 
        });
        function fetchUserNames() {
            $.ajax({
                url: "ajaxfolder/fetch_users.php",
                method: "POST",
                dataType: "html",
                success: function(response) { 
                    $('#userNames').html(response);
                    if ($('#userNames').children().length > 0) {
                        $('#userNames').children().css({"color": "red", "font-size": "24px","cursor": "pointer" });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error: ' + xhr.status);
                }
            });
        }
        fetchUserNames();
    </script>
</body>
</html>