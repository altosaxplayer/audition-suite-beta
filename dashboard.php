<?php include_once('inc/settings.php'); ?>
<?php include_once('inc/header.php'); ?>

<?php
//Create page-specific functions

if(isset($_GET['user']))
{
	$user_function = $_GET['user'];
	
	switch($user_function)
	{
		case 'add':
			echo "<div class='pg-title'>Add User</div>";
			echo "<div class='inner-wrapper'>";
				echo "<div class='form'>";
					echo "<form action='dashboard.php?user=insert' method='post'>";
						echo "<div class='form-input-label'>Username</div>";
							echo "<span style='display: block; text-align: center'><input class='form-input-box' type='text' name='username'></span>";
						echo "<div class='form-input-label'>Name</div>";
							echo "<span style='display: block; text-align: center'><input class='form-input-box' type='text' name='name'></span>";
						echo "<div class='form-input-label'>School</div>";
							echo "<span style='display: block; text-align: center'><input class='form-input-box' type='text' name='school'></span>";
						echo "<div class='form-input-label'>Email</div>";
							echo "<span style='display: block; text-align: center'><input class='form-input-box' type='text' name='email'></span>";
						echo "<div class='form-input-label'>Role</div>";
							echo "<span style='display: block; text-align: center'><input class='form-input-box' type='text' name='role'></span>";
						echo "<div class='form-input-label'>Assignment</div>";
							echo "<span style='display: block; text-align: center'><input class='form-input-box' type='text' name='assignment'></span>";
						echo "<input type='hidden' name='form_submit' value='form-submitted'>";
						echo "<input class='form-submit' type='submit' value='Submit'>";
					echo "</form>";
				echo "</div>";
			echo "</div>";
			break;
		case 'insert':
			//Get variables passed via POST
			$username = $_POST['username'];
			$name = $_POST['name'];
			$school = $_POST['school'];
			$role = $_POST['role'];
			$assignemnt = $_POST['assignemnt'];
			
			//Randomly generate new password		
				//Check to make sure the UID doesn't already exist
				$password_loop = '0';
				while($uid_loop == '0')
				{
					$password = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 8);

					$password_query = mysqli_query($sql, "SELECT COUNT(`password`) FROM `$database`.`users` WHERE `password` = '$password'") or die(mysqli_error($sql));
	
					if($password_query == '1')
					{
						$password_loop = '0';
					}
					else{
						$password_loop = '1';
					}
				}
			
			//Execute insert query
			mysqli_query($sql, "INSERT INTO `users` (`id`,`username`,`password`,`email`,`role`,`name`,`school`,`assignment`) VALUES (NULL,'$username','$password','$email','$name','$school','$role','$assignment')") or die(mysqli_error($sql));
			
			//Display success message
			echo "<br /><div class='form-success'>$name has been added</div>";
			break;
		default: // do nothing
			break;
	}
}

?>

