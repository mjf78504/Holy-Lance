<?php

if (!defined('HOLY_LANCE_PASSWORD')) {
	define('HOLY_LANCE_PASSWORD', '');
}

function convert_boolean($value) {
	if (is_bool($value) || in_array($value, array('true', 'false', '1', '0', 1, 0), true)) {
		return $value ? '√' : '×';
	} else {
		return $value;
	}
}

function get_config_value($varName)
{
	return convert_boolean(get_cfg_var($varName));
}

//格式化文件大小
function format_bytes($size, $precision = 2)
{
	// https://www.zhihu.com/question/21578998/answer/86401223
	// According to Metric prefix, IEEE 1541-2002.
	$units = array(
		' Bytes',
		' KiB',
		' MiB',
		' GiB',
		' TiB'
	);
	for ($i = 0; $size >= 1024 && $i < 4; $i++)
		$size /= 1024;
	return round($size, $precision) . $units[$i];
}

function format_number($number)
{
	return number_format($number, '0', '.', ' ');
}


function check_permission($file_name)
{
	$fp = @fopen($file_name, 'w');
	if(!$fp) {
		return false;
	}
	else {
		fclose($fp);
		@unlink($file_name);
		return true;
	}
}

function get_cpu_info_map($cpu_info_val)
{
	$result = array();
	foreach (explode("\n", $cpu_info_val) as $value) {
		if ($value) {
			$item = array_map("trim", explode(":", $value));
			$result[str_replace(" ", "_", $item[0])] = $item[1];
		}
	}
	return $result;
}

function get_mem_info_map($mem_info)
{
	$result = array();
	foreach ($mem_info as $value) {
		$value = str_ireplace(")", "", str_ireplace("(", "_", str_ireplace("kB", "", $value)));
		$item = array_map("trim", explode(":", $value));
		$result[str_replace(" ", "_", $item[0])] = $item[1];
	}
	return $result;
}

function convert_timestamp_2_string($timestamp) {
	$timestamp = intval($timestamp);
	return intval($timestamp / 86400) . ":"
		. sprintf("%02d", $timestamp % 86400 / 3600) . ":"
		. sprintf("%02d", $timestamp % 3600 / 60) . ":"
		. sprintf("%02d", $timestamp % 60);
}

function check_password(){
	if (HOLY_LANCE_PASSWORD !== '' && (!isset($_POST['password']) || $_POST['password'] !== HOLY_LANCE_PASSWORD)) {
		echo '{"status":false}';
		exit(1);
	}
	return true;
}
?>