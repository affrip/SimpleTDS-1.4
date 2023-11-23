<?php

function do_stats_arch(){
 global $global_settings;
 $today = date("Y-m-d H:i:s");
 $delete_time = date("Y-m-d H:i:s",strtotime("-{$global_settings['arch_stats_time']} day"));
 $qu = "SELECT * FROM `stats` WHERE `dt`<'$delete_time' ORDER BY `dt` DESC";
 $result = mysql_query ($qu);
 $num=mysql_num_rows($result);
 if ($global_settings['arch_stats_type']=="csv") {
   $fh = fopen("archive/stats/$today.csv","w");
   $file_content = "";
   for ($i=0;$i<mysql_num_fields($result);$i++) {
    $file_content .= mysql_field_name($result, $i).";";
   } // for
   fwrite($fh,$file_content);
 }// if
 while($line=mysql_fetch_array($result, MYSQL_ASSOC)){
 	$file_content = "\n";
 	foreach($line AS $key=>$value){
 	  if ($global_settings['arch_stats_type']=="csv") {
 		$file_content .=str_replace(";"," ",$value).";";
 	  }// if
 	}// foreach
 	fwrite($fh,$file_content);
 } // while
 fclose($fh);
 $qu = "DELETE FROM `stats` WHERE `dt`<'$delete_time'";
 $result = mysql_query ($qu);
}

/**
 * ����� ��� ���������� ���� ������ �� ���������
 *
 */
class Retriever {
    /**
     * URL ����� ������� �������
     *
     * @var string
     */
    var $remote_file;
    /**
     * ��� ����� � ������� ���������
     *
     * @var string
     */
    var $local_file;
    /**
     * ��������� ����
     *
     * @var string
     */
    var $host;
    /**
     * ���� �� ��������� �������
     *
     * @var string
     */
    var $path;
    /**
     * ���������� ����������� �����
     *
     * @var string
     */
    var $content;
    /**
     * �������
     *
     * @var string
     */
    var $refer;


    /**
     * �����������
     *
     * @param string $remote_file
     * @param string $local_file
     * @return Retriever
     */
    function Retriever( $remote_file, $local_file, $refer ) {
        $this->remote_file = $remote_file;
        $this->local_file = $local_file;
        $this->refer = $refer;

        $parts = preg_match( "@^http://(.+)/(.+)$@Uis", $this->remote_file, $matches );

        $this->host = $matches[1];
        $this->path = "/".$matches[2];
    }

    /**
     * ��������� ���� � ��������� ��� �� �����
     *
     */
    function fetch() {

        $user_agent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.9) Gecko/20071025 Firefox/2.0.0.9';

        @ini_set( 'default_socket_timeout', 10 );
        @ini_set( 'user_agent', $user_agent );

        // ����� ����
        if( function_exists('curl_init') ) {
            if( $c = @curl_init() ) {

                @curl_setopt( $c, CURLOPT_URL, $this->remote_file );
                @curl_setopt( $c, CURLOPT_HEADER, false );
                @curl_setopt( $c, CURLOPT_RETURNTRANSFER, true );
                @curl_setopt( $c, CURLOPT_CONNECTTIMEOUT, 10 );
                @curl_setopt( $c, CURLOPT_USERAGENT, $user_agent );
                @curl_setopt( $c, CURLOPT_REFERER, $this->refer );
                @curl_setopt( $c, CURLOPT_FOLLOWLOCATION, 1 );

                $text = @curl_exec( $c );

                @curl_close( $c );
            }
        // ����� ������ ����
        } else {
            $buff = '';
            $fp = @fsockopen( $this->host, 80, $errno, $errstr, 10 );
            if( $fp ) {
                @fputs( $fp, "GET ".$this->path." HTTP/1.0\r\nHost: ".$this->host."\r\n" );
                @fputs( $fp, "User-Agent: ".$user_agent."\r\n\r\n" );
                @fputs( $fp, "Referer: ".$this->refer."\r\n\r\n" );
                while( !@feof( $fp ) ) {
                    $buff.= @fgets( $fp, 128 );
                }
                @fclose($fp);
                $page = explode( "\r\n\r\n", $buff );
                $text = $page[1];
            }
        }