<div class='pg-title'>Welcome, <?php echo $user_name; ?>!</div>
<div class='dsh-wrapper'>
	<div class='dsh-box'>
		<span style='display: block; font-weight: 400; -webkit-animation: fadein 1s;'>
			<!-- Reload AJAX -->
			<script>
				$(document).ready(function(){
				setInterval(function(){
				$("#preregistrations").load('inc/functions.php?id=2')
				}, 30000);
				});
			</script>
	
			<!-- OnLoad Content -->
			<div id="preregistrations">
				<?php
					$function_2_query = mysqli_query($sql, "SELECT * FROM `$database`.`students`");
					$function_2_query_num = mysqli_num_rows($function_2_query);
					echo $function_2_query_num;
				?>
			</div>
		</span>
		<span style='display: block; font-size: 12pt;'>Pre-Registered</span>
	</div>
	<div class='dsh-box'>
		<span style='display: block; font-weight: 400; -webkit-animation: fadein 1s;'>
			<!-- Reload AJAX -->
			<script>
				$(document).ready(function(){
				setInterval(function(){
				$("#checkedin").load('inc/functions.php?id=3')
				}, 30000);
				});
			</script>
	
			<!-- OnLoad Content -->
			<div id="checkedin">
				<?php
					$function_3_query = mysqli_query($sql, "SELECT * FROM `$database`.`students` WHERE `audition_num` > 0");
					$function_3_query_num = mysqli_num_rows($function_3_query);
					echo $function_3_query_num;
				?>
			</div>
		</span>
		<span style='display: block; font-size: 12pt;'>Checked-In</span>
	</div>
	<div class='dsh-box'>
		<span style='display: block; font-weight: 400; -webkit-animation: fadein 1s;'>
			<!-- Reload AJAX -->
			<script>
				$(document).ready(function(){
				setInterval(function(){
				$("#scored").load('inc/functions.php?id=4')
				}, 30000);
				});
			</script>
	
			<!-- OnLoad Content -->
			<div id="scored">
				<?php
					$function_4_query = mysqli_query($sql, "SELECT * FROM `$database`.`students` WHERE `adj_1_wind_scale` IS NOT NULL AND`adj_1_wind_chrom` IS NOT NULL AND `adj_1_wind_sr` IS NOT NULL")  or die ('Error with Function ' . $function_id);
					$function_4_query_num = mysqli_num_rows($function_4_query);
					echo $function_4_query_num;
				?>
			</div>
		</span>
		<span style='display: block; font-size: 12pt;'>Scored</span>
	</div>
</div>

<div class='pg-title'>Reports</div>
<div class='dsh-wrapper'>
	<a href='export/print-jr-results.php' class='dsh-button-box'>Print Junior Results</a>
	<a href='export/print-sr-results.php' class='dsh-button-box'>Print Senior Results</a>
	<div class='dsh-button-box'>School Registrations</div>
	<div class='dsh-button-box'>Instrument Registrations</div>
</div>
<div class='dsh-wrapper'>
	<a href='upload.php' class='dsh-button-box'>Upload Scoresheets</a>
	<div class='dsh-button-box'>School Check-Ins</div>
	<div class='dsh-button-box'>Instrument Check-Ins</div>
	<div class='dsh-button-box'>Run Diagnostics</div>
</div>

<div class='pg-title'>Completion Reports</div>
<div class='dsh-wrapper'>
	<!-- Reload AJAX -->
	<script>
		$(document).ready(function(){
		setInterval(function(){
		$("#completion_reports").load('inc/functions.php?id=1')
		}, 3000);
		});
	</script>
	
	<!-- Content -->
	<?php
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
		?>
</div>

<div class='pg-title'>User Management</div>
<div class='form'>
	<?php 
		//Fetch students for each room
		$fetch_users = mysqli_query($sql, "SELECT * FROM `$database`.`users` ORDER BY `role`,`username` ASC")  or die ('Error with user query');
		$fetch_users_num = mysqli_num_rows($fetch_users);
		
		//Display header before content
		echo "<div class='checkin-entry'>";
			echo "<span class='checkin-entry-info' style='width: 15%; font-weight: bold;'>Role</span>";
			echo "<span class='checkin-entry-info' style='width: 15%; font-weight: bold;'>Name</span>";
			echo "<span class='checkin-entry-info' style='width: 15%; font-weight: bold;'>Username</span>";
			echo "<span class='checkin-entry-info' style='width: 20%; font-weight: bold;'>School</span>";
			echo "<span class='checkin-entry-info' style='width: 31%; font-weight: bold;'>Options</span>";
		echo "</div>";

		switch($fetch_users_num)
		{
			case '0':
				echo "No users to show";
				break;
			default:
				$student_count = '0';
				while ($row = mysqli_fetch_assoc($fetch_users))
				{
					$id = $row['id'];
					$role = $row['role'];
					$name = $row['name'];
					$username = $row['username'];
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
						echo "<span class='checkin-entry-info' style='width: 15%;'>$role</span>";
						echo "<span class='checkin-entry-info' style='width: 15%;'>$name</span>";
						echo "<span class='checkin-entry-info' style='width: 15%;'>$username</span>";
						echo "<span class='checkin-entry-info' style='width: 20%;'>$school</span>";
						echo "<a href='dashboard.php?user=edit' class='checkin-entry-checkmark'>edit user</a>";
						echo "<a href='dashboard.php?user=role' class='checkin-entry-scores'>change role</a>";
						echo "<a href='dashboard.php?user=remove' class='checkin-entry-remove'>remove user</a>";
					echo "</div>";
			
					$student_count++;
				}
		}
		//Display add user button
		echo "<a class='form-submit' href='dashboard.php?user=add'>Add User</a>";
		
		echo "</div>";
	?>



