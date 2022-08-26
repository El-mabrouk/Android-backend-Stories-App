<?php include_once('includes/header.php'); ?>

<?php

	if (isset($_GET['id'])) {

    	$ID = clean($_GET['id']);
        $sql_app = "SELECT * FROM tbl_app_config WHERE id = '$ID'";
        $result = $connect->query($sql_app);
        $row = $result->fetch_assoc();

    }

    if (isset($_POST['submit'])) {

    	if ($_POST['status'] == 'active') {
            $status = clean('1');
        } else {
            $status = clean('0');
        }

        $package_name = clean($_POST['package_name']);
        $redirect_url = clean($_POST['redirect_url']);

		        $data = array(
		            'package_name' => $package_name,
		            'status' => $status,
		            'redirect_url' => $redirect_url
		        ); 

        $update = update('tbl_app_config', $data, "WHERE id = '$ID'");

        if ($update > 0) {
            $_SESSION['msg'] = "Changes Saved...";
            header("Location:app-edit.php?id=$ID");
            exit;
        }

    }

?>

<script type="text/javascript">

    $(document).ready(function(e) {

        $("#status").change(function() {
            var type = $("#status").val();

            if (type == "active") {
                $("#active").show();
                $("#inactive").hide();
            }

            if (type == "inactive") {
                $("#active").hide();
                $("#inactive").show();
            }
            
        });

        $( window ).load(function() {
            var type=$("#status").val();

            if (type == "active")  {
                $("#active").show();
                $("#inactive").hide();
            }

            if (type == "inactive") {
                $("#active").hide();
                $("#inactive").show();
            }

        });

    });

</script>

<section class="content">

	<ol class="breadcrumb">
		<li><a href="dashboard.php">Dashboard</a></li>
		<li><a href="app.php">Manage Apps</a></li>
		<li class="active">Add App</a></li>
	</ol>

	<div class="container-fluid">

		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

				<form id="form_validation" method="post" enctype="multipart/form-data">
					<div class="card corner-radius">
						<div class="header">
							<h2>ADD APP</h2>
						</div>
						<div class="body">

							<?php if(isset($_SESSION['msg'])) { ?>
							<div class='alert alert-info alert-dismissible corner-radius' role='alert'>
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>&nbsp;&nbsp;</button>
								<?php echo $_SESSION['msg']; ?>
							</div>
							<?php unset($_SESSION['msg']); } ?>                            

							<div class="row clearfix">

								<div class="form-group col-sm-12">
									<div class="form-line">
										<div class="font-12">applicationId (Package Name)</div>
										<input type="text" class="form-control" name="package_name" id="package_name" placeholder="com.domain.appname" value="<?php echo $row['package_name']; ?>" required readonly>
									</div>
								</div>

								<div class="form-group col-sm-12">
									<div class="font-12">Status</div>
									<select class="form-control show-tick" name="status" id="status">
										<?php if ($row['status'] == '1') { ?>
										<option value="active" selected="selected">Active</option>
										<option value="inactive">Inactive</option>
										<?php } else { ?>
										<option value="active">Active</option>
										<option value="inactive" selected="selected">Inactive</option>
										<?php } ?>
									</select>
								</div>

								<div id="active">
                                    <input type="hidden" class="form-control" name="redirect_url" id="redirect_url" value="<?php echo $row['redirect_url']; ?>" required/>
                                </div>

                                <div id="inactive">
									<div class="form-group col-sm-12">
										<div class="form-line">
											<div class="font-12">Redirect Url (Optional)</div>
											<input type="text" class="form-control" name="redirect_url" id="redirect_url" value="<?php echo $row['redirect_url']; ?>" placeholder="https://play.google.com/store/apps/details?id=devApper" required>
										</div>
										<div class="help-info pull-left"><font color="#337ab7">Redirect url is only used if app status is inactive&nbsp;&nbsp;</font></div>
									</div>
								</div>

								<input type="hidden" name="id" id="id" value="<?php echo $row['id']; ?>" >

								<div class="col-sm-12">
									<button class="button button-rounded waves-effect waves-float pull-right" type="submit" name="submit">UPDATE</button>
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