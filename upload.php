<?php include('inc/settings.php'); ?>
<?php include('inc/header.php'); ?>

<div class="pg-title">Scoresheet Upload</div>

<?php 
//Do upload if form is submitted
if(isset($_POST['submit']))
{
	$uid_to_filename_query = mysqli_query($sql, "SELECT * FROM `$database`.`students` WHERE `grade_level` = '$_GET[grade_level]' AND `instrument` = '$_GET[room]' AND `audition_num` = '$_REQUEST[audition_num]'")  or die ('Error with checkin query');
	while ($row = mysqli_fetch_assoc($uid_to_filename_query))
	{
		$id = $row['id'];
		$uid = $row['uid'];
	}
	
	if(!empty($_FILES['file_to_upload']))
	{
		$path = "files/";
		$path = $path . basename( $_FILES['file_to_upload']['name']);

		if(move_uploaded_file($_FILES['file_to_upload']['tmp_name'], $path . '.pdf'))
		{
			echo "<div class='form-success'>The file " . basename( $_FILES['file_to_upload']['name']) . " has been uploaded</div>";
		}
		else{
			echo "<div class='form-alert'>There was an error uploading the file. Please try again.</div>";
		}
	}

	//Update entry to reflect upload
	$upload_timestamp = date('F, Y (g:ia)');
	mysqli_query($sql, "UPDATE `$database`.`students` SET `file_upload_complete` = '$upload_timestamp' WHERE `id` = '$id'");
}
?>

<div class='inner-wrapper'>

<?php

//Fetch entries for junior level
$checkin_room_query = mysqli_query($sql, "SELECT * FROM `$database`.`rooms` ORDER BY 'id' DESC")  or die ('Error with checkin query');
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
		else{
			echo "<div class='form-title' id='$instrument'>" . $grade_level . " " . $instrument . "</div>";
			echo "<div class='form'>";

			//Fetch students for each room
			$fetch_room_students = mysqli_query($sql, "SELECT * FROM `$database`.`students` WHERE `room_id` = '$id' AND `audition_num` > '0' ORDER BY `audition_num` ASC")  or die ('Error with student room query');
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
						$uid = $row['uid'];
						$first_name = $row['first_name'];
						$last_name = $row['last_name'];
						$instrument = $row['instrument'];
						$school = $row['school'];
						$audition_num = $row['audition_num'];
						
						$filename = 'files/' . $uid . '.pdf';
						
						if(!file_exists($filename))
						{			
							//Alternate row colors
							if($student_count % 2 == '0')
							{
								$color_switch = '-gray';
							}
							else{
								$color_switch = '';
							}

							echo "<div class='checkin-entry$color_switch'>";
								echo "<span class='checkin-entry-name'><b>Audition #$audition_num</b></span>";
								echo "<span class='checkin-entry-info'>$instrument</span>";
								echo "<span class='checkin-entry-info'>&nbsp;</span>";
								echo "<span><form action='' method='post' enctype='multipart/form-data'><input type='file' name='file_to_upload' id='file_to_upload'><input class='form-submit-small' type='submit' value='Upload' name='submit'></span>";
							echo "</div>";
						}
						$student_count++;
					}
			}
		echo "</div>";
		}
	}

?>
</div>