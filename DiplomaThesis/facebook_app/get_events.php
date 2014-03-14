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



	ini_set('max_execution_time', 3600); //300 seconds = 5 minutes
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

	//Count facebook request - max 600 reqs per 600 secs - Debug only
	//$fb_requests = 0;
 	

	// This provides access to main functions provided by the Facebook PHP SDK
	require_once('sdk/src/facebook.php');
	
	$facebook = new Facebook(array(
		'appId'  => AppInfo::appID(),
		'secret' => AppInfo::appSecret(),
	));
	
	if ( isset($_GET['step']) ) 	$step = intval($_GET['step']); 
	else $step = 1; 
	
	
	if( isset($_GET['appuser']) ){
	
		$appuser = intval( $_GET['appuser'] );
		$appusers_raw = select_appuser_by_id( $con, $appuser ); 	
		//var_dump($appusers_raw); 	
	}
	else{
		$appusers_raw = select_appuser( $con, 0, 100 ); 	
	}
	
	if ($appusers_raw){
		while ( $appuser = mysqli_fetch_object($appusers_raw)){
			//echo $appuser->appuserid;echo '<br />';
			$appusers[$appuser->appuserid] = $appuser->accesstoken;
		}		
		free_result($appusers_raw);
	}
	else{
		//echo 'no appusers found';
		exit; 
	}

	
	$fb_requests = 0; 	//debug only
	
	foreach ($appusers as $appuser=>$accesstoken){
		//echo 'appuser: ' . $appuser . '<br />'; 
		
		$facebook->setAccessToken($accesstoken);
		//echo 'access token: ' . $accesstoken . '<br />';
		
		
		$users = array();
		$users[] = $appuser;
		
		$appuser_friends_raw = select_friend( $con, $appuser, 0, 5000 );
		if ($appuser_friends_raw){ 
			while ( $friend = mysqli_fetch_object($appuser_friends_raw)){
				//echo $friend->friendid;echo '<br />';
				$users[] = $friend->friendid;
			}
			free_result($appuser_friends_raw);
		}
		else{
			//echo 'no friends found';
			continue;
		}
		//print_r($users);
		
		
		
		$users_limit = 100;		//100
		$total_steps = (int) ( (count($users) / $users_limit) + 1 ) ; 
		
		//echo '<br />----------------------------------------------------------<br />step: '.$step;

		$sub_users = array_slice($users, ( $step - 1 ) * $users_limit , $users_limit ); 
		
		foreach( $sub_users as $user ){
			//echo 'user: ' . $user . '<br />'; 
			
			
			// data for database - +1 hour from now attending, declined only
			//echo 'events';echo '<br />';echo '<br />';
			
			$events_type_resp = array();
			$events_type_resp = array( /*'created',*/ '1' => 'attending', '2' => 'declined' /*, '3' => 'not_replied'/*, '4' => 'maybe'*/);
			foreach ($events_type_resp as $resp_id => $event_resp){

				//echo $event_resp . '<br />';

				try{
					
					$events_raw = array();
					//$fb_requests++;
					$events_raw = $facebook->api('/' . $user . '?fields=events.type(' . $event_resp . ').since('. strtotime('-2 month') .').limit(100)'); //+1 hour
					
					if ( array_key_exists('events', $events_raw) ){
			
						$events = array(); 
						$events = $events_raw['events']['data'];					
						
						foreach ($events as $event){ 
						
							$event_name = $event['name'];
							$event_id = $event['id'];
							$event_start_time = $event['start_time'];
							
							//echo 'Event Name: ' . $event_name; 
							//echo ' (ID: ' . $event_id . ' ) ';
							//echo ' Start time: ' . $event_start_time . ' ( or ' . strtotime($event_start_time) . ' ) <br />' ; 
							
							insert_event( $con, $user, $event_id, $resp_id );
						
							//echo '<br />';echo '<br />';
						}

					}
					
				} catch (FacebookApiException $e) {
					
					error_log($e);
					//echo 'problem with facebook API';
					//echo 'total requests: '.$fb_requests;
				}
				
			}
			
			//echo '---------------- user end ----------------<br /><br />';
						
		}
		
		//echo '---------------- appuser end ----------------<br /><br />';
	
		
		if ( $step < $total_steps ) 	header('Location: get_events.php?appuser='.$appuser.'&step='.($step+1));
		//if ( $step < $total_steps ) 	echo '<br /><a href="get_events.php?appuser='.$appuser.'&step='.($step+1).'">epomenh</a>';
	
	}
	
	//echo 'total requests: '.$fb_requests;
	
	
?>
