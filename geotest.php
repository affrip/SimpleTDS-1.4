<?php
$cc = $_SERVER["GEOIP_COUNTRY_CODE"];	//Country Code �������
$cn = $_SERVER["GEOIP_COUNTRY_NAME"];	//Country Name �������
echo "�� ���������� ���������:<br>";
echo $cc."  ".$cn;
echo "<br>";

include("geoip/geoip.inc");
$ip = @getenv('REMOTE_ADDR');
$gi = geoip_open("geoip/GeoIP.dat",GEOIP_STANDARD);
$cc = geoip_country_code_by_addr($gi, $ip);
$cn = geoip_country_name_by_addr($gi, $ip);
geoip_close($gi);
echo "�� ��������� ���� ������ ��� IP $ip:<br>";
echo $cc."  ".$cn;
echo "<br>";
?>

