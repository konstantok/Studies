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



	//Connect to Database basic functions 
	//Make new connection 
	function connect2db() {
	
		$con = mysqli_connect( getenv('DATABASE_HOST') , getenv('DATABSE_USER') , getenv('DATABASE_PASSWORD') , getenv('DATABSE_NAME') ) or die('Problem connecting: '.mysqli_error());
		return $con; 
	}
	
	//Close connection
	function close_connection( $con ) {
		
		mysqli_close($con);
		return; 
	}
	
	//Free result
	function free_query_result ($q){
		
		mysqli_free_result($q); 
	}

	
	
	//Table appusers 
	//select appuser(s)
	function select_appuser_query( $con, $limit, $offset ){
		
		$q = mysqli_query( $con, 'SELECT * FROM `appusers` LIMIT '.$limit.', '.$offset );
		return $q;
	}
	
	function select_appuser_by_id_query( $con, $appuser ){
		
		$q = mysqli_query( $con, 'SELECT * FROM `appusers` WHERE appuserid = ' . $appuser . ' LIMIT 0, 1' );
		return $q;
	}
	
	//insert new appuser
	function insert_appuser_query( $con, $user_id, $accesstoken ){

		$q = mysqli_query( $con, 'INSERT INTO `appusers` VALUES ( NULL, '.$user_id.',  "'.$accesstoken.'" ) ON DUPLICATE KEY UPDATE appuserid = appuserid , accesstoken = "' . $accesstoken .'"'  );
		return $q;
	}


	
	//Table users
	//select user's friend
	function select_friend_query( $con, $user_id, $limit, $offset ){
	
		$q = mysqli_query( $con, 'SELECT * FROM `users` WHERE appuserid = ' . $user_id . ' LIMIT '.$limit.', '.$offset );
		return $q;
	}
	
	//insert user's friend
	function insert_friend_query( $con, $user_id, $id ){
	
		$q = mysqli_query( $con, 'INSERT INTO `users` VALUES ( NULL, '.$user_id.', '.$id .') ON DUPLICATE KEY UPDATE appuserid = appuserid ' );
		return $q;
	}



	//Table events
	//select event(s)
	function select_event_query( $con, $limit, $offset ){
		
		$q = mysqli_query( $con, 'SELECT * FROM `events` LIMIT '.$limit.', '.$offset );
		return $q;
	}
	
	//insert new event
	function insert_event_query( $con, $user_id, $event_id, $user_response ){
	
		$q = mysqli_query( $con, 'INSERT INTO `events` VALUES ( NULL , '.$user_id.', ' . $event_id . ', ' . $user_response . ' ) ON DUPLICATE KEY UPDATE userresponse = ' . $user_response  ); 
		return $q;
	}

	//update existing event
	function update_event_query( $con, $user_id, $event_id, $user_response ){
	
		//$q = pg_query( $con, 'UPDATE events SET user_response = ' . $user_response . ' WHERE user_id = '.$user_id.' AND event_id = ' . $event_id );
		//return $q;
	}
	
	
	
	//Table checkins
	//select checkin(s)
	function select_checkin_query( $con, $limit, $offset ){
		
		$q = pg_query( $con, 'SELECT * FROM `checkins` LIMIT '.$limit.', '.$offset );
		return $q;
	}
	
	//insert new event
	function insert_checkin_query( $con, $user_id, $place_id, $user_checkin, $created_time ){
	
		$q = mysqli_query( $con, 'INSERT INTO `checkins` VALUES ( NULL, '.$user_id.', ' . $place_id . ', ' . $user_checkin . ', ' . $created_time . ' ) ON DUPLICATE KEY UPDATE usercheckin = usercheckin'); 
		return $q;
	}

	//update existing event
	function update_checkin_query( $con, $user_id, $checkin_id, $user_checkin, $created_time ){
	
		//$q = pg_query( $con, 'UPDATE checkins SET user_response = ' . $user_checkin . ' WHERE user_id = '.$user_id.' AND place_id = ' . $checkin_id . ' AND created_time = ' . $created_time );
		//return $q;
	}

	
	// Select data for output file
	function select_all_events_output( $con, $limit, $offset){
	
		$q = mysqli_query( $con, 'SELECT userid, eventid, userresponse FROM `events` ORDER BY userid, eventid LIMIT ' . $limit . ' , ' . $offset ); 
		return $q;
	}
	
	
	function select_all_checkins_output( $con, $limit, $offset){
	
		$q = mysqli_query( $con, 'SELECT userid, placeid, usercheckin FROM `checkins` ORDER BY userid, placeid LIMIT ' . $limit . ' , ' . $offset ); 
		return $q;
	}
	// end ---
	
	
?>