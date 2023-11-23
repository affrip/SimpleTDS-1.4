<?php
$server_time = date("Y-m-d H:i:s");
echo '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Simple TDS</title>
<link rel="STYLESHEET" type="text/css" href="img/style.css">
</head>
<body>
<table border="1" cellpadding="0" cellspacing="0" width="98%" align="center">
<tr>
<td bgcolor="#FFFFFF">
<br>
<table border="0" cellpadding="0" cellspacing="0" width="98%" align="center">
<tr>
<td valign="top">

	<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
	<tr>
	<td><img src="img/header1.jpg" width="10" height="108" alt="" border="0"></td>
	<td background="img/bg_header.jpg" width="100%">
<div class="header">Simple TDS Mod AffRip';

if ($debug) echo "  <font color=#ffff00> // Debug Mode!</font>";

echo '
</div>
<div class="subheader">v1.4 (Security Fix, upgrade to php 8.2!!!)</div>
<div class="time">Server Time: ';
echo $server_time;
echo '</div>
	</td>
	<td><img src="img/header2.jpg" width="11" height="108" alt="" border="0"></td>
	</tr>
	</table>

</td>
</tr>
<tr>
<td height="30" class="menu">
<div class="pl25">
<!--Menu start-->
<a class="menu" href="index.php">Home</a> | 
<a class="menu" href="stats.php">Stats</a> |
<a class="menu" href="stats_daily.php">Daily Stats</a> |
<a class="menu" href="settings.php">Settings</a> |
<a class="menu" href="http://aff.rip/" target="_blanc">Help</a> |
<a class="menu" href="http://aff.rip/">Support</a>
<!--Menu end-->
</div>
</td>
</tr>
<tr>
<td bgcolor="#FFFFFF" valign="top">
<!--Content start-->
<br>
';

?>