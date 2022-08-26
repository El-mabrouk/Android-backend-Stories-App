<?php include_once('includes/header.php'); ?>

<?php

    if (isset($_GET['id'])) {

    	$ID = clean($_GET['id']);
        $users = "SELECT * FROM tbl_users WHERE id = '$ID'";
        $results = $connect->query($users);
        $data = $results->fetch_assoc();

    }

    if(isset($_POST['submit'])) {
 
        $data = array(
            'status'  => clean($_POST['status'])
        );  

        $update = update('tbl_users', $data, "WHERE id = '$ID'");

        if ($update > 0) {
            $_SESSION['msg'] = "Changes Saved...";
            header("Location:user-edit.php?id=$ID");
            exit;
        }

    }			
		
?>

    <section class="content">

        <ol class="breadcrumb breadcrumb-offset">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="user.php">Registered User</a></li>
            <li class="active">Edit User</a></li>
        </ol>

       <div class="container-fluid" id="fade-in">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <form id="form_validation" method="post" enctype="multipart/form-data">
                    <div class="card corner-radius">
                        <div class="header">
                            <h2>EDIT USER</h2>
                            <div class="header-dropdown m-r--5">
                                <button type="submit" name="submit" class="button button-rounded btn-offset bg-blue waves-effect pull-right">UPDATE</button>
                            </div>
                        </div>
                        <div class="body">
                            
                            <?php if(isset($_SESSION['msg'])) { ?>
								<div class='alert alert-info alert-dismissible corner-radius' role='alert'>
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>&nbsp;&nbsp;</button>
									<?php echo $_SESSION['msg']; ?>
								</div>
							<?php unset($_SESSION['msg']); } ?>

                            <div class="row clearfix">

                            <div class="col-sm-12">
                            	<center>
	                            <?php if ($data['imageName'] == NULL) { ?>
	                                <img src="assets/images/ic_user.png" class="rounded-image" height="100px" width="100px"/>
	                            <?php } else { ?>
	                                <img src="upload/avatar/<?php echo $data['imageName'];?>" class="rounded-image" height="100px" width="100px"/>
	                            <?php } ?>
	                            </center>                        	
	                        </div>

                                <div class="form-group col-sm-12">
                                    <div class="form-line">
                                        <div class="font-12">Name</div>
                                        <input type="text" class="form-control" value="<?php echo $data['name']; ?>" readonly />
                                    </div>
                                </div>

                                <div class="form-group col-sm-12">
                                    <div class="form-line">
                                        <div class="font-12">Email</div>
                                        <input type="text" class="form-control" value="<?php echo $data['email']; ?>" readonly />
                                    </div>
                                </div>

				                <div class="form-group col-sm-12">
				                    <div class="font-12">Status</div>
					                    <select class="form-control show-tick" name="status" id="status">	
											<?php if ($data['status'] == 1) { ?>
												<option value="1" selected="selected">Enabled</option>
												<option value="0" >Disabled</option>
											<?php } else { ?>
												<option value="1" >Enabled</option>
												<option value="0" selected="selected">Disabled</option>
											<?php } ?>
										</select>
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