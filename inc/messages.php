<?php include_once('settings.php'); ?>

<?php
//Insert new message if needed
if(isset($_POST['message_body']))
{
	mysqli_query($sql, "INSERT INTO `$database`.`messages` (`id`, `timestamp`, `sender_id`, `recipient_id`, `message_body`) VALUES (NULL, CURRENT_TIMESTAMP, '$_POST[user_id]', '1', '$_POST[message_body]')") or die ('Error with message insertion query');
}

//Display messages
$messages_query = mysqli_query($sql, "SELECT * FROM `$database`.`messages` WHERE `sender_id` = '$_GET[user_id]' OR `recipient_id` = '$_GET[user_id]' ORDER BY `timestamp` ASC")  or die ('Error with messages query');
while ($row = mysqli_fetch_assoc($messages_query))
{
	$timestamp = $row['timestamp'];
	$sender_id = $row['sender_id'];
	$recipient_id = $row['recipient_status'];
	$message_body = $row['message_body'];
	
	//Convert timestamp
	$timestamp = strtotime($timestamp);
	$timestamp = date('F j, Y (g:ia)', $timestamp); 
	
	if($sender_id == $_GET['user_id'])
	{
		echo "<div class='message-bubble-wrapper'>";
			echo "<div class='message-timestamp'>$timestamp</div>";
			echo "<div class='message-bubble-sender'>$message_body</div>";
		echo "</div>";
	}
	else{
		echo "<div class='message-bubble-wrapper'>";
			echo "<div class='message-bubble-recipient'>$message_body</div>";
			echo "<div class='message-timestamp'>$timestamp</div>";
		echo "</div>";
	}
}

?>