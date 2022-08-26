<?php include_once('includes/header.php'); ?>

<?php

	if (isset($_GET['id'])) {
		$ID = $_GET['id'];
	} else {
		$ID = "";
	}

	$qry    = "SELECT * FROM tbl_news WHERE nid = '$ID'";
	$result = mysqli_query($connect, $qry);
	$data    = mysqli_fetch_assoc($result);


	$sql_query2 = "SELECT * FROM tbl_news n, tbl_comments c, tbl_users u WHERE n.nid = c.nid AND c.user_id = u.id AND n.nid = '".$_GET['id']."'";
	$hasil = mysqli_query($connect, $sql_query2);

	$sql_comments = "SELECT COUNT(*) as num FROM tbl_news n, tbl_comments c WHERE n.nid = c.nid AND n.nid = '".$_GET['id']."'";
	$total_comments = mysqli_query($connect, $sql_comments);
	$total_comments = mysqli_fetch_array($total_comments);
	$total_comments = $total_comments['num'];

	if (isset($_GET['id']) && isset($_GET['comment'])) {
		$newsID = $_GET['id'];
		$commentID = $_GET['comment'];
		$sql_delete = "DELETE FROM tbl_comments WHERE comment_id = '$commentID'";
		$delete = $connect->query($sql_delete);
		if ($delete) {
			$_SESSION['msg'] = "Comment deleted successfully...";
		    header( "Location:comment-detail.php?id=$newsID");
		    exit;
		}
	}

?>

	<section class="content">

        <ol class="breadcrumb breadcrumb-offset">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="news.php">Manage News</a></li>
            <li class="active">Manage Comments</a></li>
        </ol>

        <div class="container-fluid" id="fade-in">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                	<form method="post">
                	<div class="card corner-radius">
                        <div class="header">
                            <h2>COMMENT DETAIL</h2>
                        </div>
                        <div class="body">

                        	<div class="row clearfix">
                        	<div class="form-group form-float col-sm-12">

                        		<?php if (isset($_SESSION['msg'])) { ?>
									<div class='alert alert-info alert-dismissible corner-radius bottom-offset' role='alert'>
										<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>&nbsp;&nbsp;</button>
										<?php echo $_SESSION['msg']; ?>
									</div>
									<?php unset($_SESSION['msg']); } ?>

                        		<p>
									<h4>
										<?php echo $data['news_title']; ?>
									</h4>
								</p>
								<p>
									<?php echo $data['news_date']; ?> 
								</p>
								
                	</form>

							<p><b>Comments ( <?php echo $total_comments;?> )</b></p>
							<?php
								$total = 0;
								while ($data2 = mysqli_fetch_array($hasil)) {
							?>
							<div>
							<table>
								<tr>
									<td>
										<b><?php echo $data2['name']; ?></b> 
									</td>
									<td><a href="comment-detail.php?id=<?php echo $data['nid'];?>&comment=<?php echo $data2['comment_id'];?>" onclick="return confirm('Are you sure want to delete this comment?')" ><i class="material-icons">delete</i></a></td>
								</tr>

								<tr>
									<td><?php echo $data2['date_time']; ?></td>
								</tr>

								<tr>
									<td><?php echo $data2['content']; ?></td>
								</tr>

								<tr>
			                        <?php if ($comment_approval == 'yes') { ?>        
				                        <td>
		                                <?php if ($data2['comment_status'] == '1') { ?>
		                                    <span class="label label-rounded bg-green">APPROVED</span>
		                                <?php } else { ?>
		                                    <span class="label label-rounded bg-red">PENDING</span>
		                                <?php } ?>														
										</td>
				                        <?php } else if ($comment_approval == 'no') { }
			                        ?>
								</tr>				

							</table>
					            	
				           	</div>
				           	<br>

				            <?php } ?>

							</div>
                        	</div>
                        </div>
                    </div>

                </div>

            </div>
            
        </div>

    </section>

<?php include_once('includes/footer.php'); ?>