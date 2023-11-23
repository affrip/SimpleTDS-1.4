<?php

include("config.php");
include("functions.php");
include("kw.php");
//include("geoip/geoip.inc");


function getip ()
  {
    if ((@getenv('HTTP_CLIENT_IP') AND @strcasecmp(@getenv('HTTP_CLIENT_IP'), 'unknown')))
    {
      $ip = @getenv('HTTP_CLIENT_IP');
    }
    else
    {
      if ((@getenv('HTTP_X_FORWARDED_FOR') AND @strcasecmp(@getenv('HTTP_X_FORWARDED_FOR'), 'unknown')))
      {
        $ip = @getenv('HTTP_X_FORWARDED_FOR');
      }
      else
      {
        if ((@getenv('REMOTE_ADDR') AND @strcasecmp(@getenv('REMOTE_ADDR'), 'unknown')))
        {
          $ip = @getenv('REMOTE_ADDR');
        }
        else
        {
          if (((isset($_SERVER['REMOTE_ADDR']) AND @$_SERVER['REMOTE_ADDR']) AND @strcasecmp(@$_SERVER['REMOTE_ADDR'], 'unknown')))
          {
            $ip = @$_SERVER['REMOTE_ADDR'];
          }
          else
          {
            $ip = 'Unknown';
          }
        }
      }
    }

    return $ip;
  }

/*���� ������ � IN-��������*/
$ip = @getip(); //IP-����� �������
if (stristr($ip,",")) {
	$ip_arr = explode(",",$ip);
	$ip = $ip_arr[0];
}
if ($debug) $ip = "210.65.34.86"; // !!! TEST TW (TAIWAN) IP-Address !!!
$ip_number = sprintf("%u", ip2long($ip));
$cc = @$_SERVER["GEOIP_COUNTRY_CODE"];	//Country Code �������
$cn = @$_SERVER["GEOIP_COUNTRY_NAME"];	//Country Name �������
$cc = "TW"; // remove this if you have geoip installed
$cn = "TW";
if ((!isset($cc) || empty($cc)) && !$debug) {
	$ip = escapeshellarg($ip); // FIX :)
 $addr = @explode (' ', @shell_exec ('' . $geoip_path . ' ' . $ip));
 $cc = @str_replace (',', '', @$addr[3]);
 $cc = @trim ($cc);
 $cn = $cc;
}
if ((!isset($cc) || empty($cc) || $cc=="")) {
 $gi = @geoip_open("geoip/GeoIP.dat",GEOIP_STANDARD);
 $cc = @geoip_country_code_by_addr($gi, $ip);
 $cn = @geoip_country_name_by_addr($gi, $ip);
 geoip_close($gi);
}
if ($debug) $cc = "TW"; // !!! TEST TW  !!!
if ($debug) $cn = "Taiwan"; // !!! TEST TAIWAN !!!
$getparams = $_GET;
unset($getparams['sid'],$getparams['sname']);
$ua = @$_SERVER['HTTP_USER_AGENT'];
$ref = @$_SERVER['HTTP_REFERER']; //HTTP_REFERER �������
if ($debug) $ref = "http//www.fff.com?sdsd=sdfsd&df=1&tt=%F3%4D"; // !!! TEST !!!
/***************************/

//echo "<pre>";
//print_r ($_SERVER);
//print_r($GLOBALS);
//echo "</pre>";

$sid = @gg('sid');
$refref = @gg('sref');
if ($debug) $refref = "http://www.google.com/search?q=buy%20xanax%20online";
unset($getparams['sref']);

unset($metaparams);
foreach ($getparams AS $key=>$value) {
	if (substr($key,0,4)=="tds-") {
		$metaparams[$key] = $value;
		unset($getparams[$key]);
	}
}

