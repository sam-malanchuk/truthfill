<?php
include 'config.php';
session_start();
if (!$_SESSION['loggedIn'] && !isset($_COOKIE['loginhash'])) { 
//	header("Location: ../login?l=user/" . $_GET['u']);
	$loggedIn = false;
} else {
	$loggedIn = true;
}

require('logincode.php');
loginWith($_SESSION['user_id'], $_SESSION['loginString'], 'feedback');


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

if($viewfullname == "") {
	$noUser = true;
} else {
	$noUser = false;
}

	$viewRatings = array();
	$conn = new Connection();
	$query = 'SELECT rating FROM ratings WHERE user_id = ?';
	$stmt = $conn->mysqli->prepare($query);
	$stmt->bind_param('i', $viewuser_id);
	$stmt->execute();
	$stmt->bind_result($allRatings);
	while ($stmt->fetch())
	{
		array_push($viewRatings, $allRatings);
	}
	$stmt->close();
	$conn->mysqli->close();
	while(count($viewRatings) < 3) {
		array_push($viewRatings, 5);
	}
	$ratingAverage = array_sum($viewRatings)/count($viewRatings);
	
	if($ratingAverage < 1.5) {
		$ratingStars = 10;
	} else if($ratingAverage < 2) {
		$ratingStars = 15;
	} else if($ratingAverage < 2.5) {
		$ratingStars = 20;
	} else if($ratingAverage < 3) {
		$ratingStars = 25;
	} else if($ratingAverage < 3.5) {
		$ratingStars = 30;
	} else if($ratingAverage < 4) {
		$ratingStars = 35;
	} else if($ratingAverage < 4.5) {
		$ratingStars = 40;
	} else if($ratingAverage < 5) {
		$ratingStars = 45;
	} else {
		$ratingStars = 50;
	}
//	print_r($viewRatings);

	$myLikes = 0;
	$conn = new Connection();
	$query = '
	SELECT messages.m_id, messages.m_from,
	likes.user_id, likes.m_id
	FROM messages LEFT JOIN likes 
	ON messages.m_id=likes.m_id
	WHERE messages.m_from = ?';
//	$query = 'SELECT user_id, m_id FROM likes WHERE m_from = ?';
	$stmt = $conn->mysqli->prepare($query);
	$stmt->bind_param('i', $viewuser_id);
	$stmt->execute();
	$stmt->bind_result($value1, $value2, $value3, $value4);
	while ($stmt->fetch())
	{
//		echo $value1 . " || " . $value2 . " || " . $value3 . " || " . $value4 . "<br>";
		if(isset($value3)) {
			$myLikes++;
		}
	}
	$stmt->close();
	$conn->mysqli->close(); 

	switch ($myLikes) {
		case $myLikes > 0 && $myLikes < 51:
			$myRank = array("Kind", 1);
			break;
		case $myLikes > 50 && $myLikes < 201:
			$myRank = array("Friendly", 2);
			break;
		case $myLikes > 200 && $myLikes < 1001:
			$myRank = array("Honest", 3);
			break;
		case $myLikes > 1000 && $myLikes < 10000:
			$myRank = array("Honorable", 4);
			break;
		case $myLikes > 9999:
			$myRank = array("Truthfill", 5);
			break;
		default:
			$myRank = array("Kind", 1);
	}

