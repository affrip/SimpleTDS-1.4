<?php

$pass = @rr('pass');
$pwd = @$_COOKIE['pwd'];
$loginok = false;
$mess = "";
 if ($pwd) { 	if ($pwd == md5($password)) { 		$loginok = true;
		$noform = true;
	} else {		$loginok = false;
		$noform = false;
	}
 }
 if ($pass) {	if ($pass == $password) {		$loginok = true;
		$noform = true;
		setcookie('pwd', md5($pass));
	} else {		$loginok = false;
		$noform = false;
		$mess = "Error!!! Wrong password!!! <br>";
	}
 }
 if (!$loginok) { echo $mess;
?>
<form name="" action="index.php" method="post">
	Password: <input name="pass" type="password" value=""><br>
	<input type="submit" value="Send">
</form>
<?php
 exit;
 }
?>