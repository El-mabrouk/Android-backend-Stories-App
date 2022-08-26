<?php

require_once("Rest.inc.php");
require_once("db.php");

class functions extends REST {
    
    private $mysqli = NULL;
    private $db = NULL;
    
    public function __construct($db) {
        parent::__construct();
        $this->db = $db;
        $this->mysqli = $db->mysqli;
    }

	public function checkConnection() {
			if (mysqli_ping($this->mysqli)) {
                $respon = array(
                    'status' => 'ok', 'database' => 'connected'
                );
                $this->response($this->json($respon), 200);
			} else {
                $respon = array(
                    'status' => 'failed', 'database' => 'not connected'
                );
                $this->response($this->json($respon), 404);
			}
	}

    public function getRecentPosts() {

    		include "../includes/config.php";
		    $setting_qry    = "SELECT * FROM tbl_settings where id = '1'";
		    $setting_result = mysqli_query($connect, $setting_qry);
		    $settings_row   = mysqli_fetch_assoc($setting_result);
		    $api_key    = $settings_row['api_key'];
		    $comment_approval    = $settings_row['comment_approval'];

			if (isset($_GET['api_key'])) {

				$access_key_received = $_GET['api_key'];

				if ($access_key_received == $api_key) {

					if($this->get_request_method() != "GET") $this->response('',406);
						$limit = isset($this->_request['count']) ? ((int)$this->_request['count']) : 10;
						$page = isset($this->_request['page']) ? ((int)$this->_request['page']) : 1;
						
						$offset = ($page * $limit) - $limit;
						$count_total = $this->get_count_result("SELECT COUNT(DISTINCT n.nid) FROM tbl_news n ");
						$query = "SELECT DISTINCT n.nid, 
									n.news_title, 
									n.cat_id,
									n.news_date, 
									n.news_image, 
									n.news_description,
									n.video_url,
									n.video_id, 
									n.content_type, 
									
									c.category_name, 
									COUNT(DISTINCT r.comment_id) as comments_count,
									n.view_count

								  FROM tbl_news n 

								  LEFT JOIN tbl_comments r ON n.nid = r.nid
								  LEFT JOIN tbl_category c ON n.cat_id = c.cid

								  GROUP BY n.nid 

								  ORDER BY n.nid 

								  DESC LIMIT $limit OFFSET $offset";

						$query2 = "SELECT DISTINCT n.nid, 
									n.news_title, 
									n.cat_id,
									n.news_date, 
									n.news_image, 
									n.news_description,
									n.video_url,
									n.video_id, 
									n.content_type, 
									
									c.category_name, 
									COUNT(DISTINCT r.comment_id) as comments_count,
									n.view_count

								  FROM tbl_news n 

								  LEFT JOIN tbl_comments r ON n.nid = r.nid AND r.comment_status = '1'
								  LEFT JOIN tbl_category c ON n.cat_id = c.cid

								  GROUP BY n.nid 

								  ORDER BY n.nid 

								  DESC LIMIT $limit OFFSET $offset";

						if ($comment_approval == 'yes') {
							$categories = $this->get_list_result($query2);
						} else if ($comment_approval == 'no') {
							$categories = $this->get_list_result($query);
						}

						$count = count($categories);
						$respon = array(
							'status' => 'ok', 'count' => $count, 'count_total' => $count_total, 'pages' => $page, 'posts' => $categories
						);
						$this->response($this->json($respon), 200);

				} else {
					$respon = array( 'status' => 'failed', 'message' => 'Oops, API Key is Incorrect!');
					$this->response($this->json($respon), 404);
				}
			} else {
				$respon = array( 'status' => 'failed', 'message' => 'Forbidden, API Key is Required!');
				$this->response($this->json($respon), 404);
			}

    }  

	public function getNewsDetail() {

    	$id = $_GET['id'];

		if($this->get_request_method() != "GET") $this->response('',406);

		$query_post = "SELECT DISTINCT n.nid, 
						n.news_title, 
						n.cat_id,
						n.news_date, 
						n.news_image, 
						n.news_description,
						n.video_url,
						n.video_id, 
						n.content_type, 
									
						c.category_name, 
						COUNT(DISTINCT r.comment_id) as comments_count,
						n.view_count

						FROM tbl_news n 

						LEFT JOIN tbl_comments r ON n.nid = r.nid 
						LEFT JOIN tbl_category c ON n.cat_id = c.cid 

						WHERE n.nid = $id

						GROUP BY n.nid
								 
						LIMIT 1";

		$post = $this->get_one($query_post);
		$count = count($post);
		$respon = array(
			'status' => 'ok', 'post' => $post
		);
		$this->response($this->json($respon), 200);

    }

