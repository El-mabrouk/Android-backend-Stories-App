<?php include_once ('includes/header.php'); ?>

<?php

  $sql_category = "SELECT COUNT(*) as num FROM tbl_category";
  $total_category = mysqli_query($connect, $sql_category);
  $total_category = mysqli_fetch_array($total_category);
  $total_category = $total_category['num'];

  $sql_news = "SELECT COUNT(*) as num FROM tbl_news";
  $total_news = mysqli_query($connect, $sql_news);
  $total_news = mysqli_fetch_array($total_news);
  $total_news = $total_news['num'];

  $sql_fcm = "SELECT COUNT(*) as num FROM tbl_fcm_template";
  $total_fcm = mysqli_query($connect, $sql_fcm);
  $total_fcm = mysqli_fetch_array($total_fcm);
  $total_fcm = $total_fcm['num'];

  $sql_user = "SELECT COUNT(*) as num FROM tbl_users";
  $total_users = mysqli_query($connect, $sql_user);
  $total_users = mysqli_fetch_array($total_users);
  $total_users = $total_users['num'];

?>

    <section class="content">

    <ol class="breadcrumb breadcrumb-offset">
        <li><a href="dashboard.php">Dashboard</a></li>
        <li class="active">Home</a></li>
    </ol>

        <div class="container-fluid" id="fade-in">
             
             <div class="row">

                <a href="category.php">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="card corner-radius demo-color-box bg-blue waves-effect col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <div class="color-name uppercase"><?php echo $menu_category; ?></div>
                            <div class="color-name"><i class="material-icons">view_list</i></div>
                            <div class="color-class-name">Total <?php echo $total_category; ?> Categories</div>
                            <br>
                        </div>
                    </div>
                </a>

               <a href="news.php">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="card corner-radius demo-color-box bg-blue waves-effect col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <div class="color-name uppercase"><?php echo $menu_news; ?></div>
                            <div class="color-name"><i class="material-icons">library_books</i></div>
                            <div class="color-class-name">Total <?php echo $total_news; ?> News</div>
                            <br>
                        </div>
                    </div>
                </a>

                <a href="notification.php">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="card corner-radius demo-color-box bg-blue waves-effect col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <div class="color-name uppercase"><?php echo $menu_notification; ?></div>
                            <div class="color-name"><i class="material-icons">notifications</i></div>
                            <div class="color-class-name">Total <?php echo $total_fcm; ?> Templates</div>
                            <br>
                        </div>
                    </div>
                </a>

                <a href="user.php">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="card corner-radius demo-color-box bg-blue waves-effect col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <div class="color-name uppercase"><?php echo $menu_user; ?></div>
                            <div class="color-name"><i class="material-icons">verified_user</i></div>
                            <div class="color-class-name">Total <?php echo $total_users; ?> Users</div>
                            <br>
                        </div>
                    </div>
                </a>

                <a href="ads.php">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="card corner-radius demo-color-box bg-blue waves-effect col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <div class="color-name uppercase"><?php echo $menu_ads; ?></div>
                            <div class="color-name"><i class="material-icons">monetization_on</i></div>
                            <div class="color-class-name">App Monetization</div>
                            <br>
                        </div>
                    </div>
                </a>

                <a href="comment.php">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="card corner-radius demo-color-box bg-blue waves-effect col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <div class="color-name uppercase"><?php echo $menu_comment; ?></div>
                            <div class="color-name"><i class="material-icons">comment</i></div>
                            <div class="color-class-name">Users Comment</div>
                            <br>
                        </div>
                    </div>
                </a>

                <a href="admin.php">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="card corner-radius demo-color-box bg-blue waves-effect col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <div class="color-name uppercase"><?php echo $menu_administrator; ?></div>
                            <div class="color-name"><i class="material-icons">people</i></div>
                            <div class="color-class-name">Admin Panel Privileges</div>
                            <br>
                        </div>
                    </div>
                </a>

                <a href="settings.php">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="card corner-radius demo-color-box bg-blue waves-effect col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <div class="color-name uppercase"><?php echo $menu_settings; ?></div>
                            <div class="color-name"><i class="material-icons">settings</i></div>
                            <div class="color-class-name">Key and Privacy Settings</div>
                            <br>
                        </div>
                    </div>
                </a>

            </div>
            
        </div>

    </section>

<?php include_once('includes/footer.php'); ?>