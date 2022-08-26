<?php include_once('includes/header.php'); ?>

<?php

	if (isset($_GET['id'])) {
		$ID = clean($_GET['id']);
	} else {
		$ID = clean("");
	}
			
	$sql_query = "SELECT n.*, c.category_name FROM tbl_news n, tbl_category c WHERE n.cat_id = c.cid AND nid = $ID";
  	$video_result = mysqli_query($connect, $sql_query);
  	$data   = mysqli_fetch_assoc($video_result);

	$setting_qry    = "SELECT * FROM tbl_settings WHERE id = '1'";
  	$setting_result = mysqli_query($connect, $setting_qry);
  	$settings_row   = mysqli_fetch_assoc($setting_result);

    $provider = $settings_row["providers"];

  	$oneSignalAppId = $settings_row['onesignal_app_id'];
  	$oneSignalRestApiKey = $settings_row['onesignal_rest_api_key'];
  	$fcmServerKey = $settings_row['app_fcm_key'];
  	$fcmNotificationTopic = $settings_row['fcm_notification_topic'];

  	$redirect = 'Location:news.php';

    if (isset($_POST['submit'])) {
        $title = $_POST["title"];
        $message = $_POST["message"];

        if ($_POST["post_id"] == "") {
            $postId = "0";
        } else {
            $postId = $_POST["post_id"];
        }

        $link = $_POST['link'];

        if ($data['content_type'] == 'youtube') {
            $bigImage = 'https://img.youtube.com/vi/'.$data['video_id'].'/mqdefault.jpg';
        } else {
            $actualLink = (isset($_SERVER['HTTPS']) ? 'https' : 'http').'://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['REQUEST_URI']);
            $bigImage = $actualLink.'/upload/'.$data['news_image'];
        }

        $uniqueId = rand(1000, 9999);

        if ($provider == 'onesignal') {
            ONESIGNAL($uniqueId,  $title, $message, $bigImage, $link, $postId, $oneSignalAppId, $oneSignalRestApiKey, $redirect);
        } else if ($provider == 'firebase') {
            FCM($uniqueId, $title, $message, $bigImage, $link, $postId, $fcmServerKey, $fcmNotificationTopic, $redirect);
        }

    }

?>

	<section class="content">

        <ol class="breadcrumb">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="news.php">Manage News</a></li>
            <li class="active">Send News Notification</a></li>
        </ol>

        <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                	<form method="post" id="form_validation" enctype="multipart/form-data">
	                	<div class="card corner-radius">
	                        <div class="header">
	                            <h2>SEND NOTIFICATION</h2>
	                        </div>
	                        <div class="body">

	                        	<div class="row clearfix">

	                        		<input type="hidden" name="post_id" id="post_id" value="<?php echo $data['nid']; ?>" required>
                                    <input type="hidden" name="link" id="link" value="" />

	                        		<div class="form-group col-sm-12">
                                        <div class="font-12">Title</div>
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="title" id="title" placeholder="Title" value="<?php echo $data['category_name']; ?>" required/>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-12">
                                        <div class="font-12">Message</div>
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="message" id="message" placeholder="Message" value="<?php echo $data['news_title']; ?>" required/>
                                        </div>
                                    </div>

			                       	<div class="col-sm-6">
			                       		<div class="font-12 ex1">Image</div>
                                        <div class="form-group">
                                            <?php if ($data['content_type'] == 'youtube') { ?>
                                                <input type="file" class="dropify-image" data-max-file-size="3M" data-allowed-file-extensions="jpg jpeg png gif" data-default-file="https://img.youtube.com/vi/<?php echo $data['video_id'];?>/mqdefault.jpg" data-show-remove="false" disabled/>
                                            <?php } else { ?>
                                                <input type="file" class="dropify-image" data-max-file-size="3M" data-allowed-file-extensions="jpg jpeg png gif" data-default-file="upload/<?php echo $data['news_image']; ?>" data-show-remove="false" disabled/>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                		<button class="button button-rounded waves-effect waves-float pull-right" type="submit" name="submit">SEND NOW</button>
                            		</div>
										
		                       	</div>
		                    </div>
		                </div>
                	</form>
                </div>
            </div>
        </div>

    </section>

<?php include_once('includes/footer.php'); ?>