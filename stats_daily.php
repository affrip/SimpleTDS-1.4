<?php
include("config.php");
include("functions.php");
include("login.php");
include("header.php");

//if ($debug) print_r($_REQUEST);


$page = @rr('page');
if (!isset($page)) $page=1;

$fi_country = @rr('f_country');
$fi_schema = @rr('f_schema');
$fi_ref = @rr('f_ref');
$i_fi_ref = @rr('i_f_ref');
$fi_ip = @rr('f_ip');
$i_fi_ip = @rr('i_f_ip');
$fi_oid = @rr('f_oid');
$fi_se = @rr('f_se');
$fi_qs = @rr('f_qs');
$i_fi_qs = @rr('i_f_qs');
$i_fi_data_from_day = @rr('i_f_data_from_day');
$i_fi_data_from_month = @rr('i_f_data_from_month');
$i_fi_data_from_year = @rr('i_f_data_from_year');
$i_fi_data_to_day = @rr('i_f_data_to_day');
$i_fi_data_to_month = @rr('i_f_data_to_month');
$i_fi_data_to_year = @rr('i_f_data_to_year');



$kvo = $global_settings['stats_num_raws'];;
$from = ($page-1)*$kvo;
$num = $page*$kvo;

$where = "";
$url_add = "";
//Формируем запрос для поиска
if ($i_fi_data_from_day && $i_fi_data_from_month && $i_fi_data_from_year){
  	$where .= "AND (";
  	$where .= "`dt`>='$i_fi_data_from_year-$i_fi_data_from_month-$i_fi_data_from_day 00:00:00'";
	$where .= ") ";
}

if ($i_fi_data_to_day && $i_fi_data_to_month && $i_fi_data_to_year){
  	$where .= "AND (";
  	$where .= "`dt`<='$i_fi_data_to_year-$i_fi_data_to_month-$i_fi_data_to_day 23:59:59'";
	$where .= ") ";
}

if ($fi_country && (!in_array("ALL",$fi_country))){
  	$where .= "AND (";
  	foreach ($fi_country AS $key => $ff_country) {
  	  	$url_add .= "&f_country[]=$ff_country";
  	  	$ff_country = str_replace("None","",$ff_country);
  	  	if (!$key){
		  $where .= "`country`='$ff_country' ";
		} else {
		  $where .= "OR `country`='$ff_country' ";
		}
	}

	$where .= ") ";
}

if ($fi_schema && (!in_array("ALL",$fi_schema))){
  	$where .= "AND (";
  	foreach ($fi_schema AS $key => $ff_schema) {
  		$url_add .= "&f_schema[]=$ff_schema";
  	  	$ff_schema = str_replace("None","",$ff_schema);
  		if (!$key){
		  $where .= "`sid`='$ff_schema' ";
		} else {
		  $where .= "OR `sid`='$ff_schema' ";
		}
	}

	$where .= ") ";
}
if ($i_fi_ref) {
	$where .= "AND (`ref` LIKE '%$i_fi_ref%') ";
	$url_add .= "&i_f_ref=$i_fi_ref";
} else {
if ($fi_ref && (!in_array("ALL",$fi_ref)) && (!in_array("false",$fi_ref))){
  	$where .= "AND (";
  	foreach ($fi_ref AS $key => $ff_ref) {
  		$url_add .= "&f_ref[]=$ff_ref";
  	  	$ff_ref = str_replace("None","",$ff_ref);
  	  //	$ff_ref = urldecode($ff_ref);
  		if (!$key){
		  $where .= "`ref`='$ff_ref' ";
		} else {
		  $where .= "OR `ref`='$ff_ref' ";
		}
	}

	$where .= ") ";
}
}

if ($i_fi_ip) {
	$where .= "AND (`ip` LIKE '%$i_fi_ip%') ";
	$url_add .= "&i_f_ip=$i_fi_ip";
} else {
if ($fi_ip && (!in_array("ALL",$fi_ip)) && (!in_array("false",$fi_ip))){
  	$where .= "AND (";
  	foreach ($fi_ip AS $key => $ff_ip) {
  		$url_add .= "&f_ip[]=$ff_ip";
  	  	$ff_ip = str_replace("None","",$ff_ip);
  	  //	$ff_ref = urldecode($ff_ref);
  		if (!$key){
		  $where .= "`ip`='$ff_ip' ";
		} else {
		  $where .= "OR `ip`='$ff_ip' ";
		}
	}

	$where .= ") ";
}
}

