<?php
session_start();
if (!$_SESSION['loggedIn'] && !isset($_COOKIE['loginhash'])) { 
	header("Location: login");
} else {
	require('logincode.php');
	loginWith($_SESSION['user_id'], $_SESSION['loginString'], 'write message');

	date_default_timezone_set("America/New_York");
	$sqlvariable1 = $_GET['chat_message'];
	$conn = new Connection();
	$query = 'SELECT m_id, m_from, m_to FROM messages WHERE m_id = ?';
	$stmt = $conn->mysqli->prepare($query);
	$stmt->bind_param('i', $sqlvariable1);
	$stmt->execute();
	$stmt->bind_result($first_value1, $first_value2, $first_value3);
	while ($stmt->fetch())
	{
		if ($_SESSION['user_id'] == $first_value2 || $_SESSION['user_id'] == $first_value3) {
			if($_SESSION['user_id'] == $first_value2) {
				$sqlvariable4 = $first_value3;
			} else {
				$sqlvariable4 = $first_value2;
			}
			$sqlvariable1 = $_GET['chat_message'];
			$sqlvariable2 = $_GET['text'];
			$sqlvariable3 = $_SESSION['user_id'];
			$sqlvariable5 = date('l F jS') . " " . date("h:ia");
			$conn = new Connection();
			$query = 'INSERT INTO chats (message_id, message_content, user_from, user_to, datestamp) VALUES (?, ?, ?, ?, ?)';
			$stmt = $conn->mysqli->prepare($query);
			$stmt->bind_param('isiis', $sqlvariable1, $sqlvariable2, $sqlvariable3, $sqlvariable4, $sqlvariable5);
			if (!$stmt->execute()) {
			//	echo "Something went wrong. Please contact the site administrator";
			} else {
			//	echo "It went through!";
			}
			$stmt->close();
			$conn->mysqli->close();
		}
	}
	header("Location: chats");
}
?>