	public function getPostDetail() {

    	$id = $_GET['id'];
    	include "../includes/config.php";
    	$setting_qry    = "SELECT cat_id FROM tbl_news WHERE nid = $id";
		$setting_result = mysqli_query($connect, $setting_qry);
		$settings_row   = mysqli_fetch_assoc($setting_result);
		$category_id    = $settings_row['cat_id'];

		if($this->get_request_method() != "GET") $this->response('',406);

		$query_image = "SELECT nid, news_image AS 'image_name', content_type, video_id, video_url FROM tbl_news WHERE nid = $id UNION SELECT n.nid, g.image_name, n.content_type, n.video_id, n.video_url FROM tbl_news n, tbl_news_gallery g WHERE n.nid = g.nid AND g.nid = $id";

		$query_post = "SELECT DISTINCT n.nid, 
						n.news_title, 
						n.cat_id,
						n.news_date, 
						n.news_image, 
						n.news_description,
						n.video_url,
						n.video_id, 
						n.content_type,
							
						c.category_name, 
						COUNT(DISTINCT r.comment_id) as comments_count,
						n.view_count

						FROM tbl_news n 

						LEFT JOIN tbl_comments r ON n.nid = r.nid 
						LEFT JOIN tbl_category c ON n.cat_id = c.cid 

						WHERE n.nid = $id

						GROUP BY n.nid
								 
						LIMIT 1";

		$query_related = "SELECT DISTINCT n.nid, 
									n.news_title, 
									n.cat_id,
									n.news_date, 
									n.news_image,
									n.news_description,
									n.video_url,
									n.video_id, 
									n.content_type,
									
									c.category_name, 
									COUNT(DISTINCT r.comment_id) as comments_count,
									n.view_count

								  FROM tbl_news n 

								  LEFT JOIN tbl_comments r ON n.nid = r.nid 
								  LEFT JOIN tbl_category c ON n.cat_id = c.cid

								  WHERE n.nid != $id AND n.cat_id = $category_id

								  GROUP BY n.nid 

								  ORDER BY n.nid 

								  DESC LIMIT 5";

		$images = $this->get_list_result($query_image);
		$post = $this->get_one($query_post);
		$related = $this->get_list_result($query_related);

		$count = count($post);
		$respon = array(
			'status' => 'ok', 'post' => $post, 'images' => $images, 'related' => $related
		);
		$this->response($this->json($respon), 200);

    }

    public function getVideoPosts() {

    		include "../includes/config.php";
		    $setting_qry    = "SELECT * FROM tbl_settings where id = '1'";
		    $setting_result = mysqli_query($connect, $setting_qry);
		    $settings_row   = mysqli_fetch_assoc($setting_result);
		    $api_key    = $settings_row['api_key'];
		    $comment_approval    = $settings_row['comment_approval'];

			if (isset($_GET['api_key'])) {

				$access_key_received = $_GET['api_key'];

				if ($access_key_received == $api_key) {

					if($this->get_request_method() != "GET") $this->response('',406);
						$limit = isset($this->_request['count']) ? ((int)$this->_request['count']) : 10;
						$page = isset($this->_request['page']) ? ((int)$this->_request['page']) : 1;
						
						$offset = ($page * $limit) - $limit;
						$count_total = $this->get_count_result("SELECT COUNT(DISTINCT n.nid) FROM tbl_news n WHERE n.content_type != 'Post' ");
						$query = "SELECT DISTINCT n.nid, 
									n.news_title, 
									n.cat_id,
									n.news_date, 
									n.news_image, 
									n.news_description,
									n.video_url,
									n.video_id, 
									n.content_type, 
									
									c.category_name, 
									COUNT(DISTINCT r.comment_id) as comments_count,
									n.view_count

								  FROM tbl_news n 

								  LEFT JOIN tbl_comments r ON n.nid = r.nid 
								  LEFT JOIN tbl_category c ON n.cat_id = c.cid

								  WHERE n.content_type != 'Post'

								  GROUP BY n.nid 

								  ORDER BY n.nid 

								  DESC LIMIT $limit OFFSET $offset";

						$query2 = "SELECT DISTINCT n.nid, 
									n.news_title, 
									n.cat_id,
									n.news_date, 
									n.news_image, 
									n.news_description,
									n.video_url,
									n.video_id, 
									n.content_type, 
									
									c.category_name, 
									COUNT(DISTINCT r.comment_id) as comments_count,
									n.view_count

								  FROM tbl_news n 

								  LEFT JOIN tbl_comments r ON n.nid = r.nid AND r.comment_status = '1'
								  LEFT JOIN tbl_category c ON n.cat_id = c.cid

								  WHERE n.content_type != 'Post'

								  GROUP BY n.nid 

								  ORDER BY n.nid 

								  DESC LIMIT $limit OFFSET $offset";

						if ($comment_approval == 'yes') {
							$categories = $this->get_list_result($query2);
						} else if ($comment_approval == 'no') {
							$categories = $this->get_list_result($query);
						}

						$count = count($categories);
						$respon = array(
							'status' => 'ok', 'count' => $count, 'count_total' => $count_total, 'pages' => $page, 'posts' => $categories
						);
						$this->response($this->json($respon), 200);

				} else {
					$respon = array( 'status' => 'failed', 'message' => 'Oops, API Key is Incorrect!');
					$this->response($this->json($respon), 404);
				}
			} else {
				$respon = array( 'status' => 'failed', 'message' => 'Forbidden, API Key is Required!');
				$this->response($this->json($respon), 404);
			}

    }
    
    public function getCategoryIndex() {

    	include "../includes/config.php";
        $setting_qry    = "SELECT * FROM tbl_settings where id = '1'";
		$setting_result = mysqli_query($connect, $setting_qry);
		$settings_row   = mysqli_fetch_assoc($setting_result);
		$api_key    = $settings_row['api_key'];
		$comment_approval    = $settings_row['comment_approval'];

			if (isset($_GET['api_key'])) {

				$access_key_received = $_GET['api_key'];

				if ($access_key_received == $api_key) {

					if($this->get_request_method() != "GET") $this->response('',406);
					$count_total = $this->get_count_result("SELECT COUNT(DISTINCT cid) FROM tbl_category");

					$query = "SELECT DISTINCT c.cid, c.category_name, c.category_image, COUNT(DISTINCT r.nid) as post_count
					  FROM tbl_category c LEFT JOIN tbl_news r ON c.cid = r.cat_id GROUP BY c.cid ORDER BY c.cid DESC";

					$news = $this->get_list_result($query);
					$count = count($news);
					$respon = array(
						'status' => 'ok', 'count' => $count, 'categories' => $news
					);
					$this->response($this->json($respon), 200);

				} else {
					$respon = array( 'status' => 'failed', 'message' => 'Oops, API Key is Incorrect!');
					$this->response($this->json($respon), 404);
				}
			} else {
				$respon = array( 'status' => 'failed', 'message' => 'Forbidden, API Key is Required!');
				$this->response($this->json($respon), 404);
			}

    }

