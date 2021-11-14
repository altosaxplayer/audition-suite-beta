<?php include_once('inc/settings.php'); ?>
<?php include_once('inc/header.php'); ?>

<?php
	//Determine page title based on result_posted_status
	switch($global_result_posted_status)
	{
		case 'no':
			echo "<div class='pg-title'>Audition Pre-Registration</div>";
				if(empty($_POST['form_submit']) && empty($_POST['first_name']) && empty($_POST['last_name']) && empty($_POST['grade']) && empty($_POST['school']) && empty($_POST['instrument']))
				{
					$form_variant = 'blank-form';
				}
				elseif(!empty($_POST['form_submit']) && empty($_POST['first_name']) || empty($_POST['last_name']) || empty($_POST['grade']) || empty($_POST['school']) || empty($_POST['instrument']))
				{
					$form_variant = 'form-finish';
					$form_error_message = 'Please fill out the form completely';
				}
				elseif(strpos($_POST['school'], 'High') == true && $_POST['grade'] < '9' || strpos($_POST['school'], 'Middle') == true && $_POST['grade'] >= '9')
				{
					$form_variant = 'form-finish';
					$form_error_message = 'Incorrect grade for school type (middle/high)';
				}
				else{
					//Determine if student is already pre-registered
					$preregistration_check = mysqli_query($sql, "SELECT * FROM `$database`.`students` WHERE `first_name` = '$_POST[first_name]' AND `last_name` = '$_POST[last_name]' AND `grade` = '$_POST[grade]' AND `school` = '$_POST[school]' AND `instrument` = '$_POST[instrument]'");
					$preregistration_check_num = mysqli_num_rows($preregistration_check);
		
					if($preregistration_check_num > '0')
					{
						$form_variant = 'duplicate';
						$form_error_message = 'Sorry, no duplicate pre-registrations allowed';
					}
					else{
						$form_variant = 'confirmation';
					}
				}
			break;
		case 'yes':
			echo "<div class='pg-title'>$global_result_type Results</div>";
			$form_variant = 'results';
			break;
	}
?>

<div class="inner-wrapper">

