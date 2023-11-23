<?php

require_once("compat.php");

$mysql_host = "localhost";
$mysql_login = "root";
$mysql_password = "root";
$my_database = "stds_1_3beta";

$password = "123456";   //������ ��� ������� � ������� �������

$debug = false;       //���������� � true ���� ����� ����� �������. � ���� ������ �� ���������� �������� �����.

$scripturl = "http://".$_SERVER["HTTP_HOST"]."/"; //��� ������� � ����������� ������ ������.

$reserved_url = "http://aff.rip"; //��������� ���, �� ������� ������ ���� ��� �������
										 //(�� ��������� ���� ��� �����,
										 //������������ sid ����� � �������, ������� ������� ������� �������� � �.�.)

$nogeoip = "allow";		//��� ������, ���� �� ��������� GeoIP, �.�. ���� �� ����������� ��� ������
				//���� ������ allow  - �� ��� ���� ����� ��������� (�.�. ��� ����� � ��� ��������� ALL)
				//���� ������ block  - �� ��� ��-ALL ���� ����� �����������

$geoip_path = "/usr/local/bin/geoiplookup";		//���� �� GeoIP. ��������� ������ ���� ��������, ��� �� ����� ����� GeoIP,
							//�� ������ �� ������������. ������ ������ ���� ����� � �������� ��������.


#### ������ �� ������������� #########################
if ($debug) error_reporting(E_ALL); else error_reporting(0);
@ignore_user_abort (true);

mysql_connect($mysql_host, $mysql_login, $mysql_password)
  or die ("Could not connect to MySQL");

mysql_select_db ($my_database)
  or die ("Could not select database");

//������ �������� Simple tDS
$global_settings = array();
$qu = "SELECT * FROM `settings`";
$result = mysql_query ($qu); //������ ��� ���������...
 while ($line = mysql_fetch_array($result)){
  $name=$line['name'];
  $val = $line['value'];
  $global_settings[$name] = $val;
 }
//����� ������ ��������

?>