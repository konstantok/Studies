<?php

/**
 *
 * Department of Computer Engineering & Informatics 
 * University of Patras 
 *
 * Diploma Thesis: Aimed Product Suggestion to Social Network Users 
 * Konstantinos Konstantopoulos 
 * kkonstanto@ceid.upatras.gr 
 * 
 * Copyright (c) 2013 
 *
 */



	ini_set('max_execution_time', 300); //300 seconds = 5 minutes
	ini_set('display_errors', '1');

	
	//Require files

	// This provides access to helper functions defined in 'utils.php'
	require_once('utils.php');
	
	//Database funtions 
	require_once('model.php'); 
	
	// Provides access to app specific values such as your app id and app secret defined in 'AppInfo.php' 
	require_once('AppInfo.php');
	

	//Connect to database 
	$con = connect2db();

	/*
	// Enforce https on production
	if (substr(AppInfo::getUrl(), 0, 8) != 'https://' && $_SERVER['REMOTE_ADDR'] != '127.0.0.1') {
		header('Location: https://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
		exit();
	}
	*/

	// This provides access to main functions provided by the Facebook PHP Sdk
	require_once('sdk/src/facebook.php');

	//$users = array();
	
	$facebook = new Facebook(array(
		'appId'  => AppInfo::appID(),
		'secret' => AppInfo::appSecret(),
		'sharedSession' => true,
		'trustForwarded' => true,
	));

	
	// Get user's Facebook id
	$user_id = $facebook->getUser();
	
	//$users[] = $user_id; 
	//$names[] = 'app_user'; 
	
	if ($user_id) {
	
		try {
			// Fetch the viewer's basic information
			$basic = $facebook->api('/me');
		} catch (FacebookApiException $e) {
			// If the call fails we check if we still have a user. The user will be
			// cleared if the error is because of an invalid accesstoken
			if (!$facebook->getUser()) {
				header('Location: '. AppInfo::getUrl($_SERVER['REQUEST_URI']));
				exit();
			}
		}
		
		$accesstoken = $facebook->getAccessToken();
		$long_lived_access_token = get_long_lived_access_token($accesstoken);
		
		// Insert user in Database
		insert_appuser($con, $user_id, $long_lived_access_token);
		//insert_appuser($con, $user_id, $accesstoken);
		
		// Get user's friends
		$friends = idx($facebook->api('/me/friends'), 'data', array());
		
		foreach ($friends as $friend) {
			$id = idx($friend, 'id');
			//$name = idx($friend, 'name'); 
			
			insert_friend($con, $user_id, $id);
			
			//$users[] = $id; 
		}
		
	}
	
	// Fetch the basic info of the app that they are using
	$app_info = $facebook->api('/'. AppInfo::appID());

	$app_name = idx($app_info, 'name', '');

?>
<!doctype html>
<html xmlns:fb="http://ogp.me/ns/fb#" lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0, user-scalable=yes" />

	<title><?php echo he($app_name); ?></title>
	<link rel="stylesheet" href="stylesheets/base.css" media="Screen" type="text/css" />
	
	<meta property="og:title" content="<?php echo he($app_name); ?>" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="<?php echo AppInfo::getUrl(); ?>" />
	<meta property="og:image" content="<?php echo AppInfo::getUrl('/logo.png'); ?>" />
	<meta property="og:site_name" content="<?php echo he($app_name); ?>" />
	<meta property="fb:app_id" content="<?php echo AppInfo::appID(); ?>" />
	
	<script type="text/javascript" src="javascript/jquery-1.7.1.min.js"></script>

	<script type="text/javascript">
	  function logResponse(response) {
		if (console && console.log) {
		  console.log('The response was', response);
		}
	  }

	  $(function(){
		// Set up so we handle click on the buttons
		$('#postToWall').click(function() {
		  FB.ui(
			{
			  method : 'feed',
			  link   : $(this).attr('data-url')
			},
			function (response) {
			  // If response is null the user canceled the dialog
			  if (response != null) {
				logResponse(response);
			  }
			}
		  );
		});

		$('#sendRequest').click(function() {
		  FB.ui(
			{
			  method  : 'apprequests',
			  message : $(this).attr('data-message')
			},
			function (response) {
			  // If response is null the user canceled the dialog
			  if (response != null) {
				logResponse(response);
			  }
			}
		  );
		});
	  });
	</script>

	<!--[if IE]>
	<script type="text/javascript">
		var tags = ['header', 'section'];
		while(tags.length)
			document.createElement(tags.pop());
	</script>
	<![endif]-->
	
