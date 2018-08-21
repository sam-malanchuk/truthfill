<?php 
session_start();
if (!$_SESSION['loggedIn'] && !isset($_COOKIE['loginhash'])) { 
	header("Location: login?l=delete");
} else {
	require('logincode.php');
	loginWith($_SESSION['user_id'], $_SESSION['loginString'], 'delete');

if(isset($_POST['submit'])) {
	if(isset($_POST['password'])) {
		$sqlvariable1 = crypt($_POST['password'], '5il1er');
		$conn = new Connection();
		$query = 'SELECT fullname FROM users WHERE password = ? AND user_id = ?';
		$stmt = $conn->mysqli->prepare($query);
		$stmt->bind_param('si', $sqlvariable1, $_SESSION['user_id']);
		$stmt->execute();
		$stmt->bind_result($fullname);
		while ($stmt->fetch())
		{
		}
		if(isset($fullname)) {
//			echo "<br>and is correct";
			// delete account now
			$query = 'DELETE FROM chats WHERE user_from=? OR user_to=?';
			$stmt = $conn->mysqli->prepare($query);
			$stmt->bind_param('ii', $_SESSION['user_id'], $_SESSION['user_id']);
			$stmt->execute();
			$query = 'DELETE FROM messages WHERE m_to=? OR m_from=?';
			$stmt = $conn->mysqli->prepare($query);
			$stmt->bind_param('ii', $_SESSION['user_id'], $_SESSION['user_id']);
			$stmt->execute();
			$query = 'DELETE FROM user_list WHERE user_id=?';
			$stmt = $conn->mysqli->prepare($query);
			$stmt->bind_param('i', $_SESSION['user_id']);
			$stmt->execute();
			$query = 'DELETE FROM likes WHERE user_id=?';
			$stmt = $conn->mysqli->prepare($query);
			$stmt->bind_param('i', $_SESSION['user_id']);
			$stmt->execute();
			$query = 'DELETE FROM users WHERE user_id=?';
			$stmt = $conn->mysqli->prepare($query);
			$stmt->bind_param('i', $_SESSION['user_id']);
			$stmt->execute();
			header("Location: deleted");
		} else {
//			echo "<br>but is incorrect";
			// redirect to incorrect password
			header("Location: delete?m=wrong");
		}
		$stmt->close();
		$conn->mysqli->close();
	}	
}

$title="Delete Account"; $thisPage="delete"; require('header.php'); 
?>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
						<?php if (isset($_GET['m'])) {
							  if ($_GET['m'] == 'wrong') { ?>
							<div class="card">
								<div class="alert alert-warning">
									<span>You entered an incorrect password. Please try again.</span>
								</div>
							</div>
						<?php }} ?>
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Delete Account</h4>
                            </div>
                            <div class="content">
							<p>To delete your account <b>FOREVER</b>, please confirm your password below and submit the form.</p>
                                <form action="delete" method="POST">
									<div class="form-group">
										<label>Password</label>
										<input type="password" name="password" class="form-control border-input" placeholder="Password" required autofocus>
									</div>
                                    <div class="text-center">
                                        <button type="submit" value="submit" name="submit" class="btn btn-info btn-fill btn-wd">Delete Forever</button>
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
