<?php 
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
session_start();
if (!$_SESSION['loggedIn'] && !isset($_COOKIE['loginhash'])) { 
	header("Location: login?l=chats");
} else {
	require('logincode.php');
	loginWith($_SESSION['user_id'], $_SESSION['loginString'], 'chats');

if (isset($_GET['m'])) {
	$chatArray= array();
	$conn = new Connection();
	$query = '
	SELECT chats.chat_id, chats.message_id, chats.message_content, chats.user_from, chats.user_to, chats.datestamp, 
	messages.m_content, messages.m_from, messages.m_to, messages.chat_on
	FROM chats RIGHT JOIN messages 
	ON messages.m_id=chats.message_id
	WHERE messages.m_id = ?';
//	$query = 'SELECT chat_id, message_id, message_content, user_from, user_to, datestamp FROM chats WHERE message_id = ?';
	$stmt = $conn->mysqli->prepare($query);
	$stmt->bind_param('i', $_GET['m']);
	$stmt->execute();
	$stmt->bind_result($value1, $value2, $value3, $value4, $value5, $value6, $value7, $value8, $value9, $value10);
	while ($stmt->fetch())
	{
		if (($_SESSION['user_id'] == $value8 || $_SESSION['user_id'] == $value9) AND ($value10 == 1 || $_GET['start'] == 'true') AND ($value8 != 0)) {
			array_push($chatArray, array($value1, $value2, $value3, $value4, $value5, $value6, $value7, $value8, $value9, $value10));
			if ($_SESSION['user_id'] == $value8) {
				$conn = new Connection();
				$query = 'SELECT user_id, username, fullname FROM users WHERE user_id = ?';
				$stmt = $conn->mysqli->prepare($query);
				$stmt->bind_param('i', $value9);
				$stmt->execute();
				$stmt->bind_result($userInfo1, $userInfo2, $userInfo3);
				while ($stmt->fetch())
				{
					$chattingWith = $userInfo3;
					$chattingWithUsername = $userInfo2;
				}
				$stmt->close();
				$conn->mysqli->close();	
			}
		} else {
			$chatArray = "empty";
		}
	}
	$stmt->close();
	$conn->mysqli->close();	
	sort($chatArray);
	if (isset($_GET['start'])) {
		if ($_GET['start'] == "true") {
			$conn = new Connection();
			$query = "UPDATE messages SET chat_on= CASE WHEN chat_on=0 THEN 1 ElSE 1 END WHERE m_id=? AND m_from!=?";
			$stmt = $conn->mysqli->prepare($query);
			$stmt->bind_param('ii', $_GET['m'], $_SESSION['user_id']);
			$stmt->execute();
			$rows_updated = $stmt->affected_rows;
			if ($rows_updated !== 1) {
			   // it didn't
			} else {
			   // it worked
			}
			$stmt->close();
			$conn->mysqli->close();
		}
	}
} else {
	$pageContent = 'select';
	$chatsToMe= array();
	$chatsFromMe= array();
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

				// put the data in a array accordly to who sent the message
				if($value4 == $_SESSION['user_id']) {
					array_push($chatsToMe, array($value1, $value2, $value3, $value4, $value5, $value6, $value7, $value8, $newMessages));
				} else {
					array_push($chatsFromMe, array($value1, $value2, $value3, $value4, $value5, $newMessages));
				}
			}
		}
	}
	$stmt->close();
	$conn->mysqli->close();	
	
}
	
