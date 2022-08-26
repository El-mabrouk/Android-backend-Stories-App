<?php include_once('includes/header.php'); ?>

<?php

	error_reporting(0);

	if (isset($_REQUEST['keyword']) && $_REQUEST['keyword']<>"") {
		$keyword = $_REQUEST['keyword'];
		$reload = "comment.php";
		$sql =  "SELECT c.comment_id, c.content, c.date_time, c.comment_status, u.id, u.name, n.nid, n.news_title 
				FROM tbl_comments c, tbl_users u, tbl_news n 
				WHERE c.user_id = u.id AND c.nid = n.nid AND c.content LIKE '%$keyword%' 
				ORDER BY c.comment_id DESC";
		$result = $connect->query($sql);
	} else {
		$reload = "comment.php";
		$sql =  "SELECT c.comment_id, c.content, c.date_time, c.comment_status, u.id, u.name, n.nid, n.news_title 
				FROM tbl_comments c, tbl_users u, tbl_news n 
				WHERE c.user_id = u.id AND c.nid = n.nid 
				ORDER BY c.comment_id DESC";
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
		$ID = $_GET['delete'];
		$sql_delete = "DELETE FROM tbl_comments WHERE comment_id = '$ID'";
		$delete = $connect->query($sql_delete);
		if ($delete) {
			$_SESSION['msg'] = "Comment deleted successfully...";
		    header( "Location: comment.php");
		    exit;
		}
	}

	if (isset($_GET['approve'])) {
		$commentId = $_GET['approve'];
		$data = array('comment_status' => '1');	
		$update = update('tbl_comments', $data, "WHERE comment_id = '$commentId'");
		if ($update > 0) {
			$_SESSION['msg'] = 'Comment approved...';
			header( "Location:comment.php");
			exit;
		}
	}

?>

<section class="content">

	<ol class="breadcrumb">
		<li><a href="dashboard.php">Dashboard</a></li>
		<li class="active">Manage Comment</a></li>
	</ol>

	<div class="container-fluid">

		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card corner-radius">
					<div class="header">
						<h2>MANAGE COMMENT</h2>
						<div class="header-dropdown m-r--5">
							
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
									<td width="1%"><a href="comment.php"><button type="button" class="button button-rounded waves-effect waves-float">RESET</button></a></td>
									<td width="1%"><button type="submit" class="btn bg-blue btn-circle waves-effect waves-circle waves-float"><i class="material-icons">search</i></button></td>
								</tr>
							</table>
						</form>

						<?php if ($tcount == 0) { ?>
							<p align="center" style="font-size: 110%;">There are no comments.</p>
						<?php } else { ?>							

						<table class='table table-hover table-striped table-offset'>
							<thead>
								<tr>
										<th width="25%">Message</th>
										<th width="15%">Date</th>
										<th width="10%">User</th>
										<th width="30%">On News</th>

		                                <?php if ($comment_approval == 'yes') { ?>        
		                                    <th width="10%">Status</th>
		                                <?php } ?>										

										<th width="10%">Action</th>
									</tr>
							</thead>
							<?php
							while(($count < $rpp) && ($i < $tcount)) {
								mysqli_data_seek($result, $i);
								$data = mysqli_fetch_array($result);
								?>
								<tr>
											<td style="vertical-align: middle;"><?php echo $data['content'];?></td>
											<td style="vertical-align: middle;"><?php echo $data['date_time'];?></td>
											<td style="vertical-align: middle;">
												<a href="user-edit.php?id=<?php echo $data['id'];?>">
													<?php echo $data['name'];?>
												</a>
											</td>
											<td style="vertical-align: middle;">
												<a href="news-detail.php?id=<?php echo $data['nid'];?>">
													<?php echo $data['news_title'];?>
												</a>
											</td>

			                                <?php if ($comment_approval == 'yes') { ?>        
			                                    <td style="vertical-align: middle;">
	                                                <?php if ($data['comment_status'] == '1') { ?>
	                                                    <span class="label label-rounded bg-green">APPROVED</span>
	                                                 <?php } else { ?>
	                                                    <span class="label label-rounded bg-red">PENDING</span>
	                                                <?php } ?>	
													
												</td>

												<td style="vertical-align: middle;">
													<?php if ($data['comment_status'] == '0') { ?>
										            <a href="comment.php?approve=<?php echo $data['comment_id'];?>" onclick="return confirm('Approve this comment? if its approved, it cannot be undone.')">
										                <i class="material-icons">check</i>
										            </a>
										            <?php } ?>
										            
										            <a href="comment.php?delete=<?php echo $data['comment_id'];?>" onclick="return confirm('Are you sure want to delete this comment?')" >
										                <i class="material-icons">delete</i>
										            </a>
										        </td>

			                                <?php } else { ?>
			                                	<td style="vertical-align: middle;">
			                                		<a href="comment.php?delete=<?php echo $data['comment_id'];?>" onclick="return confirm('Are you sure want to delete this comment?')" >
										                <i class="material-icons">delete</i>
										            </a>
										        </td>
			                                <?php } ?>
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