if ($i_fi_qs) {
	$where .= "AND (`query_string` LIKE '%$i_fi_qs%') ";
	$url_add .= "&i_f_qs=$i_fi_qs";
} else {
if ($fi_qs && (!in_array("ALL",$fi_qs))){
  	$where .= "AND (";
  	foreach ($fi_qs AS $key => $ff_qs) {
  		$url_add .= "&f_qs[]=$ff_qs";
  	  	$ff_qs = str_replace("None","",$ff_qs);
  		if (!$key){
		  $where .= "`query_string`='$ff_qs' ";
		} else {
		  $where .= "OR `query_string`='$ff_qs' ";
		}
	}

	$where .= ") ";
}
}

if ($fi_se && (!in_array("ALL",$fi_se))){
  	$where .= "AND (";
  	foreach ($fi_se AS $key => $ff_se) {
  		$url_add .= "&f_se[]=$ff_se";
  	  	$ff_se = str_replace("None","",$ff_se);
  		if (!$key){
		  $where .= "`se`='$ff_se' ";
		} else {
		  $where .= "OR `se`='$ff_se' ";
		}
	}

	$where .= ") ";
}

if ($fi_oid && (!in_array("ALL",$fi_oid))){
  	$where .= "AND (";
  	foreach ($fi_oid AS $key => $ff_oid) {
  		$url_add .= "&f_oid[]=$ff_oid";
  	  	$ff_oid = str_replace("None","",$ff_oid);
  		if (!$key){
		  $where .= "`oid`='$ff_oid' ";
		} else {
		  $where .= "OR `oid`='$ff_oid' ";
		}
	}

	$where .= ") ";
}
//***************************

$qu = "SELECT LEFT(dt,10) as dat FROM `stats` WHERE 1 $where GROUP BY dat";
$result = mysql_query($qu);
$total = mysql_num_rows($result);

$qu = "SELECT COUNT(ip) as hits, COUNT(DISTINCT ip) as unics FROM `stats` WHERE 1 $where";
$result = mysql_query($qu);
$line = mysql_fetch_array($result);
$total_hits = $line['hits'];
$total_unics = $line['unics'];
//echo "$total $total_hits $total_unics<br>";
/*
$qu = "SELECT ip FROM `stats` GROUP BY `ip`";
$result = mysql_query($qu);
$ip_sel = "<select style='width:100%' name='f_ip[]' ><option value='false' SELECTED>--</option><option value='ALL' >ALL</option><option value='None'>None</option>";
while ($line = mysql_fetch_array($result)) {
 $ip = $line['ip'];
 if ($ip) {
	$ip_sel .= "<option value='$ip'>$ip</option>";
 }
}
$ip_sel .= "</select>";
*/

/*
$qu = "SELECT ref FROM `stats` GROUP BY `ref`";
$result = mysql_query($qu);
$ref_sel = "<select style='width:100%' name='f_ref[]' ><option value='false' SELECTED>--</option><option value='ALL'>ALL</option><option value='None'>None</option>";
while ($line = mysql_fetch_array($result)) {
 $ref = $line['ref'];
 if ($ref) {
	$ref_sel .= "<option value='$ref'>".urldecode($ref)."</option>";
 }
}
$ref_sel .= "</select>";
*/

/*
$qu = "SELECT country FROM `stats` GROUP BY `country`";
$result = mysql_query($qu);
$countries_sel = "<select style='width:100%' name='f_country[]' size='4' MULTIPLE><option value='ALL' SELECTED>ALL</option><option value='None'>None</option>";
while ($line = mysql_fetch_array($result)) {
 $cou = $line['country'];
 if ($cou) {
	$countries_sel .= "<option value='$cou'>$cou</option>";
 }
}
$countries_sel .= "</select>";
*/

$qu = "SELECT sid FROM `stats` GROUP BY `sid`";
$result = mysql_query($qu);
$schema_sel = "<select style='width:100%' name='f_schema[]' size='4' MULTIPLE><option value='ALL' SELECTED>ALL</option>";
while ($line = mysql_fetch_array($result)) {
 $sch = $line['sid'];
 $qu2 = "SELECT name FROM `schems` WHERE id=$sch";
 $result2 = mysql_query($qu2);
 $line2 = mysql_fetch_array($result2);
 $sch_name = $line2['name'];
 $schema_sel .= "<option value='$sch'>$sch $sch_name</option>";
}
$schema_sel .= "</select>";

