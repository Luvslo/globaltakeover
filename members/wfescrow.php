<?php
$title="Weapon Factory Escrow";
include("config.php");
include("header.php"); 

$row=mysql_fetch_array(mysql_query("SELECT username, money, bullets, tokens FROM Players WHERE id='{$_COOKIE['id']}' LIMIT 1;"));
$u = $row[0];
$mon = $row[1];
$bullet = $row[2];
$token = $row[3];

		$result=mysql_query("SELECT id, location FROM wf WHERE owner='$u' LIMIT 1;");
		$row = mysql_fetch_array($result);
		$aid = $row[0];
		$l = $row[1];
				
		if (mysql_num_rows($result) == 0) {
		if ($_REQUEST['Accept']) {
			$sql = mysql_query("SELECT * FROM wfescrow WHERE other ='$u'");
			$row = mysql_fetch_array($sql);
			if (mysql_num_rows($sql) == 1) {
			$money = $row['money'];
			$bullets = $row['bullets'];
			$tokens = $row['tokens'];
			$owner = $row['username'];
			$l = $row['location'];
			$taxm = floor($money * 0.09);
			$tmoney = $money + $taxm;
			if ($tmoney > $mon OR $bullets > $bullet OR $tokens > $token) {
			echo "<div id='crimestext' align='center'>You do not have enough to accept the escrow!<br /><a href='wf.php'>Go Back</a></div>";
			include("footer.php");
			exit();
			}
			$nmoney = $mon - $tmoney;
			$nbullets = $bullet - $bullets;
			$ntoken = $token - $tokens;
			mysql_query("UPDATE Players SET money='$nmoney', bullets='$nbullets', tokens='$ntoken' WHERE id='{$_COOKIE['id']}'");
			$sql = mysql_query("SELECT money, bullets, tokens FROM Players WHERE username = '$owner' LIMIT 1");
			$row = mysql_fetch_array($sql);
			$omoney = $row[0];
			$obullets = $row[1];			
			$otokens = $row[2];
			$nmoney = $omoney + $money;
			$nbullets = $obullets + $bullets;
			$ntokens = $otokens + $tokens;
			mysql_query("UPDATE Players SET money='$nmoney', bullets='$nbullets', tokens='$ntokens' WHERE username='$owner' LIMIT 1");
			mysql_query("UPDATE wf SET owner='$u' WHERE location='$l'");
			mysql_query("INSERT INTO Playertoken (amount, date, username, outcome, btime, used) VALUES ('$tokens', '$date', '$u', 'Loss', '$current', 'WF escrow')");
			mysql_query("INSERT INTO Playertoken (amount, date, username, outcome, btime, used) VALUES ('$tokens', '$date', '$owner', 'Gain', '$current', 'WF escrow')");
			$subject = htmlspecialchars(addslashes("Escrow Finished"));
			$message = htmlspecialchars(addslashes("$u has accepted the escrow on $l Weapon Factory for $".number_format($money).", ".number_format($bullets)." Bullets and ".number_format($tokens)." Tokens!"));
			mysql_query("INSERT INTO `pmessages` ( `title` , `message` , `touser` , `from` , `unread` , `date` ) VALUES ('$subject', '$message', '$owner', '$u', 'unread', '$date')");
			mysql_query("DELETE FROM wfescrow WHERE other = '$u'");
			echo "<div id='crimestext' align='center'>You have accepted and finished the escrow!<br><a href='Wfo.php'>Manage Factory</a></div>";
			include("footer.php");
			exit();
			} else {
			echo "<div id='crimestext' align='center'>There is not an escrow open!<br><a href='wf.php'>Go Back</a></div>";
			include("footer.php");
			exit();
			}
		} elseif ($_REQUEST['Decline']) {
		$sql = mysql_query("SELECT * FROM wfescrow WHERE other ='$u'");
			$row = mysql_fetch_array($sql);
			$other = $row['username'];
			if (mysql_num_rows($sql) == 1) {
			mysql_query("DELETE FROM wfescrow WHERE other = '$u'");
			echo "<div id='crimestext' align='center'>The escrow was cancelled!<br /><a href='wf.php'>Go Back</a></div>";
			include("footer.php");
			$subject = htmlspecialchars(addslashes("Escrow Canceled"));
			$message = htmlspecialchars(addslashes("$u has cancelled the escrow on $l Weapon Factory!"));
			mysql_query("INSERT INTO `pmessages` ( `title` , `message` , `touser` , `from` , `unread` , `date` ) VALUES ('$subject', '$message', '$other', '$u', 'unread', '$date')");
			exit();
			} else {
			echo "<div id='crimestext' align='center'>There is not an escrow open!<br /><a href='wf.php'>Go Back</a></div>";
			include("footer.php");
			exit();
			}
		}
		} else {
		echo "<div id='crimestext' align='center'>You currently own a Waapon Factory, you cannot own two!<br /><a href='wf.php'>Go Back</a></div>";
		include("footer.php");
		exit();
		}
?>