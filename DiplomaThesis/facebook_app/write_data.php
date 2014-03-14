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

	
	// Require files

	// This provides access to helper functions defined in 'utils.php'
	require_once('utils.php');
	
	//Database funtions 
	require_once('model.php'); 
	

	// Connect to database 
	$con = connect2db();
	
	
	// Check inputs
	// Find table
	if ( !isset( $_GET['data'] ) || ( $_GET['data'] != 'events' && $_GET['data'] != 'checkins' ) ) {
		echo 'please give "events" or "checkins" GET attribute (aborting)<br />(optional: offset attribute)<br />';
		exit; 
	}
	
	// Find output values
	if ( !isset( $_GET['values'] ) ) {
		echo 'please give "values" GET attribute<br />';
		exit; 
	}
	
	// Find output
	if ( isset( $_GET['offset'] ) )
		$offset = intval( $_GET['offset'] );
	else
		$offset = 50000;

	// Table to return results
	$data_table = $_GET['data'];
		
	// Check input "values"
	$values_raw = explode( ',' , $_GET['values'] );
	if ( $data_table == 'events' && count($values_raw)!=2 ){
		echo 'please give exactly "2" numeric,comma seperated values in "values" GET attribute (for "events")<br />';
		exit; 
	}
	if ( $data_table == 'checkins' && count($values_raw)!=1 ){
		echo 'please give exactly "1" numeric,comma seperated value in "values" GET attribute (for "checkins")<br />';
		exit; 
	}
	
	foreach( $values_raw as $value ){
		if ( ctype_alpha( $value ) ) {
			echo 'please use numeric,comma seperated values in "values" GET attribute<br />';
			exit; 
		}
		$values[] = intval( $value );
	}
	// end ---	

	// Query database
	$dataout_raw = get_all_data_output( $con, $data_table, $offset ); 
	
	
	if ($dataout_raw){ 
	
		while ( $row = mysqli_fetch_object($dataout_raw)){
		
			echo $row->userid;echo ',';
			print_elementid ( $data_table, $row );
			print_userchoise ( $data_table, $row, $values );
			
			echo '<br />';
			
			/*
			//write to file
			$col1 = $row->userid;
			if ( $data_table == 'events' ) {
				$col2 = $row->eventid; 
				$col3 = $row->userresponse; 
			}
			*/

		
		}
		
		free_result($dataout_raw);
	
	}
	else{
		echo 'there was an error fetching the data from the database <br />try again';
		exit;
	}
	
	
?>