<?php include_once('inc/settings.php'); ?>
<?php include_once('inc/header.php'); ?>

<div class="pg-title">Check-In</div>

<div class='inner-wrapper'>

<?php
if(isset($_GET['id']))
{
	echo "<div class='form'>";
	
	$assign_audition_num_query = mysqli_query($sql, "SELECT * FROM `$database`.`students` WHERE `room_id` = '$_GET[room_id]' AND `audition_num` > '0'")  or die ('Error with checkin query');
	$assign_audition_num_query_num = mysqli_num_rows($assign_audition_num_query);

	$next_audition_number = $assign_audition_num_query_num + 1;
	
	$assigned_number_query = mysqli_query($sql, "SELECT * FROM `$database`.`students` WHERE `id` = '$_GET[id]'");
	while ($row = mysqli_fetch_assoc($assigned_number_query))
	{
		$audition_num = $row['audition_num'];
		$first_name = $row['first_name'];
		$last_name = $row['last_name'];
		$instrument = $row['instrument'];
		$school = $row['school'];
		$grade_level = $row['grade_level'];
		
		if($audition_num == '0')
		{
			mysqli_query($sql, "UPDATE `$database`.`students` SET `audition_num` = '$next_audition_number' WHERE `id` = '$_GET[id]'");
			$audition_num = $next_audition_number;
		}
		
		switch($grade_level)
		{
			case 'Junior':
				echo "<div class='form-title'>$first_name $last_name ($school)</div>";
				echo "<div class='junior-audition-num-box'>";
					echo "<span style='text-align: center; font-size: 20pt; margin-bottom: 15px; display: block;'>$grade_level $instrument</span>";
					echo "<span class='audition-num'>#$audition_num</span>";
				echo "</div>";
				break;
			case 'Senior':
				echo "<div class='form-title'>$first_name $last_name ($school)</div>";
				echo "<div class='senior-audition-num-box'>";
					echo "<span style='text-align: center; font-size: 20pt; margin-bottom: 15px; display: block;'>$grade_level $instrument</span>";
					echo "<span class='audition-num'>#$audition_num</span>";
				echo "</div>";
				break;
		}
	}
	
	echo "</div><br />";
	echo "<div class='checkin-level-button-wrapper'>";
		echo "<a href='checkin.php' class='checkin-level-button'>Junior Level</a>&nbsp;";
		echo "<a href='checkin.php?level=1' class='checkin-level-button'>Senior Level</a>";
		echo "</div>";
	echo "</div>";
	
	//Display search bar
	echo "<div class='checkin-jump-button-wrapper'>";
		echo "<form action='' method='get'><input id='search-bar' type='text' name='search' value='Search...' onfocus=\"this.value=''\" /><input type='submit' name='submit' style='visibility: hidden;'></form>";
	echo "</div>";
}
elseif(isset($_GET['search']))
{
	//Display search bar
	echo "<div class='checkin-jump-button-wrapper'>";
		echo "<form action='' method='get'><input id='search-bar' type='text' name='search' value='Searching for \"$_GET[search]\"' onfocus=\"this.value=''\" /><input type='submit' name='submit' style='visibility: hidden;'></form>";
	echo "</div>";

	$checkin_search_query = mysqli_query($sql, "SELECT * FROM `$database`.`students` WHERE `audition_num` = '0' AND (`first_name` LIKE '$_GET[search]%' OR `last_name` LIKE '$_GET[search]%') ORDER BY 'grade_level', 'instrument' DESC")  or die ('Error with checkin query');
	while ($row = mysqli_fetch_assoc($checkin_search_query))
	{
		$id = $row['id'];
		$grade_level = $row['grade_level'];
		$instrument = $row['instrument'];
		$room_id = $row['room_id'];

		echo "<div class='form-title' id='$instrument'>" . $grade_level . " " . $instrument . "</div>";
		echo "<div class='form'>";

		//Fetch students for each room
		$fetch_room_students = mysqli_query($sql, "SELECT * FROM `$database`.`students` WHERE `room_id` = '$room_id' AND `audition_num` = '0' ORDER BY `first_name` ASC")  or die ('Error with student room query');
		$fetch_room_students_num = mysqli_num_rows($fetch_room_students);

		switch($fetch_room_students_num)
		{
			case '0':
				echo "No students to show";
				break;
			default:	
				while ($row = mysqli_fetch_assoc($fetch_room_students))
				{
					$id = $row['id'];
					$first_name = $row['first_name'];
					$last_name = $row['last_name'];
					$school = $row['school'];
	
					echo "<div class='checkin-entry'>";
						echo "<a href='checkin.php?id=$id' 'javascript: onclick=\"return confirm('$first_name $last_name from $school will be registered for $grade_level $instrument');\" class='checkin-entry-checkmark'>check-in</a>";
						echo "<span class='checkin-entry-name'>$first_name $last_name</span>";
						echo "<span class='checkin-entry-info'>$school</span>";
						echo "<span class='checkin-entry-info'>$grade_level</span>";
						echo "<span class='checkin-entry-info'>$instrument</span>";
					echo "</div>";
				}
		}

		echo "</div>";
	}
}
else{
	//Display search bar
	echo "<div class='checkin-jump-button-wrapper'>";
		echo "<form action='' method='get'><input id='search-bar' type='text' name='search' value='Search...' onfocus=\"this.value=''\" /><input type='submit' name='submit' style='visibility: hidden;'></form>";
	echo "</div>";
	
	//Display level jump buttons
	echo "<div class='checkin-level-button-wrapper'>";
		echo "<a href='checkin.php' class='checkin-level-button'>Junior Level</a>&nbsp;";
		echo "<a href='checkin.php?level=1' class='checkin-level-button'>Senior Level</a>";
		echo "</div>";
	echo "</div>";
		
	echo "<div class='checkin-jump-button-wrapper'>";
	
	//Create jump buttons
	$jump_btn_room_query = mysqli_query($sql, "SELECT DISTINCT `instrument` FROM `$database`.`rooms` GROUP BY `instrument` ORDER BY `instrument` ASC")  or die ('Error with checkin query');
	while ($row = mysqli_fetch_assoc($jump_btn_room_query))
	{
		$instrument = $row['instrument'];
			
		//Combine percussion instruments into "percussion"
		switch($instrument)
		{
			case 'Mallets': //Do nothing
				break;
			case 'Snare': echo "<a href='#Percussion' class='checkin-jump-button'>Percussion</a>&nbsp;";
				break;
			case 'Timpani': //Do nothing
				break;
			default:
				echo "<a href='#$instrument' class='checkin-jump-button'>$instrument</a>&nbsp;";
				break;
		}
	}
	
	echo "</div>";
	echo "<div class='inner-wrapper'>";

	//Determine Junior or Senior level
	switch($_GET['level'])
	{
		case '1':
			//Fetch entries for junior level
			$checkin_room_query = mysqli_query($sql, "SELECT * FROM `$database`.`rooms` WHERE `grade_level` = 'Senior' ORDER BY 'id' DESC")  or die ('Error with checkin query');
			while ($row = mysqli_fetch_assoc($checkin_room_query))
			{
				$id = $row['id'];
				$grade_level = $row['grade_level'];
				$instrument = $row['instrument'];
				
				if($instrument == 'Snare' || $instrument == 'Mallets')
				{
					//Do nothing
				}
				elseif($instrument == 'Timpani')
				{
					echo "<div class='form-title' id='Percussion'>" . $grade_level . " Percussion</div>";
					echo "<div class='form'>";
	
					//Fetch students for each room
					$fetch_room_students = mysqli_query($sql, "SELECT * FROM `$database`.`students` WHERE `room_id` = '31, 32, 33' AND `audition_num` = '0' ORDER BY `first_name` ASC")  or die ('Error with student room query');
					$fetch_room_students_num = mysqli_num_rows($fetch_room_students);
	
					switch($fetch_room_students_num)
					{
						case '0':
							echo "No students to show";
							break;
						default:
							$student_count = '1';
							while ($row = mysqli_fetch_assoc($fetch_room_students))
							{
								$id = $row['id'];
								$first_name = $row['first_name'];
								$last_name = $row['last_name'];
								$instrument = $row['instrument'];
								$school = $row['school'];
						
								//Alternate row colors
								if($student_count % 2 == '0')
								{
									$color_switch = '-gray';
								}
								else{
									$color_switch = '';
								}
		
								echo "<div class='checkin-entry$color_switch'>";
									echo "<a href='checkin.php?id=$id' 'javascript: onclick=\"return confirm('$first_name $last_name from $school will be registered for $grade_level $instrument');\" class='checkin-entry-checkmark'>check-in</a>";
									echo "<span class='checkin-entry-name'>$first_name $last_name</span>";
									echo "<span class='checkin-entry-info'>$school</span>";
									echo "<span class='checkin-entry-info'>$grade_level</span>";
									echo "<span class='checkin-entry-info'>$instrument</span>";
								echo "</div>";
						
								$student_count++;
							}
						}
					}
					else{
						echo "<div class='form-title' id='$instrument'>" . $grade_level . " " . $instrument . "</div>";
						echo "<div class='form'>";
		
						//Fetch students for each room
						$fetch_room_students = mysqli_query($sql, "SELECT * FROM `$database`.`students` WHERE `room_id` = '$id' AND `audition_num` = '0' ORDER BY `first_name` ASC")  or die ('Error with student room query');
						$fetch_room_students_num = mysqli_num_rows($fetch_room_students);
		
						switch($fetch_room_students_num)
						{
							case '0':
								echo "No students to show";
								break;
							default:
								$student_count = '1';
								while ($row = mysqli_fetch_assoc($fetch_room_students))
								{
									$id = $row['id'];
									$first_name = $row['first_name'];
									$last_name = $row['last_name'];
									$instrument = $row['instrument'];
									$school = $row['school'];
							
									//Alternate row colors
									if($student_count % 2 == '0')
									{
										$color_switch = '-gray';
									}
									else{
										$color_switch = '';
									}
			
									echo "<div class='checkin-entry$color_switch'>";
										echo "<a href='checkin.php?id=$id' 'javascript: onclick=\"return confirm('$first_name $last_name from $school will be registered for $grade_level $instrument');\" class='checkin-entry-checkmark'>check-in</a>";
										echo "<span class='checkin-entry-name'>$first_name $last_name</span>";
										echo "<span class='checkin-entry-info'>$school</span>";
										echo "<span class='checkin-entry-info'>$grade_level</span>";
										echo "<span class='checkin-entry-info'>$instrument</span>";
									echo "</div>";
							
									$student_count++;
								}
						}
					echo "</div>";
					}
				}
				break;
		default:
			//Fetch entries for senior level
			$checkin_room_query = mysqli_query($sql, "SELECT * FROM `$database`.`rooms` WHERE `grade_level` = 'Junior' ORDER BY 'id' DESC")  or die ('Error with checkin query');
			while ($row = mysqli_fetch_assoc($checkin_room_query))
			{
				$id = $row['id'];
				$grade_level = $row['grade_level'];
				$instrument = $row['instrument'];
				
				if($instrument == 'Snare' || $instrument == 'Mallets')
				{
					//Do nothing
				}
				elseif($instrument == 'Timpani')
				{
					echo "<div class='form-title' id='Percussion'>" . $grade_level . " Percussion</div>";
					echo "<div class='form'>";
	
					//Fetch students for each room
					$fetch_room_students = mysqli_query($sql, "SELECT * FROM `$database`.`students` WHERE `room_id` = '14, 15, 16' AND `audition_num` = '0' ORDER BY `first_name` ASC")  or die ('Error with student room query');
					$fetch_room_students_num = mysqli_num_rows($fetch_room_students);
	
					switch($fetch_room_students_num)
					{
						case '0':
							echo "No students to show";
							break;
						default:
							$student_count = '1';
							while ($row = mysqli_fetch_assoc($fetch_room_students))
							{
								$id = $row['id'];
								$first_name = $row['first_name'];
								$last_name = $row['last_name'];
								$instrument = $row['instrument'];
								$school = $row['school'];
								$room_id = $row['room_id'];
						
								//Alternate row colors
								if($student_count % 2 == '0')
								{
									$color_switch = '-gray';
								}
								else{
									$color_switch = '';
								}
		
								echo "<div class='checkin-entry$color_switch'>";
									echo "<a href='checkin.php?id=$id&room_id=$room_id' 'javascript: onclick=\"return confirm('$first_name $last_name from $school will be registered for $grade_level $instrument');\" class='checkin-entry-checkmark'>check-in</a>";
									echo "<span class='checkin-entry-name'>$first_name $last_name</span>";
									echo "<span class='checkin-entry-info'>$school</span>";
									echo "<span class='checkin-entry-info'>$grade_level</span>";
									echo "<span class='checkin-entry-info'>$instrument</span>";
								echo "</div>";
						
								$student_count++;
							}
						}
					}
					else{
						echo "<div class='form-title' id='$instrument'>" . $grade_level . " " . $instrument . "</div>";
						echo "<div class='form'>";
		
						//Fetch students for each room
						$fetch_room_students = mysqli_query($sql, "SELECT * FROM `$database`.`students` WHERE `room_id` = '$id' AND `audition_num` = '0' ORDER BY `first_name` ASC")  or die ('Error with student room query');
						$fetch_room_students_num = mysqli_num_rows($fetch_room_students);
		
						switch($fetch_room_students_num)
						{
							case '0':
								echo "No students to show";
								break;
							default:
								$student_count = '1';
								while ($row = mysqli_fetch_assoc($fetch_room_students))
								{
									$id = $row['id'];
									$first_name = $row['first_name'];
									$last_name = $row['last_name'];
									$instrument = $row['instrument'];
									$school = $row['school'];
									$room_id = $row['room_id'];
							
									//Alternate row colors
									if($student_count % 2 == '0')
									{
										$color_switch = '-gray';
									}
									else{
										$color_switch = '';
									}
			
									echo "<div class='checkin-entry$color_switch'>";
										echo "<a href='checkin.php?id=$id&room_id=$room_id' 'javascript: onclick=\"return confirm('$first_name $last_name from $school will be registered for $grade_level $instrument');\" class='checkin-entry-checkmark'>check-in</a>";
										echo "<span class='checkin-entry-name'>$first_name $last_name</span>";
										echo "<span class='checkin-entry-info'>$school</span>";
										echo "<span class='checkin-entry-info'>$grade_level</span>";
										echo "<span class='checkin-entry-info'>$instrument</span>";
									echo "</div>";
							
									$student_count++;
								}
						}
					echo "</div>";
					}
				}
				break;
	}
}
?>

<?php include_once('inc/footer.php'); ?>