<?php include_once('inc/settings.php'); ?>
<?php include_once('inc/header.php'); ?>

<div class="pg-title">Changelog</div>

<div class="inner-wrapper">
<div class="form">
<?php
	//Fetch changelog entries
	$changelog_query = mysqli_query($sql, "SELECT * FROM `$database`.`changelog` ORDER BY 'id' DESC")  or die ('Error with changelog query');
	while ($row = mysqli_fetch_assoc($changelog_query))
	{
		$log_date = $row['log_date'];
		$log_type = $row['log_type'];
		$log_text = $row['log_text'];
		
		echo "<li><b>" . $log_date . "</b> - *" . $log_type . "*: " . $log_text . "</li>";
	}
?>
</div>
</div>

<?php include_once('inc/footer.php'); ?>