    public function getCategoryPosts() {

    	include "../includes/config.php";
        $setting_qry    = "SELECT * FROM tbl_settings where id = '1'";
		$setting_result = mysqli_query($connect, $setting_qry);
		$settings_row   = mysqli_fetch_assoc($setting_result);
		$api_key    = $settings_row['api_key'];
		$comment_approval    = $settings_row['comment_approval'];

			if (isset($_GET['api_key'])) {

				$access_key_received = $_GET['api_key'];

				if ($access_key_received == $api_key) {

			    	$id = $_GET['id'];

					if($this->get_request_method() != "GET") $this->response('',406);
					$limit = isset($this->_request['count']) ? ((int)$this->_request['count']) : 10;
					$page = isset($this->_request['page']) ? ((int)$this->_request['page']) : 1;

					$offset = ($page * $limit) - $limit;
					$count_total = $this->get_count_result("SELECT COUNT(DISTINCT nid) FROM tbl_news WHERE cat_id = '$id'");

					$query_category = "SELECT distinct cid, category_name, category_image FROM tbl_category WHERE cid = '$id' ORDER BY cid DESC";

					$query_post = "SELECT DISTINCT n.nid, 
									n.news_title, 
									n.cat_id,
									n.news_date, 
									n.news_image, 
									n.news_description,
									n.video_url,
									n.video_id, 
									n.content_type, 
												
									c.category_name, 
									COUNT(DISTINCT r.comment_id) as comments_count,
									n.view_count

									FROM tbl_news n 

									LEFT JOIN tbl_comments r ON n.nid = r.nid 
									LEFT JOIN tbl_category c ON n.cat_id = c.cid 

									WHERE c.cid = '$id' 

									GROUP BY n.nid 
									ORDER BY n.nid DESC 
											 
									LIMIT $limit OFFSET $offset";

					$query_post2 = "SELECT DISTINCT n.nid, 
									n.news_title, 
									n.cat_id,
									n.news_date, 
									n.news_image, 
									n.news_description,
									n.video_url,
									n.video_id, 
									n.content_type, 
												
									c.category_name, 
									COUNT(DISTINCT r.comment_id) as comments_count,
									n.view_count

									FROM tbl_news n 

									LEFT JOIN tbl_comments r ON n.nid = r.nid AND r.comment_status = '1'
									LEFT JOIN tbl_category c ON n.cat_id = c.cid 

									WHERE c.cid = '$id' 

									GROUP BY n.nid 
									ORDER BY n.nid DESC 
											 
									LIMIT $limit OFFSET $offset";

					$category = $this->get_category_result($query_category);


						if ($comment_approval == 'yes') {
							$post = $this->get_list_result($query_post2);
						} else if ($comment_approval == 'no') {
							$post = $this->get_list_result($query_post);
						}					

					$count = count($post);

					$respon = array(
						'status' => 'ok', 'count' => $count, 'count_total' => $count_total, 'pages' => $page, 'category' => $category, 'posts' => $post
					);
					$this->response($this->json($respon), 200);

				} else {
					$respon = array( 'status' => 'failed', 'message' => 'Oops, API Key is Incorrect!');
					$this->response($this->json($respon), 404);
				}
			} else {
				$respon = array( 'status' => 'failed', 'message' => 'Forbidden, API Key is Required!');
				$this->response($this->json($respon), 404);
			}


    }

    public function getSearchResults() {

    	include "../includes/config.php";
		    $setting_qry    = "SELECT * FROM tbl_settings WHERE id = '1'";
		    $setting_result = mysqli_query($connect, $setting_qry);
		    $settings_row   = mysqli_fetch_assoc($setting_result);
		    $api_key    = $settings_row['api_key'];
		    $comment_approval    = $settings_row['comment_approval'];

			if (isset($_GET['api_key'])) {

				$access_key_received = $_GET['api_key'];

				if ($access_key_received == $api_key) {

					$search = $_GET['search'];

					if($this->get_request_method() != "GET") $this->response('',406);
					$limit = isset($this->_request['count']) ? ((int)$this->_request['count']) : 10;
					$page = isset($this->_request['page']) ? ((int)$this->_request['page']) : 1;

					$offset = ($page * $limit) - $limit;
					$count_total = $this->get_count_result("SELECT COUNT(DISTINCT n.nid) FROM tbl_news n, tbl_category c WHERE n.cat_id = c.cid AND (n.news_title LIKE '%$search%' OR n.news_description LIKE '%$search%')");

					$query = "SELECT DISTINCT n.nid, 
									n.news_title, 
									n.cat_id,
									n.news_date, 
									n.news_image, 
									n.news_description,
									n.video_url,
									n.video_id, 
									n.content_type, 
									
									c.category_name, 
									COUNT(DISTINCT r.comment_id) as comments_count,
									n.view_count

								  FROM tbl_news n 

								  LEFT JOIN tbl_comments r ON n.nid = r.nid 
								  LEFT JOIN tbl_category c ON n.cat_id = c.cid 

								  WHERE n.cat_id = c.cid AND (n.news_title LIKE '%$search%' OR n.news_description LIKE '%$search%') 

								  GROUP BY n.nid 
								  ORDER BY n.nid DESC

							LIMIT $limit OFFSET $offset";

					$query2 = "SELECT DISTINCT n.nid, 
									n.news_title, 
									n.cat_id,
									n.news_date, 
									n.news_image, 
									n.news_description,
									n.video_url,
									n.video_id, 
									n.content_type, 
									
									c.category_name, 
									COUNT(DISTINCT r.comment_id) as comments_count,
									n.view_count

								  FROM tbl_news n 

								  LEFT JOIN tbl_comments r ON n.nid = r.nid AND r.comment_status = '1'
								  LEFT JOIN tbl_category c ON n.cat_id = c.cid 

								  WHERE n.cat_id = c.cid AND (n.news_title LIKE '%$search%' OR n.news_description LIKE '%$search%') 

								  GROUP BY n.nid 
								  ORDER BY n.nid DESC

							LIMIT $limit OFFSET $offset";		

					if ($comment_approval == 'yes') {
						$post = $this->get_list_result($query2);
					} else if ($comment_approval == 'no') {
						$post = $this->get_list_result($query);
					}					

					$count = count($post);
					$respon = array(
						'status' => 'ok', 'count' => $count, 'count_total' => $count_total, 'pages' => $page, 'posts' => $post
					);
					$this->response($this->json($respon), 200);

				} else {
					$respon = array( 'status' => 'failed', 'message' => 'Oops, API Key is Incorrect!');
					$this->response($this->json($respon), 404);
				}
			} else {
				$respon = array( 'status' => 'failed', 'message' => 'Forbidden, API Key is Required!');
				$this->response($this->json($respon), 404);
			}

    }

