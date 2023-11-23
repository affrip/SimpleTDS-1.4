<?php
include("config.php");
include("functions.php");
include("login.php");
include("header.php");

$new_global_settings = @gg('new_global_settings');
$action = @gg('action');
if ($action == 'edit') {             //Редактирование аута
 foreach($new_global_settings AS $key=>$value) {
 	$qu = "UPDATE `settings` SET `value`='$value' WHERE `name`='$key'";
	$result = mysql_query ($qu); //Изменяем данные об ауте
 }
 $global_settings = $new_global_settings;
}

$csv_sel="";
if ($global_settings['arch_stats_type']=="csv") {$csv_sel="SELECTED"; }

$del_sel1="";$del_sel0="";
if ($global_settings['stats_show_del']=="1") {$del_sel1="SELECTED"; }
if ($global_settings['stats_show_del']=="0") {$del_sel0="SELECTED"; }

$sel_sel1="";$sel_sel0="";
if ($global_settings['stats_show_selects']=="1") {$sel_sel1="SELECTED"; }
if ($global_settings['stats_show_selects']=="0") {$sel_sel0="SELECTED"; }

$ua_sel1="";$ua_sel0="";
if ($global_settings['stats_show_ua']=="1") {$ua_sel1="SELECTED"; }
if ($global_settings['stats_show_ua']=="0") {$ua_sel0="SELECTED"; }

$arch_sel1="";$arch_sel0="";
if ($global_settings['stats_do_arch']=="1") {$arch_sel1="SELECTED"; }
if ($global_settings['stats_do_arch']=="0") {$arch_sel0="SELECTED"; }

echo "<h2>Настройки Simple TDS</h2>";
echo "<form action=\"settings.php\" method=\"GET\" enctype=\"text/plain\" name=\"settings_form\">
<fieldset>
<legend>Общее</legend>
<div class=\"settings_item\">
Смещение часового пояса: <input name=\"new_global_settings[time_offset]\" type=\"text\" value=\"{$global_settings['time_offset']}\"/> час. (На сервере сейчас $server_time)
</div>
</fieldset>

<fieldset>
<legend>Архивация статсов</legend>

<div class=\"settings_item\">
Производить архивацию и очистку статистики?:
<select name=\"new_global_settings[stats_do_arch]\" size=\"1\">
	<option value=\"1\" $arch_sel1>Да</option>
	<option value=\"0\" $arch_sel0>Нет</option>
</select>
</div>

<div class=\"settings_item\">
Очищать и архивировать статистику, старше чем: <input name=\"new_global_settings[arch_stats_time]\" type=\"text\" value=\"{$global_settings['arch_stats_time']}\"/> дней.
</div>

<div class=\"settings_item\">
Формат архивной статистики:
<select name=\"new_global_settings[arch_stats_type]\" size=\"1\">
	<option value=\"csv\" $csv_sel>CSV</option>
</select>
</div>
</fieldset>

<fieldset>
<legend>Отображение статсов</legend>
<div class=\"settings_item\">
На страницах статистики выводить по: <input name=\"new_global_settings[stats_num_raws]\" type=\"text\" value=\"{$global_settings['stats_num_raws']}\"/> строк.
</div>

<div class=\"settings_item\">
Отображать в фильтрах для статистики удаленные ауты?:
<select name=\"new_global_settings[stats_show_del]\" size=\"1\">
	<option value=\"1\" $del_sel1>Да</option>
	<option value=\"0\" $del_sel0>Нет</option>
</select>
</div>

<div class=\"settings_item\">
Отображать в фильтрах для статистики выпадающие списки для IP,Referer,Search Query?:
<select name=\"new_global_settings[stats_show_selects]\" size=\"1\">
	<option value=\"1\" $sel_sel1>Да</option>
	<option value=\"0\" $sel_sel0>Нет</option>
</select>
</div>

<div class=\"settings_item\">
Отображать в статистике столбец User Agent?:
<select name=\"new_global_settings[stats_show_ua]\" size=\"1\">
	<option value=\"1\" $ua_sel1>Да</option>
	<option value=\"0\" $ua_sel0>Нет</option>
</select>
</div>
</fieldset>

<fieldset>
<legend>Прочее</legend>
<div class=\"settings_item\">
Время уникальности посетителя (время жизни кукисов): <input name=\"new_global_settings[user_unic_time]\" type=\"text\" value=\"{$global_settings['user_unic_time']}\"/> сек. (1 час=3600 сек. 1 сутки=86400 сек. 1 неделя=604800 сек. 1 месяц=2592000).
</div>
</fieldset>

<input type=\"hidden\" name=\"action\" value=\"edit\"/>
<input type=\"submit\" value=\"Сохранить\"/>

</form>";
?>

<!--Content end-->
</td>
</tr>
<tr>
<td class="copy" height="30" bgcolor="#585B61">Script author: mizhgan</td>
</tr>
</table>

<br/>
</td>
</tr>
</table>