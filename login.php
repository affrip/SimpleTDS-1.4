<?php

$pass = @rr('pass');
$pwd = @$_COOKIE['pwd'];
$loginok = false;
$mess = "";
 if ($pwd) {
		$noform = true;
	} else {
		$noform = false;
	}
 }
 if ($pass) {
		$noform = true;
		setcookie('pwd', md5($pass));
	} else {
		$noform = false;
		$mess = "Error!!! Wrong password!!! <br>";
	}
 }
 if (!$loginok) {
?>
<form name="" action="index.php" method="post">
	Password: <input name="pass" type="password" value=""><br>
	<input type="submit" value="Send">
</form>
<?php
 exit;
 }
?>