if ($debug) {
	echo "GET-��������� ����� �������� ���������:<br>";
	print_r($getparams);
	echo "<br>";
}
$se_url = $se_query = false;
//�������� ��������� ������ �� ���������� sref, � ������� ������� ��� (���� �������) ��������� � �������� ������ �� ���
if ($refref) {
	$refref_info = getInfo(trim(urldecode($refref)));
	if ($refref_info) {
		$se_url = (isset($refref_info['sengine_url']))?$refref_info['sengine_url']:false;
		$se_query = (isset($refref_info['query']))?$refref_info['query']:false;
	}
}


$schema_visited = rr('schema'.$sid);

$qu = "SELECT * FROM `schems` WHERE `id`=$sid";
$result = mysql_query ($qu); //������ ������ ����� �����
 if (!mysql_num_rows($result)) {   //���� ��� ����� ����� (sid �� ��� ��������) - ���� �� ��������� ���.
	if ($debug) echo "Cant fetch any schema with this sid!!! Going to reserved URL!!!<br>";
			gotoreserved();
			exit;

 }
$line = mysql_fetch_array($result);
$schema_name = @$line['name'];
if ($debug) echo "Executing schema <b>$schema_name</b>...<br>";


$qu = "SELECT * FROM `outs` WHERE `id` IN(SELECT `oid` FROM `out2s` WHERE `sid`=$sid) AND `active`=1 AND `reserved`=0 AND `exitout`=0";
$result = mysql_query ($qu); //����� ��� �������� ����������� ���������� ���� ��� ���� �����
unset($out_urls,$out_ids,$out_geos,$out_isparams,$out_empty_refs,$out_redir_types,$out_weights,$out_change_refs);
if (mysql_num_rows($result)) {
 while ($line2 = mysql_fetch_array($result)) {
	if (isset($line2['empty_ref'])) $out_empty_ref = $line2['empty_ref']; else $out_empty_ref = "";
	if ($debug) echo "Find out - <b>".$line2['url']."</b><br>";
	$out_end_url = str_replace("{{key}}",$se_query,$line2['url']); //�������� �������������� {{key}} ��������� ��������
	if(isset($metaparams) && $metaparams !== null && count($metaparams)) {
	 foreach ($metaparams AS $key=>$value) {
		 $out_end_url = str_replace("{{".$key."}}",$value,$out_end_url); //�������� ������������ �������������� �����. ���������
	 }
 }
	$out_urls[] = $out_end_url;
	$out_ids[]  = $line2['id'];
	$out_geos[]  = $line2['geo'];
	$out_isparams[]= $line2['isparam'];
	$out_redir_types[]= $line2['redir_type'];
	$out_weights[]= $line2['weight'];
	$out_empty_refs[] = $out_empty_ref;
	$out_change_refs[]= $line2['change_ref'];
 }
} else {    //���� ����� ��� ���� ��� �� ������ ��������� ���� - ���� �� ��������� ���
 if ($debug) echo "Error!!! No one OUT wasn`t set for this schema ID or no one ACTIVE OUT.!!! Going to reserved URL!!!<br>";
 			gotozapas($sid);
			gotoreserved();
			exit;
}

$qu = "SELECT * FROM `outs` WHERE `id` IN(SELECT `oid` FROM `out2s` WHERE `sid`=$sid) AND `exitout`=1 AND `active`=1";
$result = mysql_query ($qu); //����� ��� �������� ���� ��� ���� �����
unset($exitout_urls,$exitout_ids,$exitout_geos,$exitout_isparams,$exitout_empty_refs,$exitout_redir_types,$exitout_weights,$exitout_change_refs);
if (mysql_num_rows($result)) {
 $have_exitout = true;
 while ($line2 = mysql_fetch_array($result)) {
	if (isset($line2['empty_ref'])) $exitout_empty_ref = $line2['empty_ref']; else $exitout_empty_ref = "";
	if ($debug) echo "Find exitout - <b>".$line2['url']."</b><br>";
	$exitout_end_url = str_replace("{{key}}",$se_query,$line2['url']); //�������� �������������� {{key}} ��������� ��������
	foreach ($metaparams AS $key=>$value) {
		$exitout_end_url = str_replace("{{".$key."}}",$value,$exitout_end_url); //�������� ������������ �������������� �����. ���������
	}
	$exitout_urls[] = $exitout_end_url;
	$exitout_ids[]  = $line2['id'];
	$exitout_geos[]  = $line2['geo'];
	$exitout_isparams[]= $line2['isparam'];
	$exitout_redir_types[]= $line2['redir_type'];
	$exitout_weights[]= $line2['weight'];
	$exitout_empty_refs[] = $exitout_empty_ref;
	$exitout_change_refs[]= $line2['change_ref'];
 }
} else {    //���� exit����� ��� ���� ��� �� ������ ��������� exit����
 if ($debug) echo "��� ����������. ������� ����� ��������� �� ���� ����� �� �����.<br>";
 $have_exitout = false;
}

