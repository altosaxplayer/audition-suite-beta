<?php include_once('inc/settings.php'); ?>
<?php include_once('inc/header.php'); ?>

<div class="pg-title">Students</div>

<div class='inner-wrapper'>

<?php
//Remove student if necessary
if(isset($_GET['remove']))
{
	mysqli_query($sql, "DELETE FROM `$database`.`students` WHERE `id` = '$_GET[remove]'");
	echo "<div class='form-alert'>$_GET[first_name] $_GET[last_name] has been removed</div>";
}

//Determine schools to show based on user_level
switch($user_level)
{
	case '1':	
		//Fetch all entries for administrator
		$director_school_query = mysqli_query($sql, "SELECT * FROM `$database`.`schools` ORDER BY `school_name` ASC")  or die ('Error with director school query');
		while ($row = mysqli_fetch_assoc($director_school_query))
		{
			$school_name = $row['school_name'];
	
			echo "<div class='form-title'>$school_name</div>";
			echo "<div class='form'>";
	
			$student_school_query = mysqli_query($sql, "SELECT * FROM `$database`.`students` WHERE `school` = '$school_name' ORDER BY 'first_name' DESC")  or die ('Error with director school query');
			$student_school_query_num = mysqli_num_rows($student_school_query);
	
			if($student_school_query_num != '0')
			{
				$student_count = '1';
				while ($row = mysqli_fetch_assoc($student_school_query))
				{
					$id = $row['id'];
					$uid = $row['uid'];
					$first_name = $row['first_name'];
					$last_name = $row['last_name'];
					$school = $row['school'];
					$grade_level = $row['grade_level'];
					$instrument = $row['instrument'];
					$file_upload_complete = $row['file_upload_complete'];
					
					//Alternate row colors
					if($student_count % 2 == '0')
					{
						$color_switch = '-gray';
					}
					else{
						$color_switch = '';
					}
					
					echo "<div class='checkin-entry$color_switch'>";
						echo "<span class='checkin-entry-name'>$first_name $last_name</span>";
						echo "<span class='checkin-entry-info'>$school</span>";
						echo "<span class='checkin-entry-info'>$grade_level</span>";
						echo "<span class='checkin-entry-info'>$instrument</span>";
						echo "<a href='?remove=$id&first_name=$first_name&last_name=$last_name' 'javascript: onclick=\"return confirm('$first_name $last_name from $school will be registered for $grade_level $instrument');\" class='checkin-entry-remove'>remove</a>";
						
						//Display scores if score set has been released
						if($global_jr_results_posted_status == 'yes' && $grade_level == 'Junior' && $file_upload_complete != '')
						{
							echo "<a href='files/$uid.pdf' class='checkin-entry-scores'>scores</a>";
						}
						elseif($global_sr_results_posted_status == 'yes' && $grade_level == 'Senior')
						{
							echo "<a href='files/$uid.pdf' class='checkin-entry-scores'>scores</a>";
						}
						else
						{
							echo "<span class='checkin-entry-scores-disabled'>scores</span>";
						}
					echo "</div>";
					
					$student_count++;
				}
	
				echo "</div>";
			}
			else{
				echo "No students to show";
				echo "</div>";
			}
		}
		break;
	case '3':
		//Fetch entries for given director
		$director_school_query = mysqli_query($sql, "SELECT * FROM `$database`.`schools` WHERE `school_director` LIKE '%$user_first_name%' ORDER BY `school_name` ASC")  or die ('Error with director school query');
		while ($row = mysqli_fetch_assoc($director_school_query))
		{
			$school_name = $row['school_name'];
	
			echo "<div class='form-title'>$school_name</div>";
			echo "<div class='form'>";
	
			$student_school_query = mysqli_query($sql, "SELECT * FROM `$database`.`students` WHERE `school` = '$school_name' ORDER BY 'first_name' DESC")  or die ('Error with director school query');
			$student_school_query_num = mysqli_num_rows($student_school_query);
	
			if($student_school_query_num != '0')
			{
				while ($row = mysqli_fetch_assoc($student_school_query))
				{
					$id = $row['id'];
					$first_name = $row['first_name'];
					$last_name = $row['last_name'];
					$school = $row['school'];
					$grade_level = $row['grade_level'];
					$instrument = $row['instrument'];
		
					echo "<div class='checkin-entry'>";
						echo "<span class='checkin-entry-name'>$first_name $last_name</span>";
						echo "<span class='checkin-entry-info'>$school</span>";
						echo "<span class='checkin-entry-info'>$grade_level</span>";
						echo "<span class='checkin-entry-info'>$instrument</span>";
						echo "<a href='?remove=$id&first_name=$first_name&last_name=$last_name' 'javascript: onclick=\"return confirm('$first_name $last_name from $school will be registered for $grade_level $instrument');\" class='checkin-entry-remove'>remove</a>";
					echo "</div>";
				}
	
				echo "</div>";
			}
			else{
				echo "No students to show";
				echo "</div>";
			}
		}
		break;
}

?>

<?php include_once('inc/footer.php'); ?>