<head>
<link rel="STYLESHEET" type="text/css" href="img/style.css">
</head>
<?php
include("config.php");
include("functions.php");
include("login.php");


$oid = @rr('id');

$cond = @rr('cond');
$act = @rr('act');
$fid = @rr('fid');
$type = @rr('type');
$action = @rr('action');
$ftype = @rr('ftype');
$active = @rr('active');

if ($active) $active = 1; else $active = 0;

 if ($action == 'add') {
    $qu = "INSERT INTO `filters` values (NULL,'$type','$cond','$act','$ftype','$active');";
	$result = mysql_query ($qu); //Создаем фильтр
	$fid = mysql_insert_id();
	$qu = "INSERT INTO `filt2o` values ($fid,$oid);";
	$result = mysql_query ($qu); //Создаем связь фильтра с аутом
 }

 if ($action == 'delete') {
   	$qu = "DELETE FROM `filters` WHERE `id`=$fid";
	$result = mysql_query ($qu); //Удаляем фильтр
	$qu = "DELETE FROM `filt2o` WHERE `fid`=$fid";
	$result = mysql_query ($qu); //Удаляем связь фильтра и аута
 }

 if ($action == 'edit') {
 	$qu = "UPDATE `filters` SET `type`='$type',`cond`='$cond',`act`='$act',`ftype`='$ftype',`active`='$active' WHERE `id`=$fid";
	$result = mysql_query ($qu); //Изменяем фильтр
 }

$qu = "SELECT * FROM `filt2o` WHERE `oid`=$oid ORDER BY `fid`";
$result = mysql_query ($qu); //Берем все фильтры для данного аута
 if (mysql_num_rows($result)) {
  echo "<table border='1' align='center' width='100%'>";
  echo "<tr style='font-weight:bold;'><td background='img/bg_table.jpg' height='30'>";
  echo "Editing FILTERs of OUT ID $oid :</td></tr>";
  $co = 0;
  while ($line = mysql_fetch_array($result)) {
  	$ffid = $line['fid'];
	$qu = "SELECT * FROM `filters` WHERE `id`=".$ffid;
	$result2 = mysql_query ($qu);//Берем по одному фильтру для данного аута
	$line2 = mysql_fetch_array($result2);
  	$f_id = $line2['id'];
	$f_type  = $line2['type'];
	$f_cond= $line2['cond'];
	$f_act = $line2['act'];
	$f_ftype = $line2['ftype'];
	$f_active = $line2['active'];
	if ($f_active) {$chk="true"; $chkd="CHECKED"; $bcol="#F2FFE4";} else {$chk="false";$chkd="";$bcol="#FFCDCD";}
	if ($f_act=="allow") {$bcol2="#009D00"; $sel1="SELECTED"; $sel2="";} else {$bcol2="#E60000"; $sel2="SELECTED"; $sel1="";}
	if ($f_ftype=="1") {$bcol3="#009D00"; $chkd1="CHECKED"; $chkd2="";} else {$bcol3="#E60000"; $chkd2="CHECKED"; $chkd1="";}
	echo "<tr><td id='c'>";
	echo "<table border='1' cellpadding='4' width='100%'>";
	echo "<form style='display:inline; border:1px solid; padding:1px;' name='' action='' method='post'>
	 <tr style='background:$bcol'><td rowspan=2 width='3%' style='background:#FFFFFF; font-weight:bold;'>
		$f_id
	 </td><td>";
	if ($f_type == 'request') {
    echo "
    	<input name='type' type='hidden' value='request'>
		IF <input name='cond' type='text' value='$f_cond'>";
	}

	if ($f_type == 'ref_cont') {
	 echo "
	 	<input name='type' type='hidden' value='ref_cont'>
		IF HTTP_REFERER ==<input name='cond' type='text' value='$f_cond'>";
	}

	if ($f_type == 'ref_pres') {
	 echo "
	 	<input name='type' type='hidden' value='ref_pres'>
		IF HTTP_REFERER PRESENT <input name='cond' type='hidden' value='$f_cond'>";
	}

	if ($f_type == 'ip_range') {
	 echo "
	 	<input name='type' type='hidden' value='ip_range'>
		IF IP ADDRESS IN RANGE OF <input name='cond' type='text' value='$f_cond'>";
	}
	echo "
		THEN
		<select style='color:$bcol2' size='1' name='act'>
		  	<option style='color:#009D00' value='allow' $sel1>ALLOW</option>
		  	<option style='color:#E60000' value='block' $sel2>BLOCK</option>
		</select>
		 this OUT
		</td></tr><tr style='background:$bcol'><td>
		<input name='fid' type='hidden' value='$f_id'>
		<span style='border:1px solid #808080'>Active? <input name='active' type='checkbox' value='$chk' $chkd></span>
		<span style='border:1px solid #808080;display:none;'>
	 	AND<input name='ftype' type='radio' value='1' $chkd1>
	 	OR<input name='ftype' type='radio' value='2' $chkd2>
	 	</span>
		<input name='action' type='hidden' value='edit'>
		<input type='submit' value='Save'>
        </form>";
	echo "<form onsubmit=\"return confirm('Are you sure to delete this FILTER $f_id?');\" style='display:inline;' name='' action='' method='post'>
		<input name='action' type='hidden' value='delete'>
		<input name='fid' type='hidden' value='$f_id'>
		<input type='submit' value='Del'>
		</form>";
	echo "</td></tr></table>";
	$co++;
  }
  echo "</table>";
 }

 echo "<br><table border='1' cellpadding='4' width='100%' align=center>";
 echo "<tr background='img/bg_table.jpg' style='font-weight:bold;'><td height='30'>";
 echo "Add FILTER to OUT With ID $oid :</td></tr>";
 echo "<form style='border:1px solid; padding:1px;' name='' action='' method='post'>
	 <tr style='background:#FFFFFF'><td>
		<select size='1' name='type'>
		  <option value='request'>Query Parameter</option>
		  <option value='ref_cont'>HTTP_REFERER Content</option>
		  <option value='ref_pres'>HTTP_REFERER Present</option>
		  <option value='ip_range'>IP Range</option>
		</select>
		IF <input name='cond' type='text' value=''> THEN
		<select style='color:#009D00' size='1' name='act'>
		  	<option style='color:#009D00' value='allow' SELECTED>ALLOW</option>
		  	<option style='color:#E60000' value='block'>BLOCK</option>
		</select>
		this OUT
		<input name='action' type='hidden' value='add'>
	 </td></tr>
	 <tr style='background:#FFFFFF'><td>
	 	<span style='border:1px solid #808080'>Active? <input name='active' type='checkbox' value='1' CHECKED></span>
	 	<span style='border:1px solid #808080;display:none;'>
	 	AND<input name='ftype' type='radio' value='1' CHECKED>
	 	OR<input name='ftype' type='radio' value='2'>
	 	</span>
		<input type='submit' value='Add'>
	 </td></tr>
	 </form>";
 echo "</table>";
?>