        $this->content = $text;
    }

    /**
     * ���������� ����������, ���������� �� �������
     *
     * @return string
     */
    function get_content() {
        return $this->content;
    }

    /**
     * �������� ���������� ���������� � ����
     *
     */
    function save() {
        $fh = @fopen( $this->local_file, "w" );
        if( is_resource( $fh ) ) {
            @fputs( $fh, $this->get_content() );
        }
        @fclose( $fh );
        chmod($this->local_file,0666);
    }

    /**
     * ������������� ����� ����������� ���������� �����
     *
     * @param int $diff
     */
    function touch( $diff = 0 ) {
        @touch( $this->local_file, time() + $diff );
    }

    /**
     * ���������� true ��� false � ����������� �� ����, ��������� �� ���� ����������
     *
     * @param int $timeout ���������� ������, ����� �������� ������� ���� ����������
     * @return boolean
     */
    function is_old( $timeout ) {

        if( $timeout < 3600 ) {
            $timeout = 3600;
        }

        if( file_exists( $this->local_file ) ) {
            $stat = stat( $this->local_file );
            $mtime = $stat[9];
            return ( time() - $mtime > $timeout );
        } else {
            return true;
        }
    }

    /**
     * �������� ���������� �����
     *
     * @return string
     */
    function get_contens() {
        if( file_exists( $this->local_file ) ) {
            return @implode( "", file( $this->local_file ) );
        } else {
            return null;
        }
    }
}



function ip2c($ip) {
	$ip_number = sprintf("%u", ip2long($ip));
	// Query for getting visitor countrycode
    $country_query  = "SELECT country_code2,country_name FROM iptoc ".
         "WHERE IP_FROM<=$ip_number ".
          "AND IP_TO>=$ip_number ";
    // Executing above query
    $country_exec = @mysql_query($country_query);
    // Fetching the record set into an array
    $ccode_array=@mysql_fetch_array($country_exec);
    // getting the country code from the array
    $country_code=$ccode_array['country_code2'];
    // getting the country name from the array
    $country_name=$ccode_array['country_name'];
   	// Display the Visitor coountry information
	$ret[0] = $country_code;
	$ret[1] = $country_name;
  	// Closing the database connection
   	return $ret;
}

function searchany ($haystack, $needle) {
	global $debug;
 	if (substr($needle,0,1) != "*") $needle = "^".$needle;
 	if (substr($needle,-1) != "*") $needle = $needle."$";
	$needle = str_replace("*", "(.*)", $needle);
	$needle = str_replace("/", "\/", $needle);
	if ($debug) echo $needle."<br>";
	if (preg_match ("/$needle/i", $haystack)) {
	    $res=true;
	} else {
	    $res=false;
	}

	return $res;
}

function gotoreserved() {
global $reserved_url, $debug, $getparams;
################# ��������� GET ��������� ######################
 	unset($params);
 	foreach ($getparams as $key=>$val) {
 		$params[] = urlencode($key)."=".urlencode($val);
	}
	if (@is_array($params)) {
		$param_string = implode('&',$params);
		if (stristr($reserved_url, '?')) $param_string = "&".$param_string; else $param_string = "?".$param_string;
	} else {
		$param_string = '';
	}
	$reserved_url = $reserved_url.$param_string;
################################################################
 if ($debug) {
	echo "Here redirect to RESERVED URL <b>$reserved_url</b><br>";
 } else {
	header("Referer: ".@$_SERVER['HTTP_REFERER']);
	header("Location: $reserved_url");
 }

}