    public function getSearchResultsRTL() {

    	include "../includes/config.php";
		    $setting_qry    = "SELECT * FROM tbl_settings WHERE id = '1'";
		    $setting_result = mysqli_query($connect, $setting_qry);
		    $settings_row   = mysqli_fetch_assoc($setting_result);
		    $api_key    = $settings_row['api_key'];
		    $comment_approval    = $settings_row['comment_approval'];

			if (isset($_GET['api_key'])) {

				$access_key_received = $_GET['api_key'];

				if ($access_key_received == $api_key) {

					$search = $_GET['search'];

					if($this->get_request_method() != "GET") $this->response('',406);
					$limit = isset($this->_request['count']) ? ((int)$this->_request['count']) : 10;
					$page = isset($this->_request['page']) ? ((int)$this->_request['page']) : 1;

					$offset = ($page * $limit) - $limit;
					$count_total = $this->get_count_result("SELECT COUNT(DISTINCT n.nid) FROM tbl_news n, tbl_category c WHERE n.cat_id = c.cid AND (n.news_title LIKE N'%$search%' OR n.news_description LIKE N'%$search%')");

					$query = "SELECT DISTINCT n.nid, 
									n.news_title, 
									n.cat_id,
									n.news_date, 
									n.news_image, 
									n.news_description,
									n.video_url,
									n.video_id, 
									n.content_type, 
									
									c.category_name, 
									COUNT(DISTINCT r.comment_id) as comments_count,
									n.view_count

								  FROM tbl_news n 

								  LEFT JOIN tbl_comments r ON n.nid = r.nid 
								  LEFT JOIN tbl_category c ON n.cat_id = c.cid 

								  WHERE n.cat_id = c.cid AND (n.news_title LIKE N'%$search%' OR n.news_description LIKE N'%$search%') 

								  GROUP BY n.nid 
								  ORDER BY n.nid DESC

							LIMIT $limit OFFSET $offset";

					$query2 = "SELECT DISTINCT n.nid, 
									n.news_title, 
									n.cat_id,
									n.news_date, 
									n.news_image, 
									n.news_description,
									n.video_url,
									n.video_id, 
									n.content_type, 
									
									c.category_name, 
									COUNT(DISTINCT r.comment_id) as comments_count,
									n.view_count

								  FROM tbl_news n 

								  LEFT JOIN tbl_comments r ON n.nid = r.nid AND r.comment_status = '1'
								  LEFT JOIN tbl_category c ON n.cat_id = c.cid 

								  WHERE n.cat_id = c.cid AND (n.news_title LIKE N'%$search%' OR n.news_description LIKE N'%$search%') 

								  GROUP BY n.nid 
								  ORDER BY n.nid DESC

							LIMIT $limit OFFSET $offset";		

					if ($comment_approval == 'yes') {
						$post = $this->get_list_result($query2);
					} else if ($comment_approval == 'no') {
						$post = $this->get_list_result($query);
					}					

					$count = count($post);
					$respon = array(
						'status' => 'ok', 'count' => $count, 'count_total' => $count_total, 'pages' => $page, 'posts' => $post
					);
					$this->response($this->json($respon), 200);

				} else {
					$respon = array( 'status' => 'failed', 'message' => 'Oops, API Key is Incorrect!');
					$this->response($this->json($respon), 404);
				}
			} else {
				$respon = array( 'status' => 'failed', 'message' => 'Forbidden, API Key is Required!');
				$this->response($this->json($respon), 404);
			}

    }

