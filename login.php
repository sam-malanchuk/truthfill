<?php 
session_start();
if (isset($_SESSION['loggedIn'])) {
	$_SESSION = array();
	session_destroy();
	setcookie("loginhash", "", time() - 3600, "/");
}
if(isset($_POST['submit'])){
	function generateRandomString($length = 20) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = 'a';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
	}
	
	require('database_conn.php');
	$sqlvariable1 = $_POST['email'];
	$sqlvariable2 = crypt($_POST['password'], '5il1er');
	$conn = new Connection();
	$query = 'SELECT user_id, username, email, password, confirm, fullname FROM users WHERE email = ? && password = ?';
	$stmt = $conn->mysqli->prepare($query);
	$stmt->bind_param('ss', $sqlvariable1, $sqlvariable2);
	$stmt->execute();
	$stmt->bind_result($user_id, $username, $email, $password, $confirm, $fullname);
	while ($stmt->fetch())
	{
	   $_SESSION['loggedIn'] = true;
	   $_SESSION['user_id'] = $user_id;
	   $_SESSION['fullname'] = $fullname;
	   if($confirm === 0) {
			header("Location: login.php?m=a");
	   } else {
		   $_SESSION['loginString'] = generateRandomString();
			$sqlvariable1 = $_POST['email'];
			$conn = new Connection();
			$query = 'UPDATE users SET login_hash=? WHERE user_id=?';
			$stmt = $conn->mysqli->prepare($query);
			$stmt->bind_param('si', $_SESSION['loginString'], $user_id);
			if (!$stmt->execute()) {
//				echo "Something went wrong. Please contact the site administrator";
			}
			$stmt->close();
			$conn->mysqli->close();
			
			setcookie('loginhash', $_SESSION['loginString'], time() + (86400 * 30 * 7), "/"); // = 1 week
			
		   if(isset($_GET['l'])) {
				header("Location: " . $_GET['l']);
		   } else {
				header("Location: dashboard");
		   }
	   }
	}
   if($user_id === 0) {
	   if(isset($_GET['l'])) {
			header("Location: login.php?m=e&l=" . $_GET['l']);
	   } else {
			header("Location: login.php?m=e");
	   }
   }
	$stmt->close();
	$conn->mysqli->close();
}

$title="Login"; $thisPage="login"; require('header.php');
?>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
					<?php if (strpos($_GET['l'], 'user/') !== false) { ?>
                        <div class="card">
							<div class="alert alert-info">
								<span>Please login or register to Truthfill to leave feedback on your friends page.</span>
							</div>
						</div>
                        <div class="card">
							<div class="alert alert-warning">
								<span>Truthfill will never give out your identity but requires login for chatting functionalities.</span>
							</div>
						</div><?php } ?>
					<?php if ($_GET['m'] === 'e') { ?>
                        <div class="card">
							<div class="alert alert-warning">
								<span>The email or password you entered is incorrect, please try again.</span>
							</div>
					</div><?php } ?>
					<?php if ($_GET['m'] === 'a') { ?>
                        <div class="card">
							<div class="alert alert-warning">
								<span>Your account has not yet been activated. Please click the link in your email to activate your account.</span>
							</div>
					</div><?php } ?>
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Account Login</h4>
                            </div>
                            <div class="content">
                                <form action="login<?php if(isset($_GET['l'])) { echo "?l=" . $_GET['l']; } ?>" method="POST">
									<div class="form-group">
										<label>Email</label>
										<input type="email" id="emailInput" name="email" class="form-control border-input" placeholder="example@email.com" required autofocus>
									</div>
									<div class="form-group">
										<label>Password</label>
										<input type="password" class="form-control border-input" name="password" placeholder="Password" required>
									</div>
                                    <div class="text-center">
									<p><a href="register">Not Registered?</a> or <a class="pointer-mouse" onclick="passwordReset();">Forgot Password?</a></p>
                                        <button type="submit" value="submit" name="submit" class="btn btn-info btn-fill btn-wd">Login</button>
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
<script>
function passwordReset() {
	var emailInput = document.getElementById("emailInput").value;
	var form = $(document.createElement('form'));
    $(form).attr("action", "reset");
    $(form).attr("method", "POST");
    $(form).css("display", "none");

    var input_emailInput = $("<input>")
    .attr("type", "text")
    .attr("name", "employee_name")
    .val(emailInput);
    $(form).append($(input_emailInput));

    form.appendTo( document.body );
    $(form).submit();
}
</script>