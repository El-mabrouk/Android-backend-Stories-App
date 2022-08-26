<?php include_once ('session.php'); ?>
<?php include_once ('config.php'); ?>
<?php include_once ('constant.php'); ?>
<?php include_once ('strings.php'); ?>
<?php include_once ('functions.php'); ?>


<?php

    $sqlSettings = "SELECT * FROM tbl_settings WHERE id = '1'";
    $resultSettings = $connect->query($sqlSettings);
    $settings_row = $resultSettings->fetch_assoc();

    $comment_approval = $settings_row['comment_approval'];
    $login_feature = $settings_row['login_feature'];

    $sql_count = "SELECT COUNT(*) as num FROM tbl_comments WHERE comment_status = '0' ";
    $total_pending_comment = mysqli_query($connect, $sql_count);
    $total_pending_comment = mysqli_fetch_array($total_pending_comment);
    $total_pending_comment = $total_pending_comment['num'];

    $username = $_SESSION['user'];
    $sqlUser = "SELECT id, username, email FROM tbl_admin WHERE username = '$username'";
    $userResult = $connect->query($sqlUser);
    $data = $userResult->fetch_assoc();
            
?>

<!DOCTYPE html>
<html>
 
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title><?php echo $app_name; ?></title>

    <?php include_once ('assets/css.min.php'); ?>

</head>

<body class="theme-blue poppins">

    <!-- Top Bar -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="dashboard.php"><div class="uppercase"><?php echo $app_name; ?></div></a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <?php if ($login_feature == 'yes') { ?>
                        <a href="comment.php">
                            <i class="material-icons">comment</i>
                                <?php if ($comment_approval == 'yes') { ?>        
                                    <span class="label-count"><?php echo $total_pending_comment; ?></span>
                                <?php } else if ($comment_approval == 'no') { }                                 
                                ?>
                        </a>
                        <?php } ?>
                    </li>
                    <li><a href="notification.php"><i class="material-icons">notifications</i></a></li>
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="material-icons">more_vert</i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="admin-edit.php?id=<?php echo $data['id']; ?>"><i class="material-icons">person</i>Profile</a></li>
                            <li><a href="logout.php"><i class="material-icons">power_settings_new</i>Logout</a></li>
                        </ul>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
    <!-- #Top Bar -->

<?php include_once ('sidebar.php'); ?>

    