    public function getComments() {

    	include "../includes/config.php";
		$setting_qry    = "SELECT * FROM tbl_settings where id = '1'";
		$setting_result = mysqli_query($connect, $setting_qry);
		$settings_row   = mysqli_fetch_assoc($setting_result);

		$comment_approval    = $settings_row['comment_approval'];

			$nid = $_GET['nid'];

			if($this->get_request_method() != "GET") $this->response('',406);
			$count_total = $this->get_count_result("SELECT COUNT(DISTINCT comment_id) FROM tbl_comments c, tbl_news n WHERE n.nid = c.nid AND n.nid = '$nid'");
			
			$query = "SELECT 

			c.comment_id,
			c.user_id,
			u.name,
			u.imageName AS 'image',
			c.date_time,
			c.content

			FROM tbl_news n, tbl_comments c, tbl_users u WHERE n.nid = c.nid AND c.user_id = u.id AND n.nid = '$nid' 

			ORDER BY c.comment_id DESC";

			$query2 = "SELECT 

			c.comment_id,
			c.user_id,
			u.name,
			u.imageName AS 'image',
			c.date_time,
			c.content

			FROM tbl_news n, tbl_comments c, tbl_users u WHERE n.nid = c.nid AND c.user_id = u.id AND c.comment_status = '1' AND n.nid = '$nid' 

			ORDER BY c.comment_id DESC";
			
			if ($comment_approval == 'yes') {
				$categories = $this->get_list_result($query2);
			} else if ($comment_approval == 'no') {
				$categories = $this->get_list_result($query);
			}

			$count = count($categories);
			$respon = array(
				'status' => 'ok', 'count' => $count, 'comments' => $categories
			);
			$this->response($this->json($respon), 200);
	}

	public function postComment() {

			if($_SERVER['REQUEST_METHOD'] == 'POST') {

			       $response = array();

			       include "../includes/config.php";

			       //mendapatkan data
			       $nid = $_POST['nid'];
			       $user_id = $_POST['user_id'];
			       $content = mysqli_real_escape_string($connect, $_POST['content']);
			       $date_time = $_POST['date_time'];

			       $sql = "INSERT INTO tbl_comments (nid, user_id, content, date_time) VALUES('$nid', '$user_id', '$content', '$date_time')";
			       if (mysqli_query($connect, $sql)) {
			         $response["value"] = 1;
			         $response["message"] = "success post comment";
			         echo json_encode($response);
			       } else {
			         $response["value"] = 0;
			         $response["message"] = "oops! failed!";
			         echo json_encode($response);
			       }
			     
			     // tutup database
			     mysqli_close($connect);

			  } else {
			    $response["value"] = 0;
			    $response["message"] = "oops! failed!";

			    header( 'Content-Type: application/json; charset=utf-8' );
			    echo json_encode($response);
			  }

	}

