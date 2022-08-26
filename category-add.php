<?php include_once('includes/header.php'); ?>

<?php 

    if (isset($_POST['submit'])) {

        $category_image = time().'_'.$_FILES['category_image']['name'];
        $category_image_tmp = $_FILES['category_image']['tmp_name'];
        $file_path = 'upload/category/'.$category_image;
        copy($category_image_tmp, $file_path);
 
        $data = array(
            'category_name'	 => clean($_POST['category_name']),
            'category_image' => clean($category_image)
        );

        $qry = insert('tbl_category', $data);

        $_SESSION['msg'] = "Category added successfully...";
        header( "Location: category-add.php");
        exit;

    }

?>

<section class="content">

	<ol class="breadcrumb">
		<li><a href="dashboard.php">Dashboard</a></li>
		<li><a href="category.php">Manage Category</a></li>
		<li class="active">Add Category</a></li>
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

								<div class="form-group form-float col-sm-12">
									<div class="form-line">
										<div class="font-12">Category Name</div>
										<input type="text" class="form-control" name="category_name" id="category_name" placeholder="Category Name" required>
									</div>
								</div>

								<div class="col-sm-6" style="margin-bottom: 0px;">
									<div class="form-group">
										<div class="font-12 ex1">Image ( JPG, JPEG, PNG or GIF )</div>
										<input type="file" name="category_image" id="category_image" class="dropify-image" data-max-file-size="3M" data-allowed-file-extensions="jpg jpeg png gif" required/>
									</div>
								</div>
								<div class="col-sm-12">
									<button class="button button-rounded waves-effect waves-float pull-right" type="submit" name="submit">SUBMIT</button>
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