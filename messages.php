<?php 
include 'config.php';
session_start();
if (!$_SESSION['loggedIn'] && !isset($_COOKIE['loginhash'])) { 
	header("Location: login?l=messages");
} else {
	require('logincode.php');
	loginWith($_SESSION['user_id'], $_SESSION['loginString'], 'messages');

	$likedCheck = array();
	$userMessages = array();
	$userMessages2 = array();
	$messagesLikedByMe = array();
	$messagesOther = array();
	$conn = new Connection();
	$query = '
	SELECT messages.m_id, messages.m_content, messages.m_date, messages.m_status, messages.m_to, messages.m_from,
	likes.user_id, likes.m_id
	FROM messages LEFT JOIN likes 
	ON messages.m_id=likes.m_id
	WHERE messages.m_to = ?
	ORDER BY messages.m_id';
//	$query = 'SELECT m_id, m_content, m_status, m_to FROM messages WHERE m_to = ?';
	$stmt = $conn->mysqli->prepare($query);
	$stmt->bind_param('i', $_SESSION['user_id']);
	$stmt->execute();
	$stmt->bind_result($value1, $value2, $value3, $value4, $value5, $value6, $value7, $value8);
	while ($stmt->fetch())
	{
		$tempDate = strtotime($value3);
		if ($tempDate > strtotime('-9 days')) {
			if($value7 == $_SESSION['user_id']) {
				array_push($messagesLikedByMe, array($value1, $value2, date('F jS, Y', $tempDate), $value4, $value5, $value6, $value7, $value8));
			} else {
				array_push($messagesOther, array($value1, $value2, date('F jS, Y', $tempDate), $value4, $value5, $value6, $value7, $value8));
			}
		}
	}
	$stmt->close();
	$conn->mysqli->close();

	$userMessages2 = array_merge($messagesLikedByMe ,$messagesOther);
	foreach ($userMessages2 as $message) {
		if($message[6] == "" || $message[6] == $_SESSION['user_id']) {
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
//	print_r($userMessages);

$title="Messages"; $thisPage="messages"; require('header.php'); ?>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                     <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Messages</h4>
                            </div>
                            <div class="content">
                            </div>
                        </div>
					 </div>
					<?php foreach($userMessages as $innerArray) { ?>
                     <div class="col-md-6">
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
													<?php if($innerArray[5] == 0) {?>
													(private)
													<div class="margin-10px"></div>
													<?php } else { ?>
													<a href="chats?m=<?php echo $innerArray[0] ?>&start=true" class="btn btn-sm btn-info btn-icon"><i class="fa fa-envelope"></i></a>
													<div class="margin-10px"></div>
													<?php } ?>
													<button id="message<?php echo $innerArray[0] ?>" class="btn btn-sm btn-danger btn-icon<?php if($innerArray[4] == $innerArray[6]){ echo " active"; } ?>" onClick="updateLiked('<?php echo $innerArray[0] ?>')"><i class="fa fa-heart"></i></button>
											</div>
										</div>
									</li>
								</ul>
                            </div>
                        </div>
                    </div>
					<? } if (empty($userMessages)) { ?>
                    <div class="col-md-6">
						<div class="card">
                            <div class="header">
								<h4 class="title">You don't have any messages yet.</h4>
							</div>
							<div class="content">
								<p>Share your link with your friends so they can write you some.</p>
								<div class="form-group">
									<input type="text" class="form-control border-input" name="users_url" id="users_url" value="https://<?php echo $_SERVER['HTTP_HOST'] . $dir_path; ?>/user/<?php echo $_SESSION['username']; ?>" onfocus="this.select();">
								</div>
							</div>
						</div>
                    </div>
					<? } ?>
                </div>
            </div>
        </div>
<?php require('footer.php'); }?>

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

    	$(document).ready(function(){

        	demo.initChartist();


    	});

</script>