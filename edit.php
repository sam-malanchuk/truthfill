<?php 
session_start();
if (!$_SESSION['loggedIn'] && !isset($_COOKIE['loginhash'])) { 
	header("Location: login?l=edit");
} else {
	require('logincode.php');
	loginWith($_SESSION['user_id'], $_SESSION['loginString'], 'edit');
	
if(isset($_POST['submit'])){
	if($_FILES['fileToUpload']['tmp_name'] !== '') {
	$target_dir = "avatars/";
	$target_file2 = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$target_file = $target_dir . "user_" . $_SESSION['user_id'] . "." . strtolower(pathinfo($target_file2,PATHINFO_EXTENSION));
	$target_file_thumb = $target_dir . "user_" . $_SESSION['user_id'] . "_thumb." . strtolower(pathinfo($target_file2,PATHINFO_EXTENSION));
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file2,PATHINFO_EXTENSION));
	// Check if image file is a actual image or fake image
	if(isset($_POST["submit"])) {
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		if($check !== false) {
			$uploadOk = 1;
		} else {
			$uploadFail = "File is not an image.";
			$uploadOk = 0;
		}
	}
	// Check if file already exists - doesn't matter, we want the user to overwrite
//	if (file_exists($target_file)) {
//		$uploadFail = "Sorry, file already exists.";
//		$uploadOk = 0;
//	}
	// Check file size
	if ($_FILES["fileToUpload"]["size"] > 5000000) {
		$uploadFail = "Sorry, your file is too large.";
		$uploadOk = 0;
	}
	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ) {
		$uploadFail = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		$uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		$uploadFail .= " Your file was not uploaded.";
		$imageFileType = "";
	// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			//	echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
			require('create_square_image.php');
			correctImageOrientation($target_file);
			create_square_image($target_file, $target_file_thumb, 200);
			$uploadOk = 1;
		} else {
			echo "Sorry, there was an error uploading your file.";
		}
	}
		$uploadNotTry = false;
	} else {
		$uploadNotTry = true;
	}
	$sqlvariable2 = $_POST['username'];
	$conn = new Connection();
	$query = 'SELECT username, user_id, profile_pic FROM users WHERE username = ?';
	$stmt = $conn->mysqli->prepare($query);
	$stmt->bind_param('s', $sqlvariable2);
	$stmt->execute();
	$stmt->bind_result($users_name, $users_id, $profile_pic);
	while ($stmt->fetch())
	{
		if($users_id == $_SESSION['user_id']){
			$usernameTaken = false;
		} else {
			$usernameTaken = true;
		}
	}
	$stmt->close();
	$conn->mysqli->close();
	if(!$usernameTaken) {
		$sqlvariable1 = $_POST['fullname'];
		$sqlvariable3 = $_POST['aboutme'];
		$sqlvariable4 = $_SESSION['user_id'];
		$conn = new Connection();
		if($uploadOk == 0) {
			$query = "UPDATE users SET fullname=?, username=?, aboutme=? WHERE user_id=?";
			$stmt = $conn->mysqli->prepare($query);
			$stmt->bind_param('sssi', $sqlvariable1, $sqlvariable2, $sqlvariable3, $sqlvariable4);
		} else {
			$query = "UPDATE users SET fullname=?, username=?, aboutme=?, profile_pic=? WHERE user_id=?";
			$stmt = $conn->mysqli->prepare($query);
			$stmt->bind_param('ssssi', $sqlvariable1, $sqlvariable2, $sqlvariable3, $imageFileType, $sqlvariable4);
		}
		$stmt->execute();
		$rows_updated = $stmt->affected_rows;
		if ($rows_updated !== 1) {
		   // it didn't
			$infoUpdated = false;
		} else {
		   // it worked
			$infoUpdated = true;
			$_SESSION['fullname'] = $sqlvariable1;
			if($uploadOk == 1 || $uploadNotTry) {
				header("Location: dashboard");
			}
		}
		$stmt->close();
		$conn->mysqli->close();
	}
} else {
	$sqlvariable1 = $_SESSION['user_id'];
	$conn = new Connection();
	$query = 'SELECT fullname, username, aboutme, email, profile_pic FROM users WHERE user_id = ?';
	$stmt = $conn->mysqli->prepare($query);
	$stmt->bind_param('i', $sqlvariable1);
	$stmt->execute();
	$stmt->bind_result($fullname, $username, $aboutme, $useremail, $profile_pic);
	while ($stmt->fetch())
	{
		
	}
	$stmt->close();
	$conn->mysqli->close();
}

$title="Edit Profile"; $thisPage="profile"; require('header.php'); 

if($usernameTaken) {
?>
<style>
#usernameTaken {
	border-color: red;
}
</style>
<?php } ?>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
							<?php if (isset($uploadFail)) { ?>
						<div class="card">
							<div class="alert alert-danger">
								<span><?php echo $uploadFail; ?></span>
							</div>
						</div>
							<?php } ?>
							<?php if ($usernameTaken) { ?>
						<div class="card">
							<div class="alert alert-warning">
								<span>The username is already taken, please try a different one.</span>
							</div>
						</div>
							<?php } ?>
							<?php if ($_GET['m'] == 'first') { ?>
						<div class="card">
							<div class="alert alert-info">
								<span>You need to complete this page before you can access your account.</span>
							</div>
						</div>
							<?php } ?>
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Edit Profile</h4>
                            </div>
                            <div class="content">
                                <form action="edit" method="POST" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-6">
											<div class="author text-center">
											  <img class="avatar avatar-edit" id="avatar" name="avatar" src="avatars/user_<?php if(file_exists("avatars/user_" . $_SESSION['user_id'] . "_thumb." . $profile_pic)) { echo $_SESSION['user_id'] . "_thumb." . $profile_pic; } else { echo "default.jpg"; } ?>" alt="Profile Image"/>
											</div>
										</div>
                                        <div class="col-md-6">
											<div class="form-group">
												<input type="file" class="form-control-file" name="fileToUpload">
												<small id="fileHelp" class="form-text">Please choose an avatar image.</small>
											</div>
                                            <div class="form-group">
                                                <label>Username<span class="red-text">*</span></label>
                                                <input type="text" class="form-control border-input" placeholder="Username" name="username" id="usernameTaken" <?php if(isset($username)) { echo 'value="' . $username . '"'; } ?><?php if(isset($_POST['username'])) { echo 'value="' . $_POST['username'] . '"'; } ?> required>
                                            </div>
										</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Full Name<span class="red-text">*</span></label>
                                                <input type="text" class="form-control border-input" placeholder="Full Name" name="fullname" <?php if(isset($_POST['fullname'])) { echo 'value="' . $_POST['fullname'] . '"'; } ?><?php if(isset($fullname)) { echo 'value="' . $fullname . '"'; } ?> required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Email address<span class="red-text">*</span></label>
                                                <input type="email" class="form-control border-input" placeholder="Email" name="email" <?php if(isset($useremail)) { echo 'value="' . $useremail . '"'; } ?> disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>About Me<span class="red-text">*</span></label>
                                                <textarea rows="5" class="form-control border-input" placeholder="Add a little description of yourself :)" name="aboutme" required><?php if(isset($aboutme)) { echo $aboutme; } ?><?php if(isset($_POST['aboutme'])) { echo $_POST['aboutme']; } ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
									<p class="red-text">* Required Fields</p>
                                        <button type="submit" value="submit" name="submit" class="btn btn-info btn-fill btn-wd">Update Profile</button>
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
