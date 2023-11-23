<head>
<link rel="STYLESHEET" type="text/css" href="img/style.css">
</head>
<?php
include("config.php");
include("functions.php");
include("login.php");

//$tds = new tds;
$sid = @gg('sid');
if (!$sid) $sid = @pp('sid');
if (!$sid) $sid=1;

$out_url = @rr('url');
$out_hits = @rr('hits');
$out_unics = @rr('unics');
$out_geo = @rr('geo');
$out_active = @rr('active');
$out_id = @rr('out_id');
$out_isparam = @rr('out_isparam');
$out_empty_ref = @rr('empty_ref');
$out_reserved = @rr('reserved');
$out_redir_type = @rr('redir_type');
$out_exitout = @rr('exitout');
$out_weight = @rr('weight');
$out_change_ref = @rr('change_ref');
$action = @rr('action');


 if ($action == 'delete') {           //Удаление аута

 	$qu = "DELETE FROM `outs` WHERE `id`=$out_id";
	$result = mysql_query ($qu); //Удаляем аут

	$qu = "DELETE FROM `filters` WHERE `id` IN (SELECT `fid` FROM `filt2o` WHERE `oid`=$out_id)";
	$result = mysql_query ($qu); //Удаляем все фильтры удаляемого аута

	$qu = "DELETE FROM `outs_stat` WHERE `oid`=$out_id";
	$result = mysql_query ($qu); //Удаляем всю статистику удаляемого аута

	$qu = "DELETE FROM `filt2o` WHERE `oid`=$out_id";
	$result = mysql_query ($qu); //Удаляем все связи фильтров и удаляемого аута

	$qu = "DELETE FROM `out2s` WHERE `oid`=$out_id";
	$result = mysql_query ($qu); //Удаляем связь аута со схемой
 }

 if ($action == 'add') {               //Добавление нового аута
 	if ($out_active) $act = 1; else $act=0;
	if ($out_isparam) $par = 1; else $par=0;
	if ($out_reserved) $res = 1; else $res=0;
	if ($out_exitout) $exi = 1; else $exi=0;
	if ($out_change_ref) $chref = 1; else $chref=0;
	if ($out_weight<1) $out_weight = 1;

	$qu = "INSERT INTO `outs` values (NULL,'$out_url',$act,'$out_geo',$par,'$out_empty_ref','$res','$out_redir_type','$exi','$out_weight','$chref');";
	$result = mysql_query ($qu); //Добавляем аут

	$out_id = mysql_insert_id();

	$qu = "INSERT INTO `out2s` values ($out_id,$sid);";
	$result = mysql_query ($qu); //Добавляем связь аута со схемой

	$qu = "INSERT INTO `outs_stat` values ($out_id,$out_hits,$out_unics);";
	$result = mysql_query ($qu); //Добавляем данные о статсах аута
 }

 if ($action == 'edit') {             //Редактирование аута
 	if ($out_active) $act = 1; else $act=0;
	if ($out_isparam) $par = 1; else $par=0;
	if ($out_reserved) $reserv = 1; else $reserv=0;
	if ($out_exitout) $exi = 1; else $exi=0;
	if ($out_change_ref) $chref = 1; else $chref=0;
	if ($out_weight<1) $out_weight = 1;

	$qu = "UPDATE `outs` SET `url`='$out_url',`active`=$act,`geo`='$out_geo',`isparam`=$par,`empty_ref`='$out_empty_ref',`reserved`=$reserv, `redir_type`='$out_redir_type', `exitout`='$exi', `weight`=$out_weight, `change_ref`=$chref WHERE `id`=$out_id";
	$result = mysql_query ($qu); //Изменяем данные об ауте

	$qu = "UPDATE `outs_stat` SET `hits`='$out_hits',`unics`='$out_unics' WHERE `oid`=$out_id";
	$result = mysql_query ($qu); //Изменяем данные о статсах аута
 }


$qu = "SELECT * FROM `schems` WHERE `id`=$sid";
$result = mysql_query ($qu); //Читаем данные о схеме

while ($rres = mysql_fetch_array($result)) {
	$schema_name = $rres['name'];
}

