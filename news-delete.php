<?php ob_start(); ?>
<?php include_once('includes/header.php'); ?>

<?php
	
	if (isset($_GET['id'])) {
		$ID = clean($_GET['id']);
	} else {
		$ID = clean('');
	}

	// get image file from table
	$sql = "SELECT content_type, news_image, video_url FROM tbl_news WHERE nid = '$ID'";
	$result = $connect->query($sql);
	$row = $result->fetch_assoc();

	$content_type = $row['content_type'];
	$news_image = $row['news_image'];
	$video_url = $row['video_url'];

	// delete data from menu table
	$sql_delete = "DELETE FROM tbl_news WHERE nid = '$ID'";
	$delete = $connect->query($sql_delete);

	// if delete data success
	if ($delete) {
		if ($content_type == 'Upload') {
			unlink('upload/'.$news_image);
			unlink('upload/video/'.$video_url);
		} else if ($content_type == 'youtube') {
			//do nothing
		} else {
			unlink('upload/'.$news_image);
		}
		
		$_SESSION['msg'] = "News deleted successfully...";
	    header( "Location: news.php");
	    exit;
	}

?>

<?php include_once('includes/footer.php'); ?>