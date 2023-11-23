<?php
include("config.php");
include("functions.php");

/**
 * cron.php
 * Планировщик задач. Запускать через cron один раз в сутки.
 *
 * @version $Id$
 * @copyright 2008
 */


//Очистка устаревших статсов и их архивация в папку archive/stats
if ($global_settings['stats_do_arch']) {
 do_stats_arch();
} // if
//***********************//

//Оптимизируем таблицы
 $qu = "OPTIMIZE TABLE `filt2o` , `filters` , `out2s` , `outs` , `outs_stat` , `schems` , `settings` , `stats`";
 $result = mysql_query ($qu);
//***********************//
?>