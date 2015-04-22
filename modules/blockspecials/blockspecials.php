<?php

class BlockSpecials extends Module
{
    private $_html = '';
    private $_postErrors = array();

    function __construct()
    {
        $this->name = 'blockspecials';
        $this->tab = 'Blocks';
        $this->version = 0.8;

        parent::__construct();

        $this->displayName = $this->l('Specials block');
        $this->description = $this->l('Adds a block with current product Specials');
    }

    function install()
    {
        parent::install();
        $this->registerHook('rightColumn');
    }

    function hookRightColumn($params)
    {
		global $smarty;
		// egy jelenik meg alapbol, itt megprobalunk egy masikat is talalni
		$special = Product::getRandomSpecial(intval($params['cookie']->id_lang));
		$special2 = Product::getRandomSpecial(intval($params['cookie']->id_lang));
		
		if ($special)
			$smarty->assign(array(
			'special' => $special,
			'special2' => $special2,
			'oldPrice' => number_format($special['price'] + $special['reduction'], 2, '.', ''),
			'mediumSize' => Image::getSize('medium')));
			
		return $this->display(__FILE__, 'blockspecials.tpl');
	}
	
	function hookLeftColumn($params)
	{
		return $this->hookRightColumn($params);
	}
}

?>
