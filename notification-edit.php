<?php include_once('includes/header.php'); ?>

<?php 
    
    if (isset($_GET['id'])) {

    	$ID = clean($_GET['id']);
        $sql_notification = "SELECT * FROM tbl_fcm_template WHERE id = '$ID'";
        $result = $connect->query($sql_notification);
        $row = $result->fetch_assoc();

    }

    if(isset($_POST['submit'])) {

        $old_image = clean($_POST['old_image']);

        if ($_FILES['image']['name'] != '') {
            unlink('upload/notification/'.$old_image);
            $image = time().'_'.$_FILES['image']['name'];
            $image_tmp = $_FILES['image']['tmp_name'];
            $file_path = 'upload/notification/'.$image;
            copy($image_tmp, $file_path);
        } else {
            $image = $old_image;
        }

        $title = clean($_POST['title']);
        $message = clean($_POST['message']);
        $link = clean($_POST['link']);
 
        $data = array(
            'title' => $title,
            'message' => $message,
            'image' => clean($image),
            'link' => clean($link)
        );  

        $hasil = update('tbl_fcm_template', $data, "WHERE id = '$ID'");

        if ($hasil > 0) {
            $_SESSION['msg'] = "Changes Saved...";
            header("Location:notification-edit.php?id=$ID");
            exit;
        }

    }

?>

<section class="content">

	<ol class="breadcrumb">
		<li><a href="dashboard.php">Dashboard</a></li>
		<li><a href="notification.php">Manage Notification</a></li>
		<li class="active">Add Notification</a></li>
	</ol>

	<div class="container-fluid">

		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

				<form id="form_validation" method="post" enctype="multipart/form-data">
					<div class="card corner-radius">
						<div class="header">
							<h2>ADD CATEGORY</h2>
						</div>
						<div class="body">

							<?php if(isset($_SESSION['msg'])) { ?>
							<div class='alert alert-info alert-dismissible corner-radius' role='alert'>
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>&nbsp;&nbsp;</button>
								<?php echo $_SESSION['msg']; ?>
							</div>
							<?php unset($_SESSION['msg']); } ?>                            

							<div class="row clearfix">

								<div class="col-md-12">
									<div class="form-group col-sm-12">
										<div class="form-line">
											<div class="font-12">Title</div>
											<input type="text" class="form-control" name="title" id="title" value="<?php echo $row['title']; ?>" placeholder="Title" required>
										</div>
									</div>

									<div class="form-group col-sm-12">
										<div class="form-line">
											<div class="font-12">Message</div>
											<input type="text" class="form-control" name="message" id="message" value="<?php echo $row['message']; ?>" placeholder="Message" required>
										</div>
									</div>

									<div class="col-sm-6" style="margin-bottom: 0px;">
										<div class="form-group">
                                            <div class="font-12 ex1">Image ( JPG, JPEG, PNG or GIF )</div>
                                            <input type="file" name="image" id="image" class="dropify-image" data-max-file-size="3M" data-allowed-file-extensions="jpg jpeg png gif" data-default-file="upload/notification/<?php echo $row['image']; ?>" data-show-remove="false"/>
                                        </div>
									</div>

									<div class="form-group col-sm-12">
										<div class="form-line">
											<div class="font-12">Link (Optional)</div>
											<input type="text" class="form-control" name="link" id="link" value="<?php echo $row['link']; ?>" placeholder="https://google.com">
										</div>
									</div>

									<input type="hidden" name="old_image" id="old_image" value="<?php echo $row['image']; ?>" >
                                    <input type="hidden" name="id" id="id" value="<?php echo $row['id']; ?>" >

									<div class="col-sm-12">
										<button class="button button-rounded waves-effect waves-float pull-right" type="submit" name="submit">UPDATE</button>
									</div>

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