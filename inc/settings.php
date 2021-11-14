<?php $user_level = '1'; //Remove from final ?>
<?php $database = 'altosaxp_audBETA'; //Remove from final ?>
<?php $user_name = 'Logan'; //Remove from final ?>
<?php $global_double_nine = 'yes'; //Remove from final ?>
<?php $user_first_name = 'Mark'; //Remove from final ?>
<?php $user_id = '1'; //Remove from final ?>
<?php $user_room_assign = 'All'; //Remove from final ?>
<?php $global_jr_results_posted_status = 'yes'; //Remove from final ?>
<?php $global_sr_results_posted_status = 'no'; //Remove from final ?>



<?php
	//Force HTTPS on live
	if($_SERVER['HTTP_HOST'] != 'localhost:8888')
	{
		if(!isset($_SERVER['HTTPS']))
		{
			header('Location: https://' . $_SERVER["SERVER_NAME"] . $_SERVER['REQUEST_URI']);
		}
	}
	
	//Initiate database connection
	if($_SERVER['HTTP_HOST'] == 'localhost:8888') //Connection details if development
	{
		$sql = mysqli_connect('localhost','root','root',$database);
	}
	else{
		$sql = mysqli_connect('localhost','altosaxp_auditio','Irisnell2',$database);
	}
		
	//Fetch event title
	$global_event_title_query = mysqli_query($sql, "SELECT * FROM `$database`.`settings` WHERE `setting_name` = 'Event Title'")  or die ('Error fetching event title');
	while ($row = mysqli_fetch_assoc($global_event_title_query))
	{
		$global_event_title = $row['setting_value'];
	}
	
	//Fetch scale score maximum
	$global_scale_score_query = mysqli_query($sql, "SELECT * FROM `$database`.`settings` WHERE `setting_name` = 'Scale Score Max'")  or die ('Error fetching scale score max');
	while ($row = mysqli_fetch_assoc($global_scale_score_query))
	{
		$global_scale_max = $row['setting_value'];
	}
	
	//Fetch chromatic scale score maximum
	$global_chromatic_score_query = mysqli_query($sql, "SELECT * FROM `$database`.`settings` WHERE `setting_name` = 'Chromatic Score Max'")  or die ('Error fetching chromatic scale score max');
	while ($row = mysqli_fetch_assoc($global_chromatic_score_query))
	{
		$global_chrom_max = $row['setting_value'];
	}
	
	//Fetch chromatic scale score maximum
	$global_sight_reading_score_query = mysqli_query($sql, "SELECT * FROM `$database`.`settings` WHERE `setting_name` = 'Sight Reading Score Max'")  or die ('Error fetching sight reading score max');
	while ($row = mysqli_fetch_assoc($global_sight_reading_score_query))
	{
		$global_sr_max = $row['setting_value'];
	}
	
	//Fetch result posted status
	$global_result_posted_status_query = mysqli_query($sql, "SELECT * FROM `$database`.`settings` WHERE `setting_name` = 'Results Posted Status'")  or die ('Error fetching results posted status');
	while ($row = mysqli_fetch_assoc($global_result_posted_status_query))
	{
		$global_result_posted_status = $row['setting_value'];
	}
	
	//Fetch result type
	$global_result_type_query = mysqli_query($sql, "SELECT * FROM `$database`.`settings` WHERE `setting_name` = 'Results Type'")  or die ('Error fetching results posted status');
	while ($row = mysqli_fetch_assoc($global_result_type_query))
	{
		$global_result_type = $row['setting_value'];
	}
	
	//Fetch adjudicator num
	$global_result_type_query = mysqli_query($sql, "SELECT * FROM `$database`.`settings` WHERE `setting_name` = 'Adjudicator Number'")  or die ('Error fetching adjudicator number');
	while ($row = mysqli_fetch_assoc($global_result_type_query))
	{
		$global_adjudicator_num = $row['setting_value'];
	}
?>