$title="Chats"; $thisPage="chats"; require('header.php'); 
?>
<style>
#messagesArea {
	height: 350px;
	max-height: 350px;
    overflow: auto;
}
</style>
        <div class="content">
            <div class="container-fluid">
			<?php if($pageContent == "select") { ?>
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="header">
								<h2 class="title">Chats</h2>
							</div>
                            <div class="content">
                                <div class="row">
                                    <div class="col-lg-12">
										<p>Please click the message icon to continue chatting in your previous or new conversations.</p>
                                    </div>
                                </div>
                            </div>
						</div>
					 </div>
					<div class="col-md-6">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Chats to Me</h4><?php echo $newChatsFromMe; ?>
                            </div>
                            <div class="content">
                                <ul class="list-unstyled team-members">
								<?php if(!empty($chatsToMe)) { foreach($chatsToMe as $chatRecord) { ?>
									<li>
										<div class="row">
											<div class="col-xs-3">
												<div class="avatar">
													<a href="user/<?php echo $chatRecord[7]; ?>"><img src="avatars/user_<?php if(file_exists('avatars/user_' . $chatRecord[5] . "_thumb." . $chatRecord[6])) { echo $chatRecord[5] . "_thumb." . $chatRecord[6]; } else { echo "default.jpg"; } ?>" alt="Circle Image" class="img-circle img-no-padding img-responsive"></a>
												</div>
											</div>
											<div class="col-xs-6">
												<?php echo $chatRecord[1]; ?>
												<br />
												<span class="text<?php if($chatRecord[8] > 0) { echo "-success"; } ?>"><small><?php if($chatRecord[8] > 0) { echo $chatRecord[8]; } else { echo "No"; } ?> New Messages</small></span>
											</div>

											<div class="col-xs-3 text-right">
												<a class="btn btn-sm btn-success btn-icon" href="chats?m=<?php echo $chatRecord[0] ?>"><i class="fa fa-envelope"></i></a>
											</div>
										</div>
									</li>
								<?php }} else { ?>
									There is no chats started with you.
								<?php } ?>
								</ul>
                            </div>
                        </div>
					</div>
					<div class="col-md-6">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">My Chats</h4>
                            </div>
                            <div class="content">
                                <ul class="list-unstyled team-members">
								<?php if(!empty($chatsFromMe)) { foreach($chatsFromMe as $chatRecord) { ?>
									<li>
										<div class="row">
											<div class="col-xs-3">
												<div class="avatar">
													<img src="avatars/user_default.jpg" alt="Circle Image" class="img-circle img-no-padding img-responsive">
												</div>
											</div>
											<div class="col-xs-6">
												<?php echo $chatRecord[1]; ?>
												<br />
												<span class="text<?php if($chatRecord[5] > 0) { echo "-success"; } ?>"><small><?php if($chatRecord[5] > 0) { echo $chatRecord[5]; } else { echo "No"; } ?> New Messages</small></span>
											</div>

											<div class="col-xs-3 text-right">
												<a class="btn btn-sm btn-success btn-icon" href="chats?m=<?php echo $chatRecord[0] ?>"><i class="fa fa-envelope"></i></a>
											</div>
										</div>
									</li>
								<?php }} else { ?>
									You have not started any chats. Head to <a href="messages">Messages</a> and click the envelope icon to start one.
								<?php } ?>
								</ul>
                            </div>
                        </div>
					</div>
				 </div>
			<?php } else { ?>
                <div class="row">
                     <div class="col-md-12">
					 <?php if($chatArray == "empty") { ?>
						<div class="header">
							<h4 class="title">This chat is missing, please go back.</h4>
							<a href="chats" class="btn btn-info btn-fill">Back to chats</a>
						</div>
					<?php } else { ?>
						<div class="header">
							<h4 class="title" id="chatTitle">Chatting with <?php if(isset($chattingWith)) { echo "<a href='user/" . $chattingWithUsername . "'>" . $chattingWith . "</a>"; } else { echo "Anonymous"; } ?></h4>
						</div>
						<div class="content" id="messagesArea">
						<?php foreach($chatArray as $userChat) { ?>
							<div class="row">
								<div class="col-md-4 col-md-offset-8">
									<div class="card message">
										<div class="content">
											<?php echo $userChat[2]; ?><br>
											<p class="mdate"><?php echo $userChat[5]; ?></p>
										</div>
									</div>
								</div>
							</div>
						<?php } ?>
						</div>
						<div class="container-fluid">
							<div class="row">
								<div class="col-xs-10">
									<div class="content">
										<div class="form-group">
											<input type="text" class="form-control border-input" placeholder="enter your message" name="message" id="msgBox" autofocus>
											<input type="hidden" value="<?php echo $_GET['m']; ?>" id="message_id">
										</div>
									</div>
								</div>
								<div class="col-xs-2">
									<div class="content">
										<button id="chatSend" class="btn btn-info btn-fill btn-md">Send</button>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
                    </div>
                </div>
			<?php } ?>
            </div>
        </div>
<?php require('footer.php'); }?>
<script>
$(document).ready(function () {
	var chatInterval = 1000; //refresh interval in ms
	var chatOutput = document.getElementById('chatOutput');
	var chatInput = document.getElementById('msgBox');
	var chatSend = document.getElementById('chatSend');
	scrollChat();
	
	function scrollChat() {
		var objDiv = document.getElementById("messagesArea");
		objDiv.scrollTop = objDiv.scrollHeight;
	}
	
	function sendMessage() {
		var chatInputString = chatInput.value;

		$.get("./write_message.php", {
			text: chatInputString,
			chat_message: <?php echo $_GET['m']; ?>
		});

		chatInput.value = "";
		
		retrieveNewMessages();
	}

	function retrieveMessagesAll() {
		$.get("./read_message.php?chat_message=<?php echo $_GET['m']; ?>&new=false", function (data) {
			document.getElementById('messagesArea').innerHTML = data;
			scrollChat();
		});
	}
	
	function retrieveNewMessages() {
		$.get("./read_message.php?chat_message=<?php echo $_GET['m']; ?>&new=true", function (data) {
			if (data.includes("content")) {
				$("#messagesArea").append(data);
				scrollChat();
			}
		});
	}

	retrieveMessagesAll();
	
	document.getElementById("chatSend").addEventListener("click", sendMessage);

//	chatSend.click(function () {
//		sendMessage();
//	});

	chatInput.addEventListener("keydown", function(event) {
		if (event.key === "Enter") {
			event.preventDefault();
			sendMessage();
		}
	});

	setInterval(function () {
		retrieveNewMessages();
	}, chatInterval);
});
</script>