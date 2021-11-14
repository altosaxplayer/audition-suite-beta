<?php include_once('inc/settings.php'); ?>
<?php include_once('inc/header.php'); ?>

<div class="pg-title">Results</div>

<div class='inner-wrapper'>

<?php
	//Display level jump buttons
	echo "<div class='checkin-level-button-wrapper'>";
		echo "<a href='results.php' class='checkin-level-button'>Junior Level</a>&nbsp;";
		echo "<a href='?level=1' class='checkin-level-button'>Senior Level</a>";
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
					$fetch_room_students = mysqli_query($sql, "SELECT * FROM `$database`.`students` WHERE `room_id` = '31, 32, 33' AND `audition_num` > '0' ORDER BY `first_name` ASC")  or die ('Error with student room query');
					$fetch_room_students_num = mysqli_num_rows($fetch_room_students);
	
					switch($fetch_room_students_num)
					{
						case '0':
							echo "No students to show";
							break;
						default:
							//Display results headings
								echo "<div class='checkin-entry'>";
									echo "<span class='result-entry-rank' style='font-weight: bold; text-align: center;'>Rank</span>";
									echo "<span class='result-entry-name' style='font-weight: bold; text-align: center;'>Name</span>";
									echo "<span class='result-entry-school' style='font-weight: bold; text-align: center;'>School</span>";
									echo "<span class='result-entry-info' style='font-weight: bold;'>Total</span>";
									echo "<span class='result-entry-info' style='font-weight: bold;'>Mallets</span>";
									echo "<span class='result-entry-info' style='font-weight: bold;'>Snare</span>";
									echo "<span class='result-entry-info' style='font-weight: bold;'>Timpani</span>";
								echo "</div>";
								
							$student_count = '1';
							while ($row = mysqli_fetch_assoc($fetch_room_students))
							{
								$id = $row['id'];
								$first_name = $row['first_name'];
								$last_name = $row['last_name'];
								$instrument = $row['instrument'];
								$school = $row['school'];
								$wind_overall_total = $row['wind_overall_total'];
								$snare_overall_total = $row['snare_overall_total'];
								$timp_overall_total = $row['timp_overall_total'];
								$perc_overall_total = $row['perc_overall_total'];
						
								//Alternate row colors
								if($student_count % 2 == '0')
								{
									$color_switch = '-gray';
								}
								else{
									$color_switch = '';
								}
		
								echo "<div class='checkin-entry$color_switch'>";
									echo "<span class='result-entry-rank'>Sym 1</span>";
									echo "<span class='result-entry-name'>$first_name $last_name</span>";
									echo "<span class='result-entry-school'>$school</span>";
									echo "<span class='result-entry-info'>$perc_overall_total</span>";
									echo "<span class='result-entry-info'>$wind_overall_total</span>";
									echo "<span class='result-entry-info'>$snare_overall_total</span>";
									echo "<span class='result-entry-info'>$timp_overall_total</span>";
									
								echo "</div>";
						
								$student_count++;
							}
						}
					}
					else{
						echo "<div class='form-title' id='$instrument'>" . $grade_level . " " . $instrument . "</div>";
						echo "<div class='form'>";
		
						//Fetch students for each room
						$fetch_room_students = mysqli_query($sql, "SELECT * FROM `$database`.`students` WHERE `room_id` = '$id' AND `audition_num` > '0' ORDER BY `first_name` ASC")  or die ('Error with student room query');
						$fetch_room_students_num = mysqli_num_rows($fetch_room_students);
		
						switch($fetch_room_students_num)
						{
							case '0':
								echo "No students to show";
								break;
							default:
								//Display results headings
								echo "<div>";
									echo "<span class='result-entry-rank'>Rank</span>";
									echo "<span class='checkin-entry-name'>Name</span>";
									echo "<span class='checkin-entry-info'>School</span>";
									echo "<span class='checkin-entry-info'>SR</span>";
									echo "<span class='checkin-entry-info'>Chrom</span>";
									echo "<span class='checkin-entry-info'>Scales</span>";
									echo "<span class='checkin-entry-info'>Total</span>";
								echo "</div>";
								
								$student_count = '1';
								while ($row = mysqli_fetch_assoc($fetch_room_students))
								{
									$id = $row['id'];
									$first_name = $row['first_name'];
									$last_name = $row['last_name'];
									$instrument = $row['instrument'];
									$school = $row['school'];
									$wind_scale_total = $row['wind_scale_total'];
									$wind_chrom_total = $row['wind_chrom_total'];
									$wind_sr_total = $row['wind_sr_total'];
									$wind_overall_total = $row['wind_overall_total'];
							
									//Alternate row colors
									if($student_count % 2 == '0')
									{
										$color_switch = '-gray';
									}
									else{
										$color_switch = '';
									}
			
									echo "<div class='checkin-entry$color_switch'>";
										echo "<span class='result-entry-rank'>Sym 1</span>";
										echo "<span class='result-entry-name'>$first_name $last_name</span>";
										echo "<span class='result-entry-school'>$school</span>";
										echo "<span class='result-entry-info'>$wind_sr_total</span>";
										echo "<span class='result-entry-info'>$wind_chrom_total</span>";
										echo "<span class='result-entry-info'>$wind_scale_total</span>";
										echo "<span class='result-entry-info'>$wind_overall_total</span>";
									echo "</div>";
							
									$student_count++;
								}
						}
					echo "</div>";
					}
				}
				break;
		default:
			//Fetch entries for junior level
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
					$fetch_room_students = mysqli_query($sql, "SELECT * FROM `$database`.`students` WHERE `room_id` = '14, 15, 16' AND `audition_num` > '0' ORDER BY `first_name` ASC")  or die ('Error with student room query');
					$fetch_room_students_num = mysqli_num_rows($fetch_room_students);
	
					switch($fetch_room_students_num)
					{
						case '0':
							echo "No students to show";
							break;
						default:
							//Display results headings
								echo "<div class='checkin-entry'>";
									echo "<span class='result-entry-rank' style='font-weight: bold; text-align: center;'>Rank</span>";
									echo "<span class='result-entry-name' style='font-weight: bold; text-align: center;'>Name</span>";
									echo "<span class='result-entry-school' style='font-weight: bold; text-align: center;'>School</span>";
									echo "<span class='result-entry-info' style='font-weight: bold;'>Mallets</span>";
									echo "<span class='result-entry-info' style='font-weight: bold;'>Snare</span>";
									echo "<span class='result-entry-info' style='font-weight: bold;'>Timpani</span>";
									echo "<span class='result-entry-info' style='font-weight: bold;'>Total</span>";
								echo "</div>";
								
							$student_count = '1';
							while ($row = mysqli_fetch_assoc($fetch_room_students))
							{
								$id = $row['id'];
								$first_name = $row['first_name'];
								$last_name = $row['last_name'];
								$instrument = $row['instrument'];
								$school = $row['school'];
								$wind_overall_total = $row['wind_overall_total'];
								$snare_overall_total = $row['snare_overall_total'];
								$timp_overall_total = $row['timp_overall_total'];
								$perc_overall_total = $row['perc_overall_total'];
						
								//Alternate row colors
								if($student_count % 2 == '0')
								{
									$color_switch = '-gray';
								}
								else{
									$color_switch = '';
								}
		
								echo "<div class='checkin-entry$color_switch'>";
									echo "<span class='result-entry-rank'>Sym 1</span>";
									echo "<span class='result-entry-name'>$first_name $last_name</span>";
									echo "<span class='result-entry-school'>$school</span>";
									echo "<span class='result-entry-info'>$perc_overall_total</span>";
									echo "<span class='result-entry-info'>$wind_overall_total</span>";
									echo "<span class='result-entry-info'>$snare_overall_total</span>";
									echo "<span class='result-entry-info'>$timp_overall_total</span>";
									
								echo "</div>";
						
								$student_count++;
							}
						}
					}
					else{
						echo "<div class='form-title' id='$instrument'>" . $grade_level . " " . $instrument . "</div>";
						echo "<div class='form'>";
		
						//Fetch students for each room
						$fetch_room_students = mysqli_query($sql, "SELECT * FROM `$database`.`students` WHERE `room_id` = '$id' AND `audition_num` > '0' ORDER BY `wind_overall_total` DESC")  or die ('Error with student room query');
						$fetch_room_students_num = mysqli_num_rows($fetch_room_students);
		
						switch($fetch_room_students_num)
						{
							case '0':
								echo "No students to show";
								break;
							default:
								//Display results headings
								echo "<div class='checkin-entry'>";
									echo "<span class='result-entry-rank' style='font-weight: bold; text-align: center;'>Rank</span>";
									echo "<span class='result-entry-name' style='font-weight: bold; text-align: center;'>Name</span>";
									echo "<span class='result-entry-school' style='font-weight: bold; text-align: center;'>School</span>";
									echo "<span class='result-entry-info' style='font-weight: bold;'>Total</span>";
									echo "<span class='result-entry-info' style='font-weight: bold;'>SR</span>";
									echo "<span class='result-entry-info' style='font-weight: bold;'>Chrom</span>";
									echo "<span class='result-entry-info' style='font-weight: bold;'>Scales</span>";
									echo "<span class='result-entry-info' style='font-weight: bold;'>#</span>";
								echo "</div>";
								
								$student_count = '0';
								while ($row = mysqli_fetch_assoc($fetch_room_students))
								{
									$id = $row['id'];
									$first_name = $row['first_name'];
									$last_name = $row['last_name'];
									$instrument = $row['instrument'];
									$audition_num = $row['audition_num'];
									$room_id = $row['room_id'];
									$school = $row['school'];
									$wind_scale_total = $row['wind_scale_total'];
									$wind_chrom_total = $row['wind_chrom_total'];
									$wind_sr_total = $row['wind_sr_total'];
									$wind_overall_total = $row['wind_overall_total'];
									
									$rank_query = mysqli_query($sql, "SELECT * FROM `$database`.`ranks` WHERE `room_id` = '$room_id' AND `rank_id` = '$student_count'")  or die ('Error with rank query');
									while ($row = mysqli_fetch_assoc($rank_query))
									{
										$student_rank = $row['rank'];
									}
									
									//Alternate row colors
									if($student_count % 2 == '0')
									{
										$color_switch = '-gray';
									}
									else{
										$color_switch = '';
									}
			
									echo "<div class='checkin-entry$color_switch'>";
										echo "<span class='result-entry-rank'>$student_rank</span>";
										echo "<span class='result-entry-name'>$first_name $last_name</span>";
										echo "<span class='result-entry-school'>$school</span>";
										echo "<span class='result-entry-info'>$wind_overall_total</span>";
										echo "<span class='result-entry-info'>$wind_sr_total</span>";
										echo "<span class='result-entry-info'>$wind_chrom_total</span>";
										echo "<span class='result-entry-info'>$wind_scale_total</span>";
										echo "<span class='result-entry-info'>$audition_num</span>";
									echo "</div>";
							
									$student_count++;
								}
						}
					echo "</div>";
					}
				}
				break;
	}
?>

<?php include_once('inc/footer.php'); ?>