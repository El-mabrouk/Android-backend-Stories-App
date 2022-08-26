<?php ob_start(); ?>
<?php include_once('includes/header.php'); ?>

<?php
	
	if (isset($_GET['id'])) {
		$ID = clean($_GET['id']);
	} else {
		$ID = clean("");
	}

	// get image file from table
	$sql = "SELECT image FROM tbl_fcm_template WHERE id = '$ID'";
	$result = $connect->query($sql);
	$row = $result->fetch_assoc();

	$image = $row['image'];

	// delete data from menu table
	$sql_delete = "DELETE FROM tbl_fcm_template WHERE id = '$ID'";
	$delete = $connect->query($sql_delete);

	// if delete data success
	if ($delete) {
		$delete_image = unlink('upload/notification/'.$image);
		$_SESSION['msg'] = "Notification deleted successfully...";
	    header( "Location: notification.php");
	     exit;
	}

?>

<?php include_once('includes/footer.php'); ?>