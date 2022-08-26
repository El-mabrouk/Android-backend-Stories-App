<?php

	require_once("Rest.inc.php");
	require_once("db.php");
	require_once("functions.php");

	error_reporting(0);

	class API extends REST {

		private $functions = NULL;
		private $db = NULL;

		public function __construct() {
			$this->db = new DB();
			$this->functions = new functions($this->db);
		}

		public function check_connection() {
			$this->functions->checkConnection();
		}

		/*
		 * ALL API Related android client -------------------------------------------------------------------------
		*/

	    public function get_post_detail() {
	        $this->functions->getNewsDetail();
	    }

	    public function get_news_detail() {
	        $this->functions->getPostDetail();
	    }
		
		public function get_category_index() {
	        $this->functions->getCategoryIndex();
	    }

		public function get_recent_posts() {
			$this->functions->getRecentPosts();
		}

		public function get_video_posts() {
			$this->functions->getVideoPosts();
		}

		public function get_category_posts() {
	        $this->functions->getCategoryPosts();
	    }

	    public function get_search_results() {
	        $this->functions->getSearchResults();
	    }

	    public function get_search_results_rtl() {
	        $this->functions->getSearchResultsRTL();
	    }

	    public function user_register() {
	        $this->functions->userRegister();
	    }

	    public function get_user_data() {
	        $this->functions->getUserData();
	    }

	    public function get_user() {
	        $this->functions->getUser();
	    }

	    public function get_user_login() {
	        $this->functions->getUserLogin();
	    }

	    public function get_user_profile() {
	        $this->functions->getUserProfile();
	    }

	    public function update_user_data() {
	        $this->functions->updateUserData();
	    }

	    public function update_photo_profile() {
	        $this->functions->updatePhotoProfile();
	    }

	    //deprecated
	    public function update_user_profile() {
	        $this->functions->updateUserProfileLegacy();
	    }

	    //deprecated
	    public function update_user_photo() {
	        $this->functions->updateUserPhotoLegacy();
	    }

	    public function get_comments() {
	        $this->functions->getComments();
	    }

	    public function post_comment() {
	        $this->functions->postComment();
	    } 

	    public function update_comment() {
	        $this->functions->updateComment();
	    }

	    public function delete_comment() {
	        $this->functions->deleteComment();
	    }

	    public function forgot_password() {
	        $this->functions->forgotPassword();
	    }

	    public function get_package_name() {
	        $this->functions->getPackageName();
	    }

	    public function get_user_token() {
	        $this->functions->getUserToken();
	    }

	    public function get_privacy_policy() {
	        $this->functions->getPrivacyPolicy();
	    }

	    public function get_settings() {
	        $this->functions->getSettings();
	    }

	    public function settings() {
	        $this->functions->getNewSettings();
	    }

	    public function get_ads() {
	        $this->functions->getAds();
	    }

	    public function update_view() {
	        $this->functions->updateView();
	    }

	    public function check_email() {
	        $this->functions->checkEmail();
	    }

	    //version 4.3.2
	    public function register_user() {
	        $this->functions->registerUser();
	    }

	    public function login_user() {
	        $this->functions->loginUser();
	    }

	    public function get_config() {
	        $this->functions->getConfig();
	    }

		/*
		 * End of API Transactions ----------------------------------------------------------------------------------
		*/

		public function processApi() {
			if(isset($_REQUEST['x']) && $_REQUEST['x']!=""){
				$func = strtolower(trim(str_replace("/","", $_REQUEST['x'])));
				if((int)method_exists($this,$func) > 0) {
					$this->$func();
				} else {
					echo 'processApi - method not exist';
					exit;
				}
			} else {
				echo 'processApi - method not exist';
				exit;
			}
		}

	}

	// Initiiate Library
	$api = new API;

	if (isset($_GET['check_connection'])) {
		$api->check_connection();
	} else if (isset($_GET['get_post_detail'])) {
		$api->get_post_detail();
	} else if (isset($_GET['get_news_detail'])) {
		$api->get_news_detail();
	} else if (isset($_GET['get_category_index'])) {
		$api->get_category_index();
	} else if (isset($_GET['get_recent_posts'])) {
		$api->get_recent_posts();
	} else if (isset($_GET['get_video_posts'])) {
		$api->get_video_posts();
	} else if (isset($_GET['get_category_posts'])) {
		$api->get_category_posts();
	} else if (isset($_GET['get_search_results'])) {
		$api->get_search_results();
	} else if (isset($_GET['get_search_results_rtl'])) {
		$api->get_search_results_rtl();
	} else if (isset($_GET['user_register'])) {
		$api->user_register();
	} else if (isset($_GET['get_user_data'])) {
		$api->get_user_data();
	} else if (isset($_GET['get_user'])) {
		$api->get_user();
	} else if (isset($_GET['get_user_login'])) {
		$api->get_user_login();
	} else if (isset($_GET['get_user_profile'])) {
		$api->get_user_profile();
	} else if (isset($_GET['update_user_data'])) {
		$api->update_user_data();
	} else if (isset($_GET['update_photo_profile'])) {
		$api->update_photo_profile();
	} else if (isset($_GET['update_user_profile'])) {
		$api->update_user_profile();
	} else if (isset($_GET['update_user_photo'])) {
		$api->update_user_photo();
	} else if (isset($_GET['get_comments'])) {
		$api->get_comments();
	} else if (isset($_GET['post_comment'])) {
		$api->post_comment();
	} else if (isset($_GET['update_comment'])) {
		$api->update_comment();
	} else if (isset($_GET['delete_comment'])) {
		$api->delete_comment();
	} else if (isset($_GET['forgot_password'])) {
		$api->forgot_password();
	} else if (isset($_GET['get_package_name'])) {
		$api->get_package_name();
	} else if (isset($_GET['get_user_token'])) {
		$api->get_user_token();
	} else if (isset($_GET['get_privacy_policy'])) {
		$api->get_privacy_policy();
	} else if (isset($_GET['get_settings'])) {
		$api->get_settings();
	} else if (isset($_GET['settings'])) {
		$api->settings();
	} else if (isset($_GET['get_ads'])) {
		$api->get_ads();
	} else if (isset($_GET['update_view'])) {
		$api->update_view();
	} else if (isset($_GET['check_email'])) {
		$api->check_email();
	} else if (isset($_GET['register_user'])) {
		$api->register_user();
	} else if (isset($_GET['login_user'])) {
		$api->login_user();
	} else {
		$api->processApi();
	}

?>
