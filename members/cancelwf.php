<?php
$title="Cancel Escrow";
include("config.php");
include("header.php");
checks();
online();

$row=mysql_fetch_array(mysql_query("SELECT username FROM Players WHERE id='{$_COOKIE['id']}' LIMIT 1;"));
$u = $row[0];

		$result=mysql_query("SELECT id, location FROM wf WHERE owner='$u' LIMIT 1;");
		$row = mysql_fetch_array($result);
		$aid = $row[0];
		$l = $row[1];
				
		if (mysql_num_rows($result) >= 1) {
			$sql = mysql_query("SELECT * FROM wfescrow WHERE location ='$l'");
			$row = mysql_fetch_array($sql);
			$other = $row['other'];
			if (mysql_num_rows($sql) == 1) {
			mysql_query("DELETE FROM wfescrow WHERE location = '$l'");
			echo "<div id='crimestext' align='center'>The escrow was canceled!<br><a href='wfo.php'>Go Back</a></div>";
			include("footer.php");
			$subject = htmlspecialchars(addslashes("Escrow Canceled"));
			$message = htmlspecialchars(addslashes("$u has canceled the escrow for the $l Weapon Factory!"));
			mysql_query("INSERT INTO `pmessages` ( `title` , `message` , `touser` , `from` , `unread` , `date` ) VALUES ('$subject', '$message', '$other', 'Global Takeover', 'unread', '$date')");
			exit();
			} else {
			echo "<div id='crimestext' align='center'>There is not an escrow open!<br><a href='wfo.php'>Go Back</a></div>";
			include("footer.php");
			exit();
			}
		} else {
		echo "<div id='crimestext' align='center'>You do not own an Weapons Factory!<br><a href='wfo.php'>Go Back</a></div>";
		include("footer.php");
		exit();
		}
?>