<?php
include("config.php");
include("functions.php");
include("login.php");
include("header.php");

$new_global_settings = @gg('new_global_settings');
$action = @gg('action');
if ($action == 'edit') {             //�������������� ����
 foreach($new_global_settings AS $key=>$value) {
 	$qu = "UPDATE `settings` SET `value`='$value' WHERE `name`='$key'";
	$result = mysql_query ($qu); //�������� ������ �� ����
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

echo "<h2>��������� Simple TDS</h2>";
echo "<form action=\"settings.php\" method=\"GET\" enctype=\"text/plain\" name=\"settings_form\">
<fieldset>
<legend>�����</legend>
<div class=\"settings_item\">
�������� �������� �����: <input name=\"new_global_settings[time_offset]\" type=\"text\" value=\"{$global_settings['time_offset']}\"/> ���. (�� ������� ������ $server_time)
</div>
</fieldset>

<fieldset>
<legend>��������� �������</legend>

<div class=\"settings_item\">
����������� ��������� � ������� ����������?:
<select name=\"new_global_settings[stats_do_arch]\" size=\"1\">
	<option value=\"1\" $arch_sel1>��</option>
	<option value=\"0\" $arch_sel0>���</option>
</select>
</div>

<div class=\"settings_item\">
������� � ������������ ����������, ������ ���: <input name=\"new_global_settings[arch_stats_time]\" type=\"text\" value=\"{$global_settings['arch_stats_time']}\"/> ����.
</div>

<div class=\"settings_item\">
������ �������� ����������:
<select name=\"new_global_settings[arch_stats_type]\" size=\"1\">
	<option value=\"csv\" $csv_sel>CSV</option>
</select>
</div>
</fieldset>

<fieldset>
<legend>����������� �������</legend>
<div class=\"settings_item\">
�� ��������� ���������� �������� ��: <input name=\"new_global_settings[stats_num_raws]\" type=\"text\" value=\"{$global_settings['stats_num_raws']}\"/> �����.
</div>

<div class=\"settings_item\">
���������� � �������� ��� ���������� ��������� ����?:
<select name=\"new_global_settings[stats_show_del]\" size=\"1\">
	<option value=\"1\" $del_sel1>��</option>
	<option value=\"0\" $del_sel0>���</option>
</select>
</div>

<div class=\"settings_item\">
���������� � �������� ��� ���������� ���������� ������ ��� IP,Referer,Search Query?:
<select name=\"new_global_settings[stats_show_selects]\" size=\"1\">
	<option value=\"1\" $sel_sel1>��</option>
	<option value=\"0\" $sel_sel0>���</option>
</select>
</div>

<div class=\"settings_item\">
���������� � ���������� ������� User Agent?:
<select name=\"new_global_settings[stats_show_ua]\" size=\"1\">
	<option value=\"1\" $ua_sel1>��</option>
	<option value=\"0\" $ua_sel0>���</option>
</select>
</div>
</fieldset>

<fieldset>
<legend>������</legend>
<div class=\"settings_item\">
����� ������������ ���������� (����� ����� �������): <input name=\"new_global_settings[user_unic_time]\" type=\"text\" value=\"{$global_settings['user_unic_time']}\"/> ���. (1 ���=3600 ���. 1 �����=86400 ���. 1 ������=604800 ���. 1 �����=2592000).
</div>
</fieldset>

<input type=\"hidden\" name=\"action\" value=\"edit\"/>
<input type=\"submit\" value=\"���������\"/>

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