/*
$qu = "SELECT se FROM `stats` GROUP BY `se`";
$result = mysql_query($qu);
$se_sel = "<select style='width:100%' name='f_se[]' size='4' MULTIPLE><option value='ALL' SELECTED>ALL</option><option value='None'>None</option>";
while ($line = mysql_fetch_array($result)) {
 $se = $line['se'];
 if ($se) $se_sel .= "<option value='$se'>$se</option>";
}
$se_sel .= "</select>";
*/

/*
$qu = "SELECT query_string FROM `stats` GROUP BY `query_string`";
$result = mysql_query($qu);
$qs_sel = "<select style='width:100%' name='f_qs[]'><option value='ALL' SELECTED>ALL</option><option value='None'>None</option>";
while ($line = mysql_fetch_array($result)) {
 $qs = $line['query_string'];
 if ($qs) $qs_sel .= "<option value='$qs'>$qs</option>";
}
$qs_sel .= "</select>";
*/

$qu = "SELECT oid FROM `stats` GROUP BY `oid`";
$result = mysql_query($qu);
$oid_sel = "<select style='width:100%' name='f_oid[]' size='4' MULTIPLE><option value='ALL' SELECTED>ALL</option>";
while ($line = mysql_fetch_array($result)) {
 $oid = $line['oid'];
 $qu2 = "SELECT url FROM `outs` WHERE id=$oid";
 $result2 = mysql_query($qu2);
 if (mysql_num_rows($result2)) {
   $line2 = mysql_fetch_array($result2);
   $out_url = $line2['url'];
   $dots = (strlen($out_url)>50)?"...":"";
   $out_url = substr($out_url,0,50).$dots;
 } else {
   $out_url = "Deleted";
 }
 $oid_sel .= "<option value='$oid'>$oid $out_url</option>";
}
$oid_sel .= "</select>";

$date_from_sel = "<select size=\"1\" height=\"1\" name=\"i_f_data_from_day\">";
$date_from_sel .= "<option value=\"0\" SELECTED>--</option>";
for ($ii=1;$ii<32;$ii++) {
 	$jj = sprintf("%02d",$ii);
	$date_from_sel .= "<option value=\"$jj\">$jj</option>";
}
$date_from_sel .= "</select>";
$date_from_sel .= "<select size=\"1\" height=\"1\" name=\"i_f_data_from_month\">";
$date_from_sel .= "<option value=\"0\" SELECTED>--</option>";
for ($ii=1;$ii<13;$ii++) {
 	$jj = sprintf("%02d",$ii);
	$date_from_sel .= "<option value=\"$jj\">$jj</option>";
}
$date_from_sel .= "</select>";
$date_from_sel .= "<select size=\"1\" height=\"1\" name=\"i_f_data_from_year\">";
$date_from_sel .= "<option value=\"0\" SELECTED>--</option>";
for ($ii=2007;$ii<2020;$ii++) {
 	$jj = sprintf("%04d",$ii);
	$date_from_sel .= "<option value=\"$jj\">$jj</option>";
}
$date_from_sel .= "</select>";


$date_to_sel = "<select size=\"1\" height=\"1\" name=\"i_f_data_to_day\">";
$date_to_sel .= "<option value=\"0\" SELECTED>--</option>";
for ($ii=1;$ii<32;$ii++) {
 	$jj = sprintf("%02d",$ii);
	$date_to_sel .= "<option value=\"$jj\">$jj</option>";
}
$date_to_sel .= "</select>";
$date_to_sel .= "<select size=\"1\" height=\"1\" name=\"i_f_data_to_month\">";
$date_to_sel .= "<option value=\"0\" SELECTED>--</option>";
for ($ii=1;$ii<13;$ii++) {
 	$jj = sprintf("%02d",$ii);
	$date_to_sel .= "<option value=\"$jj\">$jj</option>";
}
$date_to_sel .= "</select>";
$date_to_sel .= "<select size=\"1\" height=\"1\" name=\"i_f_data_to_year\">";
$date_to_sel .= "<option value=\"0\" SELECTED>--</option>";
for ($ii=2007;$ii<2020;$ii++) {
 	$jj = sprintf("%04d",$ii);
	$date_to_sel .= "<option value=\"$jj\">$jj</option>";
}
$date_to_sel .= "</select>";

/*
$ref_inp = "<input style='width:100%' type='text' name='i_f_ref' />";

$ip_inp = "<input style='width:100%' type='text' name='i_f_ip' />";

$qs_inp = "<input style='width:100%' type='text' name='i_f_qs' />";
*/