if ($loggedIn) {
	$notPublic = true;
	$likedCheck = array();
	$userMessages = array();
	$userMessages2 = array();
	$messagesLikedByMe = array();
	$messagesOther = array();
	$conn = new Connection();
	$query = '
	SELECT messages.m_id, messages.m_content, messages.m_date, messages.m_status, messages.m_to,
	likes.user_id, likes.m_id,
	users.public_on
	FROM messages
	LEFT JOIN likes ON messages.m_id=likes.m_id
	LEFT JOIN users ON messages.m_to=users.user_id
	WHERE messages.m_to = ? && messages.m_from!=0';
//	$query = 'SELECT m_id, m_content, m_status, m_to FROM messages WHERE m_to = ?';
	$stmt = $conn->mysqli->prepare($query);
	$stmt->bind_param('i', $viewuser_id);
	$stmt->execute();
	$stmt->bind_result($value1, $value2, $value3, $value4, $value5, $value6, $value7, $value8);
	while ($stmt->fetch())
	{
		$tempDate = strtotime($value3);
		if ($value8 == 1) {
			$notPublic = false;
			if ($tempDate > strtotime('-9 days')) {
				if($value6 == $_SESSION['user_id']) {
					array_push($messagesLikedByMe, array($value1, $value2, date('F jS, Y', $tempDate), $value4, $value5, $value6, $value7, $value8));
				} else {
					array_push($messagesOther, array($value1, $value2, date('F jS, Y', $tempDate), $value4, $value5, $value6, $value7, $value8));
				}
			}
		}
	}
	$stmt->close();
	$conn->mysqli->close();

	$userMessages2 = array_merge($messagesLikedByMe ,$messagesOther);
	foreach ($userMessages2 as $message) {
		if($message[5] == "" || $message[5] == $_SESSION['user_id']) {
		//	echo $message[0] . " || " . $message[1] . " || " . $message[2] . " || " . $message[3] . " || " . $message[4] . " || " . $message[5] . " || " . $message[6] . " || " . $message[7] . "<br>";
			array_push($likedCheck, $message[0]);
			array_push($userMessages, array($message[0], $message[1], $message[2], $message[3], $message[4], $message[5], $message[6], $message[7]));
		} else {
			if (in_array($message[0], $likedCheck))
			{
			//	echo "Do Nothing";
			}
			else
			{
			//	echo "Match not found";
			//	echo $message[0] . " || " . $message[1] . " || " . $message[2] . " || " . $message[3] . " || " . $message[4] . " || " . $message[5] . " || " . $message[6] . " || " . $message[7] . "<br>";
				array_push($userMessages, array($message[0], $message[1], $message[2], $message[3], $message[4], $message[5], $message[6], $message[7]));
			}
		}
	}
	rsort($userMessages);
//	print_r($userMessages2);

	
	$conn = new Connection();
	$query = 'SELECT user_id FROM user_list WHERE user_id=? && added_by=?';
	$stmt = $conn->mysqli->prepare($query);
	$stmt->bind_param('ii', $_SESSION['user_id'], $viewuser_id);
	$stmt->execute();
	$stmt->bind_result($my_user_id);
	while ($stmt->fetch())
	{
		if ($my_user_id == $_SESSION['user_id']) {
			$added_by_me = true;
		} else {
			$added_by_me = false;
		}
	}
	$stmt->close();
	$conn->mysqli->close();

}

$title="Leave Feedback"; $thisPage="feedback"; require('header.php');
?>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
					<?php if($noUser) { ?>
                    <div class="col-lg-6 col-lg-offset-3">
						<div class="card">
							<div class="header">
								<h3 class="title">User <?php echo $_GET['u']; ?> has not been created.</h3>
							</div>
                            <div class="content">
                                <div class="row">
                                    <div class="col-lg-12">
                                    </div>
                                </div>
                            </div>
                        </div>
						<?php } else { ?>
                    <div class="col-lg-6">
						<?php if (isset($_GET['m'])) { ?>
                        <div class="card">
							<div class="content text-center">
								<?php if ($_GET['m'] == 'error') { echo "<h4 class='title'>There seems to have been some issues submitting your message.</h4>"; } ?>
								<?php if ($_GET['m'] == 'success') { echo "<h4 class='title'>Your message has been sent!</h4>"; } ?>
								<?php if ($_GET['m'] == 'limit') { echo "<h4 class='title'>Your message has not been sent, you can only send 1 message to each user once per day.</h4>"; } ?>
								<br><a href="user/<?php echo $viewusername; ?>" class="btn btn-info btn-fill btn-wd">Go back</a>
							</div>
                        </div>
								<?php } else { ?>
						<?php if(isset($_SESSION['update_message'])) { ?>
                        <div class="card">
							<?php if ($_SESSION['update_message'] == 'nochange') { echo "<div class='alert alert-warning'><span>You did not make a change to your rating.</span></div>"; } ?>
							<?php if ($_SESSION['update_message'] == 'error') { echo "<div class='alert alert-error'><span>Your rating was not submitted, please try again later.</span></div>"; } ?>
							<?php if ($_SESSION['update_message'] == 'newdone') { echo "<div class='alert alert-success'><span>Your new rating has been submitted!</span></div>"; } ?>
							<?php if ($_SESSION['update_message'] == 'updatedone') { echo "<div class='alert alert-success'><span>Your rating has been updated!</span></div>"; } ?>
                        </div>
						<?php unset($_SESSION['update_message']); } ?>
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
								<p class="description text-center"><span class="rating-static rating-<?php echo $ratingStars; ?>"></span> <?php echo number_format($ratingAverage, 1); ?><br>Rank <?php echo $myRank[1]; ?>: <b><?php echo $myRank[0]; ?></b></p>
									<p class="description text-center"><?php echo $viewaboutme; ?></p>
								<?php if ($_SESSION['loggedIn']) { ?>
								<div class="text-center open-user-buttons">
									<button class="btn btn-sm btn-info btn-icon<?php if($added_by_me) { echo " active"; } ?>" id="addButton" onClick="addUser('<?php echo $viewuser_id ?>')"><i class="fas fa-user-plus"></i> Add to favorites</button>
									<a class="btn btn-sm btn-warning btn-icon" href="rate/<?php echo $viewusername; ?>"><i class="fas far fa-star"></i> Rate this person</a>
                                </div>
								<?php } ?>
								<span id="sendMessage"><form action="send_message.php" method="POST">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <textarea rows="5" name="message_content" autocomplete="off" class="form-control border-input" placeholder=<?php if ($_SESSION['user_id'] == $viewuser_id) { echo "'You cannot leave yourself messages.' disabled"; } else { echo "'Leave a constructive message :)'"; } ?>></textarea>
                                            </div>
                                        </div>
                                    </div>
									<input type="hidden" value="<?php echo $viewusername; ?>" name="viewusername">
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-info btn-fill btn-wd"<?php if ($_SESSION['user_id'] == $viewuser_id) { echo "disabled"; } ?>>Send</button>
                                    </div>
								</form></span>
								<!--<a href="#">Report User</a>-->
								<div class="clearfix"></div>
							</div>
                        </div>
					<?php } ?>
                    </div>
						<?php if(!$loggedIn) { ?>
					<div class="col-lg-6">
                        <div class="card">
                            <div class="content">
								For more functionalities like anonymously chatting, leaving personality ratings, adding users to favorites, and creating your own page:
								<br><br><div class="text-center"><a href="register" class="btn btn-info btn-fill">Sign Up Now!</a></div>
                            </div>
                        </div>
					</div>
						<?php } else { ?>
					<div class="col-lg-6">
						<h3 class="title">Recent Messages:</h3>
						<?php foreach($userMessages as $innerArray) { ?>
					<div class="col-lg-12">
                        <div class="card">
                            <div class="content">
                                <ul class="list-unstyled team-members">
									<li>
										<div class="row">
											<div class="col-xs-3">
												<div class="avatar">
													<img src="avatars/user_default.jpg" alt="anonymous user image" class="img-circle img-no-padding img-responsive">
												</div>
											</div>
											<div class="col-xs-6"><?php echo $innerArray[1]; ?><br><small><?php echo $innerArray[2]; ?></small></div>
											<div class="col-xs-3 text-right">
													<button id="message<?php echo $innerArray[0] ?>" class="btn btn-sm btn-danger btn-icon<?php if($innerArray[5] == $_SESSION['user_id']){ echo " active"; } ?>" onClick="updateLiked('<?php echo $innerArray[0] ?>')"><i class="fa fa-heart"></i></button>
											</div>
										</div>
									</li>
								</ul>
                            </div>
                        </div>
                    </div>
						<?php }?>
						<?php if ($notPublic) {?>
					<div class="col-lg-12">
                        <div class="card">
                            <div class="content">
								<h4 class="title"><?php echo $viewfullname; ?> decided to keep their account messages private.</h4>
                            </div>
                        </div>
					</div>
						<?php } else { ?>
					<?php if (empty($userMessages)) {?>
					<div class="col-lg-12">
                        <div class="card">
                            <div class="content">
								<h4 class="title">Looks like <?php echo $viewfullname; ?> did not recieve any public messages this week. Come back later to check again.</h4>
                            </div>
                        </div>
					</div>
						<?php } ?>
						<?php } ?></div>
					<?php }} ?>
                </div>
            </div>
        </div>
