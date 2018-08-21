<?php 
include 'config.php';
session_start();
if (!$_SESSION['loggedIn'] && !isset($_COOKIE['loginhash'])) { 
	header("Location: login?l=dashboard");
} else {
	require('logincode.php');
	loginWith($_SESSION['user_id'], $_SESSION['loginString'], 'dashboard');

	$totalMessagesCount= array();
	$myLikes = 0;
	$conn = new Connection();
	$query = 'SELECT m_id, m_date FROM messages WHERE m_to = ?';
	$stmt = $conn->mysqli->prepare($query);
	$stmt->bind_param('i', $_SESSION['user_id']);
	$stmt->execute();
	$stmt->bind_result($value1, $value2);
	while ($stmt->fetch())
	{
		$tempDate = strtotime($value2);
		if ($tempDate > strtotime('-9 days')) {
			array_push($totalMessagesCount, $value1);
		}
	}
	$stmt->close();
	$conn->mysqli->close(); 
	
	$allChats= array();
	$conn = new Connection();
	$query = '
	SELECT messages.m_id, messages.m_content, messages.chat_on, messages.m_from, messages.m_to,
	users.user_id, users.profile_pic, users.username
	FROM messages RIGHT JOIN users 
	ON messages.m_to=users.user_id
	WHERE messages.m_from=? OR messages.m_to=?';
//	$query = 'SELECT m_id, m_content, chat_on, m_from, m_to FROM messages WHERE m_from=? OR m_to=?';
	$stmt = $conn->mysqli->prepare($query);
	$stmt->bind_param('ii', $_SESSION['user_id'], $_SESSION['user_id']);
	$stmt->execute();
	$stmt->bind_result($value1, $value2, $value3, $value4, $value5, $value6, $value7, $value8);
	while ($stmt->fetch())
	{
		$newMessages = 0;
		if($value3 == '1') {
			if($value4 == $_SESSION['user_id'] || $value5 == $_SESSION['user_id']) {
				// see if any new messages have been sent - unread messages
				$conn2 = new Connection();
				$query = 'SELECT chat_id, message_id, user_from, user_to, user_to_new, user_from_new FROM chats WHERE message_id = ?';
				$stmt2 = $conn2->mysqli->prepare($query);
				$stmt2->bind_param('i', $value1);
				$stmt2->execute();
				$stmt2->bind_result($first_value1, $first_value2, $first_value3, $first_value4, $first_value5, $first_value6);
				while ($stmt2->fetch())
				{
					if($first_value3 != $_SESSION['user_id']){
						if($value4 == $_SESSION['user_id']) {
							if($first_value5 == '1') {
								$newMessages++;
							}
						} else {
							if($first_value6 == '1') {
								$newMessages++;
							}
						}
					}
				}
				$stmt2->close();
				$conn2->mysqli->close();
				if($newMessages > 0) {
					array_push($allChats, array($value1, $value2, $value3, $value4, $value5, $value6, $value7, $value8, $newMessages));
//					echo $value1 . " | " . $value2 . " | " . $value3 . " | " . $value4 . " | " . $value5 . " | " . $value6 . " | " . $value7 . " | " . $value8 . " | " . $newMessages . "<br>";
				}
			}
		}
	}
	$stmt->close();
	$conn->mysqli->close();	

	$totalChats = 0;
	$conn = new Connection();
	$query = 'SELECT m_id, m_content, chat_on, m_from, m_to, chat_on FROM messages WHERE m_from=? OR m_to=?';
	$stmt = $conn->mysqli->prepare($query);
	$stmt->bind_param('ii', $_SESSION['user_id'], $_SESSION['user_id']);
	$stmt->execute();
	$stmt->bind_result($value1, $value2, $value3, $value4, $value5, $value6);
	while ($stmt->fetch())
	{
		if($value6 == '1') {
			$totalChats++;
		}
	}
	$stmt->close();
	$conn->mysqli->close(); 
	
	$favoritesUsers = array();
	$conn = new Connection();
	$query = '
	SELECT users.user_id, users.fullname, users.username, users.profile_pic,
	user_list.user_id, user_list.added_by
	FROM users LEFT JOIN user_list 
	ON users.user_id=user_list.added_by
	WHERE user_list.user_id = ?
	ORDER BY list_item_id';
//	$query = 'SELECT user_id, m_id FROM likes WHERE m_from = ?';
	$stmt = $conn->mysqli->prepare($query);
	$stmt->bind_param('i', $_SESSION['user_id']);
	$stmt->execute();
	$stmt->bind_result($value1, $value2, $value3, $value4, $value5, $value6);
	while ($stmt->fetch())
	{
//		echo $value1 . " || " . $value2 . " || " . $value3 . " || " . $value4 . " || " . $value5 . " || " . $value6 . "<br>";
		array_push($favoritesUsers, array($value1, $value2, $value3, $value4, $value5, $value6));
	}
	$stmt->close();
	$conn->mysqli->close(); 


	$conn = new Connection();
	$query = '
	SELECT messages.m_id, messages.m_from,
	likes.user_id, likes.m_id
	FROM messages LEFT JOIN likes 
	ON messages.m_id=likes.m_id
	WHERE messages.m_from = ?';
//	$query = 'SELECT user_id, m_id FROM likes WHERE m_from = ?';
	$stmt = $conn->mysqli->prepare($query);
	$stmt->bind_param('i', $_SESSION['user_id']);
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
	$title="Dashboard"; $thisPage="dashboard"; require('header.php'); 
?>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
						<div class="card">
							<div class="header">
								<h2 class="title"><small>Welcome</small><br><?php echo $_SESSION['fullname']; ?></h2>
							</div>
                            <div class="content">
                                <div class="row">
                                    <div class="col-lg-12">
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="card">
							<div class="header">
								<h4 class="title">Just copy this link and share it with your friends!</h4>
							</div>
                            <div class="content">
                                <div class="row">
                                    <div class="col-lg-10">
										<div class="form-group">
											<input type="text" class="form-control border-input" name="users_url" id="users_url" value="https://<?php echo $_SERVER['HTTP_HOST'] . $dir_path . "/user/" . $_SESSION['username']; ?>" onfocus="this.select();" disabled>
										</div>
                                    </div>
                                    <div class="col-lg-2">
										<div class="form-group">
											<button class="btn btn-info btn-fill btn-wd" id="copyButton" onclick="copyTextFunction('https://<?php echo $_SERVER['HTTP_HOST'] . $dir_path . "/user/" . $_SESSION['username']; ?>')">Copy Link</button>
										</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="content">
                                <div class="row">
                                    <div class="col-xs-5">
                                        <div class="icon-big icon-warning text-center">
											<div class="css-rank-sprite-rank<?php echo $myRank[1]; ?>"></div>
                                        </div>
                                    </div>
                                    <div class="col-xs-7">
                                        <div class="numbers">
                                            <p>Rank <?php echo $myRank[1]; ?></p>
                                            <?php echo $myRank[0]; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <hr />
                                    <div class="stats">
                                        <i class="ti-reload"></i> Updated now
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="content">
                                <div class="row">
                                    <div class="col-xs-5">
                                        <div class="icon-big icon-success text-center">
                                            <i class="ti-comments"></i>
                                        </div>
                                    </div>
                                    <div class="col-xs-7">
                                        <div class="numbers">
                                            <p>My Chats</p>
                                            <?php echo $totalChats; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <hr />
                                    <div class="stats">
                                        <i class="ti-reload"></i> Updated now
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="content">
                                <div class="row">
                                    <div class="col-xs-5">
                                        <div class="icon-big icon-warning text-center">
                                            <i class="ti-comment"></i>
                                        </div>
                                    </div>
                                    <div class="col-xs-7">
                                        <div class="numbers">
                                            <p>Messages</p>
                                            <?php echo count($totalMessagesCount); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <hr />
                                    <div class="stats">
                                        <i class="ti-reload"></i> Updated now
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="content">
                                <div class="row">
                                    <div class="col-xs-5">
                                        <div class="icon-big icon-danger text-center">
                                            <i class="ti-heart"></i>
                                        </div>
                                    </div>
                                    <div class="col-xs-7">
                                        <div class="numbers">
                                            <p>Likes</p>
                                            <?php echo $myLikes; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <hr />
                                    <div class="stats">
                                        <i class="ti-reload"></i> Updated now
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">My Favorites</h4>
                                <p class="category">Users you saved</p>
                            </div>
                            <div class="content">
							<?php  if(empty($favoritesUsers)) { ?>
								You have not yet added any favorites. 
							<?php  } else { ?>
								<ul class="list-unstyled team-members">
								<?php foreach($favoritesUsers as $userValue) { ?>
									<a class="user-link" href="user/<?php echo $userValue[2]; ?>"><li>
										<div class="row">
											<div class="col-xs-3">
												<div class="avatar">
													<img src="avatars/user_<?php if(file_exists('avatars/user_' . $userValue[0] . "_thumb." . $userValue[3])) { echo $userValue[0] . "_thumb." . $userValue[3]; } else { echo "default.jpg"; } ?>" alt="Circle Image" class="img-circle img-no-padding img-responsive">
												</div>
											</div>
											<div class="col-xs-9">
												<p><?php echo $userValue[1]; ?><br><small>@<?php echo $userValue[2]; ?></small></p>
											</div>
										</div>
									</li></a>
								<?php } ?>
								</ul>
							<?php  } ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">My Chats</h4>
                                <p class="category">New unseen messages</p>
                            </div>
                            <div class="content">
							<?php  if(empty($allChats)) { ?>
								No new unread chats messages. 
							<?php  } else { ?>
								<ul class="list-unstyled team-members">
								<?php foreach($allChats as $chatRecord) {?>
									<li>
										<div class="row">
											<div class="col-xs-3">
												<div class="icon-big icon-info text-center">
													<i class="far fa-bell"></i>
												</div>
											</div>
											<div class="col-xs-9">
												<?php echo $chatRecord[8]; ?> New Chat Messages
												<br />
												<a href="<?php echo $dir_path; ?>/chats?m=<?php echo $chatRecord[0]; ?>"><small>Open chat</small></a>
											</div>
										</div>
									</li>
								<?php } ?>
								</ul>
							<?php  } ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Ranks</h4>
                                <p class="category">Depending on your likes</p>
                            </div>
                            <div class="content">
                                <div class="row">
                                    <div class="col-xs-5">
                                        <div class="text-center">
											<div class="css-rank-sprite-rank1"></div>
                                        </div>
                                    </div>
                                    <div class="col-xs-7">
                                        <div class="numbers">
                                            <p>0-50 Likes</p>Kind
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-5">
                                        <div class="text-center">
											<div class="css-rank-sprite-rank2"></div>
                                        </div>
                                    </div>
                                    <div class="col-xs-7">
                                        <div class="numbers">
                                            <p>51-200 Likes</p>Friendly
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-5">
                                        <div class="text-center">
											<div class="css-rank-sprite-rank3"></div>
                                        </div>
                                    </div>
                                    <div class="col-xs-7">
                                        <div class="numbers">
                                            <p>201-1,000 Likes</p>Honest
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-5">
                                        <div class="text-center">
											<div class="css-rank-sprite-rank4"></div>
                                        </div>
                                    </div>
                                    <div class="col-xs-7">
                                        <div class="numbers">
                                            <p>1,001-9,999 Likes</p>Honorable
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-5">
                                        <div class="text-center">
											<div class="css-rank-sprite-rank5"></div>
                                        </div>
                                    </div>
                                    <div class="col-xs-7">
                                        <div class="numbers">
                                            <p>10,000+ Likes</p>Truthfill
                                        </div>
                                    </div>
                                </div>
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