<div class='pg-title'>Toggles</div>	
	<?php
	//Results Posted Toggle
		//Change toggle status if necessary
		if(isset($_GET['resultpostedtoggle']))
		{
			mysqli_query($sql, "UPDATE `$database`.`settings` SET `setting_value` = '$_GET[resultpostedtoggle]' WHERE `setting_name` = 'Results Posted Status'") or die ('Error with toggle');
		}
		
		//Fetch button status
		$result_posted_toggle_query = mysqli_query($sql, "SELECT * FROM `$database`.`settings` WHERE `setting_name` = 'Results Posted Status'") or die ('Error with toggle');
		while ($row = mysqli_fetch_assoc($result_posted_toggle_query))
		{
			$result_posted_toggle_status = $row['setting_value'];
			
			switch($result_posted_toggle_status)
			{
				case 'no':
					echo "<a href='?resultpostedtoggle=yes' class='dsh-toggle'>";
						echo "<span class='dsh-cmp-rpt-light'><span class='dsh-cmp-rpt-box-light-red'>NO</span>";
						echo "<span class='dsh-toggle-info'>Results Posted</span>";
					echo "</a>&nbsp;";
					break;
				case 'yes':
					echo "<a href='?resultpostedtoggle=no' class='dsh-toggle'>";
						echo "<span class='dsh-cmp-rpt-light'><span class='dsh-cmp-rpt-box-light-green'>YES</span>";
						echo "<span class='dsh-toggle-info'>Results Posted
						</span>";
					echo "</a>&nbsp;";
					break;
				default:
				break;
			}
		}
		
	//Results Official/Unofficial Toggle
		//Change toggle status if necessary
		if(isset($_GET['resulttoggle']))
		{
			mysqli_query($sql, "UPDATE `$database`.`settings` SET `setting_value` = '$_GET[resulttoggle]' WHERE `setting_name` = 'Results Type'") or die ('Error with toggle');
		}
		
		//Fetch button status
		$result_type_toggle_query = mysqli_query($sql, "SELECT * FROM `$database`.`settings` WHERE `setting_name` = 'Results Type'") or die ('Error with toggle');
		while ($row = mysqli_fetch_assoc($result_type_toggle_query))
		{
			$result_type_toggle_status = $row['setting_value'];
			
			switch($result_type_toggle_status)
			{
				case 'Unofficial':
					echo "<a href='?resulttoggle=Official' class='dsh-toggle'>";
						echo "<span class='dsh-cmp-rpt-light'><span class='dsh-cmp-rpt-box-light-red'>NO</span>";
						echo "<span class='dsh-toggle-info'>Results Official</span>";
					echo "</a>&nbsp;";
					break;
				case 'Official':
					echo "<a href='?resulttoggle=Unofficial' class='dsh-toggle'>";
						echo "<span class='dsh-cmp-rpt-light'><span class='dsh-cmp-rpt-box-light-green'>YES</span>";
						echo "<span class='dsh-toggle-info'>Results Official</span>";
					echo "</a>&nbsp;";
					break;
			}
		}
		
	//Prepared Piece Status Toggle
		//Change toggle status if necessary
		if(isset($_GET['preparedtoggle']))
		{
			mysqli_query($sql, "UPDATE `$database`.`settings` SET `setting_value` = '$_GET[preparedtoggle]' WHERE `setting_name` = 'Prepared Piece Status'") or die ('Error with toggle');
		}
		
		//Fetch button status
		$prepared_toggle_query = mysqli_query($sql, "SELECT * FROM `$database`.`settings` WHERE `setting_name` = 'Prepared Piece Status'") or die ('Error with toggle');
		while ($row = mysqli_fetch_assoc($prepared_toggle_query))
		{
			$prepared_toggle_status = $row['setting_value'];
			
			switch($prepared_toggle_status)
			{
				case 'no':
					echo "<a href='?preparedtoggle=yes' class='dsh-toggle'>";
						echo "<span class='dsh-cmp-rpt-light'><span class='dsh-cmp-rpt-box-light-red'>NO</span>";
						echo "<span class='dsh-toggle-info'>Prepared Piece</span>";
					echo "</a>&nbsp;";
					break;
				case 'yes':
					echo "<a href='?preparedtoggle=no' class='dsh-toggle'>";
						echo "<span class='dsh-cmp-rpt-light'><span class='dsh-cmp-rpt-box-light-green'>YES</span>";
						echo "<span class='dsh-toggle-info'>Prepared Piece</span>";
					echo "</a>&nbsp;";
					break;
			}
		}
		
	//Adjudicator Number Toggle
		//Change toggle status if necessary
		if(isset($_GET['adjnumtoggle']))
		{
			mysqli_query($sql, "UPDATE `$database`.`settings` SET `setting_value` = '$_GET[adjnumtoggle]' WHERE `setting_name` = 'Adjudicator Number'") or die ('Error with toggle');
		}
		
		//Fetch button status
		$adjnum_toggle_query = mysqli_query($sql, "SELECT * FROM `$database`.`settings` WHERE `setting_name` = 'Adjudicator Number'") or die ('Error with toggle');
		while ($row = mysqli_fetch_assoc($adjnum_toggle_query))
		{
			$adjnum_toggle_status = $row['setting_value'];
			
			switch($adjnum_toggle_status)
			{
				case '1':
					echo "<a href='?adjnumtoggle=2' class='dsh-toggle'>";
						echo "<span class='dsh-cmp-rpt-light'><span class='dsh-cmp-rpt-box-light-green'>1</span>";
						echo "<span class='dsh-toggle-info'>Adjudicator Number</span>";
					echo "</a>&nbsp;";
					break;
				case '2':
					echo "<a href='?adjnumtoggle=1' class='dsh-toggle'>";
						echo "<span class='dsh-cmp-rpt-light'><span class='dsh-cmp-rpt-box-light-green'>2</span>";
						echo "<span class='dsh-toggle-info'>Adjudicator Number</span>";
					echo "</a>&nbsp;";
					break;
			}
		}
		
	//Chron Status Toggle
		//Change toggle status if necessary
		if(isset($_GET['chrontoggle']))
		{
			mysqli_query($sql, "UPDATE `$database`.`settings` SET `setting_value` = '$_GET[chrontoggle]' WHERE `setting_name` = 'Chron Status'") or die ('Error with toggle');
		}
		
		//Fetch button status
		$chron_toggle_query = mysqli_query($sql, "SELECT * FROM `$database`.`settings` WHERE `setting_name` = 'Chron Status'") or die ('Error with toggle');
		while ($row = mysqli_fetch_assoc($chron_toggle_query))
		{
			$chron_toggle_status = $row['setting_value'];
			
			switch($chron_toggle_status)
			{
				case 'no':
					echo "<a href='?chrontoggle=yes' class='dsh-toggle'>";
						echo "<span class='dsh-cmp-rpt-light'><span class='dsh-cmp-rpt-box-light-red'>NO</span>";
						echo "<span class='dsh-toggle-info'>Chron Status</span>";
					echo "</a>&nbsp;";
					break;
				case 'yes':
					echo "<a href='?chrontoggle=no' class='dsh-toggle'>";
						echo "<span class='dsh-cmp-rpt-light'><span class='dsh-cmp-rpt-box-light-green'>YES</span>";
						echo "<span class='dsh-toggle-info'>Chron Status</span>";
					echo "</a>&nbsp;";
					break;
			}
		}
?>
</div>

<?php include_once('inc/footer.php'); ?>