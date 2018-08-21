<?php 
session_start();
if(isset($_POST['user']) && !empty($_POST['user']) && !empty($_POST['key']))
{
    $user = $_POST['user'];
	$sqlvariable1 = '1';
	
	if($_POST['key'] == "connectionNow") {

		require('database_conn.php');
		$conn = new Connection();
		$query = 'SELECT user_id, added_by FROM user_list WHERE user_id=? && added_by=?';
		$stmt = $conn->mysqli->prepare($query);
		$stmt->bind_param('ii', $_SESSION['user_id'], $user);
		$stmt->execute();
		$stmt->bind_result($user_id, $m_id);
		while ($stmt->fetch())
		{
		}
		if($user_id == $_SESSION['user_id']) {
		//	echo "A like exists";
			$conn = new Connection();
			$query = 'DELETE FROM user_list WHERE user_id=? && added_by=?';
			$stmt = $conn->mysqli->prepare($query);
			$stmt->bind_param('ii', $_SESSION['user_id'], $user);
			if (!$stmt->execute()) {
			//	echo "Something went wrong. Please contact the site administrator";
			} else {
			//	echo "<br>Like has been deleted";
			//	echo $_SERVER["HTTP_REFERER"];
			}
		} else {
		//	echo "No likes yet";
			$conn = new Connection();
			$query = 'INSERT INTO user_list (user_id, added_by) VALUES (?, ?)';
			$stmt = $conn->mysqli->prepare($query);
			$stmt->bind_param('ii', $_SESSION['user_id'], $user);
			if (!$stmt->execute()) {
			//	echo "Something went wrong. Please contact the site administrator";
			} else {
			//	echo "<br>New one created";
			//	echo $_SERVER["HTTP_REFERER"];
			}
		}
		$stmt->close();
		$conn->mysqli->close();


	}
}
?>