<?php
include("config.php");
include("functions.php");

/**
 * cron.php
 * ����������� �����. ��������� ����� cron ���� ��� � �����.
 *
 * @version $Id$
 * @copyright 2008
 */


//������� ���������� ������� � �� ��������� � ����� archive/stats
if ($global_settings['stats_do_arch']) {
 do_stats_arch();
} // if
//***********************//

//������������ �������
 $qu = "OPTIMIZE TABLE `filt2o` , `filters` , `out2s` , `outs` , `outs_stat` , `schems` , `settings` , `stats`";
 $result = mysql_query ($qu);
//***********************//
?>