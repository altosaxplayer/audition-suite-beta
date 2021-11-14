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
			$("#message-inbox").load("inc/functions.php?id=6")
			}, 5000);
	});
</script>

<div class="pg-title">Messages</div>
<div class='inner-wrapper'>
<div class='form'>
	<div id='message-inbox'></div>
</div>

<?php include_once('inc/footer.php'); ?>