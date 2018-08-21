<?php
session_start();
if (!$_SESSION['loggedIn'] && !isset($_COOKIE['loginhash'])) { 
	header("Location: login");
} else {
	require('logincode.php');
	loginWith($_SESSION['user_id'], $_SESSION['loginString'], 'write message');
	$sqlvariable1 = $_GET['chat_message'];
	
	$conn = new Connection();
	$query = 'SELECT m_to, m_from FROM messages WHERE m_id=?';
	$stmt = $conn->mysqli->prepare($query);
	$stmt->bind_param('i', $sqlvariable1);
	$stmt->execute();
	$stmt->bind_result($second_value1, $second_value2);
	while ($stmt->fetch())
	{
//			echo "Chat to: " . $second_value1 . "<br>Chat from: " . $second_value2;
	}
	$stmt->close();
	$conn->mysqli->close();	

	if($_SESSION['user_id'] == $second_value2) {
//			echo "<br>I am the one who sent the message";
		$userIsSender = 1;
	} else {
//			echo "<br>I am the one who recieved the message";
		$userIsSender = 0;
	}

	if($_GET['new'] == 'true') {
		$conn = new Connection();
		$query = 'SELECT message_id, message_content, datestamp, user_from, user_to, user_to_new, user_from_new, chat_id FROM chats WHERE message_id = ? ORDER BY chat_id ASC';
		$stmt = $conn->mysqli->prepare($query);
		$stmt->bind_param('i', $sqlvariable1);
		$stmt->execute();
		$stmt->bind_result($first_value1, $first_value2, $first_value3, $first_value4, $first_value5, $first_value6, $first_value7, $first_value8);
		while ($stmt->fetch())
		{
			if($first_value4 == $_SESSION['user_id'] || $first_value5 == $_SESSION['user_id']) {
				if($userIsSender == 1) {
					if($first_value6 == 1) {
						echo "<div class='row'>";
						if($first_value5 == $_SESSION['user_id']) {
							echo "<div class='col-md-4'>";
							echo "<div class='card'>";
						} else { 
							echo "<div class='col-md-4 col-md-offset-8'>";
							echo "<div class='card message'>";
						}
						echo "<div class='content'>";
						echo $first_value2 . "<br>";
						echo "<p class='mdate'>" . $first_value3 . "</p>";
						echo "</div></div></div></div>";
						
						$conn = new Connection();
						$query = "UPDATE chats SET user_to_new=0 WHERE chat_id=?";
						$stmt = $conn->mysqli->prepare($query);
						$stmt->bind_param('i', $first_value8);
						$stmt->execute();
						$rows_updated = $stmt->affected_rows;
						if ($rows_updated !== 1) {
						   // it didn't
						} else {
						   // it worked
						}
					}
				} else {
					if($first_value7 == 1) {
						echo "<div class='row'>";
						if($first_value5 == $_SESSION['user_id']) {
							echo "<div class='col-md-4'>";
							echo "<div class='card'>";
						} else { 
							echo "<div class='col-md-4 col-md-offset-8'>";
							echo "<div class='card message'>";
						}
						echo "<div class='content'>";
						echo $first_value2 . "<br>";
						echo "<p class='mdate'>" . $first_value3 . "</p>";
						echo "</div></div></div></div>";
						
						$conn = new Connection();
						$query = "UPDATE chats SET user_from_new=0 WHERE chat_id=?";
						$stmt = $conn->mysqli->prepare($query);
						$stmt->bind_param('i', $first_value8);
						$stmt->execute();
						$rows_updated = $stmt->affected_rows;
						if ($rows_updated !== 1) {
						   // it didn't
						} else {
						   // it worked
						}
					}
				}
			} else {
				header("Location: chats");
			}
		}
		$stmt->close();
		$conn->mysqli->close();	
	} else {
		$conn = new Connection();
		$query = 'SELECT message_id, message_content, datestamp, user_from, user_to, user_to_new, user_from_new, chat_id FROM chats WHERE message_id = ? ORDER BY chat_id ASC';
		$stmt = $conn->mysqli->prepare($query);
		$stmt->bind_param('i', $sqlvariable1);
		$stmt->execute();
		$stmt->bind_result($first_value1, $first_value2, $first_value3, $first_value4, $first_value5, $first_value6, $first_value7, $first_value8);
		while ($stmt->fetch())
		{
			if($first_value4 == $_SESSION['user_id'] || $first_value5 == $_SESSION['user_id']) {
				echo "<div class='row'>";
				if($first_value5 == $_SESSION['user_id']) {
					echo "<div class='col-md-4'>";
					echo "<div class='card'>";
				} else { 
					echo "<div class='col-md-4 col-md-offset-8'>";
					echo "<div class='card message'>";
				}
				echo "<div class='content'>";
				echo $first_value2 . "<br>";
				echo "<p class='mdate'>" . $first_value3 . "</p>";
				echo "</div></div></div></div>";
			} else {
				header("Location: chats");
			}
			if($userIsSender == 1) {
				if($first_value6 == 1) {
					$conn = new Connection();
					$query = "UPDATE chats SET user_to_new=0 WHERE chat_id=?";
					$stmt = $conn->mysqli->prepare($query);
					$stmt->bind_param('i', $first_value8);
					$stmt->execute();
					$rows_updated = $stmt->affected_rows;
					if ($rows_updated !== 1) {
					   // it didn't
					} else {
					   // it worked
					}
				}
			} else {
				if($first_value7 == 1) {
					$conn = new Connection();
					$query = "UPDATE chats SET user_from_new=0 WHERE chat_id=?";
					$stmt = $conn->mysqli->prepare($query);
					$stmt->bind_param('i', $first_value8);
					$stmt->execute();
					$rows_updated = $stmt->affected_rows;
					if ($rows_updated !== 1) {
					   // it didn't
					} else {
					   // it worked
					}
				}
			}
		}
		$stmt->close();
		$conn->mysqli->close();	
	}
}
?>