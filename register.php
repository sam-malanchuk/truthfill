<?php 
date_default_timezone_set("America/New_York");
if(isset($_POST['submit'])){
	if(isset($_POST['agreement'])) {
	require('database_conn.php');
	$sqlvariable1 = $_POST['email'];
	$conn = new Connection();
	$query = 'SELECT email, confirm FROM users WHERE email = ?';
	$stmt = $conn->mysqli->prepare($query);
	$stmt->bind_param('s', $sqlvariable1);
	$stmt->execute();
	$stmt->bind_result($email, $confirm);
	while ($stmt->fetch())
	{
	   $emailTaken = true;
	}
	$stmt->close();
	$conn->mysqli->close();
	if (!$emailTaken) {
	$sqlvariable1 = $_POST['email'];
	$sqlvariable2 = crypt($_POST['password'], '5il1er');
	$sqlvariable3 = date("Y-m-d h:i:sa");
	$conn = new Connection();
	$query = 'INSERT INTO users (email, password, creation_date) VALUES (?, ?, ?)';
	$stmt = $conn->mysqli->prepare($query);
	$stmt->bind_param('sss', $sqlvariable1, $sqlvariable2, $sqlvariable3);
	if (!$stmt->execute()) {
//		echo "Something went wrong. Please contact the site administrator";
	} else {
		$registrationGood = true;
		session_start();
		$_SESSION["email"] = $_POST["email"];
		$_SESSION["user_id"] = $stmt->insert_id;
		$_SESSION["justRegistered"] = true;
		header("Location: verify");
	}
	$stmt->close();
	$conn->mysqli->close();
	}
	} else {
		// not agree
	}
}

$title="Register"; $thisPage="register"; require('header.php');
?>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
					<?php if ($emailTaken) { ?>
                        <div class="card">
							<div class="alert alert-warning">
										<span>Sorry the email <?php echo $_POST['email']; ?> is already in use. Please try a different one. If you are the account holder please click the verification link in your email.</span>
									</div>
					</div><?php } ?>
					<?php if (isset($_POST['submit'])) { if(!isset($_POST['agreement'])) {?>
                        <div class="card">
							<div class="alert alert-warning">
										<span>You have not agreed to the terms and conditions.</span>
									</div>
					</div><?php }} ?>
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Account Creation</h4>
                            </div>
                            <div class="content">
                                <form action="register" method="POST">
									<div class="form-group">
										<label>Email</label>
										<input type="email" name="email" class="form-control border-input" placeholder="example@email.com" required>
									</div>
									<div class="form-group">
										<label>Password <span class="red-text">*</span></label>
										<input type="password" class="form-control border-input" name="password" input pattern=".{8,}" required title="8 characters minimum" placeholder="Password" required>
									</div>
									<div class="form-check">
										<input type="checkbox" class="form-check-input" name="agreement" value="true" required>
										<label class="form-check-label">
											I have read and agree to the <a target="_blank" href="terms">Terms and Conditions</a>
										</label>
									</div>
                                    <div class="text-center">
									<p class="red-text">* 8 characters minimum</p>
									<a href="login"><p>Already Registered? Login.</p></a>
                                        <button type="submit" value="submit" name="submit" class="btn btn-info btn-fill btn-wd">Create my Account!</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php require('footer.php'); ?>
