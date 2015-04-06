<?php

include(dirname(__FILE__).'/config/config.inc.php');
Currency::refreshCurrencies();

if(intval(Configuration::get('PS_REWRITING_SETTINGS')) === 1)
	$rewrited_url = __PS_BASE_URI__;

include(dirname(__FILE__).'/header.php');

// Blocknewsletter.hookLeftColumn() is kiertekelesre kerul itt a hatterben, ami a cron-os kuldest megvalositja
// @see Util::sendNewProductMail(intval($_GET['hours']), intval($_GET['lang']));
if(isset($_REQUEST["call_method"]))
{
	if($_REQUEST["call_method"]=="mailSent")
	{
			if($cookie->id_lang ==3)
			{
				echo "Levél sikeresen elküldve!";
			}
			else
			{
				echo "Letter has been sent successfully!";
			}
	}
}
else
{
	$smarty->assign('HOOK_HOME', Module::hookExec('home'));
	$smarty->display(_PS_THEME_DIR_.'index.tpl');
}

include(dirname(__FILE__).'/footer.php');



?>

