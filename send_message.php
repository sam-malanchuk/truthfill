<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
	
if (!empty($_POST)) {	
	if(isset($_SESSION['loggedIn'])) {
		if ($_POST['viewusername'] !== $_SESSION['username']) {
			require('database_conn.php');
			$currentDate = date('m/d/Y');
			$m_id = 0;
			$conn = new Connection();
			$query = 'SELECT m_id FROM messages WHERE m_from=? AND m_to=? AND m_date=?';
			$stmt = $conn->mysqli->prepare($query);
			$stmt->bind_param('iis', $_SESSION['user_id'], $_SESSION['viewuser_id'], $currentDate);
			$stmt->execute();
			$stmt->bind_result($m_id);
			while ($stmt->fetch())
			{
			}
			$stmt->close();
			$conn->mysqli->close();

			if($m_id == 0) {
				$conn = new Connection();
				$query = 'INSERT INTO messages (m_content, m_date, m_status, m_from, m_to) VALUES (?, ?, 1, ?, ?)';
				$stmt = $conn->mysqli->prepare($query);
				$stmt->bind_param('ssii', $_POST['message_content'], $currentDate, $_SESSION['user_id'], $_SESSION['viewuser_id']);
				if (!$stmt->execute()) {
			//		echo "Something went wrong. Please contact the site administrator";
					header("Location: user/" . $_POST['viewusername'] . "?m=error");
				} else {
			//		echo "Everything is good";
					header("Location: user/" . $_POST['viewusername'] . "?m=success");
				}
				$stmt->close();
				$conn->mysqli->close();
			} else {
					header("Location: user/" . $_POST['viewusername'] . "?m=limit");
			}
		} else {
			header("Location: user/" . $_POST['viewusername'] . "?m=error");
		}
	} else {
		require('database_conn.php');
		$currentDate = date('m/d/Y');
		$tempUser = 0;
		$conn = new Connection();
		$query = 'INSERT INTO messages (m_content, m_date, m_status, m_from, m_to) VALUES (?, ?, 1, ?, ?)';
		$stmt = $conn->mysqli->prepare($query);
		$stmt->bind_param('ssii', $_POST['message_content'], $currentDate, $tempUser, $_SESSION['viewuser_id']);
		if (!$stmt->execute()) {
		//	echo "Something went wrong. Please contact the site administrator";
			header("Location: user/" . $_POST['viewusername'] . "?m=error");
		} else {
		//	echo "Everything is good";
			header("Location: user/" . $_POST['viewusername'] . "?m=success");
		}
		$stmt->close();
		$conn->mysqli->close();
	}
}

?>
<br><br><a href="user/<?php echo $_POST['viewusername']; ?>">Done, go back</a>