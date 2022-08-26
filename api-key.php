<?php include_once('includes/header.php'); ?>

<?php 

    if (isset($_POST['submit'])) {

    	$ID = clean('1');

        $data = array(
            'api_key' => $_POST['api_key']
        );

        $update_setting = update('tbl_settings', $data, "WHERE id = '$ID'");

        if ($update_setting > 0) {
            $_SESSION['msg'] = "Changes saved...";
            header( "Location:settings.php");
            exit;
        }
    }

?>

   <section class="content">

        <ol class="breadcrumb">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="settings.php">Settings</a></li>
            <li class="active">Change API Key</a></li>
        </ol>

       <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <form method="post">
                        <div class="card corner-radius">
                            <div class="header">
                                <h2>CHANGE API KEY</h2>
                                <div class="header-dropdown m-r--5">
                                	<a href="api-key.php?generate=true"><button type="button" class="button button-rounded btn-offset waves-effect waves-float">GENERATE</button></a>
                                </div>
                                <br>

                                <?php if(isset($_SESSION['msg'])) { ?>
                                <div class='alert alert-info'>
                                    <?php echo $message[$_SESSION['msg']] ; ?>
                                </div>
                                <?php unset($_SESSION['msg']); }?>
                            </div>

                            <div class="body">

                                <div class="row clearfix">

                                    <?php
                                        $apiKey = generateApiKey();
                                        if (isset($_GET['generate'])) {
                                    ?>
	                                    <div class="col-sm-12">
	                                        <div class="form-group">
	                                            <div class="form-line">
	                                                <div class="font-12">Generated API Key</div>
	                                                <input type="text" class="form-control" name="api_key" id="api_key" value="cda11<?php echo $apiKey;?>" required />
	                                            </div>
	                                        </div>

	                                        <button type="submit" name="submit" class="button button-rounded waves-effect waves-float" onclick="return confirm('Are you sure want to update API Key?')">UPDATE API KEY</button>
	                                    </div>

                                    <?php } ?>

                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            
        </div>

    </section>

<?php include_once('includes/footer.php'); ?>