################### ������ ����� ###############################
/*GEO ��������*/
 foreach ($out_geos as $nn=>$curr_geo) {
 	$todel = false;
	if (!isset($cc) && $nogeoip == "allow") {
		continue;
	}
 	$geos_array = explode(",",$curr_geo);
	if (in_array($cc,$geos_array)) continue;
	if (in_array("!".$cc,$geos_array)) {
		$todel=true;
	} else {
		if (in_array('ALL',$geos_array)) continue; else $todel = true;
	}
    if ($todel) {
    	if ($debug) echo "$out_urls[$nn] NOT SATISFIED GEO CONDITION!!! WILL BE SKIPPED!!!<br>";
    	unset ($out_urls[$nn], $out_ids[$nn], $out_geos[$nn], $out_isparams[$nn], $out_empty_refs[$nn],$out_redir_types[$nn]); //��������� ���� �� ��������� ��� ��������
	}
 }
/**************/

/**** �������� �� �������������� �������� ****/
 foreach ($out_ids AS $nn=>$oid) {
 	$todel=false;
	$skip = false;

	$qu = "SELECT * FROM `filters` WHERE `id` IN (SELECT `fid` FROM `filt2o` WHERE `oid`=$oid) AND `active`=1";
	$result = mysql_query($qu); //����� ��� ���. ������� �� ������� ����������� ����
	unset ($f_ids);
	 if (!mysql_num_rows($result)) {
		if ($debug) echo "The OUT with id=$oid have not additional filters or ALL filters are disabled.<br>"; //���� ���. �������� ��� ��� ������� ���������. ��������� ���� ���
		$skip = true;
	 }
	if ($skip) continue;
	if ($debug) echo "The OUT with id=$oid have additional filters.<br>"; //���� ���.�������. ����� ��������� �����.

	 while (($line = mysql_fetch_array($result)) && !$todel) {
		$f_id  = $line['id'];
		$f_type = $line['type'];
		$f_cond = $line['cond'];
		$f_act  = $line['act'];
		$f_ftype = $line['ftype'];
		if ($debug) echo "Find filter - <b>$f_id $f_type $f_cond $f_act $f_ftype</b><br>";

       //��������� ������ �-��� !!!!   ���� ������ �.

		/*�������� �� �������-���������� ������*/
		if ($f_type == 'ref_pres') {
			if ($ref) {
				$todel = ($f_act=='allow') ? false : true;
			} else {
				$todel = ($f_act=='allow') ? true : false;
			}
		}
		/**************************************/

		/*�������� �� IP ��������*/
		if ($f_type == 'ip_range') {
			list($ipmin,$ipmax) = @explode ("-", $f_cond);
            $ipmin = sprintf("%u", @ip2long($ipmin));
			$ipmax = sprintf("%u", @ip2long($ipmax));

			if (($ip_number>=$ipmin) && ($ip_number<=$ipmax)) {
				$todel = ($f_act=='allow') ? false : true;
			} else {
				$todel = ($f_act=='allow') ? true : false;
			}
		}
		/**************************************/

		/*�������� �� ����������� ������*/
		if ($f_type == 'ref_cont') {
			if (searchany($ref,$f_cond)) {
				$todel = ($f_act=='allow') ? false : true;
			} else {
				$todel = ($f_act=='allow') ? true : false;
			}
		}
		/**************************************/

		/*�������� �� ����������� ��������� ������ �������*/
		if ($f_type == 'request') {
			list ($f_param,$f_value) = @explode ("==", $f_cond);
			if (searchany(@$getparams[$f_param],$f_value)) {
				$todel = ($f_act=='allow') ? false : true;
			} else {
				$todel = ($f_act=='allow') ? true : false;
			}
		}
		/**************************************/
	 }
	if ($todel) {     //��� ���� �� ������������ ������ �� allow-��������, ���� ������������ ������ �� block. ������� ���.
    	if ($debug) echo "$out_urls[$nn] NOT SATISFIED ADDITIONAL FILTER!!! WILL BE SKIPPED!!!<br>";
    	unset ($out_urls[$nn], $out_ids[$nn], $out_geos[$nn], $out_isparams[$nn], $out_empty_refs[$nn], $out_redir_types[$nn]);
	}
 }
