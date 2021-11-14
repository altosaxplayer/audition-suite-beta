<?php include_once('settings.php'); ?>

<?php

if(isset($_GET['id']))
{
	$function_id = $_GET['id'];
}
else{
	$function_id = $_POST['id'];
}

switch($function_id)
{
	case '1':
		$function_1_query = mysqli_query($sql, "SELECT * FROM `$database`.`rooms` ORDER BY `id` ASC")  or die ('Error with Function ' . $function_id);
		while ($row = mysqli_fetch_assoc($function_1_query))
		{
			$grade_level = $row['grade_level'];
			$instrument = $row['instrument'];
			
			//Determine expected audition number
			$expected_comp = mysqli_query($sql, "SELECT `id` FROM `$database`.`students` WHERE `grade_level` = '$grade_level' AND `instrument` = '$instrument' AND `audition_num` > '0'");
			$expected_comp_num = mysqli_num_rows($expected_comp);
			
			//Determine completed audition number
			$comp_audition = mysqli_query($sql, "SELECT `id` FROM `$database`.`students` WHERE `grade_level` = '$grade_level' AND `instrument` = '$instrument' AND `audition_num` > '0' AND `wind_overall_total` IS NOT NULL");
			$comp_audition_num = mysqli_num_rows($comp_audition);
			
			//Determine completion box color
			if($comp_audition_num == $expected_comp_num)
			{
				$comp_box_color = 'green';
			}
			else{
				$comp_box_color = 'red';
			}
			
			echo "<div class='dsh-cmp-rpt-box'>";
				echo "<span class='dsh-cmp-rpt-light'><span class='dsh-cmp-rpt-box-light-$comp_box_color'>$comp_audition_num/$expected_comp_num</span></span>";
				echo "<span class='dsh-cmp-rpt-box-info'>$grade_level $instrument</span>";
			echo "</div>&nbsp;";
		}
		break;
	case '2':
		$function_2_query = mysqli_query($sql, "SELECT * FROM `$database`.`students`")  or die ('Error with Function ' . $function_id);
		$function_2_query_num = mysqli_num_rows($function_2_query);
		echo $function_2_query_num;
		break;
	case '3':
		$function_3_query = mysqli_query($sql, "SELECT * FROM `$database`.`students` WHERE `audition_num` > '0'")  or die ('Error with Function ' . $function_id);
		$function_3_query_num = mysqli_num_rows($function_3_query);
		echo $function_3_query_num;
		break;
	case '4':
		$function_4_query = mysqli_query($sql, "SELECT * FROM `$database`.`students` WHERE `adj_1_wind_scale` IS NOT NULL AND`adj_1_wind_chrom` IS NOT NULL AND `adj_1_wind_sr` IS NOT NULL")  or die ('Error with Function ' . $function_id);
		$function_4_query_num = mysqli_num_rows($function_4_query);
		echo $function_4_query_num;
		break;
	case '5': //Update scores
		//Create timestamp to enter on update
		$last_saved = date('g:ia');
		
		//Determine which save to use
		if(isset($_POST['adj_1_wind_scale']))
		{
			mysqli_query($sql, "UPDATE `$database`.`students` SET `last_saved` = '$last_saved', `adj_1_wind_scale` = '" . $_POST['adj_1_wind_scale'] . "', `adj_1_wind_chrom` = '" . $_POST['adj_1_wind_chrom'] . "', `adj_1_wind_sr` = '" . $_POST['adj_1_wind_sr'] . "', `adj_2_wind_scale` = '" . $_POST['adj_2_wind_scale'] . "', `adj_2_wind_chrom` = '" . $_POST['adj_2_wind_chrom'] . "', `adj_2_wind_sr` = '" . $_POST['adj_2_wind_sr'] . "' WHERE `id` = '" . $_POST['student_id'] . "'");
		}
		elseif(isset($_POST['adj_1_snare_rudiment']))
		{
			mysqli_query($sql, "UPDATE `$database`.`students` SET `last_saved` = '$last_saved', `adj_1_snare_rudiment` = '" . $_POST['adj_1_snare_rudiment'] . "', `adj_1_snare_technique` = '" . $_POST['adj_1_snare_technique'] . "', `adj_1_snare_sr` = '" . $_POST['adj_1_snare_sr'] . "', `adj_2_snare_rudiment` = '" . $_POST['adj_2_snare_rudiment'] . "', `adj_2_snare_technique` = '" . $_POST['adj_2_snare_technique'] . "', `adj_2_snare_sr` = '" . $_POST['adj_2_snare_sr'] . "' WHERE `id` = '" . $_POST['student_id'] . "'");
		}
		break;
	case '6':
		$messages_for_inbox = mysqli_query($sql, "SELECT DISTINCT `recipient_id` FROM `$database`.`messages` WHERE `recipient_id` != '1'") or die ('Error with Function ' . $function_id);;
		while ($row = mysqli_fetch_assoc($messages_for_inbox))
		{
			$recipient_id = $row['recipient_id'];
			
			$messages_by_user = mysqli_query($sql, "SELECT * FROM `$database`.`users` WHERE `id` = '$recipient_id'") or die ('Error with Function ' . $function_id);;
			while ($row = mysqli_fetch_assoc($messages_by_user))
			{
				$name = $row['name'];
				echo "$name";
			}
		}
		break;
	case '7':
		$function_7_query = mysqli_query($sql, "SELECT `last_saved` FROM `$database`.`students` WHERE `id` = '$_GET[user_id]'")  or die ('Error with Function ' . $function_id);
		while ($row = mysqli_fetch_assoc($function_7_query))
		{
			$last_saved = $row['last_saved'];
			echo "<b>Last Saved:</b> $last_saved";
		}
		break;
	case '8':
		$function_8_query = mysqli_query($sql, "SELECT `message_content` FROM `$database`.`alerts` WHERE `room_id` = '$_GET[room_id]' ORDER BY `expiration` ASC")  or die ('Error with Function ' . $function_id);
		while ($row = mysqli_fetch_assoc($function_8_query))
		{
			$expiration = $row['expiration'];
			$message_content = $row['message_content'];

			echo "<div id='alert'><div id='alert-icon'></div><div id='alert-message'>$message_content</div></div>";
		}
		break;
	case '9': //Tabulate score
		$function_9_query = mysqli_query($sql, "SELECT * FROM `$database`.`students` WHERE `audition_num` > '0'")  or die ('Error with Function ' . $function_id);
		while ($row = mysqli_fetch_assoc($function_9_query))
		{
			$id = $row['id'];
			$instrument = $row['instrument'];
			
			//Wind and Mallet Scores
			$adj_1_wind_scale = $row['adj_1_wind_scale'];
			$adj_2_wind_scale = $row['adj_2_wind_scale'];
			$adj_1_wind_chrom = $row['adj_1_wind_chrom'];
			$adj_2_wind_chrom = $row['adj_2_wind_chrom'];
			$adj_1_wind_sr = $row['adj_1_wind_sr'];
			$adj_2_wind_sr = $row['adj_2_wind_sr'];
			
			//Only calculate percussion scores if room is percussion room
			if($instrument == 'Percussion')
			{
				//Snare Scores
				$adj_1_snare_rudiment = $row['adj_1_snare_rudiment'];
				$adj_1_snare_technique = $row['adj_1_snare_technique'];
				$adj_1_snare_sr = $row['adj_1_snare_sr'];
				$adj_2_snare_rudiment = $row['adj_2_snare_rudiment'];
				$adj_2_snare_technique = $row['adj_2_snare_technique'];
				$adj_2_snare_sr = $row['adj_2_snare_sr'];
			
				//Timpani Scores
				$adj_1_snare_rudiment = $row['adj_1_timp_tuning'];
				$adj_1_timp_technique = $row['adj_1_timp_technique'];
				$adj_1_timp_sr = $row['adj_1_timp_sr'];
				$adj_2_timp_tuning = $row['adj_2_timp_tuning'];
				$adj_1_timp_technique = $row['adj_2_timp_technique'];
				$adj_2_timp_sr = $row['adj_2_timp_sr'];
			
				//Calculate percussion category totals
				$snare_overall_total = $adj_1_snare_rudiment + $adj_1_snare_technique + $adj_1_snare_sr + $adj_2_snare_rudiment + $adj_2_snare_technique + $adj_2_snare_sr;
				$timp_overall_total = $adj_1_snare_rudiment + $adj_1_timp_technique + $adj_1_timp_sr + $adj_2_timp_tuning + $adj_1_timp_technique + $adj_2_timp_sr;
			
				//Calculate percussion overall total
				$perc_overall_total = $wind_overall_total + $snare_overall_total + $wind_sr_total;
				
				//Store totals
				mysqli_query($sql, "UPDATE `students` SET `snare_overall_total` = '$snare_overall_total', `timp_overall_total` = '$timp_overall_total', `perc_overall_total` = '$perc_overall_total' WHERE `id` = '$id'");
			}
			
			//Calculate wind and mallet category totals
			$wind_scale_total = $adj_1_wind_scale + $adj_2_wind_scale;
			$wind_chrom_total = $adj_1_wind_chrom + $adj_2_wind_chrom;
			$wind_sr_total = $adj_1_wind_sr + $adj_2_wind_sr;
			
			//Calculate wind only overall total
			$wind_overall_total = $wind_scale_total + $wind_chrom_total + $wind_sr_total;
			
			//Store totals
			mysqli_query($sql, "UPDATE `students` SET `wind_scale_total` = '$wind_scale_total', `wind_chrom_total` = '$wind_chrom_total', `wind_sr_total` = '$wind_sr_total', `wind_overall_total` = '$wind_overall_total' WHERE `id` = '$id'");
		}
		break;
	default: "Error!";
		break;
}

?>