<?php
	
	switch($form_variant)
	{
		case 'confirmation':
			//Uppercase names before displaying again
			$first_name = ucwords(strtolower($_POST['first_name']));
			$last_name = ucfirst(strtolower($_POST['last_name']));
			
			//Get rest of data
			$school = $_POST['school'];
			$grade = $_POST['grade'];
			$instrument = $_POST['instrument'];
			
			//Determine double 9th grade registration or no before inserting
			if($grade == '9' && $global_double_nine == 'yes')
			{
				$grade_level = 'Junior';
				
				for($i = 0; $i < 2; $i++)
				{
					//Determine room number before inserting
					$room_id_query = mysqli_query($sql, "SELECT * FROM `$database`.`rooms` WHERE `grade_level` = '$grade_level' AND `instrument` = '$instrument'");
					
					while ($row = mysqli_fetch_assoc($room_id_query))
					{
						$room_id = $row['id'];
					}
					
					//Create and verify unique identifier
					$uid_loop = '0';
					while($uid_loop == '0')
					{
						$uid = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 15);
						
						//Check to make sure the UID doesn't already exist
						$uid_query = mysqli_query($sql, "SELECT COUNT(`uid`) FROM `$database`.`students` WHERE `uid` = '$uid'") or die('Error! ' . mysqli_error($sql));
					
						if($uid_query == '1')
						{
							$uid_loop = '0';
						}
						else{
							$uid_loop = '1';
						}
					}
					
					//Insert pre-registration information into database
					mysqli_query($sql, "INSERT INTO `$database`.`students` (`id`, `timestamp`, `uid`, `first_name`, `last_name`, `school`, `grade`, `grade_level`, `instrument`, `room_id`) VALUES (NULL, CURRENT_TIMESTAMP, '$uid', '$first_name', '$last_name', '$school', '$grade', '$grade_level', '$instrument', '$room_id')") or die('Error! ' . mysqli_error($sql));
					
					$grade_level = 'Senior';
				}
			}
			elseif($grade == '9' && $global_double_nine == 'no')
			{
				//Determine room number before inserting
				$room_id_query = mysqli_query($sql, "SELECT * FROM `$database`.`rooms` WHERE `grade_level` = 'Senior' AND `instrument` = '$instrument'");
				while ($row = mysqli_fetch_assoc($room_id_query))
				{
					$room_id = $row['id'];
				}
				
				//Create and verify unique identifier
				$uid_loop = '0';
				while($uid_loop == '0')
				{
					$uid = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 15);
					
					//Check to make sure the UID doesn't already exist
					$uid_query = mysqli_query($sql, "SELECT COUNT(`uid`) FROM `$database`.`students` WHERE `uid` = '$uid'") or die('Error! ' . mysqli_error($sql));
				
					if($uid_query == '1')
					{
						$uid_loop = '0';
					}
					else{
						$uid_loop = '1';
					}
				}
				
				//Insert pre-registration information into database
				mysqli_query($sql, "INSERT INTO `$database`.`students` (`id`, `timestamp`, `uid`, `first_name`, `last_name`, `school`, `grade`, `grade_level`, `instrument`, `room_id`) VALUES (NULL, CURRENT_TIMESTAMP, '$uid', '$first_name', '$last_name', '$school', '$grade', 'Junior', '$instrument', '$room_id')") or die('Error! ' . mysqli_error($sql));
			}
			elseif($grade >= '10')
			{
				//Determine room number before inserting
				$room_id_query = mysqli_query($sql, "SELECT * FROM `$database`.`rooms` WHERE `grade_level` = 'Senior' AND `instrument` = '$instrument'");
				while ($row = mysqli_fetch_assoc($room_id_query))
				{
					if($instrument == 'Percussion')
					{
						$room_id = '31, 32, 33';
					}
					else{
						$room_id = $row['id'];
					}
				}
			
				//Create and verify unique identifier
				$uid_loop = '0';
				while($uid_loop == '0')
				{
					$uid = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 15);
					
					//Check to make sure the UID doesn't already exist
					$uid_query = mysqli_query($sql, "SELECT COUNT(`uid`) FROM `$database`.`students` WHERE `uid` = '$uid'") or die('Error! ' . mysqli_error($sql));
				
					if($uid_query == '1')
					{
						$uid_loop = '0';
					}
					else{
						$uid_loop = '1';
					}
				}
				
				//Insert pre-registration information into database
				mysqli_query($sql, "INSERT INTO `$database`.`students` (`id`, `timestamp`, `uid`, `first_name`, `last_name`, `school`, `grade`, `grade_level`, `instrument`, `room_id`) VALUES (NULL, CURRENT_TIMESTAMP, '$uid', '$first_name', '$last_name', '$school', '$grade', 'Senior', '$instrument', '$room_id')") or die('Error! ' . mysqli_error($sql));
			}
			elseif($grade < '9')
			{
				//Determine room number before inserting
				$room_id_query = mysqli_query($sql, "SELECT * FROM `$database`.`rooms` WHERE `grade_level` = 'Junior' AND `instrument` = '$instrument'");
				while ($row = mysqli_fetch_assoc($room_id_query))
				{
					if($instrument == 'Percussion')
					{
						$room_id = '14, 15, 16';
					}
					else{
						$room_id = $row['id'];
					}
				}
				
				//Create and verify unique identifier
				$uid_loop = '0';
				while($uid_loop == '0')
				{
					$uid = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 15);
					
					//Check to make sure the UID doesn't already exist
					$uid_query = mysqli_query($sql, "SELECT COUNT(`uid`) FROM `$database`.`students` WHERE `uid` = '$uid'") or die('Error! ' . mysqli_error($sql));
				
					if($uid_query == '1')
					{
						$uid_loop = '0';
					}
					else{
						$uid_loop = '1';
					}
				}
				
				//Insert pre-registration information into database
				mysqli_query($sql, "INSERT INTO `$database`.`students` (`id`, `timestamp`, `uid`, `first_name`, `last_name`, `school`, `grade`, `grade_level`, `instrument`, `room_id`) VALUES (NULL, CURRENT_TIMESTAMP, '$uid', '$first_name', '$last_name', '$school', '$grade', 'Junior', '$instrument', '$room_id')") or die('Error! ' . mysqli_error($sql));
			}
			
			echo "<div class='form'>";
				echo "<div class='form-title'>Pre-Registration Confirmation</div>";
					echo "<b>Name:</b> $first_name $last_name<br />";
					echo "<b>School:</b> $school<br />";
					echo "<b>Grade:</b> $grade<br />";
					echo "<b>Instrument:</b> $instrument<br /><br />";
					echo "<span style='display: block; font-style: italic; text-align: center; font-size: 10pt;'>you will be returned to audition pre-registration automatically</span>";
					echo "<meta http-equiv='refresh' content='5;url=index.php'>";
			break;
		case 'results':
			echo "<div class='front-results'>";
				echo "<div style='font-size: 22pt; font-family: Teko;'>Junior Level Results</div>";
				echo "<div class='form-submit'>view</div>";
			echo "</div>";
			echo "<div class='front-results'>";
				echo "<div style='font-size: 22pt; font-family: Teko;'>Senior Level Results</div>";
				echo "<div class='form-submit'>view</div>";
			echo "</div>";
			break;
		case 'form-finish':
			//Uppercase names before displaying again
			$first_name = ucfirst(strtolower($_POST['first_name']));
			$last_name = ucfirst(strtolower($_POST['last_name']));
			
			echo "<div class='form'>";
			echo "<div class='form-alert'>$form_error_message</div>";
				echo "<form action='' method='post'>";
					echo "<div class='form-input-label'>First Name</div>";
						echo "<span style='display: block; text-align: center'><input class='form-input-box' type='text' name='first_name' value='$first_name'></span>";
					echo "<div class='form-input-label'>Last Name</div>";
						echo "<span style='display: block; text-align: center'><input class='form-input-box' type='text' name='last_name' value='$last_name'></span>";
					echo "<div class='form-input-label'>Grade</div>";
						echo "<span style='display: block; text-align: center'><div class='radio-group'>";
							for($i = 7; $i < 13; $i++)
							{
								$option_selected = '';
								
								//Show selected field if set
								if(isset($_POST['grade']))
								{
									if($_POST['grade'] == $i)
									{
										$option_selected = 'checked';
									}
								}
								echo "<input type='radio' name='grade' id='$i' value='$i' $option_selected><label for='$i'>$i</label>";
							}
						echo "</div></span>";
					echo "<div class='form-input-label'>School</div>";
						echo "<span style='display: block; text-align: center'><select class='form-input-dropdown' name='school'>";
							echo "<option></option>";
								$school_query = mysqli_query($sql, "SELECT * FROM `$database`.`schools` ORDER BY `school_name` ASC");
								while ($row = mysqli_fetch_assoc($school_query))
								{
									$school_name = $row['school_name'];
									$option_selected = '';
									
									//Show selected field if set
									if(isset($_POST['school']))
									{
										if($_POST['school'] == $school_name)
										{
											$option_selected = 'selected';
										}
									}
					
									echo "<option name='school' value='$school_name' $option_selected>$school_name</option>";
								}
							echo "</select></span>";
					echo "<div class='form-input-label'>Instrument</div>";
						echo "<span style='display: block; text-align: center'><select class='form-input-dropdown' name='instrument'>";
							echo "<option></option>";
								$room_query = mysqli_query($sql, "SELECT DISTINCT `instrument` FROM `$database`.`rooms` GROUP BY `instrument` ORDER BY `instrument` ASC");
								while ($row = mysqli_fetch_assoc($room_query))
								{
									$instrument = $row['instrument'];
									$option_selected = '';
									
									//Show selected field if set
									if(isset($_POST['instrument']))
									{
										if($_POST['instrument'] == $instrument)
										{
											$option_selected = 'selected';
										}
									}
									
									//Combine percussion instruments into "percussion"
									switch($instrument)
									{
										case 'Mallets': //Do nothing
											break;
										case 'Snare': //Do nothing
											break;
										case 'Timpani': echo "<option name='instrument' value='Percussion'>Percussion</option>";
											break;
										default:
											echo "<option name='instrument' value='$instrument'>$instrument</option>";
											break;
									}
								}
							echo "</select></span>";
					echo "<br />";
					echo "<input type='hidden' name='form_submit' value='form-submitted'>";
					echo "<input class='form-submit' type='submit' value='Submit'>";
				echo "</form>";
				echo "</div>";
			break;
		case 'duplicate':
			echo "<div class='form'>";
			echo "<div class='form-alert'>$form_error_message</div>";
				echo "<form action='' method='post'>";
					echo "<div class='form-input-label'>First Name</div>";
						echo "<span style='display: block; text-align: center'><input class='form-input-box' type='text' name='first_name'></span>";
					echo "<div class='form-input-label'>Last Name</div>";
						echo "<span style='display: block; text-align: center'><input class='form-input-box' type='text' name='last_name'></span>";
					echo "<div class='form-input-label'>Grade</div>";
						echo "<span style='display: block; text-align: center'><div class='radio-group'>";
							for($i = 7; $i < 13; $i++)
							{
								echo "<input type='radio' name='grade' id='$i' value='$i'><label for='$i'>$i</label>";
							}
						echo "</div></span>";
					echo "<div class='form-input-label'>School</div>";
						echo "<span style='display: block; text-align: center'><select class='form-input-dropdown' name='school'>";
							echo "<option></option>";
								$school_query = mysqli_query($sql, "SELECT * FROM `$database`.`schools` ORDER BY `school_name` ASC");
								while ($row = mysqli_fetch_assoc($school_query))
								{
									$school_name = $row['school_name'];
									echo "<option name='school' value='$school_name'>$school_name</option>";
								}
							echo "</select></span>";
					echo "<div class='form-input-label'>Instrument</div>";
						echo "<span style='display: block; text-align: center'><select class='form-input-dropdown' name='instrument'>";
							echo "<option></option>";
								$room_query = mysqli_query($sql, "SELECT DISTINCT `instrument` FROM `$database`.`rooms` GROUP BY `instrument` ORDER BY `instrument` ASC");
								while ($row = mysqli_fetch_assoc($room_query))
								{
									$instrument = $row['instrument'];
									
									//Combine percussion instruments into "percussion"
									switch($instrument)
									{
										case 'Mallets': //Do nothing
											break;
										case 'Snare': //Do nothing
											break;
										case 'Timpani': echo "<option name='instrument' value='Percussion'>Percussion</option>";
											break;
										default:
											echo "<option name='instrument' value='$instrument'>$instrument</option>";
											break;
									}
								}
							echo "</select></span>";
					echo "<br />";
					echo "<input type='hidden' name='form_submit' value='form-submitted'>";
					echo "<input class='form-submit' type='submit' value='Submit'>";
				echo "</form>";
				echo "</div>";
			break;
		case 'blank-form':
		default:
			echo "<div class='form'>";
				echo "<form action='' method='post'>";
					echo "<div class='form-input-label'>First Name</div>";
						echo "<span style='display: block; text-align: center'><input class='form-input-box' type='text' name='first_name'></span>";
					echo "<div class='form-input-label'>Last Name</div>";
						echo "<span style='display: block; text-align: center'><input class='form-input-box' type='text' name='last_name'></span>";
					echo "<div class='form-input-label'>Grade</div>";
						echo "<span style='display: block; text-align: center'><div class='radio-group'>";
							for($i = 7; $i < 13; $i++)
							{
								echo "<input type='radio' name='grade' id='$i' value='$i'><label for='$i'>$i</label>";
							}
						echo "</div></span>";
					echo "<div class='form-input-label'>School</div>";
						echo "<span style='display: block; text-align: center'><select class='form-input-dropdown' name='school'>";
							echo "<option></option>";
								$school_query = mysqli_query($sql, "SELECT * FROM `$database`.`schools` ORDER BY `school_name` ASC");
								while ($row = mysqli_fetch_assoc($school_query))
								{
									$school_name = $row['school_name'];
									echo "<option name='school' value='$school_name'>$school_name</option>";
								}
							echo "</select></span>";
					echo "<div class='form-input-label'>Instrument</div>";
						echo "<span style='display: block; text-align: center'><select class='form-input-dropdown' name='instrument'>";
							echo "<option></option>";
								$room_query = mysqli_query($sql, "SELECT DISTINCT `instrument` FROM `$database`.`rooms` GROUP BY `instrument` ORDER BY `instrument` ASC");
								while ($row = mysqli_fetch_assoc($room_query))
								{
									$instrument = $row['instrument'];
									
									//Combine percussion instruments into "percussion"
									switch($instrument)
									{
										case 'Mallets': //Do nothing
											break;
										case 'Snare': //Do nothing
											break;
										case 'Timpani': echo "<option name='instrument' value='Percussion'>Percussion</option>";
											break;
										default:
											echo "<option name='instrument' value='$instrument'>$instrument</option>";
											break;
									}
								}
							echo "</select></span>";
					echo "<br />";
					echo "<input type='hidden' name='form_submit' value='form-submitted'>";
					echo "<input class='form-submit' type='submit' value='Submit'>";
				echo "</form>";
				echo "</div>";
			break;
	}
			
?>

<?php include_once('inc/footer.php'); ?>