/*********************************************/

 if (empty($out_urls)) {     //��� ���� �������. ������ ���� �� ������ �� ��� ��������, ���� �������� ���������. ���� �� ��������� ���.
 	if ($debug) echo "Warning!!! Losing traff!!! The $cc country ($cn) is not satisfied any GEO conditions in outs OR additional filters is too strong!!! Going to reserved URL!!!<br>";
 			gotozapas($sid);
			gotoreserved();
			exit;

 }

############ �������� ����� �� ����� ##########################
$visited_outs = rr('visited'.$sid);
 if (isset($visited_outs)) {
	$vis_outs_arr = explode(",", urldecode($visited_outs));
     unset($un_out_ids);
 	 foreach($out_ids AS $key=>$out_id) {
		if (array_search($out_id,$vis_outs_arr) === false) $un_out_ids[$key] = $out_id;    //������� ����, ��������� ������ ���� ��� ���
	 }
 	 if (!isset($un_out_ids)) { //��� �� ������ ������������� ����, ����� ����.
 	  $all_outs_visited = true;
 	  if (!$have_exitout) {
	    setcookie("visited".$sid);
		$vis_outs_arr = array();
		$visited_outs = "";
	  }
	 } else {
	    $all_outs_visited = false;
	 	$out_ids = $un_out_ids;
	 }
 }
###############################################################
/*����������� �� ����� �����*/
$sum_weight = 0;
if (isset($out_ids)) {
foreach($out_ids as $key => $each_id){
	$sum_weight = $sum_weight + $out_weights[$key];
}
if ($debug) {
	echo "����� ������������ - $sum_weight<br>";
}
}

$sum_weight_exitout = 0;
if (isset($exitout_ids)) {
foreach($exitout_ids as $key => $each_id){
	$sum_weight_exitout = $sum_weight_exitout + $exitout_weights[$key];
}
if ($debug) {
	echo "����� ������������ ��� ���������� (���� ��� ����) - $sum_weight_exitout<br>";
}
}
/****************************/