function gotozapas($sid) {
global $debug, $getparams, $cc, $ip, $refref, $ua, $schema_visited, $se_url, $se_query;
$qu = "SELECT * FROM `outs` WHERE `id` IN(SELECT `oid` FROM `out2s` WHERE `sid`=$sid) AND `active`=1 AND `reserved`=1";
$result = mysql_query ($qu); //����� ��� �������� ��������� ���� ��� ���� �����
if (mysql_num_rows($result)) {
  while ($line = mysql_fetch_array($result)){
	$o_urls[] = $line['url'];
	$o_isparams[] = $line['isparam'];
	$o_empty_refs[] = $line['empty_ref'];
	$o_ids[] = $line['id'];
  }
 } else {
 	return false;
 }

 $rand_num = array_rand($o_urls);     //�� ��������� ����� �������� ������� ����...
 $redir_url = $o_urls[$rand_num];
 $redir_isparam = $o_isparams[$rand_num];
 $redir_empty_ref = $o_empty_refs[$rand_num];
 $redir_id = $o_ids[$rand_num];

 if ($redir_isparam) {
################# ��������� GET ��������� ######################
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
################################################################
 }

 if ($redir_empty_ref) {
 	$ref = $redir_empty_ref;
 } else {
 	$ref = @$_SERVER['HTTP_REFERER'];
 }

 $qu = "SELECT * FROM `outs_stat` WHERE `oid`=$redir_id";
 $result = mysql_query($qu);
 $line = mysql_fetch_array($result);
 $redir_hits=$line['hits'] + 1;
 $redir_unics=$line['unics'];
 if (!$schema_visited) {
 	$redir_unics++;
 	setcookie("schema".$sid,"true",time()+60*60*24*3650);
 }
 $qu = "UPDATE `outs_stat` SET `hits`=$redir_hits, `unics`=$redir_unics WHERE `oid`=$redir_id";    //���������� ������ �� ����� � �����
 $ins = mysql_query($qu);

 ################ ���������� ������ #############################
 $ts = time();
 $dttm = date('Y-m-d H:i:s', $ts);
 $qu = "INSERT INTO `stats` values ('$dttm', '$sid', '$redir_id', '$cc','$ip','$ref','$refref','$ua','$se_url','$se_query','$redir_url');";
 $ins = mysql_query($qu);
 ################################################################

 if ($debug) {
	echo "Here redirect to RESERVED URL <b>$redir_url</b><br>";
 } else {
	header("Referer: ".$ref);
	header("Location: $redir_url");
 }
 exit();

}

function gg($v) {
	return @$_GET[$v];
}

function pp($v) {
	return @$_POST[$v];
}

function rr($v) {
	return @$_REQUEST[$v];
}

class tds
{

 var $schems = Array();
 var $outs = Array();
 var $out2s = Array();
 var $filters = Array();
 var $filt2o = Array();
 var $stats = Array();

 var $tables = Array(); //�������� ���� ������
 var $ai_tables = Array("tds_schems","tds_outs","tds_filters"); //������� � ���������������
 var $db = array();

 function tds () {
	$d = opendir ("data");
    while ($file = readdir ($d))
    {
      if (stristr($file,'.dat'))
      {
        $tablename = str_replace('.dat','',$file);
		$this->tables[] = $tablename;
		$ff = fopen("data/$file","r");
		$buf="";
		while (!feof($ff)) {
			$buf .= fgets($ff);
		}
		$this->db[$tablename] = unserialize($buf);
     }
    }
    closedir ($d);
 }

 function select_all($table) {
	$data = $this->db[$table];
	unset($data['autoinc']);
	return $data;
 }

 function select_where($table, $wheres) {
 	$data = $this->db[$table];
 	$ret_data = array();
	foreach ($wheres AS $key=>$val) {
		foreach ($data as $line) {
			if (isset($line[$key])) {
				if ($line[$key] == $val) $ret_data[] = $line;
			}
		}
	}
	return $ret_data;
 }

 function insert($table,$date) {
//	$data = $this->db[$table];
	$ai = $this->db[$table]['autoinc'];
	if (!(array_search($table,$this->ai_tables) === false)) {
		$date['id'] = $ai;
		$insert_id = $ai;
		$this->db[$table][] = $date;
		$ai++;
		$this->db[$table]['autoinc'] = $ai;
		$this->save_tables($table);
		return $insert_id;
	} else {
		$this->db[$table][] = $date;
		$this->save_tables($table);
		return 0;
	}
 }

 function update_where($table,$date,$wheres) {
 	$data = $this->db[$table];
	foreach ($wheres AS $key=>$val) {
		foreach ($data as $kk=>$line) {
			if (isset($line[$key])) {
				if ($line[$key] == $val) {
					foreach ($date AS $kkey=>$vval) {
 						$this->db[$table][$kk][$kkey] = $vval;
					}
				}
			}
		}
	}
	$this->save_tables($table);
 }

 function delete_where($table,$wheres) {
 	$data = $this->db[$table];
 	foreach ($wheres AS $key=>$val) {
		foreach ($data as $kk=>$line) {
			if (isset($line[$key])) {
				if ($line[$key] == $val) unset($this->db[$table][$kk]);
			}
		}
	}
	$this->save_tables($table);
 }

