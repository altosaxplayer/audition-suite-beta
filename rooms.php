<?php include_once('inc/settings.php'); ?>
<?php include_once('inc/header.php'); ?>

<div class="pg-title">Rooms</div>

<div class='inner-wrapper'>

<?php
//Determine rooms to show based on user assignments
switch($user_room_assign)
{
	case 'All':	
		//Fetch entries for administrator
		$room_query = mysqli_query($sql, "SELECT * FROM `$database`.`rooms` ORDER BY `id` ASC")  or die ('Error with room query');
		echo "<div class='form'>";
		
		$room_count = '1';
		while ($row = mysqli_fetch_assoc($room_query))
		{
			$id = $row['id'];
			$grade_level = $row['grade_level'];
			$instrument = $row['instrument'];
			
			//Alternate row colors
			if($room_count % 2 == '0')
			{
				$room_color_switch = '-gray';
			}
			else{
				$room_color_switch = '';
			}
			
			echo "<a href='score.php?room_id=$id' class='room-entry$room_color_switch'>$grade_level $instrument</a>";
			
			$room_count++;
		}
		echo "</div>";
		break;
	default:
		//Fetch entries for given director
		$room_query = mysqli_query($sql, "SELECT * FROM `$database`.`rooms` WHERE `id` IN ($user_room_assign) ORDER BY `id` ASC")  or die ('Error with room query');
		echo "<div class='form'>";
		while ($row = mysqli_fetch_assoc($room_query))
		{
			$id = $row['id'];
			$grade_level = $row['grade_level'];
			$instrument = $row['instrument'];

			echo "<a href='score.php?room_id=$id' class='room-entry'>$grade_level $instrument</a>";
		}
		echo "</div>";
		break;
}

?>

<?php include_once('inc/footer.php'); ?>