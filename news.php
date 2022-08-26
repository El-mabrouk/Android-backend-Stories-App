<?php include_once('includes/header.php'); ?>

<?php

	error_reporting(0);

    // delete selected records
    if(isset($_POST['submit'])) {

        $arr = $_POST['chk_id'];
        $count = count($arr);
        if ($count > 0) {
            foreach ($arr as $nid) {

                $sql_image = "SELECT news_image FROM tbl_news WHERE nid = $nid";
                $img_results = mysqli_query($connect, $sql_image);

                $sql_delete = "DELETE FROM tbl_news WHERE nid = $nid";

                if (mysqli_query($connect, $sql_delete)) {
                    while ($row = mysqli_fetch_assoc($img_results)) {
                        unlink('upload/' . $row['news_image']);
                    }
                    $_SESSION['msg'] = "$count Selected news deleted";
                } else {
                    $_SESSION['msg'] = "Error deleting record";
                }

            }
        } else {
            $_SESSION['msg'] = "Whoops! no news selected to delete";
        }
        header("Location:news.php");
        exit;
    } 

	if (isset($_REQUEST['keyword']) && $_REQUEST['keyword']<>"") {
		$keyword = $_REQUEST['keyword'];
		$reload = "news.php";
		$sql =  "SELECT n.nid, n.news_title, n.news_image, n.news_date, c.category_name, n.video_id, n.content_type, COUNT(comment_id) AS comments_count, n.view_count
					FROM tbl_news n 
						LEFT JOIN tbl_comments r ON n.nid = r.nid 
						LEFT JOIN tbl_category c ON n.cat_id = c.cid
					WHERE n.news_title LIKE '%$keyword%'
					GROUP BY n.nid  
					ORDER BY n.nid DESC";
		$result = $connect->query($sql);
	} else {
		$reload = "news.php";
		$sql =  "SELECT n.nid, n.news_title, n.news_image, n.news_date, c.category_name, n.video_id, n.content_type, COUNT(comment_id) AS comments_count, n.view_count
					FROM tbl_news n 
						LEFT JOIN tbl_comments r ON n.nid = r.nid 
						LEFT JOIN tbl_category c ON n.cat_id = c.cid
					GROUP BY n.nid  
					ORDER BY n.nid DESC";
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

?>

<section class="content">

	<ol class="breadcrumb">
		<li><a href="dashboard.php">Dashboard</a></li>
		<li class="active">Manage News</a></li>
	</ol>

	<div class="container-fluid">

		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card corner-radius">
					<div class="header">
						<h2>MANAGE NEWS</h2>
						<div class="header-dropdown m-r--5">
							<a href="news-add.php"><button type="button" class="button button-rounded btn-offset waves-effect waves-float">ADD NEW NEWS</button></a>
						</div>
					</div>

					<div style="margin-top: -10px;" class="body table-responsive">

						<?php if(isset($_SESSION['msg'])) { ?>
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
									<td width="1%"><a href="news.php"><button type="button" class="button button-rounded waves-effect waves-float">RESET</button></a></td>
									<td width="1%"><button type="submit" class="btn bg-blue btn-circle waves-effect waves-circle waves-float"><i class="material-icons">search</i></button></td>
								</tr>
							</table>
						</form>

						<?php if ($tcount == 0) { ?>
							<p align="center" style="font-size: 110%;">There are no news.</p>
						<?php } else { ?>

						<form method="post" action="">

							<div style="margin-left: 8px; margin-top: -36px; margin-bottom: 10px;">
								<button type="submit" name="submit" id="submit" class="button button-rounded waves-effect waves-float" onclick="return confirm('Are you sure want to delete all selected news?')">Delete selected items(s)</button>
							</div>				

							<table class='table table-hover table-striped'>
								<thead>
									<tr>
										<th width="1%">
											<div style="margin-bottom: -15px">
												<input id="chk_all" name="chk_all" type="checkbox" class="filled-in chk-col-blue" />
												<label for="chk_all"></label>
											</div>
										</th>
										<th width="40%">News Title</th>
										<th width="1%">Image</th>
										<th width="15%">Date</th>
										<th width="5%">Category</th>
										<th width="1%"><center>Comment</center></th>
										<th width="1%"><center>View</center></th>
										<th width="10%"><center>Type</center></th>
										<th width="25%"><center>Action</center></th>
									</tr>
								</thead>
								<?php
								while(($count < $rpp) && ($i < $tcount)) {
									mysqli_data_seek($result, $i);
									$data = mysqli_fetch_array($result);
									?>
									<tr>

										<td style="vertical-align: middle;" width="1%">
											<div style="margin-top: 10px;">
												<input type="checkbox" name="chk_id[]" id="<?php echo $data['nid'];?>" class="chkbox filled-in chk-col-blue" value="<?php echo $data['nid'];?>"/>
					                            <label for="<?php echo $data['nid'];?>"></label>
											</div>
										</td>

										<td style="vertical-align: middle;"><?php echo $data['news_title'];?></td>

										<td style="vertical-align: middle;">
											<?php
											if ($data['content_type'] == 'youtube') { ?>
												<img class="img-corner-radius" style="object-fit:cover;" src="https://img.youtube.com/vi/<?php echo $data['video_id'];?>/mqdefault.jpg" height="60px" width="80px"/>
											<?php } else { ?>
												<img class="img-corner-radius" style="object-fit:cover;" src="upload/<?php echo $data['news_image'];?>" height="60px" width="80px"/>
											<?php } ?>
										</td>

										<td style="vertical-align: middle;"><?php echo $data['news_date'];?></td>
										<td style="vertical-align: middle;"><?php echo $data['category_name'];?></td>
										<td style="vertical-align: middle;">
											<?php
											if ($data['comments_count'] > 0) {
												?>
												<a href="comment-detail.php?id=<?php echo $data['nid'];?>">
													<center>
														<?php echo $data['comments_count'];?>
													</center>
												</a>
												<?php
											} else {
												?>
												<center>
													<?php echo $data['comments_count'];?>
												</center>
												<?php
											}
											?>
										</td>
										<td style="vertical-align: middle;"><center><?php echo $data['view_count'];?></center></td>
										<td style="vertical-align: middle;"><center>
											<?php if ($data['content_type'] == 'Post') { ?>
											<span class="label label-rounded bg-blue">NEWS</span>
											<?php } else { ?>
											<span class="label label-rounded bg-red">VIDEO</span>
											<?php } ?>	
										</center></td>
										<td style="vertical-align: middle;"><center>

											<a href="news-send.php?id=<?php echo $data['nid'];?>">
												<i class="material-icons">notifications_active</i>
											</a>	

											<a href="news-detail.php?id=<?php echo $data['nid'];?>">
												<i class="material-icons">launch</i>
											</a>

											<a href="news-edit.php?id=<?php echo $data['nid'];?>">
												<i class="material-icons">mode_edit</i>
											</a>

											<a href="news-delete.php?id=<?php echo $data['nid'];?>" onclick="return confirm('Are you sure want to delete this News?')" >
												<i class="material-icons">delete</i>
											</a></center>
										</td>
									</tr>
									<?php
									$i++; 
									$count++;
								}
								?>
							</table>

						</form>

						<?php } ?>

						<?php if ($tcount > $postPerPage) { echo pagination($reload, $page, $keyword, $tpages); } ?>
					</div>

				</div>
			</div>
		</div>
	</div>
</section>

<?php include_once('includes/footer.php'); ?>