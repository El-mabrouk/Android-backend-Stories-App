<?php include_once('includes/header.php'); ?>

<?php

	$error = false;

	if (isset($_GET['id'])) {
		$id =  clean($_GET['id']);
		$sql = "SELECT * FROM tbl_admin WHERE id = ? LIMIT 1";
		$stmt = $connect->prepare($sql);
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($id, $username, $password, $email, $full_name, $role);
		$stmt->fetch();
	} else {
		die('404 Oops!!!');
	}

	if (isset($_POST['submit'])) {

		$newusername   	= clean($_POST['username']);
		$newfullname    = clean($_POST['full_name']);
		$newpassword   	= clean($_POST['password']);
		$newrepassword 	= clean($_POST['repassword']);
		$newemail 		= clean($_POST['email']);
		$newrole  		= clean($_POST['role']);

		if (strlen($newusername) < 3) {
			$error[] = 'Username is too short!';
		}

		if (empty($newfullname)) {
			$error[] = 'Full name can not be empty!';
		}

		if (empty($newpassword)) {
			$error[] = 'Password can not be empty!';
		}

		if ($newpassword != $newrepassword) {
			$error[] = 'Password does not match!';
		}

		$newpassword = hash('sha256', $newusername.$newpassword);

		if (filter_var($newemail, FILTER_VALIDATE_EMAIL) === FALSE) {
			$error[] = 'Email is not valid!';
		}

		if (! $error) {
			$sql = "UPDATE tbl_admin SET username = ?, password = ?, email = ?, full_name = ?, user_role = ? WHERE id = ?";
			$update = $connect->prepare($sql);
			$update->bind_param(
				'sssssi',
				$newusername,
				$newpassword,
				$newemail,
				$newfullname,
				$newrole,
				$id
			);

			$update->execute();

            $_SESSION['msg'] = "Changes Saved...";
            header("Location:admin-edit.php?id=$id");
            exit;
		}
	}

?>

    <section class="content">

        <ol class="breadcrumb">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="admin.php">Manage Admin</a></li>
            <li class="active">Edit Admin</a></li>
        </ol>

       <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <form id="form_validation" method="post">
	                    <div class="card corner-radius">
	                        <div class="header">
	                            <h2>EDIT ADMIN</h2>
	                        </div>
	                        <div class="body">

	                            <?php echo $error ? '<div class="alert alert-info alert-dismissible corner-radius"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>&nbsp;&nbsp;</button>'. implode('<br>', $error) . '</div>' : '';?>

								<?php if(isset($_SESSION['msg'])) { ?>
								<div class='alert alert-info alert-dismissible corner-radius' role='alert'>
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>&nbsp;&nbsp;</button>
									<?php echo $_SESSION['msg']; ?>
								</div>
								<?php unset($_SESSION['msg']); } ?>

	                            <div class="row clearfix">
	                                
	                                <div>
	                                    <div class="form-group col-sm-12">
	                                        <div class="form-line">
	                                            <div class="font-12">Username</div>
	                                            <input type="text" class="form-control" value="<?php echo $username; ?>" readonly />

	                                            <input type="hidden" class="form-control" value="<?php echo $username; ?>" name="username" id="username" />
	                                            <!-- <label class="form-label">Username</label> -->
	                                        </div>
	                                    </div>

	                                    <div class="form-group col-sm-12">
	                                        <div class="form-line">
	                                            <div class="font-12">Full Name</div>
	                                            <input type="text" class="form-control" name="full_name" id="full_name" value="<?php echo $full_name; ?>" />
	                                            <!-- <label class="form-label">Username</label> -->
	                                        </div>
	                                    </div>

	                                    <div class="form-group col-sm-12">
	                                        <div class="form-line">
	                                            <div class="font-12">Email</div>
	                                            <input type="email" class="form-control" name="email" id="email" value="<?php echo $email; ?>" />
	                                            <!-- <label class="form-label">Email</label> -->
	                                        </div>
	                                    </div>

	                                    <div class="form-group form-float col-sm-12">
	                                        <div class="form-line">
	                                            <input type="password" class="form-control" name="password" id="password" required />
	                                            <label class="form-label">Password</label>
	                                        </div>
	                                    </div>

	                                    <div class="form-group form-float col-sm-12">
	                                        <div class="form-line">
	                                            <input type="password" class="form-control" name="repassword" id="repassword" required />
	                                            <label class="form-label">Re Password</label>
	                                        </div>
	                                    </div>

	                                    <input type="hidden" name="role" id="role" value="<?php echo $role; ?>" />

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