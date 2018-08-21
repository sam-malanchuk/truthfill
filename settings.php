<?php 
session_start();
if (!$_SESSION['loggedIn'] && !isset($_COOKIE['loginhash'])) { 
	header("Location: login?l=settings");
} else {
	require('logincode.php');
	loginWith($_SESSION['user_id'], $_SESSION['loginString'], 'settings');

	$conn = new Connection();
	$query = 'SELECT public_on FROM users WHERE user_id=?';
	$stmt = $conn->mysqli->prepare($query);
	$stmt->bind_param('i', $_SESSION['user_id']);
	$stmt->execute();
	$stmt->bind_result($public_on);
	while ($stmt->fetch())
	{
//		echo $public_on;
	}
	$stmt->close();
	$conn->mysqli->close();
	
if(isset($_POST['submit'])) {
	if(isset($_POST['public_on'])) {
		$new_public_on = 1;
	} else {
		$new_public_on = 0;
	}
	if($new_public_on == $public_on) {
	//	echo "it did not change";
		$updateWorked = "nothing";
	} else {
	//	echo "it changed";
		$conn = new Connection();
		$query = "UPDATE users SET public_on= CASE WHEN public_on=0 THEN 1 ElSE 0 END WHERE user_id=?";
		$stmt = $conn->mysqli->prepare($query);
		$stmt->bind_param('i', $_SESSION['user_id']);
		$stmt->execute();
		$rows_updated = $stmt->affected_rows;
		if ($rows_updated !== 1) {
		   // it didn't
		   $updateWorked = "wrong";
		} else {
		   // it worked
		   $updateWorked = "saved";
		}
		$stmt->close();
		$conn->mysqli->close();
		$public_on = $new_public_on;
	}
}

	
$title="Account Settings"; $thisPage="settings"; require('header.php'); 
?>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
						<?php if (isset($updateWorked)) { ?>
							  <?php if ($updateWorked == "saved") { ?>
							<div class="card">
								<div class="alert alert-success">
									<span>Your changes have been saved.</span>
								</div>
							</div>
						<?php } ?>
							  <?php if ($updateWorked == "nothing") { ?>
							<div class="card">
								<div class="alert alert-info">
									<span>You have not made any changes.</span>
								</div>
							</div>
						<?php } ?>
							  <?php if ($updateWorked == "wrong") { ?>
							<div class="card">
								<div class="alert alert-error">
									<span>There was an error making changes.</span>
								</div>
							</div>
						<?php } ?>
						<?php } ?>
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Account Settings</h4>
                            </div>
                            <div class="content">
                                <form action="settings" method="POST">
								<p>Allow logged in users to view public comments sent to you on your profile page?</p>
								<label class="switch">
									<input type="checkbox" name="public_on" value="1" <?php if(isset($public_on)) { if($public_on == 1) { echo "checked"; }} ?>>
									<span class="slider round"></span>
								</label>
                                    <div class="text-center">
                                        <button type="submit" value="submit" name="submit" class="btn btn-info btn-fill btn-wd">Submit Changes</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php require('footer.php'); }?>
	<script type="text/javascript">
	<?php if (isset($_GET['m'])) { if ($_GET['m'] == 'passwordupdate') { ?>
    	$(document).ready(function(){
			$.notify({
				icon: 'ti-key',
				message: "Your password has been successfully updated!"

			},{
				type: 'success',
				timer: 1000
			});
    	});
	<?php }} ?>
		
		function copyTextFunction(theText) {
			Clipboard.copy(theText);
			document.getElementById('copyButton').innerHTML = "Link Copied!";
			setTimeout(function(){ 
				document.getElementById("copyButton").innerHTML = "Copy Link";
			}, 2500);
		}
</script>
