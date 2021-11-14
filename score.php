<?php include_once('inc/settings.php'); ?>
<?php include_once('inc/header.php'); ?>

<script>
	function processForm(form) { 
		$.ajax( {
			type: 'POST',
			url: 'inc/functions.php',
			data: $(form).serialize(),
			success: function(data) {
				$('#message').fadeIn(500).delay(1000).html("<div id='message-inner-success'>Scores saved successfully</div>").fadeOut(1000);
			}
		} );
		return false;
	}
	function sendMessage(form) { 
		$.ajax( {
			type: 'POST',
			url: 'inc/messages.php',
			data: $(form).serialize(),
			success: function(data) {
				$('#message-form').trigger('reset');
			}
		} );
		return false;
	}
	$(document).ready(function(){
		setInterval(function(){
		$("#alert-wrapper").load("inc/functions.php?id=8&room_id=<?php echo $_GET['room_id']; ?>")
		}, 3000);
	});
</script>

<?php
//Display correct page title
$room_information_query = mysqli_query($sql, "SELECT * FROM `$database`.`rooms` WHERE `id` = '$_GET[room_id]'");
while ($row = mysqli_fetch_assoc($room_information_query))
{
	$grade_level = $row['grade_level'];
	$instrument = $row['instrument'];
	
	echo "<div class='pg-title'>$grade_level $instrument</div>";
}
echo "<div class='inner-wrapper'>";

