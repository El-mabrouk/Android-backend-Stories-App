<?php include_once('includes/header.php'); ?>

<?php

	error_reporting(0);

	if (isset($_REQUEST['keyword']) && $_REQUEST['keyword']<>"") {
		$keyword = $_REQUEST['keyword'];
		$reload = "app.php";
		$sql =  "SELECT a.*, s.api_key FROM tbl_app_config a, tbl_settings s WHERE s.id = 1 AND a.package_name LIKE '%$keyword%'";
		$result = $connect->query($sql);
	} else {
		$reload = "app.php";
		$sql =  "SELECT a.*, s.api_key FROM tbl_app_config a, tbl_settings s WHERE s.id = 1 ORDER BY a.id DESC";
		$result = $connect->query($sql);
	}

	$rpp = $postPerPage;
	$page = intval($_GET["page"]);
	if($page <= 0) $page = 1;  
	$tcount = mysqli_num_rows($result);
	$tpages = ($tcount) ? ceil($tcount / $rpp) : 1;
	$count = 0;
	$i = ($page-1) * $rpp;
	$no_urut = ($page-1) * $rpp;

	if (isset($_GET['delete'])) {
		$appId = $_GET['delete'];
		$sql_delete = "DELETE FROM tbl_app_config WHERE id = '$appId'";
		$delete = $connect->query($sql_delete);
		if ($delete) {
			$_SESSION['msg'] = "App deleted successfully...";
		    header( "Location:app.php");
		    exit;
		}
	}

?>

<section class="content">

	<ol class="breadcrumb">
		<li><a href="dashboard.php">Dashboard</a></li>
		<li class="active">Manage Apps</a></li>
	</ol>

	<div class="container-fluid">

		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card corner-radius">
					<div class="header">
						<h2>MANAGE APPS</h2>
						<div class="header-dropdown m-r--5">
							<a href="app-add.php"><button type="button" class="button button-rounded btn-offset waves-effect waves-float">ADD NEW APP</button></a>
						</div>
					</div>

					<div style="margin-top: -10px;" class="body table-responsive">

						<?php if (isset($_SESSION['msg'])) { ?>
						<div class='alert alert-info alert-dismissible corner-radius bottom-offset' role='alert'>
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>&nbsp;&nbsp;</button>
							<?php echo $_SESSION['msg']; ?>
						</div>
						<?php unset($_SESSION['msg']); } ?>

						<form method="get" id="form_validation">
							<table class='table'>
								<tr>
									<td>
										<div class="form-group form-float">
											<div class="form-line">
												<input type="text" class="form-control" name="keyword" placeholder="Search..." required>
											</div>
										</div>
									</td>
									<td width="1%"><a href="app.php"><button type="button" class="button button-rounded waves-effect waves-float">RESET</button></a></td>
									<td width="1%"><button type="submit" class="btn bg-blue btn-circle waves-effect waves-circle waves-float"><i class="material-icons">search</i></button></td>
								</tr>
							</table>
						</form>

						<?php if ($tcount == 0) { ?>
							<p align="center" style="font-size: 110%;">No app created, add new app to get your Server Key and manage redirect.</p>
						<?php } else { ?>

							<table style="table-layout: fixed; width: 100%;" class="table table-hover table-striped table-offset">

							<?php
							while(($count < $rpp) && ($i < $tcount)) {
								mysqli_data_seek($result, $i);
								$data = mysqli_fetch_array($result);
								?>

								<tr>
									<td width="25%" style="vertical-align: middle;">applicationId (Package Name)</td>
									<td width="75%" style="vertical-align: middle;"><?php echo $data['package_name'];?></td>
								</tr>

								<tr>
									<?php 
										$server_url = (isset($_SERVER['HTTPS']) ? 'https' : 'http').'://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['REQUEST_URI']);
										$applicationId = $data['package_name'];
										$plain_text = $server_url.'_applicationId_'.$applicationId;
										$encode = base64_encode(base64_encode(base64_encode($plain_text)));
									?>
									<td style="vertical-align: middle;">Server Key</td>
									<td style="word-wrap: break-word; vertical-align: middle;"><?php echo $encode; ?></td>
								</tr>

								<tr>
									<td style="vertical-align: middle;">Rest API Key</td>
									<td style="word-wrap: break-word; vertical-align: middle;"><?php echo $data['api_key'];?></td>
								</tr>

								<tr>
									<td style="vertical-align: middle;">Status</td>
									<td style="vertical-align: middle;">
										<?php if ($data['status'] == 1) { ?>
											<span class="label label-rounded bg-blue">ACTIVE</span>
										<?php } else { ?>
											<span class="label label-rounded bg-grey">INACTIVE</span>
										<?php } ?>
									</td>
								</tr>

								<tr>
									<td style="vertical-align: middle;">Redirect Url</td>
									<td style="word-wrap: break-word; vertical-align: middle;"><?php if ($data['redirect_url'] == '') { echo '-'; } else { echo $data['redirect_url']; } ?></td>
								</tr>

								<tr>
									<td></td>
									<td align="right">
										<a href="app-edit.php?id=<?php echo $data['id'];?>">
											<i class="material-icons">mode_edit</i>
										</a>

										<a href="app.php?delete=<?php echo $data['id'];?>" onclick="return confirm('Are you sure want to delete this app?')" >
											<i class="material-icons">delete</i>
										</a>
									</td>
								</tr>

								<?php
								$i++; 
								$count++;
							}
							?>
						</table>

						<?php } ?>

						<?php if ($tcount > $postPerPage) { echo pagination($reload, $page, $keyword, $tpages); } ?>

					</div>

										
				</div>
			</div>
		</div>
	</div>
</section>

<?php include_once('includes/footer.php'); ?>