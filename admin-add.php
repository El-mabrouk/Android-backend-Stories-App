<?php include_once('includes/header.php'); ?>

<?php

	$error = false;

	if (isset($_POST['submit'])) {

        $username = clean($_POST['username']);
		$full_name = clean($_POST['full_name']);
		$password = clean($_POST['password']);
		$repassword = clean($_POST['repassword']);
		$email = clean($_POST['email']);
        $role = clean($_POST['role']);

		if (strlen($username) < 3) {
			$error[] = 'Username is too short!';
		}

		if (empty($password)) {
			$error[] = 'Password can not be empty!';
		}

        if (empty($full_name)) {
            $error[] = 'Full name can not be empty!';
        }

		if ($password != $repassword) {
			$error[] = 'Password does not match!';
		}

		$password = hash('sha256',$username.$password);

		if (filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE) {
			$error[] = 'Email is not valid!'; 
		}

		if (!$error) {

			$sql = "SELECT * FROM tbl_admin WHERE (username = '$username' OR email = '$email');";
            $result = mysqli_query($connect, $sql);
            if (mysqli_num_rows($result) > 0) {

            	$row = mysqli_fetch_assoc($result);

            	if ($username == $row['username']) {
                	$error[] = 'Username already exists!';
            	} 

            	if ($email == $row['email']) {
                	$error[] = 'Email already exists!';
            	}

	        } else {

				$sql = "INSERT INTO tbl_admin (username, password, email, full_name, user_role) VALUES (?, ?, ?, ?, ?)";

				$insert = $connect->prepare($sql);
				$insert->bind_param('sssss', $username, $password, $email, $full_name, $role);
				$insert->execute();

 				$_SESSION['msg'] = "Admin added successfully...";
        		header( "Location: admin-add.php");
        		exit;
			}
		}
	}

?>

    <section class="content">

        <ol class="breadcrumb">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="admin.php">Manage Admin</a></li>
            <li class="active">Add New Admin</a></li>
        </ol>

       <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                	<form id="form_validation" method="post">
                    <div class="card corner-radius">
                        <div class="header">
                            <h2>ADD NEW ADMIN</h2>
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
                                    <div class="form-group form-float col-sm-12">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="username" id="username" required>
                                            <label class="form-label">Username</label>
                                        </div>
                                    </div>

                                    <div class="form-group form-float col-sm-12">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="full_name" id="full_name" required>
                                            <label class="form-label">Full Name</label>
                                        </div>
                                    </div>

                                    <div class="form-group form-float col-sm-12">
                                        <div class="form-line">
                                            <input type="email" class="form-control" name="email" id="email" required>
                                            <label class="form-label">Email</label>
                                        </div>
                                    </div>

                                    <div class="form-group form-float col-sm-12">
                                        <div class="form-line">
                                            <input type="password" class="form-control" name="password" id="password" required>
                                            <label class="form-label">Password</label>
                                        </div>
                                    </div>

                                    <div class="form-group form-float col-sm-12">
                                        <div class="form-line">
                                            <input type="password" class="form-control" name="repassword" id="repassword" required>
                                            <label class="form-label">Re Password</label>
                                        </div>
                                    </div>

                                    <input type="hidden" name="role" id="role" value="100" />

                                    <div class="col-sm-12">
                                         <button class="button button-rounded waves-effect waves-float pull-right" type="submit" name="submit">SUBMIT</button>
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