if ($have_exitout && $all_outs_visited){ //���� ��� ���� ��� �������� ������ ������� (�� ����) � ���� ���������, �� ���� �� ���� �� ����������
/*����� ��������� ������ �� �����*/
 $random_number_exitout = rand(1,$sum_weight_exitout);
 if ($debug) {
 	echo "��������� ����� ��� ���������� - $random_number_exitout<br>";
 }
 $min_val = 0;
 foreach($exitout_ids as $key => $each_id){
	$max_val = $min_val + $exitout_weights[$key];
	if ($random_number_exitout>$min_val && $random_number_exitout<=$max_val) {
		$rand_num = $key;
	}
	$min_val = $max_val;
 }
/*********************************/
 //$rand_num = array_rand($exitout_ids);
 $redir_url = $exitout_urls[$rand_num];
 $redir_id = $exitout_ids[$rand_num];
 $redir_geo  = $exitout_geos[$rand_num];
 $redir_isparam = $exitout_isparams[$rand_num];
 $out_redir_type = $exitout_redir_types[$rand_num];
 $redir_empty_ref = $exitout_empty_refs[$rand_num];
} else {
/*����� ���� ������ �� �����*/
 $random_number = rand(1,$sum_weight);
 if ($debug) {
 	echo "��������� ����� ��� ���� - $random_number<br>";
 }
 $min_val = 0;
 foreach($out_ids as $key => $each_id){
	$max_val = $min_val + $out_weights[$key];
	if ($random_number>$min_val && $random_number<=$max_val) {
		$rand_num = $key;
	}
	$min_val = $max_val;
 }
/*********************************/
 //$rand_num = array_rand($out_ids);     //���� �� ���� ������������ ����, ���� ��������� ���, �� ���� �� ���� �� ���������� �����
 $redir_url = $out_urls[$rand_num];
 $redir_id  = $out_ids[$rand_num];
 $redir_geo  = $out_geos[$rand_num];
 $redir_isparam = $out_isparams[$rand_num];
 $redir_empty_ref = $out_empty_refs[$rand_num];
 $out_redir_type = $out_redir_types[$rand_num];
 $redir_change_ref= $out_change_refs[$rand_num];;
}
################# ����� ������ #################################

if (!$ref || $redir_change_ref) {
	$_SERVER['HTTP_REFERER'] = $redir_empty_ref;
	$ref = @$_SERVER['HTTP_REFERER'];
}


$qu = "SELECT * FROM `outs_stat` WHERE `oid`=$redir_id";
$result = mysql_query($qu);
$line = mysql_fetch_array($result);
$redir_hits=$line['hits'] + 1;
$redir_unics=$line['unics'];
if (!$schema_visited) {
	$redir_unics++;
	setcookie("schema".$sid,"true",time()+$global_settings['user_unic_time']);
}
$qu = "UPDATE `outs_stat` SET `hits`=$redir_hits, `unics`=$redir_unics WHERE `oid`=$redir_id";    //���������� ������ �� ����� � �����
$ins = mysql_query($qu);

################# ��������� GET ��������� ######################
 if ($redir_isparam) {
 	unset($params);
 	foreach ($getparams as $key=>$val) {
 		$params[] = urlencode($key)."=".urlencode($val);
	}
	if (@is_array($params)) {
		$param_string = implode('&',$params);
		if (stristr($redir_url, '?')) $param_string = "&".$param_string; else $param_string = "?".$param_string;
	} else {
		$param_string = '';
	}
	$redir_url = $redir_url.$param_string;
 }
################################################################

################ ���������� ������ #############################
$ts = time();
$dttm = date('Y-m-d H:i:s', $ts);
$qu = "INSERT INTO `stats` values ('$dttm', '$sid', '$redir_id', '$cc','$ip','$ref','$refref','$ua','$se_url','$se_query','$redir_url');";
$ins = mysql_query($qu);
################################################################
 if ($debug) {
	echo "Here redirect to <b>$redir_url</b> with id=$redir_id, unics=$redir_unics, hits=$redir_hits , geo=$redir_geo , isparam=$redir_isparam <br>";
 } else {
 	if ($visited_outs) {
 		$visited_outs .= ",$redir_id";
	} else {
		$visited_outs = "$redir_id";
	}
 	setcookie("visited".$sid,$visited_outs,time()+$global_settings['user_unic_time']);


 	if ($out_redir_type=="curl") {
 		  $retr = new Retriever($redir_url,"temp.tmp",$ref);
 		  $retr->fetch();
 		  echo $retr->get_content();
	}
	else {
		header("Referer: ".$ref);
		header("Location: $redir_url");
	}

 }
?>