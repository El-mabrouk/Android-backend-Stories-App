<?php ob_start(); ?>
<?php include_once('includes/header.php'); ?>

<?php
	
	if (isset($_GET['id'])) {
		$ID = clean($_GET['id']);
	} else {
		$ID = clean("");
	}

	// delete data from menu table
	$sql_delete = "DELETE FROM tbl_admin WHERE id = '$ID'";
	$delete = $connect->query($sql_delete);

	// if delete data success
	if ($delete) {
		$_SESSION['msg'] = "Admin deleted successfully...";
	    header( "Location: admin.php");
	    exit;
	}

?>

<?php include_once('includes/footer.php'); ?>