 function save_tables($table = "all") {
 	if ($table=="all") {
 		$alltables = $this->tables;
	} else {
		$alltables[] = $table;
	}

	foreach ($alltables AS $tablename) {
 		$full_ser = serialize($this->db[$tablename]);
		$fd = fopen("data/$table.dat", "w+");
		fputs($fd,$full_ser);
		fclose($fd);
    }
 }

}

class stats
{
	var $ip; //IP-����� �������
	var $ip_number;
	var $cc;	//Country Code �������
	var $cn;	//Country Name �������
	var $getparams;
	var $ua;
	var $ref; //HTTP_REFERER �������

 	var $st_days; //�������� ���� ������ � ������� �� ����. (��� ���������� .dat)
	var $all_stats; //���� ������ ���������� �� ���� ��������� ����
	var $today;    //����������� ����� � ������� ��������
	var $delday;   //����� ��� ��������

	function stats() {
		global $debug;

		$this->ip = @$_SERVER['REMOTE_ADDR']; //IP-����� �������
		if ($debug) $this->ip = "210.65.34.86"; // !!! TEST TW (TAIWAN) IP-Address !!!
		$this->ip_number = sprintf("%u", ip2long($this->ip));
		#$codes = ip2c($this->ip);
		$this->cc = @$_SERVER["GEOIP_COUNTRY_CODE"];	//Country Code �������
		$this->cn = @$_SERVER["GEOIP_COUNTRY_NAME"];	//Country Name �������
		if ($debug) $this->cc = "TW"; // !!! TEST TW  !!!
		if ($debug) $this->cn = "Taiwan"; // !!! TEST TAIWAN !!!
		$this->getparams = $_GET;
		unset($this->getparams['sid'],$this->getparams['sname']);
		$this->ua = @$_SERVER['HTTP_USER_AGENT'];
		$this->ref = @$_SERVER['HTTP_REFERER']; //HTTP_REFERER �������
		if ($debug) $this->ref = "http//www.fff.com?sdsd=sdfsd&df=1&tt=%F3%4D"; // !!! TEST !!!

		$d = opendir ("data/stats");
	    while ($file = readdir ($d))
	    {
	     if (stristr($file,'.dat'))
 	     {
	        $st_day = str_replace('.dat','',$file);
			$this->st_days[] = $st_day;
	     }
	    }
	    closedir ($d);

		$ts = time();
		$delts = $ts - 60*60*24*10;

		$this->today = date('Ymd', $ts);
		$this->delday = date('Ymd', $delts);
		if ((@array_search($this->today,$this->st_days)) === false) {
			$ff = fopen("data/stats/$this->today.dat","w+");
			flock($ff, LOCK_EX);
			$dat = array();
			fputs($ff,serialize($dat));
			flock($ff, LOCK_UN);
			fclose($ff);
			$this->st_days[] = $this->today;
		}

		foreach($this->st_days AS $kk=>$st_day) {
			if ($st_day <= $this->delday) {
				unset($this->st_days[$kk]);
				unlink("data/stats/$st_day.dat");
				continue;
			}
		}

	}

	function getstats($day_stamp = "all") {
		if ($day_stamp == "all") {
			$stamps = $this->st_days;
		} else {
			$stamps = array($day_stamp);
		}
		foreach ($stamps AS $stamp) {
			$ff = fopen("data/stats/$stamp.dat","r");
			flock($ff, LOCK_SH);
			$buf="";
			while (!feof($ff)) {
				$buf .= fgets($ff);
			}
			$ret[$stamp] = unserialize($buf);
			flock($ff, LOCK_UN);
			fclose($ff);
		}
		return $ret;
	}

	function update($out_num){
		$today_stats = $this->getstats($this->today);
		$ts = time();
		$dttm = date('Y-m-d H:i:s', $ts);
		$curr_stats['dt'] = $dttm;
		$curr_stats['country'] = $this->cn;
		$curr_stats['ip'] = $this->ip;
		$curr_stats['ref'] = $this->ref;
		$curr_stats['ua'] = $this->ua;
		$curr_stats['oid'] = $out_num;
		$today_stats[$this->today][] = $curr_stats;
		$ff = fopen("data/stats/$this->today.dat","w+");
		flock($ff, LOCK_EX);
		fputs($ff,serialize($today_stats[$this->today]));
		flock($ff, LOCK_UN);
		fclose($ff);
	}
}
?>