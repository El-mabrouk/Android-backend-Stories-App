<?php include_once('includes/header.php'); ?>

<?php 
    
    if (isset($_GET['id'])) {

    	$ID = clean($_GET['id']);
        $sql_category = "SELECT * FROM tbl_category WHERE cid = '$ID'";
        $result = $connect->query($sql_category);
        $row = $result->fetch_assoc();

    }

    if(isset($_POST['submit'])) {

        $old_image = clean($_POST['old_image']);

        if ($_FILES['category_image']['name'] != '') {

            unlink('upload/category/'.$old_image);

            $category_image = time().'_'.$_FILES['category_image']['name'];
            $category_image_tmp = $_FILES['category_image']['tmp_name'];
            $file_path = 'upload/category/'.$category_image;
            copy($category_image_tmp, $file_path);
            
        } else {
            $category_image = $old_image;
        }
 
        $data = array(
            'category_name'  => clean($_POST['category_name']),
            'category_image' => $category_image
        );  

        $update = update('tbl_category', $data, "WHERE cid = '$ID'");

        if ($update > 0) {
            $_SESSION['msg'] = "Changes Saved...";
            header("Location:category-edit.php?id=$ID");
            exit;
        }

    }

?>

    <section class="content">

        <ol class="breadcrumb">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="category.php">Manage Category</a></li>
            <li class="active">Edit Category</a></li>
        </ol>

       <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <form id="form_validation" method="post" enctype="multipart/form-data">
                    <div class="card corner-radius">
                        <div class="header">
                            <h2>EDIT CATEGORY</h2>
                        </div>
                        <div class="body">

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
                                            <div class="font-12">Category Name</div>
                                            <input type="text" class="form-control" name="category_name" id="category_name" value="<?php echo $row['category_name']; ?>" required>
                                        </div>
                                    </div>

                                    <div class="col-sm-6" style="margin-bottom: 0px;">
                                        <div class="form-group">
                                            <div class="font-12 ex1">Image ( JPG, JPEG, PNG or GIF )</div>
                                            <input type="file" name="category_image" id="category_image" class="dropify-image" data-max-file-size="3M" data-allowed-file-extensions="jpg jpeg png gif" data-default-file="upload/category/<?php echo $row['category_image']; ?>" data-show-remove="false"/>
                                        </div>
                                    </div>

                                    <input type="hidden" name="old_image" id="old_image" value="<?php echo $row['category_image']; ?>" >
                                    <input type="hidden" name="id" id="id" value="<?php echo $row['cid']; ?>" >

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