$schema_hits = 0;
$schema_unics = 0;
if ($result) {
 $qu = "SELECT SUM(`hits`) FROM `outs_stat` WHERE `oid` IN (SELECT `oid` FROM `out2s` WHERE `sid`=$sid)";
 $result = mysql_query ($qu); //Считаем сумму всех хитов для данной схемы
 $line = mysql_fetch_array($result);
 $schema_hits = $line[0];

 $qu = "SELECT SUM(`unics`) FROM `outs_stat` WHERE `oid` IN (SELECT `oid` FROM `out2s` WHERE `sid`=$sid)";
 $result = mysql_query ($qu); //Считаем сумму всех уников для данной схемы
 $line = mysql_fetch_array($result);
 $schema_unics = $line[0];

echo "<table border='1' width='100%' align=center>";
echo "<tr background='img/bg_table.jpg' style='font-weight:bold;'><td height='30'>";
echo "Editing OUTs of schema <b>$schema_name</b>:</td><td align=center>$schema_unics<sub>U</sub> ($schema_hits<sub>H</sub>)</td></tr>";
$co = 0;
$stat_style = "	border:1px solid #84D878;
				text-decoration:none;
				padding:1px;
				color:#84D878;
				font-size:10px;
				";
$qu = "SELECT * FROM `outs` WHERE `id` IN (SELECT `oid` FROM `out2s` WHERE `sid`=$sid) ORDER BY `reserved`,`exitout`,`id`";
$result = mysql_query ($qu); //Читаем все ауты для данной схемы...
 while ($line = mysql_fetch_array($result)){
 	$out_url = $line['url'];
	$out_id  = $line['id'];
	$out_active = $line['active'];
	$out_geo = $line['geo'];
	$out_isparam = $line['isparam'];
	$out_reserved = $line['reserved'];
	$out_redir_type = $line['redir_type'];
	$out_exitout = $line['exitout'];
	$out_weight = $line['weight'];
	$out_change_ref = $line['change_ref'];
	if (isset($line['empty_ref'])) $out_empty_ref = $line['empty_ref']; else $out_empty_ref = "";

	$qu = "SELECT * FROM `outs_stat` WHERE `oid`=".$out_id;
	$result2 = mysql_query ($qu); //...и их статы...
	$line2 = mysql_fetch_array($result2);
	$out_hits = $line2['hits'];
	if (isset($line2['unics'])) $out_unics = $line2['unics']; else $out_unics = 0;
	$bcol="#F2FFE4";
	if ($out_isparam) {$paramchk="true"; $paramchkd="CHECKED"; $parambcol="#80FF80";} else {$paramchk="false";$paramchkd="";$parambcol="#FF8080";}
	if ($out_reserved) {$chk2="true"; $chkd2="CHECKED"; $bcol="#E1E1E1";} else {$chk2="false";$chkd2="";}
	if ($out_exitout) {$chk3="true"; $chkd3="CHECKED"; $bcol="#DBF7F9";} else {$chk3="false";$chkd3="";}
	if ($out_active) {$chk="true"; $chkd="CHECKED";} else {$chk="false";$chkd="";$bcol="#FFCECE";}
	if ($out_change_ref) {$chref_chk="true"; $chref_chkd="CHECKED";} else {$chref_chk="false";$chref_chkd="";}
	if ($out_redir_type=="location") {$loc_sel="SELECTED";$curl_sel="";}
	if ($out_redir_type=="curl") {$loc_sel="";$curl_sel="SELECTED";}
	echo "<tr><td colspan=2 id='c'><br>";
	echo "<table border='1' cellpadding='4' width='100%'>";
	if ($co == 0) {
	echo "<tr background='img/bg_table.jpg' style='font-weight:bold;'><td>";
	echo "&nbsp;";
	echo "</td><td>";
	echo "OUT Url";
	echo "</td><td>";
	echo "Unics";
	echo "</td><td>";
	echo "Hits";
	echo "</td><td>";
	echo "GEO";
	echo "</td></tr>";
	}
	echo "<form style='display:inline; border:1px solid $bcol; padding:1px;' name='' action='' method='post'>
	<tr style='background:$bcol'><td rowspan=3 width='3%' style='background:#FFFFFF; font-weight:bold;' align='center'>
	$out_id<br/><br/>
	<span style='background:#DEF9C0; padding:1px; cursor:pointer; font-size:10px;border:1px solid #AFAFAF'>
	<a target='_top' href='stats.php?f_oid[]=$out_id'>&nbsp;S&nbsp;</a>
	</span>
	</td><td>
	<input style='width:100%' name='url' type='text' value='$out_url'>
	</td><td width='8%'>
	<input style='width:100%' name='unics' type='text' value='$out_unics'>
	</td><td width='8%'>
	<input style='width:100%' name='hits' type='text' value='$out_hits'>
	</td><td width='25%'>
	<input style='width:100%' name='geo' type='text' value='$out_geo'>
	</td>
	</tr>
	<tr style='background:$bcol'><td>
	Empty Ref = <input style='width:75%' name='empty_ref' type='text' value='$out_empty_ref'>
	<span style='border:1px solid #808080'>Change existing ref?<input name='change_ref' type='checkbox' value='$chref_chk' $chref_chkd></span>
	</td><td colspan=2>
	Weight = <input style='width:40%' name='weight' type='text' value='$out_weight'>
	</td><td>
	Redir Type
	<select size='1' name='redir_type'>
  		<option value='location' $loc_sel>Location</option>
  		<option value='curl' $curl_sel>Curl</option>
	</select>
	</td>
	</td>
	</tr>
	<tr style='background:$bcol'><td colspan=3>
	<span style='border:1px solid #808080'>Active?<input name='active' type='checkbox' value='$chk' $chkd></span>
	<span style='border:1px solid #808080'>FW Params?<input name='out_isparam' type='checkbox' value='$paramchk' $paramchkd></span>
	<span style='border:1px solid #808080'>Reserved?<input name='reserved' type='checkbox' value='$chk2' $chkd2></span>
	<span style='border:1px solid #808080'>Exit Out?<input name='exitout' type='checkbox' value='$chk3' $chkd3></span>
	<input name='sid' type='hidden' value='$sid'>
	<input name='out_id' type='hidden' value='$out_id'>
	<input name='action' type='hidden' value='edit'>
	<input type='submit' value='Save'>
	</form>
		<form onsubmit=\"return confirm('Are you sure to delete this OUT $out_id?');\" style='display:inline;' name='' action='' method='post'>
		<input name='action' type='hidden' value='delete'>
		<input name='out_id' type='hidden' value='$out_id'>
		<input type='submit' value='Del'>
		</form>
	</td><td>
	<input id='sbutt' style='width:100%;display:inline;' onclick = 'showf($out_id);' type='button' value='Show Filters&darr;'>
	</td></tr>";
	echo "</table>";
	$co++;
 }
echo "</table>";
}
echo "<br/><table border='1' width='100%' align=center>";
echo "<tr background='img/bg_table.jpg' style='font-weight:bold;'><td height='30'>";
echo "Add new OUT to schema <b>$schema_name</b>:</td></tr>";
echo "<tr><td id='c'>";
echo "<table border='1' cellpadding='3' width='100%'>";
echo "<tr background='img/bg_table.jpg' style='font-weight:bold;'><td>";
echo "&nbsp;";
echo "</td><td>";
echo "OUT Url";
echo "</td><td>";
echo "Unics";
echo "</td><td>";
echo "Hits";
echo "</td><td>";
echo "GEO";
echo "</td></tr>";
echo "<form style='padding:1px;' name='' action='' method='post'>
	<tr style='background:#FFFFFF'>
	<td rowspan=3 width='3%' style='font-weight:bold;'>
	&nbsp;
	</td><td>
	<input size='40' name='url' type='text' value=''>
	</td><td width='8%'>
	<input style='width:100%' name='unics' type='text' value='0'>
	</td><td width='8%'>
	<input style='width:100%' name='hits' type='text' value='0'>
	</td><td width='25%'>
	<input name='geo' type='text' value='ALL'>
	</td></tr>
	<tr style='background:#FFFFFF'><td>
	Empty Ref = <input style='width:75%' name='empty_ref' type='text' value=''>
	<span style='border:1px solid #808080'>Change existing ref?<input name='change_ref' type='checkbox'></span>
	</td><td colspan=2>
	Weight = <input style='width:40%' name='weight' type='text' value='1'>
	</td><td>
	Redir Type
	<select size='1' name='redir_type'>
  		<option value='location' SELECTED>Location</option>
  		<option value='curl'>Curl</option>
	</select>
	</td>
	</tr>
	<tr style='background:#FFFFFF'><td colspan=4>
	<span style='border:1px solid #808080'>Active?<input name='active' type='checkbox' value='true' CHECKED></span>
	<span style='border:1px solid #808080'>FW Params?<input name='out_isparam' type='checkbox' value='true' CHECKED></span>
	<span style='border:1px solid #808080'>Reserved?<input name='reserved' type='checkbox' value='false'></span>
	<span style='border:1px solid #808080'>Exit Out?<input name='exitout' type='checkbox' value='false'></span>
	<input name='sid' type='hidden' value='$sid'>
	<input name='action' type='hidden' value='add'>
	<input type='submit' value='Add'>
	</td></tr>
	</form>";
echo "</table>";
echo "</table>";
?>
<script language=javascript>

 function showf (id) {
	var ccc = parent.document.getElementById("filters");
	ccc.innerHTML = "<iframe style='border:1px solid #808080' width='100%' height='200' src='editfilters.php?id="+id+"'></iframe>";
 }

</script>