	public function updateComment() {

		    include "../includes/config.php";

		    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		        $response = array();

		        //mendapatkan data
		        $comment_id = $_POST['comment_id'];
		        $date_time  = $_POST['date_time'];
		        $content    = mysqli_real_escape_string($connect, $_POST['content']);

		        $sql = "UPDATE tbl_comments SET comment_id = '$comment_id', date_time = '$date_time', content = '$content' WHERE comment_id = '$comment_id'";

		        if(mysqli_query($connect, $sql)) {
		            $response["value"] = 1;
		            $response["message"] = "your comment successfully updated";

		            header( 'Content-Type: application/json; charset=utf-8' );
		            echo json_encode($response);
		        } else {
		            $response["value"] = 0;
		            $response["message"] = "oops! failed update comment!";

		            header( 'Content-Type: application/json; charset=utf-8' );
		            echo json_encode($response);
		        }

		        mysqli_close($connect);

		    }

	}

	public function deleteComment() {

		    include "../includes/config.php";

		    if($_SERVER['REQUEST_METHOD'] == 'POST') {
		      
		        $response = array();
		        //mendapatkan data
		        $comment_id = $_POST['comment_id'];
		        $sql = "DELETE FROM tbl_comments WHERE comment_id = '$comment_id'";

		        if(mysqli_query($connect, $sql)) {
		            $response["value"] = 1;
		            $response["message"] = "Your comment was deleted successfully.";

		            header( 'Content-Type: application/json; charset=utf-8' );
		            echo json_encode($response);
		        } else {
		            $response["value"] = 0;
		            $response["message"] = "oops! Failed to delete comment!";

		            header( 'Content-Type: application/json; charset=utf-8' );
		            echo json_encode($response);
		        }
		        mysqli_close($connect);

		    }

	}

		public function userRegister() {

			include "../includes/config.php";
			include "../public/register.php";

			if(isset($_GET['email'])) {
	
				$qry = "SELECT * FROM tbl_users WHERE email = '".$_GET['email']."'"; 
				$sel = mysqli_query($connect, $qry);
			
				if(mysqli_num_rows($sel) > 0) {
					$set['result'][]=array('msg' => "Email address already used!", 'success'=>'0');
					echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
					die();
				} else {
		 			$data = array(
		 			'user_type'=>'Normal',											 
					'name'  => $_GET['name'],
					'email'  =>  $_GET['email'],
					'password'  =>  $_GET['password'],
					'confirm_code'  =>  '',
					'status'  =>  '1',
					'imageName' => ''
					);

					$qry = Insert('tbl_users', $data);									 
					
					$set['result'][] = array('msg' => "Register succesfully...!", 'success'=>'1');
					echo $val= str_replace('\\/', '/', json_encode($set, JSON_UNESCAPED_UNICODE));
					die();
				}
				
			} else {
				
				 header( 'Content-Type: application/json; charset=utf-8' );
				 $json = json_encode($set);

				 echo $json;
				 exit;		 
			}

		}

		public function registerUser() {

	        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	        
	           	$response = array();
	            
	           	$user_type = 'Normal';
	           	$name = $_POST['name'];
	           	$email = $_POST['email'];
	           	$password = $_POST['password'];
	           	$confirm_code = '';
	           	$status = '1';
	           	$imageName = '';

	           	include "../includes/config.php";

	           	$sql = "SELECT * FROM tbl_users WHERE email = '$email'";
	           	$check = mysqli_fetch_array(mysqli_query($connect, $sql));

	           	if (isset($check)) {
	                $response["value"] = 0;
	                $response["message"] = "oops! Email already registered!";
	                header( 'Content-Type: application/json; charset=utf-8' );
	                echo json_encode($response);
	           	} else {
	                $insert = "INSERT INTO tbl_users (user_type, name, email, password, confirm_code, status, imageName) VALUES('$user_type','$name', '$email', '$password', '$confirm_code', '$status', '$imageName')";
	                if (mysqli_query($connect, $insert)) {
	                    $response["value"] = 1;
	                    $response["message"] = "Register success";
	                    header( 'Content-Type: application/json; charset=utf-8' );
	                    echo json_encode($response);
	                } else {
	                    $response["value"] = 2;
	                    $response["message"] = "Register failed!";
	                    header( 'Content-Type: application/json; charset=utf-8' );
	                    echo json_encode($response);
	                }
	           }
	           mysqli_close($connect);
	        } else {
	          $response["value"] = 0;
	          $response["message"] = "Register failed!";
	          header( 'Content-Type: application/json; charset=utf-8' );
	          echo json_encode($response);
	        }

		}

		public function loginUser() {

			include "../includes/config.php";

			//$response = array("error" => FALSE);

			if (isset($_POST['email']) && isset($_POST['password'])) {
				
				$email = $_POST['email'];
				$password = $_POST['password'];
			        
				$sql = $connect->query("SELECT * FROM tbl_users WHERE email = '$email' AND password = '$password'");

				if (mysqli_num_rows($sql) > 0) {
					while($row = $sql->fetch_array()){
						//$response["error"] = FALSE;
						$response["status"] = "ok";
						$response["message"] = "Login success";
					    $response["user"]["id"] = $row['id'];
					    $response["user"]["user_type"] = $row['user_type'];
					    $response["user"]["name"] = $row['name'];
					    $response["user"]["email"] = $row['email'];
	                    $response["user"]["status"] = $row['status'];
					    $response["user"]["image"] = $row['imageName'];
				    }
				    header( 'Content-Type: application/json; charset=utf-8' );
					echo json_encode($response);
			  	} else {
			  		$response["status"] = "failed";
				    $response["message"] = "Invalid email or password";
				    $response["user"] = null;
				    header( 'Content-Type: application/json; charset=utf-8' );
					echo json_encode($response);
			  	}
			}

		}			

		public function getUser() {

		    include "../includes/config.php";
		    
		    if(isset($_REQUEST['user_id'])) {
		         
		         $id = $_REQUEST['user_id'];
		         
		         $query = " SELECT * FROM tbl_users WHERE id = '$id' ";
		         $result = mysqli_query($connect, $query);
		         
		         while ($row = mysqli_fetch_assoc($result)) {
		              $output[] = $row;
		         }
		         
		         print(json_encode($output));
		         
		         mysqli_close($connect);

		    } else {

		       $output = "not found";
		       print(json_encode($output));
		    
		    }

		}

		public function getUserLogin() {

			include "../includes/config.php";

			$qry = "SELECT * FROM tbl_users WHERE email = '".$_GET['email']."' AND password = '".$_GET['password']."'"; 
			$result = mysqli_query($connect, $qry);
			$num_rows = mysqli_num_rows($result);
			$row = mysqli_fetch_assoc($result);
				
		    if ($num_rows > 0 && $row['status'] == 1) { 		 
				$set['result'][] = array('user_id' => $row['id'], 'name' => $row['name'], 'success' => '1'); 
			} else if ($num_rows > 0 && $row['status'] == 0) {
				$set['result'][] = array('msg' => 'Account disabled', 'success' => '2');
			} else {
				$set['result'][] = array('msg' => 'Login failed', 'success' => '0');
			}
			 
			header( 'Content-Type: application/json; charset=utf-8' );
			$json = json_encode($set);

			echo $json;
			exit;
			
		}

		public function checkEmail() {

	        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	        
	        	$response = array();

	            $email = $_POST['email'];

	        	include "../includes/config.php";
	           
	           	//check whether the user is registered or not
	          	$sql = "SELECT * FROM tbl_users WHERE email = '$email'";
	          	$result = mysqli_query($connect, $sql);
	           	$check = mysqli_fetch_array($result);
	           	//$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

	           	if (isset($check)) {
	                $response["value"] = 1;
	                $response["message"] = $check['password'];
	                header( 'Content-Type: application/json; charset=utf-8' );
	                echo json_encode($response);
	           	} else {
	                $response["value"] = 0;
	                $response["message"] = "email not found!";
	                header( 'Content-Type: application/json; charset=utf-8' );
	                echo json_encode($response);
	           	}
	           	mysqli_close($connect);
	        } else {
	          $response["value"] = 0;
	          $response["message"] = "oops! failed error!";
	          echo json_encode($response);
	        }

	    }

		public function getUserProfile() {

			include "../includes/config.php";

			$id = $_GET['id'];

 	 		$qry = "SELECT * FROM tbl_users WHERE id = '$id' ";
			$result = mysqli_query($connect, $qry);	 
			$row = mysqli_fetch_assoc($result);
			  				 
			$set['result'][] = array(
				'user_id' => $row['id'],
				'name'=>$row['name'],
				'email'=>$row['email'],
				'password'=>$row['password'],
				'image'=>$row['imageName'],
				'success'=>'1'
			);

			header( 'Content-Type: application/json; charset=utf-8' );
			$json = json_encode($set);

			echo $json;
			exit;

		}

		public function updateUserData() {

			include "../includes/config.php";

			$id = $_POST['id'];
			$name = $_POST['name'];
			$email = $_POST['email'];
			$password = $_POST['password'];

			$sql = "UPDATE tbl_users SET id = '$id', name = '$name', email = '$email', password = '$password' WHERE id = '$id'";
			
			if (mysqli_query($connect, $sql)) {
				echo json_encode(array('response' => "Successfully Uploaded..."));
			}
			else {
				echo json_encode(array('response' => "Failed..."));
			}

			mysqli_close($connect);

		}

		public function updatePhotoProfile() {

			include "../includes/config.php";

			$id = $_POST['id'];
			$name = $_POST['name'];
			$email = $_POST['email'];
			$password = $_POST['password'];

			$old_image = $_POST['old_image'];
			$new_image = $_POST['image'];
			
			$time = time();
			$image_name = $name.'_'.$time.".jpg";
			$image_name = str_replace(' ', '_', $image_name);
			
			$path = "../upload/avatar/$image_name";

			$sql = "UPDATE tbl_users SET id = '$id', name = '$name', email = '$email', password = '$password', imageName = '$image_name' WHERE id = '$id'";
			
			if (mysqli_query($connect, $sql)) {
				file_put_contents($path, base64_decode($new_image));
				if ($old_image != "") {
            	 	$delete = unlink("../upload/avatar/".$old_image);
        		}
				echo json_encode(array('response' => "Successfully Uploaded..."));
			}
			else {
				echo json_encode(array('response' => "Failed..."));
			}

			mysqli_close($connect);

		}

		//deprecated
		public function updateUserProfileLegacy() {

			include "../includes/config.php";
			include "../public/register.php";
	
		 	if($_GET['password']!="") {
				$data = array(
				'name'  =>  $_GET['name'],
				'email'  =>  $_GET['email'],
				'password'  =>  $_GET['password']
				);
			} else {
				$data = array(
				'name'  =>  $_GET['name'],
				'email'  =>  $_GET['email']
				);
			}
				
			$user_edit = Update('tbl_users', $data, "WHERE id = '".$_GET['user_id']."'");
		 	$set['result'][] = array('msg'=>'Updated', 'success'=>'1');
					 
			header( 'Content-Type: application/json; charset=utf-8' );
			$json = json_encode($set);

			echo $json;
			exit;

		}

		//deprecated
		public function updateUserPhotoLegacy() {

		    include "../includes/config.php";

		    // check if "image" abd "user_id" is set 
		    if(isset($_POST["image"]) && isset($_POST["user_id"])) {

		        $data = $_POST["image"];
		        $time = time();

		        $user_id = $_POST["user_id"];
		        //$oldImage ="images/"."1_1497679518.jpg";
		        $ImageName = $user_id.'_'.$time.".jpg";

		        //$filePath = "images/".$ImageName;
		        $filePath = '../upload/avatar/'.$ImageName; // path of the file to store
		        echo "file : ".$filePath;
		        //echo "unlink : ".$oldImage;

		        // check if file exits
		        if (file_exists($filePath)) {
		            unlink($filePath); // delete the old file
		        } 
		        // create a new empty file
		        $myfile = fopen($filePath, "w") or die("Unable to open file!");
		        // add data to that file
		        file_put_contents($filePath, base64_decode($data));

		        // update the Customer table with new image name.
		        $query = " UPDATE tbl_users SET imageName = '$ImageName' WHERE id = '$user_id' ";
		        mysqli_query($connect, $query);

		        
		    } else {
		        echo 'not set';
		    }
		    
		    mysqli_close($connect);

		}

	public function forgotPassword() {

		include "../includes/config.php";
		    
		$qry = "SELECT * FROM tbl_users WHERE email = '".$_GET['email']."'"; 
		$result = mysqli_query($connect, $qry);
		$row = mysqli_fetch_assoc($result);
		
		if ($row['email'] != "") {

			include_once ('../includes/php-mail.php');

			$set['result'][]=array('msg' => "Password has been sent on your mail!",'success'=>'1');

		} else {

			$set['result'][]=array('msg' => "Email not found in our database!",'success'=>'0');		
		}

	 	header( 'Content-Type: application/json; charset=utf-8');
	    $json = json_encode($set);
					
		echo $json;
		exit;

	}

	public function getAds() {

    	include "../includes/config.php";
        $setting_qry    = "SELECT * FROM tbl_settings WHERE id = '1'";
		$setting_result = mysqli_query($connect, $setting_qry);
		$settings_row   = mysqli_fetch_assoc($setting_result);
		$api_key    = $settings_row['api_key'];

			if (isset($_GET['api_key'])) {

				$access_key_received = $_GET['api_key'];

				if ($access_key_received == $api_key) {

					if($this->get_request_method() != "GET") $this->response('',406);
							
					$query = "SELECT a.*, s.youtube_api_key, s.fcm_notification_topic, s.onesignal_app_id, s.more_apps_url FROM tbl_ads a, tbl_settings s WHERE a.id = 1 AND s.id = 1";

					$result = $this->get_one($query);
					$respon = array(
						'status' => 'ok', 'ads' => $result
					);
					$this->response($this->json($respon), 200);	

				} else {
					$respon = array( 'status' => 'failed', 'message' => 'Oops, API Key is Incorrect!');
					$this->response($this->json($respon), 404);
				}
			} else {
				$respon = array( 'status' => 'failed', 'message' => 'Forbidden, API Key is Required!');
				$this->response($this->json($respon), 404);
			}

    }

	public function getSettings() {

		include "../includes/config.php";

		if ($this->get_request_method() != "GET") $this->response('',406);

		$query = "SELECT * FROM tbl_settings WHERE id = 1";
		$result = $this->get_one($query);
	
		$respon = array(
			'status' => 'ok', 'post' => $result
		);
		$this->response($this->json($respon), 200);

	}

	public function getNewSettings() {

    	include "../includes/config.php";
        $setting_qry    = "SELECT * FROM tbl_settings WHERE id = '1'";
		$setting_result = mysqli_query($connect, $setting_qry);
		$settings_row   = mysqli_fetch_assoc($setting_result);
		$api_key    = $settings_row['api_key'];

			if (isset($_GET['api_key'])) {

				$access_key_received = $_GET['api_key'];
				$package_name = $_GET['package_name'];

				if ($access_key_received == $api_key) {

					if($this->get_request_method() != "GET") $this->response('',406);
							
					$sqlAds = "SELECT * FROM tbl_ads WHERE id = 1";
					$sqlSettings = "SELECT * FROM tbl_settings WHERE id = 1";
					$sqlLicense = "SELECT item_id, item_name, license_type FROM tbl_license LIMIT 1";
					$sqlApp = "SELECT package_name, status, redirect_url FROM tbl_app_config WHERE package_name = '$package_name' LIMIT 1 ";

					$ads = $this->get_one($sqlAds);
					$settings = $this->get_one($sqlSettings);
					$license = $this->get_one($sqlLicense);

					//$appCount = $this->get_list_result($sqlApp);
					$appCount = count($this->get_list_result($sqlApp));
					$app = $this->get_one($sqlApp);

					$AppArray = array( 'package_name' => '', 'status' => '', 'redirect_url' => '');

					if ($appCount > 0) {
						$respon = array('status' => 'ok', 'app' => $app, 'settings' => $settings, 'ads' => $ads, 'license' => $license);
					} else {
						$respon = array('status' => 'ok', 'app' => $AppArray, 'settings' => $settings, 'ads' => $ads, 'license' => $license);
					}

					$this->response($this->json($respon), 200);	

				} else {
					$respon = array( 'status' => 'failed', 'message' => 'Oops, API Key is Incorrect!');
					$this->response($this->json($respon), 404);
				}
			} else {
				$respon = array( 'status' => 'failed', 'message' => 'Forbidden, API Key is Required!');
				$this->response($this->json($respon), 404);
			}

    }	

	public function getUserData() {

		include "../includes/config.php";

		$id = $_GET['id'];

		if ($this->get_request_method() != "GET") $this->response('',406);

		$query = "SELECT id, name, email, password, status, imageName AS image FROM tbl_users WHERE id = '$id' ";
		$result = $this->get_one($query);
	
		$respon = array(
			'status' => 'ok', 'response' => $result
		);
		$this->response($this->json($respon), 200);

	}

	public function getPrivacyPolicy() {

		include "../includes/config.php";
		
		$sql = "SELECT * FROM tbl_settings WHERE id = 1";
		$result = mysqli_query($connect, $sql);

		header( 'Content-Type: application/json; charset=utf-8' );
		print json_encode(mysqli_fetch_assoc($result));

	}

	public function getUserToken() {

	    $user_unique_id = $_GET['user_unique_id'];

		if($this->get_request_method() != "GET") $this->response('', 406);

		$query_post = "SELECT * FROM tbl_fcm_token WHERE user_unique_id = $user_unique_id ";

		$post = $this->get_one_result($query_post);
		$count = count($post);
		$respon = array(
			'status' => 'ok', 'response' => $post
		);
		$this->response($this->json($respon), 200);
	}

	public function getPackageName() {

		include "../includes/config.php";
			
		$sql = "SELECT package_name FROM tbl_settings WHERE id = 1";
		$result = mysqli_query($connect, $sql);

		header( 'Content-Type: application/json; charset=utf-8' );
		print json_encode(mysqli_fetch_assoc($result));

	}


	public function updateView() {

		include "../includes/config.php";

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			$response = array();

			$nid = $_POST['nid'];

			$sql = "UPDATE tbl_news SET view_count = view_count + 1 WHERE nid = '$nid'";

			if(mysqli_query($connect, $sql)) {
				$response["value"] = 1;
				$response["message"] = "success update views";

				header( 'Content-Type: application/json; charset=utf-8' );
				echo json_encode($response);
			} else {
				$response["value"] = 0;
				$response["message"] = "failed update vieww!";

				header( 'Content-Type: application/json; charset=utf-8' );
				echo json_encode($response);
			}

			mysqli_close($connect);

		}

	}

    public function get_list_result($query) {
		$result = array();
		$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		if($r->num_rows > 0) {
			while($row = $r->fetch_assoc()) {
				$result[] = $row;
			}
		}
		return $result;
	}

    public function get_count_result($query) {
		$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		if($r->num_rows > 0) {
			$result = $r->fetch_row();
			return $result[0];
		}
		return 0;
	}

	private function get_category_result($query) {
		$result = array();
		$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		if($r->num_rows > 0) {
			while($row = $r->fetch_assoc()) {
				$result = $row;
			}
		}
		return $result;
	}

	private function get_one_result($query) {
		$result = array();
		$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		if($r->num_rows > 0) $result = $r->fetch_assoc();
			return $result;
	}	

	private function get_one($query) {
		$result = array();
		$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
		if($r->num_rows > 0) $result = $r->fetch_assoc();
		return $result;
	}
    
}

?>