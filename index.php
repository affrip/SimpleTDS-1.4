<?php
include("config.php");
include("functions.php");
include("login.php");
include("header.php");


$action = @rr('action');
$name = @rr('name');
$sid = @rr('sid');

if ($action == 'add') { //���������� �����
	$qu = "INSERT INTO `schems` values (NULL,'$name');";
	$result = mysql_query ($qu);
}

if ($action == 'reset') {
	echo "No available!!!<br>";
}

 if ($action == 'delete') {
	$qu = "DELETE FROM `schems` WHERE `id`=$sid";
	$result = mysql_query ($qu); //������� �����

	$qu = "DELETE FROM `outs` WHERE `id` IN (SELECT `oid` FROM `tds_out2s` WHERE `sid`=$sid)";
	$result = mysql_query ($qu); //������� ��� ���� ��������� �����

	$qu = "DELETE FROM `filters` WHERE `id` IN (SELECT `fid` FROM `filt2o` WHERE `oid` IN (SELECT `oid` FROM `out2s` WHERE `sid`=$sid))";
	$result = mysql_query ($qu); //������� ��� ������� ���� ����� ��������� �����

	$qu = "DELETE FROM `outs_stat` WHERE `oid` IN (SELECT `oid` FROM `out2s` WHERE `sid`=$sid)";
	$result = mysql_query ($qu); //������� ��� ���������� ����� ��������� �����

	$qu = "DELETE FROM `filt2o` WHERE `oid` IN (SELECT `oid` FROM `out2s` WHERE `sid`=$sid)";
	$result = mysql_query ($qu); //������� ��� ����� �������� � ����� ��������� �����

	$qu = "DELETE FROM `out2s` WHERE `sid`=$sid";
	$result = mysql_query ($qu); //������� ��� ����� ����� � ��������� �����
 }

$shad = "#EAEAEA";
$light = "#FFFFFF";
$fl = true;
echo "<table border='0' cellpadding='1' cellspacing='0' width='100%'>";
echo "<tr><td width='40%' valign=top>";

echo "<table border='1' cellpadding='5' width='400'>";
echo "<tr background='img/bg_table.jpg'><td>";
echo "<strong>ID</strong>";
echo "</td><td width='100'>";
echo "<strong>Schema Name</strong>";
echo "</td><td>";
echo "<strong>Send Traffic URL</strong>";
echo "</td><td>";
echo "<strong>Opt.</strong>";
echo "</td></tr>";
$sch = 0;
$stat_style = "	border:1px solid #84D878;
				text-decoration:none;
				padding:1px;
				color:#84D878;
				font-size:10px;
				";

$qu = "SELECT * FROM `schems` ORDER BY `id`";
$result = mysql_query ($qu);
 while ($schema = mysql_fetch_array($result)) {
	if ($fl) $col = $shad; else $col = $light;
	echo "<tr style='background:$col'><td id='c$sch'>";
	echo $ssid = $schema['id'];
	echo "</td><td>";
	echo $sname = $schema['name'];
	echo "</td><td>";
	echo "<a target='_blank' href='$scripturl"."go.php?sid=".$ssid."'>$scripturl"."go.php?sid=".$ssid."</a>";
	echo "</td><td>";
	echo "<div onclick='rems(".$ssid.",\"".$sname."\")' style='background:#FEDC9A; padding:1px; cursor:pointer; font-size:10px;border:1px solid #AFAFAF'>DEL</div>
	<a style='background:#DEF9C0; padding:1px; cursor:pointer; font-size:10px;border:1px solid #AFAFAF; display:block' href='stats.php?f_schema[]=$ssid'>&nbsp;<strong>S</strong>&nbsp;</a>
	<div onclick='showout(".$ssid.")' style='background:#DBF7F9; padding:1px; cursor:pointer; font-size:10px;border:1px solid #AFAFAF'>OUTS&rarr;</div>
	<form style='display:none' name='ddd$ssid' action='' method='post'>
	<input name='action' type='hidden' value='delete'>
	<input name='sid' type='hidden' value='$ssid'>
	</form>";
	echo "</td></tr>";
	$fl = !$fl;
	$sch++;
 }
echo "</table>

";
//window.parent.frames['nav'].document.getElementById
?>
<br/>
<form style="margin:2px;" name="aaa" action="" method="post">
<input name="action" type="hidden" value="add">
<input id="iii" name="name" type="text" value="">
<input type="submit" value="Add New Schema">
</form>
<?php
if ($debug) {
?>
<br/><hr/>
<form style="display:inline; margin:2px;" name="aaa" action="" method="post">
<input name="action" type="hidden" value="reset">
<input type="submit" value="Reset Auto Inc">
</form>
<?php
}
?>
All Countries
<a href="#" onclick="showc();return true;">Show</a> |
<a href="#" onclick="hidec();return true;">Hide</a>
</td><td valign=top>
<div id='outs'></div>
</td></tr></table>
<script language=javascript>

 function rems (sid,sname) {
	var is_del = confirm("Are you sure to delete schema '"+sname+"' with id="+sid+"?");
	if (is_del) {
		document.forms['ddd' + sid].submit();
	}
 }
 function showc () {
	var ccc = document.getElementById("country");
	ccc.innerHTML = "<iframe style='border:1px solid #B2B2B2' width='99%' height='200' src='allc.php'></iframe>";
 }
 function hidec () {
	var ccc = document.getElementById("country");
	ccc.innerHTML = "";
 }
 function showout (id) {
 	var ccc = document.getElementById("outs");
	ccc.innerHTML = "<iframe style='border:1px solid #B2B2B2' width='100%' height='300' src='editout.php?sid="+id+"'></iframe>";
	var ccc2 = document.getElementById("filters");
	ccc2.innerHTML = "";
 }
</script>
<table width='100%'>
<tr><td width='40%' valign=top>
<div id='country'></div>
</td><td valign=top>
<div id='filters'></div>
</td>
</tr>
</table>


<br/><br/><br/>
<!--Content end-->
</td>
</tr>
<tr>
<td class="copy" height="30" bgcolor="#585B61" style="padding: 10px; border-radius: 5px;">
	<p>Script author: mizhgan</p>
	<p>Mod <a href="https://aff.rip/">Aff.Rip</a> crew, 2023</p>
	<p>Donate BTC: <span style="color: red;">bc1qfy8ldp4ggzetmak8jyml9mqlla9h3dreqznge7</span></p>
</td>
</tr>
</table>

<br/>
</td>
</tr>
</table>