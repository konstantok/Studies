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



	function he($str) {
		return htmlentities($str, ENT_QUOTES, "UTF-8");
	}

	
	function idx(array $array, $key, $default = null) {
		return array_key_exists($key, $array) ? $array[$key] : $default;
	}
	
	
	function idxx(array $array, $key1, $key2, $default = null) {
		return array_key_exists($key1, $array) ? $array[$key1][$key2] : $default;
	}


	// Help function for events
	function insert_event( $con, $user_id, $event_id, $user_response ) { 
	
		$res = insert_event_query( $con, $user_id, $event_id, $user_response ); 
		free_result($res);
		//return $res; 	
	}
	// end --- 

	
	// Help function for checkins
	function insert_checkin( $con, $user_id, $place_id, $user_checkin, $created_time ) { 
	
		$res = insert_checkin_query( $con, $user_id, $place_id, $user_checkin, $created_time ); 
		free_result($res);
		//return $res; 	
	}
	// end --- 

	
	// Help function for appuser
	function select_appuser( $con, $limit, $offset ) { 
	
		$res = select_appuser_query( $con, $limit, $offset ); 
		return $res;
		/*
		$result = $res;
		free_result($res);
		return $result;
		*/
	}

	
	function select_appuser_by_id( $con, $appuser ){
	
		$res = select_appuser_by_id_query( $con, $appuser ); 
		return $res;
		/*
		$result = $res;
		free_result($res);
		return $result;
		*/
	}
	
	
	function insert_appuser($con, $user_id, $accesstoken) { 
	
		$res = insert_appuser_query($con, $user_id, $accesstoken);
		free_result($res);	
	}
	// end --- 

	
	// Help function for appuser's friends
	function insert_friend( $con, $user_id, $id ){

		$res = insert_friend_query( $con, $user_id, $id ); 
		free_result($res);
		//return $res; 	
	}
	
	
	function select_friend( $con, $user_id, $limit, $offset ){

		$res = select_friend_query( $con, $user_id, $limit, $offset ); 
		return $res;
		/*
		$result = $res;
		free_result($res);
		return $result;
		*/
	}
	// end --- 
	
	
	// Get Long Lived Access Token for the appuser
	function get_long_lived_access_token( $accesstoken ) {
	
		$extend_url = 'https://graph.facebook.com/oauth/access_token?grant_type=fb_exchange_token&client_id=' . AppInfo::appID() . '&client_secret=' . AppInfo::appSecret() . '&fb_exchange_token=' . $accesstoken;

		$response = file_get_contents( $extend_url ); 
		parse_str( $response, $parsed_resp );

		$new_access_token = $parsed_resp['access_token'];

		return $new_access_token; 
	}
	// end ---
	
	
	// Preparing output files
	function get_all_data_output( $con, $table, $offset ){
	
		if ( $table == 'events' )
			$res = select_all_events_output( $con, 0, $offset ); 
		else if ( $table == 'checkins' )
			$res = select_all_checkins_output( $con, 0, $offset ); 
		
		return $res;
		/*
		$result = $res;
		free_result($res);
		return $result;
		*/
	}
	
	
	function print_elementid ( $data_table, $row ){
		
		if ( $data_table == 'events' ) 			echo $row->eventid;
		else if ( $data_table == 'checkins' ) 	echo $row->placeid;
		
		echo ',';
	}

	
	function print_userchoise ( $data_table, $row, $values ){
		
		if ( $data_table == 'events' ) 	{
		
			if ( $row->userresponse == 1 ) 	echo $values[0];
			else if ( $row->userresponse == 2 ) 	echo $values[1];
		
		}
		else if ( $data_table == 'checkins' ) {
		
			if ( $row->usercheckin == 1 ) 	echo $values[0];
	
		}
	}
	// end ---
	
	
	// Memory usage optimization
	function free_result($res){
		
		//var_dump($res);
		if ( $res === true || $res === false ) 	return;
		free_query_result($res); 
	}
	// end ---
	
	
?>