<?php


$key = getValue('key');
$class = getValue('class');

if($key && $class){
	
	$string = str_replace('"', '&quot;', $key);
	$key = md5(str_replace('\'', '\\\'', $string));
	echo $class.'_'.$key;
	
}else{
	echo 'Valami hiányos: '.$class.'.'.$key.' '.$key;
}

	

function getValue($key, $defaultValue = false)
{
 	if (!isset($key) OR empty($key) OR !is_string($key))
		return false;
	$ret = (isset($_POST[$key]) ? $_POST[$key] : (isset($_GET[$key]) ? $_GET[$key] : $defaultValue));

	if (is_string($ret) === true)
		$ret = urldecode(preg_replace('/((\%5C0+)|(\%00+))/i', '', urlencode($ret)));
	return !is_string($ret)? $ret : stripslashes($ret);
}
?>	