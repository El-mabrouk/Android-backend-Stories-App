<?php 
    ob_start();
    if (!session_start()) {
        session_start();
    }

    if (isset($_SESSION['user'])) {
        header('location: dashboard.php');
        exit;
    }

?>
<?php error_reporting(0); ?>
<?php include ('includes/config.php'); ?>
<?php include ('includes/constant.php'); ?>
<?php include ('includes/strings.php'); ?>
<?php include ('functions.php'); ?>

<?php

 
    if(isset($_POST['login'])) {

        $username = clean($_POST['username']);
        $password = clean($_POST['password']);

        $currentTime = time() + 25200;
        $expired = 86400;

        $error = array();

        if(empty($username)) {
            $error['username'] = "*Username should be filled.";
        }

        if(empty($password)) {
            $error['password'] = "*Password should be filled.";
        }

        if(!empty($username) && !empty($password)) {

            $password = hash('sha256', $username.$password);
            $sql_query = "SELECT * FROM tbl_admin WHERE username = ? AND password = ?";

            $stmt = $connect->stmt_init();
            if($stmt->prepare($sql_query)) {
                $stmt->bind_param('ss', $username, $password);
                $stmt->execute();
                $stmt->store_result();
                $num = $stmt->num_rows;
                $stmt->close();
                if($num == 1) {
                        $_SESSION['user'] = $username;
                        $_SESSION['timeout'] = $currentTime + $expired;
                        header("location: dashboard.php");
                    
                } else {
                    $error['failed'] = "Invalid Username or Password!";
                }
            }

        }
    }
?>

<!DOCTYPE html>
<html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="msapplication-tap-highlight" content="no">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <title><?php echo $app_name; ?></title>
        <?php include_once ('assets/css.min.php'); ?>
    </head>

    <body class="login-page poppins">
        
        <div class="login-box">
            <div class="card corner-radius">
                <div class="body">
                    <form id="form_validation" method="post">
                        <center>
                            <img src="assets/images/ic_launcher.png" width="100px" height="100px">
                            <br>
                            <div class="custom-padding1"><div class="uppercase"><?php echo $app_name; ?></div></div>
                            <div class="custom-padding2 col-pink"><?php echo isset($error['failed']) ? $error['failed'] : '';?></div>
                        </center>
                        
                        <div class="input-group form-group">
                            <span class="input-group-addon">
                                <i class="material-icons">person</i>
                            </span>
                            <div class="form-line">
                                <input type="text" class="form-control" name="username" id="username" placeholder="Username" required>
                            </div>
                        </div>
                        <div class="input-group form-group form-float">
                            <span class="input-group-addon">
                                <i class="material-icons">lock</i>
                            </span>
                            <div class="form-line">
                                <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4 p-t-5"></div>
                            <div class="col-xs-8">
                                <button class="button button-rounded waves-effect waves-float" type="submit" name="login">LOGIN</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php include_once ('assets/js.min.php'); ?>

    </body>

</html>