</head>
<body>

    <div id="fb-root"></div>	
	<script type="text/javascript">
		window.fbAsyncInit = function() {
			FB.init({
				appId      : '<?php echo AppInfo::appID(); ?>', // App ID
				channelUrl : '//<?php echo $_SERVER["HTTP_HOST"]; ?>/channel.html', // Channel File
				status     : true, // check login status
				cookie     : true, // enable cookies to allow the server to access the session
				xfbml      : true // parse XFBML
			});

			// Listen to the auth.login which will be called when the user logs in
			// using the Login button
			FB.Event.subscribe('auth.login', function(response) {
				// We want to reload the page now so PHP can read the cookie that the
				// Javascript SDK sat. But we don't want to use
				// window.location.reload() because if this is in a canvas there was a
				// post made to this page and a reload will trigger a message to the
				// user asking if they want to send data again.
			window.location = window.location;
			});

			FB.Canvas.setAutoGrow();
		};

		// Load the SDK Asynchronously
		(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/en_US/all.js";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	</script>
	
	
    <header class="clearfix">
		<?php if (isset($basic)) { ?>
			<div id="picture" style="background-image: url(https://graph.facebook.com/<?php echo he($user_id); ?>/picture?type=normal)"></div>
		 
			<div>
				<h1>Welcome <?php echo he(idx($basic, 'first_name')); ?></h1>   
				
				<div id="share-app">
					<p>Spread the word:</p>
					<ul>
						<li>
							<a href="#" class="facebook-button" id="postToWall" data-url="<?php echo AppInfo::getUrl(); ?>">
							<span class="plus">Post to Wall</span>
							</a>
						</li>
						<li>
							<a href="#" class="facebook-button apprequests" id="sendRequest" data-message="Test this awesome app">
							<span class="apprequests">Send Requests</span>
							</a>
						</li>
					</ul>
				</div>
			</div>
			
		<?php } else { ?>
		
			<div>
				<h1>Welcome</h1>
				<div class="fb-login-button" data-scope="user_events,user_checkins,friends_events,friends_checkins"></div>
			</div>
		<?php } ?>
    </header>
	
   
   
	<?php
		if ($user_id) {
	?>
	
		<section id="thanks" class="clearfix">
			<h2>Thank you for participating in our research</h2>
			You have just used an app developed for a thesis. The app implements a research on <a href="http://en.wikipedia.org/wiki/Recommender_system">Recomeneder Systems</a> and explores new ways to suggest events you might be interested in. <!--Please take a moment and tell us what you think about the suggestions given below. -->
			<br /><br />The data you provided using this app is used for academic purposes only and remain anonymous. 
		</section>
		
	<?php
		}
    ?>
	
	
	<!--
	
	!@# 	!@# 	!@# 	!@# 	!@# 	!@# 	!@# 	!@# 	
	
	U N C O M M E N T 
	
	!@# 	!@# 	!@# 	!@# 	!@# 	!@# 	!@# 	!@# 	
	
	
    <section id="opinion" class="clearfix">
	
	edw tha mpei to opinion mining system
    
	</section> 
	-->

	<footer class="clearfix">
		<a href="http://www.upatras.gr/index/index/lang/en" target="_blank">University of Patras</a> 
		<a href="http://www.ceid.upatras.gr/en/" target="_blank">Computer Engineering and Informatics Department</a> <br />
		Diploma Thesis <i>Aimed Product Suggestion to Social Network Users</i> - Evnt Fndr v0.3 <br />
		&copy; <a href="mailto:kkonstanto@ceid.upatras.gr">Konstantinos Konstantopoulos</a>  
	</footer>
	
	
	
	<script>
	
		$.get("get_events.php", {appuser: "<?php echo $user_id; ?>"}, function() {
			//alert("success");
			});
		
		$.get("get_checkins.php", {appuser: "<?php echo $user_id; ?>"}, function() {
			//alert("success");
			});	
		
	</script>
	
	
	
	
	</body>
	
</html>
