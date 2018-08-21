<?php 
session_start();
if (!$_SESSION['loggedIn'] && !isset($_COOKIE['loginhash'])) { 
	header("Location: login?l=change");
} else {
	require('logincode.php');
	loginWith($_SESSION['user_id'], $_SESSION['loginString'], 'change');
	
if(isset($_POST['submit'])){
	if($_POST['new_password'] === $_POST['new_password_2']){
		$sqlvariable1 = $_SESSION['user_id'];
		$conn = new Connection();
		$query = 'SELECT password, user_id FROM users WHERE user_id = ?';
		$stmt = $conn->mysqli->prepare($query);
		$stmt->bind_param('i', $sqlvariable1);
		$stmt->execute();
		$stmt->bind_result($result_password, $result_user_id);
		while ($stmt->fetch())
		{
			$result_password = $result_password;
		}
		$stmt->close();
		$conn->mysqli->close();
		if ((crypt($_POST['old_password'], '5il1er')) == $result_password) {
			$sqlvariable2 = crypt($_POST['new_password'], '5il1er');
			$sqlvariable3 = $_SESSION['user_id'];
			$conn = new Connection();
			$query = "UPDATE users SET password=? WHERE user_id=?";
			$stmt = $conn->mysqli->prepare($query);
			$stmt->bind_param('ss', $sqlvariable2, $sqlvariable3);
			$stmt->execute();
			$rows_updated = $stmt->affected_rows;
			if ($rows_updated !== 1) {
			   // it didn't
			   $passwordStatus = "update error";
			} else {
			   // it worked
				header("Location: dashboard?m=passwordupdate");
			}
			$stmt->close();
			$conn->mysqli->close();
		} else {
			$passwordStatus = "no same";
		}
	} else {
		$passwordStatus = "not match";
	}
}

$title="Change Password"; $thisPage="change"; require('header.php'); 
?>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
					<?php if (isset($passwordStatus)) {
						  if ($passwordStatus == 'all good') { ?>
                        <div class="card">
							<div class="alert alert-success">
								<span>It worked!</span>
							</div>
						</div>
					<?php } ?>
					<?php if ($passwordStatus == 'no same') { ?>
                        <div class="card">
							<div class="alert alert-warning">
								<span>The current password is not correct.</span>
							</div>
						</div>
					<?php } ?>
					<?php if ($passwordStatus == 'update error') { ?>
                        <div class="card">
							<div class="alert alert-danger">
								<span>There was an error updating your password.</span>
							</div>
						</div>
					<?php } ?>
					<?php if ($passwordStatus == 'not match') { ?>
                        <div class="card">
							<div class="alert alert-warning">
								<span>The new passwords do not match.</span>
							</div>
						</div>
					<?php }} ?>
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Change Password</h4>
                            </div>
                            <div class="content">
                                <form action="change" method="POST">
									<div class="form-group">
										<label>Current Password <span class="red-text">*</span></label>
										<input type="password" name="old_password" class="form-control border-input" input pattern=".{8,}" required title="8 characters minimum" placeholder="Password" required autofocus>
									</div>
									<div class="form-group">
										<label>New Password <span class="red-text">*</span></label>
										<input type="password" class="form-control border-input" name="new_password" input pattern=".{8,}" required title="8 characters minimum" placeholder="Password" required>
									</div>
									<div class="form-group">
										<label>Repeat New Password <span class="red-text">*</span></label>
										<input type="password" class="form-control border-input" name="new_password_2" input pattern=".{8,}" required title="8 characters minimum" placeholder="Password" required>
									</div>
                                    <div class="text-center">
										<p class="red-text">* 8 characters minimum</p>
                                        <button type="submit" value="submit" name="submit" class="btn btn-info btn-fill btn-wd">Reset</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php require('footer.php'); } ?>
