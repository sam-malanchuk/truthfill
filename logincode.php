<?php
include 'config.php';
echo "<base href='" . $dir_path . "/' />";
if(!isset($_SESSION['user_id'])) {
	if(isset($_COOKIE['loginhash'])) {
		require('database_conn.php');
		$conn = new Connection();
		$query = 'SELECT user_id FROM users WHERE login_hash=?';
		$stmt = $conn->mysqli->prepare($query);
		$stmt->bind_param('s', $_COOKIE['loginhash']);
		$stmt->execute();
		$stmt->bind_result($userid_value);
		while ($stmt->fetch())
		{
			$_SESSION['user_id'] = $userid_value;
			$_SESSION['loginString'] = $_COOKIE['loginhash'];
		}
		$stmt->close();
		$conn->mysqli->close();
		if($userid_value == 0) {
			header("Location: " . $dir_path . "/login");
		}
		$_SESSION['loggedIn'] = true;
	}
}
	
$_SESSION['user_id'] = $_SESSION['user_id'];
$_SESSION['loginString'] = $_SESSION['loginString'];

	function loginWith($varible1, $varible2, $varible3){
		$server_check = SERVER;
		if($server_check != "localhost"){
			require('database_conn.php');
		}
		$conn = new Connection();
		$query = 'SELECT user_id, login_hash, fullname, username FROM users WHERE user_id=? AND login_hash=?';
		$stmt = $conn->mysqli->prepare($query);
		$stmt->bind_param('ss', $varible1, $varible2);
		$stmt->execute();
		$stmt->bind_result($check1, $check2, $info1, $info2);
		while ($stmt->fetch())
		{
			$_SESSION['fullname'] = $info1;
			$_SESSION['username'] = $info2;
			if(empty($check1) && empty($check2)){
				header("Location: login.php");
			} else {
			//	echo "login hash and user id are good";
			}
			if((($varible3 === 'dashboard') || ($varible3 === 'messages') || ($varible3 === 'chats')) && empty($info1) && empty($info2)){
				header("Location: edit.php?m=first");
			}
		}
		$stmt->close();
		$conn->mysqli->close();
	}
?>