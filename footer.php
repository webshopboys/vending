<?php

global $cookie;
$ps_language = new Language(intval($cookie->id_lang));

if (isset($smarty))
{
	$smarty->assign(array(
		'HOOK_RIGHT_COLUMN' => Module::hookExec('rightColumn'),
		'HOOK_FOOTER' => Module::hookExec('footer'),
		'ps_language' => $ps_language->iso_code, 	
		'content_only' => intval(Tools::getValue('content_only'))));
	$smarty->display(_PS_THEME_DIR_.'footer.tpl');
}