<?php
require('footer.php'); 
if($loggedIn) { ?>
<script>
function updateLiked(message_id)
{
	var http = new XMLHttpRequest();
	var url = "message_liked.php";
	var params = "key=connectionNow&message_id="+message_id;
	http.open("POST", url, true);

	//Send the proper header information along with the request
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	http.onreadystatechange = function() {//Call a function when the state changes.
		if(http.readyState == 4 && http.status == 200) {
			var thisMessage = document.getElementById("message"+message_id);
			thisMessage.classList.toggle("active");
			thisMessage.blur();
			if(thisMessage.classList.contains("active")) {
				$.notify({
					icon: 'ti-heart',
					message: "You have liked the message!"

				},{
					type: 'success',
					timer: 2000
				});
			} else {
				$.notify({
					icon: 'ti-heart',
					message: "You have unliked the message :("

				},{
					type: 'info',
					timer: 2000
				});
			};
		}
	}
	http.send(params);
}

function addUser(user_id)
{
	var http = new XMLHttpRequest();
	var url = "user_favorites.php";
	var params = "key=connectionNow&user="+user_id;
	http.open("POST", url, true);

	//Send the proper header information along with the request
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	http.onreadystatechange = function() {//Call a function when the state changes.
		if(http.readyState == 4 && http.status == 200) {
			var addButton = document.getElementById("addButton");
			addButton.classList.toggle("active");
			addButton.blur();
			if(addButton.classList.contains("active")) {
				$.notify({
					icon: 'fas fa-user-plus',
					message: "User added to your favorites!"

				},{
					type: 'success',
					timer: 2000
				});
			} else {
				$.notify({
					icon: 'fas fa-user-times',
					message: "User removed from your favorites :("

				},{
					type: 'info',
					timer: 2000
				});
			};
		}
	}
	http.send(params);
}
</script>	
<?php } ?>