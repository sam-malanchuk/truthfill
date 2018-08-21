<?php 
session_start();

if(isset($_GET['a'])) {
	$sqlvariable1 = $_GET['a'];
	require('database_conn.php');
	$conn = new Connection();
	$query = "SELECT confirm_id, user_id FROM users WHERE confirm_id=?";
	$stmt = $conn->mysqli->prepare($query);
	$stmt->bind_param('s', $sqlvariable1);
	$stmt->execute();
	$stmt->bind_result($confirm_id, $user_id);
	while ($stmt->fetch())
	{
	}
	$stmt->close();
	$conn->mysqli->close();

	if (isset($confirm_id)) {
		$keyStatus = true;
	} else {
		$keyStatus = false;
	}
} else {
		$keyStatus = false;
}

$title="Password Reset"; $thisPage="reset"; require('header.php');
?>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Password Reset</h4>
                            </div>
                            <div class="content">
							<?php if ($keyStatus) { ?>
								<p>Please enter your new account password below.</p>
                                <form action="reset" method="POST">
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
							<?php } else { ?>
								<p>This following password reset link is not valid.</p>
								<p>If you need to request a password reset click the link below.</p>
								<a class="btn btn-info btn-fill btn-wd" href="reset">Password Reset</a>
							<?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php require('footer.php'); ?>