$qu = "SELECT LEFT(dt,10) as dat FROM `stats` WHERE 1 $where GROUP BY dat ORDER BY dat DESC LIMIT $from,$num";
$result = mysql_query($qu);


echo "<br/><br/><table border='1' cellpadding='4' width='100%'>";
echo "<tr background='img/bg_table.jpg' style='font-weight:bold;'><td height='30'>";
echo "Filters";
echo "</td><tr><td>";
$style1 = "style='width:15%'";
$style2 = "style='width:50%'";
$style3 = "";
echo "<form style='font-size:10px' method='GET' enctype='application/x-www-form-urlencoded' name='filters_form' id='filters_form' title='Filters'>
 <table style='width:100%'>
  <tr>
  <td style='width:15%'>
  <div>
  Дата:
  </div>
  <div>
  С:
  </div>
  <div>
  $date_from_sel
  </div>
  <div>
  По:
  </div>
  <div>
  $date_to_sel
  </div>
  </td>
  <td>
	<table style='width:100%'>
	<tr>
	 <td>
	 Схема:
	 </td>
	 <td>
	 Аут:
	 </td>
	</tr>
	<tr>
	 <td>
	 $schema_sel
	 </td>
	 <td>
	 $oid_sel
	 </td>
	</tr>
	</table>
  </td>
  </tr>
 </table>
 <input type='submit' value='Вперед' />
</form>
";
echo "</td></tr></table>";
echo "<table border='1' cellpadding='4' width='100%'>";
echo "<tr background='img/bg_table.jpg' style='font-weight:bold;'><td height='30'>";
echo "TDS Traffic Daily Stats (Showing $kvo records per page. Total $total records.)";
echo "</td><tr><td>";
echo "Pages ";
$nnum = 0;
$ppage = 1;
 while ($nnum <= $total) {
 	if ($page==$ppage) $col="#FF0000"; else $col = "#400000";
	echo "<a style='font-weight:bold; color:$col' href='stats.php?page=$ppage".$url_add."'>$ppage</a>  ";
	$nnum = $nnum+$kvo;
	$ppage++;
 }
echo "</td></tr></table>";

$sch = 0;
$shad = "#E0E0E0";
$light = "#FFFFFF";
$stat_style = "	border:1px solid #84D878;
				text-decoration:none;
				margin:2px;
				padding:2px;
				color:#84D878;
				font-size:9px;
				";
$fl = true;
echo "<table border='1' cellpadding='4' width='100%'>";
echo "<tr background='img/bg_table.jpg' style='font-weight:bold;'><td height='30'>";
echo "Date";
echo "</td><td>";
echo "Hits";
echo "</td><td>";
echo "Unics";
echo "</td><td>";
echo "% Hits";
echo "</td><td>";
echo "% Unics";
echo "</td><td style='width:200px'>";
echo "Graph Hits";
echo "</td><td style='width:200px'>";
echo "Graph Unics";
echo "</td></tr>";
$sch = 0;
$ii=0;
 while ($line = mysql_fetch_array($result)) {

    $datt = $line['dat'];
 	$qu2 = "SELECT COUNT(ip) as hits, COUNT(DISTINCT ip) as unics FROM `stats` WHERE dt>='$datt 00:00:00' AND dt<='$datt 23:59:59'";
 	$result2 = mysql_query($qu2);
 	$line2 = mysql_fetch_array($result2);
	$hits = $line2['hits'];
	$unics = $line2['unics'];

  	if ($fl) $col = $shad; else $col = $light;
	echo "<tr style='background:$col'><td id='c$sch'>";
	echo $datt;
	echo "</td><td>";
	echo $hits;
	echo "</td><td>";
	echo $unics;
	echo "</td><td>";
	$pers_h = ($hits/$total_hits)*100;
	echo $pers_h."%";
	echo "</td><td>";
	$pers_u = ($unics/$total_unics)*100;
	echo $pers_u."%";
	echo "</td><td>";
	echo "<div style='width:$pers_h%;height:10px;border:1px solid #B4B4B4;background-color: #FFAAAA'></div>";
	echo "</td><td>";
	echo "<div style='width:$pers_u%;height:10px;border:1px solid #B4B4B4;background-color: #AAFFAA'></div>";
	echo "</td></tr>";
	$fl = !$fl;
	$sch++;
 }
echo "</table><br/>";
?>
<!--Content end-->
</td>
</tr>
</table>
<br/>
</td>
</tr>
</table>