//Determine fields to show based on room id
switch($_GET['room_id'])
{
	case '14':
		//Set needed variables for non-wind room
		$room_id_sql_query = '14, 15, 16';
		$score_grouping_title_1 = 'Rudiments';
		$score_grouping_title_2 = 'Technique';
		$score_grouping_title_3 = 'Sight Reading';
	case '31':
		//Only set these if empty from above
		if(empty($room_id_sql_query))
		{
			$room_id_sql_query = '31, 32, 33';
		}
		
		$room_auditionee_query = mysqli_query($sql, "SELECT * FROM `$database`.`students` WHERE `room_id` LIKE '$room_id_sql_query' AND `audition_num` > '0' ORDER BY `audition_num` ASC");
		while ($row = mysqli_fetch_assoc($room_auditionee_query))
		{
			$id = $row['id'];
			$grade_level = $row['grade_level'];
			$audition_num = $row['audition_num'];
			$adj_1_snare_rudiment = $row['adj_1_snare_rudiment'];
			$adj_1_snare_technique = $row['adj_1_snare_technique'];
			$adj_1_snare_sr = $row['adj_1_snare_sr'];
			$adj_2_snare_rudiment = $row['adj_2_snare_rudiment'];
			$adj_2_snare_technique = $row['adj_2_snare_technique'];
			$adj_2_snare_sr = $row['adj_2_snare_sr'];
			
			//Show audition number color based on room level
			if($grade_level == 'Junior')
			{
				$audition_number_color = 'junior-score-audition-num';
			}
			else{
				$audition_number_color = 'senior-score-audition-num';
			}
			
			echo "<div class='form'>";
			echo "<div class='$audition_number_color'>$audition_num</div>";
				echo "<form action='' onsubmit='processForm(this); return false;' method='post' style='display: inline-block; width: 92%;'>";
					echo "<div class='score-audition-inner-wrapper'>";
						echo "<span class='score-audition-title'>$score_grouping_title_1</span>";
						echo "<span class='score-audition-title'>$score_grouping_title_2</span>";
						echo "<span class='score-audition-title'>$score_grouping_title_3</span>";
					echo "</div>";
					echo "<div class='score-audition-inner-wrapper'>";
						echo "<span class='score-max-info'>$global_scale_max maximum</span>";
						echo "<span class='score-max-info'>$global_chrom_max maximum</span>";
						echo "<span class='score-max-info'>$global_sr_max maximum</span>";
					echo "</div>";
					echo "<div class='score-audition-inner-wrapper'>";
						echo "<input type='text' class='score-audition-input' name='adj_1_snare_rudiment' value='$adj_1_snare_rudiment' />";
						echo "<input type='text' class='score-audition-input' name='adj_1_snare_technique' value='$adj_1_snare_technique' />";
						echo "<input type='text' class='score-audition-input' name='adj_1_snare_sr' value='$adj_1_snare_sr' />";
					echo "</div>";
						//Add a second set of input fields if global_adj_num equals 2
						if($global_adjudicator_num == '2')
						{
							echo "<div class='score-audition-inner-wrapper'>";
								echo "<input type='text' class='score-audition-input' name='adj_2_snare_rudiment' value='$adj_2_snare_rudiment' />";
								echo "<input type='text' class='score-audition-input' name='adj_2_snare_technique' value='$adj_2_snare_technique' />";
								echo "<input type='text' class='score-audition-input' name='adj_2_snare_sr' value='$adj_2_snare_sr' />";
							echo "</div>";
						}
					echo "<input type='hidden' name='id' value='5' />";
					echo "<input type='hidden' name='student_id' value='$id' />";
					echo "<input type='submit' class='score-audition-submit' value='' />";
				echo "</form>";
				echo "<script>$(document).ready(function(){";
					echo "setInterval(function(){";
					echo "$(\".$id\").load(\"inc/functions.php?id=7&user_id=$id\")";
					echo "}, 10000);";
					echo "});";
				echo "</script>";
				echo "<div class='score-last-saved-meta $id'><b>Last Saved:</b></div>";
			echo "</div>";
		}
		break;
	case '15':
		//Set needed variables for non-wind room
		$room_id_sql_query = '14, 15, 16';
		$score_grouping_title_1 = 'Rudiments';
		$score_grouping_title_2 = 'Technique';
		$score_grouping_title_3 = 'Sight Reading';
	case '32':
		//Only set these if empty from above
		if(empty($room_id_sql_query))
		{
			$room_id_sql_query = '31, 32, 33';
		}
		
		$room_auditionee_query = mysqli_query($sql, "SELECT * FROM `$database`.`students` WHERE `room_id` LIKE '$room_id_sql_query' AND `audition_num` > '0' ORDER BY `audition_num` ASC");
		while ($row = mysqli_fetch_assoc($room_auditionee_query))
		{
			$id = $row['id'];
			$grade_level = $row['grade_level'];
			$audition_num = $row['audition_num'];
			$adj_1_wind_scale = $row['adj_1_wind_scale'];
			$adj_1_wind_chrom = $row['adj_1_wind_chrom'];
			$adj_1_wind_sr = $row['adj_1_wind_sr'];
			$adj_2_wind_scale = $row['adj_2_wind_scale'];
			$adj_2_wind_chrom = $row['adj_2_wind_chrom'];
			$adj_2_wind_sr = $row['adj_2_wind_sr'];
			
			//Show audition number color based on room level
			if($grade_level == 'Junior')
			{
				$audition_number_color = 'junior-score-audition-num';
			}
			else{
				$audition_number_color = 'senior-score-audition-num';
			}
			
			echo "<div class='form'>";
			echo "<div class='$audition_number_color'>$audition_num</div>";
				echo "<form action='' onsubmit='processForm(this); return false;' method='post' style='display: inline-block; width: 92%;'>";
					echo "<div class='score-audition-inner-wrapper'>";
						echo "<span class='score-audition-title'>$score_grouping_title_1</span>";
						echo "<span class='score-audition-title'>$score_grouping_title_2</span>";
						echo "<span class='score-audition-title'>$score_grouping_title_3</span>";
					echo "</div>";
					echo "<div class='score-audition-inner-wrapper'>";
						echo "<span class='score-max-info'>$global_scale_max maximum</span>";
						echo "<span class='score-max-info'>$global_chrom_max maximum</span>";
						echo "<span class='score-max-info'>$global_sr_max maximum</span>";
					echo "</div>";
					echo "<div class='score-audition-inner-wrapper'>";
						echo "<input type='text' class='score-audition-input' name='adj_1_wind_scale' value='$adj_1_wind_scale' />";
						echo "<input type='text' class='score-audition-input' name='adj_1_wind_chrom' value='$adj_1_wind_chrom' />";
						echo "<input type='text' class='score-audition-input' name='adj_1_wind_sr' value='$adj_1_wind_sr' />";
					echo "</div>";
						//Add a second set of input fields if global_adj_num equals 2
						if($global_adjudicator_num == '2')
						{
							echo "<div class='score-audition-inner-wrapper'>";
								echo "<input type='text' class='score-audition-input' name='adj_2_wind_scale' value='$adj_2_wind_scale' />";
								echo "<input type='text' class='score-audition-input' name='adj_2_wind_chrom' value='$adj_2_wind_chrom' />";
								echo "<input type='text' class='score-audition-input' name='adj_2_wind_sr' value='$adj_2_wind_sr' />";
							echo "</div>";
						}
					echo "<input type='hidden' name='id' value='5' />";
					echo "<input type='hidden' name='student_id' value='$id' />";
					echo "<input type='submit' class='score-audition-submit' value='' />";
				echo "</form>";
				echo "<script>$(document).ready(function(){";
					echo "setInterval(function(){";
					echo "$(\".$id\").load(\"inc/functions.php?id=7&user_id=$id\")";
					echo "}, 10000);";
					echo "});";
				echo "</script>";
				echo "<div class='score-last-saved-meta $id'><b>Last Saved:</b></div>";
			echo "</div>";
		}
		break;

	default:
		$room_auditionee_query = mysqli_query($sql, "SELECT * FROM `$database`.`students` WHERE `room_id` = '$_GET[room_id]' AND `audition_num` > '0' ORDER BY `audition_num` ASC");
		while ($row = mysqli_fetch_assoc($room_auditionee_query))
		{
			$id = $row['id'];
			$grade_level = $row['grade_level'];
			$audition_num = $row['audition_num'];
			$adj_1_wind_scale = $row['adj_1_wind_scale'];
			$adj_1_wind_chrom = $row['adj_1_wind_chrom'];
			$adj_1_wind_sr = $row['adj_1_wind_sr'];
			$adj_2_wind_scale = $row['adj_2_wind_scale'];
			$adj_2_wind_chrom = $row['adj_2_wind_chrom'];
			$adj_2_wind_sr = $row['adj_2_wind_sr'];
			
			//Show audition number color based on room level
			if($grade_level == 'Junior')
			{
				$audition_number_color = 'junior-score-audition-num';
			}
			else{
				$audition_number_color = 'senior-score-audition-num';
			}
			
			echo "<div class='form'>";
			echo "<div class='$audition_number_color'>$audition_num</div>";
				echo "<form action='' onsubmit='processForm(this); return false;' method='post' style='display: inline-block; width: 92%;'>";
					echo "<div class='score-audition-inner-wrapper'>";
						echo "<span class='score-audition-title'>Major Scales</span>";
						echo "<span class='score-audition-title'>Chromatic Scale</span>";
						echo "<span class='score-audition-title'>Sight Reading</span>";
					echo "</div>";
					echo "<div class='score-audition-inner-wrapper'>";
						echo "<span class='score-max-info'>$global_scale_max maximum</span>";
						echo "<span class='score-max-info'>$global_chrom_max maximum</span>";
						echo "<span class='score-max-info'>$global_sr_max maximum</span>";
					echo "</div>";
					echo "<div class='score-audition-inner-wrapper'>";
						echo "<input type='text' class='score-audition-input' name='adj_1_wind_scale' value='$adj_1_wind_scale' />";
						echo "<input type='text' class='score-audition-input' name='adj_1_wind_chrom' value='$adj_1_wind_chrom' />";
						echo "<input type='text' class='score-audition-input' name='adj_1_wind_sr' value='$adj_1_wind_sr' />";
					echo "</div>";
						//Add a second set of input fields if global_adj_num equals 2
						if($global_adjudicator_num == '2')
						{
							echo "<div class='score-audition-inner-wrapper'>";
								echo "<input type='text' class='score-audition-input' name='adj_2_wind_scale' value='$adj_2_wind_scale' />";
								echo "<input type='text' class='score-audition-input' name='adj_2_wind_chrom' value='$adj_2_wind_chrom' />";
								echo "<input type='text' class='score-audition-input' name='adj_2_wind_sr' value='$adj_2_wind_sr' />";
							echo "</div>";
						}
					echo "<input type='hidden' name='id' value='5' />";
					echo "<input type='hidden' name='student_id' value='$id' />";
					echo "<input type='submit' class='score-audition-submit' value='' />";
				echo "</form>";
				echo "<script>$(document).ready(function(){";
					echo "setInterval(function(){";
					echo "$(\".$id\").load(\"inc/functions.php?id=7&user_id=$id\")";
					echo "}, 10000);";
					echo "});";
				echo "</script>";
				echo "<div class='score-last-saved-meta $id'><b>Last Saved:</b></div>";
			echo "</div>";
		}
		break;
}

?>

<?php include_once('inc/footer.php'); ?>