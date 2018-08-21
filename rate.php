<?php 
include 'config.php';
session_start();
if (!$_SESSION['loggedIn'] && !isset($_COOKIE['loginhash'])) { 
	header("Location: " . $dir_path . "/login?l=rate/" . $_GET['u']);
} else {
	require('logincode.php');
	loginWith($_SESSION['user_id'], $_SESSION['loginString'], 'rate');

	if(isset($_GET['u'])){
	$viewusername = $_GET['u'];
	$conn = new Connection();
	$query = 'SELECT fullname, aboutme, profile_pic, user_id FROM users WHERE username = ?';
	$stmt = $conn->mysqli->prepare($query);
	$stmt->bind_param('s', $viewusername);
	$stmt->execute();
	$stmt->bind_result($viewfullname, $viewaboutme, $viewpic, $viewuser_id);
	while ($stmt->fetch())
	{
		$_SESSION['viewuser_id'] = $viewuser_id;
	}
	$stmt->close();
	$conn->mysqli->close();
}

	$rating = 0;
	$conn = new Connection();
	$query = 'SELECT user_id, rated_by, rating FROM ratings WHERE rated_by = ? AND user_id = ?';
	$stmt = $conn->mysqli->prepare($query);
	$stmt->bind_param('ii', $_SESSION['user_id'], $viewuser_id);
	$stmt->execute();
	$stmt->bind_result($rate_user_id, $rated_by, $rating);
	while ($stmt->fetch())
	{
//		echo "Rating For: " . $rate_user_id . "<br>";
//		echo "Rated By: " . $rated_by . "<br>";
//		echo "Rating: " . $rating . "<br>";
	}
	$stmt->close();
	$conn->mysqli->close();

if($rating != 0) {
	$firstTime = false;
} else {
	$firstTime = true;
}

if($viewfullname == "") {
	$noUser = true;
} else {
	$noUser = false;
}

	if (!empty($_POST)) {
		if (!empty($_POST['starRating'])) {
			if (!$firstTime) {
				if($_POST['starRating'] == $rating) {
				//	echo "you are not making any changes to your rating";
					$_SESSION['update_message'] = "nochange";
					header("Location: " . $dir_path . "/user/" . $_GET['u']);
				} else {
				//	echo "You have already rated, but it's ok.";
					$conn = new Connection();
					$query = "UPDATE ratings SET rating=? WHERE user_id=? AND rated_by=?";
					$stmt = $conn->mysqli->prepare($query);
					$stmt->bind_param('iii', $_POST['starRating'], $_POST['viewuser_id'], $_SESSION['user_id']);
					$stmt->execute();
					$rows_updated = $stmt->affected_rows;
					if ($rows_updated !== 1) {
					   // it didn't
					//   echo "<br> There was an error.";
					$_SESSION['update_message'] = "error";
					header("Location: " . $dir_path . "/user/" . $_GET['u']);
					} else {
					   // it worked
					 //  echo "<br> Rating Updated.";
					$_SESSION['update_message'] = "updatedone";
					header("Location: " . $dir_path . "/user/" . $_GET['u']);
					}
					$stmt->close();
					$conn->mysqli->close();
				}
			} else {
			//	echo $_POST['starRating'] . "<br>";
			//	echo $_POST['viewuser_id'];
				
				$conn = new Connection();
				$query = 'INSERT INTO ratings (user_id, rated_by, rating) VALUES (?, ?, ?)';
				$stmt = $conn->mysqli->prepare($query);
				$stmt->bind_param('iii', $_POST['viewuser_id'], $_SESSION['user_id'], $_POST['starRating']);
				if (!$stmt->execute()) {
				//	echo "<br>";
				//	echo "Something went wrong. Please contact the site administrator";
					$_SESSION['update_message'] = "error";
					header("Location: " . $dir_path . "/user/" . $_GET['u']);
				} else {
				//	echo "<br>";
				//	echo "All went good!";
					$_SESSION['update_message'] = "newdone";
					header("Location: " . $dir_path . "/user/" . $_GET['u']);
				}
				$stmt->close();
				$conn->mysqli->close();

			}
		}
	}
	
	$title="Rate User"; $thisPage="rate"; require('header.php'); 
?>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
					<div class="col-lg-6 col-lg-offset-3">
						<div class="card card-user">
							<div class="image">
								<img src="assets/img/background1.png" alt="background image"/>
							</div>
							<div class="content">
								<div class="author">
								  <img class="avatar border-white" src="avatars/user_<?php if(file_exists('avatars/user_' . $viewuser_id . "_thumb." . $viewpic)) { echo $viewuser_id . "_thumb." . $viewpic; } else { echo "default.jpg"; } ?>" alt="<?php echo $viewfullname ?>'s Profile Pic"/>
								  <h4 class="title"><?php echo $viewfullname; ?><br />
									 <a href="<?php echo $dir_path; ?>/user/<?php echo $viewusername; ?>"><small>@<?php echo $viewusername; ?></small></a>
								  </h4>
								</div>
									<p class="description text-center"><?php echo $viewaboutme; ?></p>
									<form action="rate/<?php echo $viewusername; ?>" method="POST">
										<div class="row">
											<div class="col-md-12 text-center">
												<span class="rating">
													<span class="ios-label">Choose the amount of stars:<br></span>
													<span class="non-ios-label">
														<input class="input" id="input-1-5" name="starRating" type="radio" value="5"<?php if($rating == 5) { echo "checked"; } ?>>
														<label for="input-1-5" class="star"></label><!--<span class="ios-label">5</span>-->
														<input class="input" id="input-1-4" name="starRating" type="radio" value="4"<?php if($rating == 4) { echo "checked"; } ?>>
														<label for="input-1-4" class="star"></label><!--<span class="ios-label">4</span>-->
														<input class="input" id="input-1-3" name="starRating" type="radio" value="3"<?php if($rating == 3) { echo "checked"; } ?>>
														<label for="input-1-3" class="star"></label><!--<span class="ios-label">3</span>-->
														<input class="input" id="input-1-2" name="starRating" type="radio" value="2"<?php if($rating == 2) { echo "checked"; } ?>>
														<label for="input-1-2" class="star"></label><!--<span class="ios-label">2</span>-->
														<input class="input" id="input-1-1" name="starRating" type="radio" value="1"<?php if($rating == 1) { echo "checked"; } ?> required>
														<label for="input-1-1" class="star"></label><!--<span class="ios-label">1</span>-->
													</span>
												</span>
											</div>
										</div>
										<input type="hidden" value="<?php echo $viewuser_id; ?>" name="viewuser_id">
										<div class="text-center">
											<button type="submit" class="btn btn-info btn-fill btn-wd"<?php if ($_SESSION['user_id'] == $viewuser_id) { echo "disabled"; } ?>>Submit Rating</button>
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
