<?php

/**
  * Currency class, Currency.php
  * Currencies management
  * @category classes
  *
  * @author PrestaShop <support@prestashop.com>
  * @copyright PrestaShop
  * @license http://www.opensource.org/licenses/osl-3.0.php Open-source licence 3.0
  * @version 1.2
  *
  */

class		Currency extends ObjectModel
{
	public 		$id;

	/** @var string Name */
	public 		$name;

	/** @var string Iso code */
	public 		$iso_code;

	/** @var string Symbol for short display */
	public 		$sign;
	
	/** @var int bool used for displaying blank between sign and price */
	public		$blank;

	/** @var string Conversion rate from euros */
	public 		$conversion_rate;

	/** @var boolean True if currency has been deleted (staying in database as deleted) */
	public 		$deleted = 0;

	/** @var int ID used for displaying prices */
	public		$format;

	/** @var int bool Display decimals on prices */
	public		$decimals;

 	protected 	$fieldsRequired = array('name', 'iso_code', 'sign', 'conversion_rate', 'format', 'decimals');
 	protected 	$fieldsSize = array('name' => 32, 'iso_code' => 3, 'sign' => 8);
 	protected 	$fieldsValidate = array('name' => 'isGenericName', 'sign' => 'isGenericName',
		'format' => 'isUnsignedId', 'decimals' => 'isBool', 'conversion_rate' => 'isFloat', 'deleted' => 'isBool');

	protected 	$table = 'currency';
	protected 	$identifier = 'id_currency';

	
	public function getFields()
	{
		parent::validateFields();

		$fields['name'] = pSQL($this->name);
		$fields['iso_code'] = pSQL($this->iso_code);
		$fields['sign'] = pSQL($this->sign);
		$fields['format'] = intval($this->format);
		$fields['decimals'] = intval($this->decimals);
		$fields['blank'] = intval($this->blank);
		$fields['conversion_rate'] = floatval($this->conversion_rate);
		$fields['deleted'] = intval($this->deleted);

		return $fields;
	}

	public function deleteSelection($selection)
	{
		if (!is_array($selection) OR !Validate::isTableOrIdentifier($this->identifier) OR !Validate::isTableOrIdentifier($this->table))
			die(Tools::displayError());
		$result = true;
		foreach ($selection AS $id)
		{
			$obj = new Currency(intval($id));
			$res[$id] = $obj->delete();
		}
		foreach ($res AS $value)
			if (!$value)
				return false;
		return true;
	}

	public function delete()
	{
		if ($this->id == Configuration::get('PS_CURRENCY_DEFAULT'))
		{
			$result = Db::getInstance()->getRow('SELECT `id_currency` FROM '._DB_PREFIX_.'currency WHERE `id_currency` != '.intval($this->id).' AND `deleted` = 0');
			if (!$result['id_currency'])
				return false;
			Configuration::updateValue('PS_CURRENCY_DEFAULT', $result['id_currency']);
		}
		$this->deleted = 1;
		return $this->update();
	}

	/**
	  * Return formated sign
	  *
	  * @param string $side left or right
	  * @return string formated sign
	  */
	public function getSign($side=NULL)
	{
		if (!$side)
			return $this->sign;
		$formated_strings = array(
			'left' => $this->sign.' ',
			'right' => ' '.$this->sign
		);
		$formats = array(
			1 => array('left' => &$formated_strings['left'], 'right' => ''),
			2 => array('left' => '', 'right' => &$formated_strings['right']),
			3 => array('left' => &$formated_strings['left'], 'right' => ''),
			4 => array('left' => '', 'right' => &$formated_strings['right']),
		);
		return ($formats[$this->format][$side]);
	}

	/**
	  * Return available currencies
	  *
	  * @return array Currencies
	  */
	static public function getCurrencies($object = false)
	{
		$tab = Db::getInstance()->ExecuteS('
		SELECT *
		FROM `'._DB_PREFIX_.'currency`
		WHERE `deleted` = 0
		ORDER BY `name` ASC');
		if ($object)
			foreach ($tab as $key => $currency)
				$tab[$key] = new Currency($currency['id_currency']);
		return $tab;
	}
	
	static public function getPaymentCurrenciesSpecial($id_module)
	{
		return Db::getInstance()->getRow('
		SELECT mc.*
		FROM `'._DB_PREFIX_.'module_currency` mc
		WHERE mc.`id_module` = '.intval($id_module));
	}
	
	static public function getPaymentCurrencies($id_module)
	{
		return Db::getInstance()->ExecuteS('
		SELECT c.*
		FROM `'._DB_PREFIX_.'module_currency` mc
		LEFT JOIN `'._DB_PREFIX_.'currency` c ON c.`id_currency` = mc.`id_currency`
		WHERE c.`deleted` = 0
		AND mc.`id_module` = '.intval($id_module).'
		ORDER BY c.`name` ASC');
	}
	
	static public function checkPaymentCurrencies($id_module)
	{
		return Db::getInstance()->ExecuteS('
		SELECT mc.*
		FROM `'._DB_PREFIX_.'module_currency` mc
		WHERE mc.`id_module` = '.intval($id_module));
	}

	static public function getCurrency($id_currency)
	{
		return Db::getInstance()->getRow('
		SELECT *
		FROM `'._DB_PREFIX_.'currency`
		WHERE `deleted` = 0
		AND `id_currency` = '.intval($id_currency));
	}
	
	static public function getIdByIsoCode($iso_code)
	{
		$result = Db::getInstance()->getRow('
		SELECT `id_currency`
		FROM `'._DB_PREFIX_.'currency`
		WHERE `deleted` = 0
		AND `iso_code` = \''.pSQL($iso_code).'\'');
		return $result['id_currency'];
	}

	// call every shop currencies
	// simplexml node (array of currency), xmlcurr, shopcurr
	public function refreshCurrency($data, $isoCodeSource, $defaultCurrency) 
	{
		
		if ($this->iso_code != $isoCodeSource)
		{
			/* Seeking for rate in feed */
			//foreach ($data->currency AS $obj)
			foreach ($data[0]->children() as $obj){ 
				if ($this->iso_code == strval($obj['iso_code'])){
					$this->conversion_rate = round(floatval($obj['rate']) /  $defaultCurrency->conversion_rate, 6);
				}	
			}
		}
		else
		{
		    /* If currency is like isoCodeSource, setting it to default conversion rate */
			$this->conversion_rate = round(1 / floatval($defaultCurrency->conversion_rate), 6);
		}
		$this->update();
	}

	static public function refreshCurrenciesGetDefault($data, $isoCodeSource, $idCurrency)
	{
		$defaultCurrency = new Currency($idCurrency);
		
		/* Change defaultCurrency rate if not as currency of feed source */
		if ($defaultCurrency->iso_code != $isoCodeSource) { 
			foreach ($data[0]->children() as $obj){ 
				if ($defaultCurrency->iso_code == strval($obj['iso_code'])) {// currency node's attrib
					//$defaultCurrency->conversion_rate = round(floatval($obj['rate']), 6); // currency node's attrib
					$defaultCurrency->conversion_rate = floatval($obj['rate']);
				}	
			}		
		}			
		return $defaultCurrency;
	}


	static private function before_refreshCurrencies()
	{
		$updated = Configuration::get('LAST_CURRENCY_UPDATED');
		if ( !empty($updated) )
		{
			$today = date("Y.m.d");
			//return ($today != $updated);
				
		}
		return true; 
	}
	
	
	static public function refreshCurrencies()
	{
		
		if ( !self::before_refreshCurrencies() ) 
		{
			return Tools::displayError('Currencies are already updated today!');
		}
		 
		if (!$defaultCurrency = intval(Configuration::get('PS_CURRENCY_DEFAULT'))){
			return Tools::displayError('No default currency!');
		}
		
		$process = 1;
		
		if($process === 1){
			
			$mnb = self::getMNBXml();
			
			if (!isset($mnb) OR empty($mnb)){
				return Tools::displayError('Cannot parse MNB feed!');
			}
			  
			$feed = self::mnb2presta($mnb);
			
		}else{
			$feed = self::getPrestaXml();
		} 
		
		
		 
		if (!isset($feed) OR empty($feed)){
			return Tools::displayError('Cannot parse feed!');
		}
		
		$isoCodeSource = strval($feed->source['iso_code']);	
			
		$currencies = self::getCurrencies(true);
			
		$defaultCurrency = self::refreshCurrenciesGetDefault($feed->list, $isoCodeSource, $defaultCurrency);
		
			
		if (!isset($defaultCurrency) OR $defaultCurrency == false){
			return Tools::displayError('No default currency!');
		}

		foreach ($currencies as $currency){
			if ($currency->iso_code != $defaultCurrency->iso_code){
				$currency->refreshCurrency($feed->list, $isoCodeSource, $defaultCurrency);
			}
		}	
		self::after_refreshCurrencies();
		//return 'b';
	}
	
	
	static private function after_refreshCurrencies()
	{
		$value = date("Y.m.d");
		Configuration::updateValue('LAST_CURRENCY_UPDATED', $value);
		
	}
	
	static private function getMNBXml()
	{
		$bdy = "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
		$bdy.= "<soap:Envelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\">";
		$bdy.= "<soap:Body>";
		$bdy.= "<GetCurrentExchangeRates xmlns=\"http://www.mnb.hu/webservices/\" />";
		$bdy.= "</soap:Body>";
		$bdy.= "</soap:Envelope>\r\n";
		
		$req = "POST /arfolyamok.asmx HTTP/1.1\r\n";
		$req.= "Host: www.mnb.hu\r\n";
		$req.= "Connection: Close\r\n";
		$req.= "Content-Type: text/xml; charset=utf-8\r\n";
		$req.= "Content-Length: ".strlen($bdy)."\r\n";
		$req.= "SOAPAction: \"http://www.mnb.hu/webservices/GetCurrentExchangeRates\"\r\n\r\n";
		
		$fs = fsockopen("www.mnb.hu", 80);
		
		$prefix = "<?xml version=";
		$isxmldata = false;
		$xmldata = "";
		
		fwrite($fs, $req.$bdy);
		while (!feof($fs))
		{
			$s = fgets($fs);
			if( StrStr( $s, $prefix ) ){
				$isxmldata = true;
			}
			if ( $isxmldata ){
				 $xmldata.= $s;
			}
		}
		fclose($fs);
		
		$xmldata = str_replace('&lt;', '<', str_replace('&gt;', '>', $xmldata));
		$xmldata = str_replace('soap:', '', $xmldata);
		
		//echo $xmldata;
		
		$mnbxml = simplexml_load_string ($xmldata);
		
		return $mnbxml;
	}
	
	
	static private function mnb2presta($mnb_feed)
	{
		//$body = $mnb_feed->children("http://schemas.xmlsoap.org/soap/envelope/");
		$body = $mnb_feed->children();
		$tags = $body[0]->children(); // response
		$tags = $tags[0]->children(); // result
		$tags = $tags[0]->children(); // rates
		$tags = $tags[0]->children(); // day
		
		$xml = simplexml_load_string ("<?xml version='1.0' encoding='UTF-8'?><currencies></currencies>");
		$newNode = $xml->addChild ('source');
        $newNode->addAttribute ('iso_code', 'HUF');
        $listNode = $xml->addChild ('list');
        		
		foreach ($tags[0]->children() as $tag) {
			$attr = (array)$tag->attributes();
			$attr = $attr["@attributes"];
			$iso_code = (string)$attr["curr"];
			$rate = (string)$tag;
			$rate = str_replace(',','.',$rate);
			$frate = floatval(1/$rate);
			//echo $iso_code.'='.$frate.'<BR/>';
			$newNode = $listNode->addChild ('currency ');	
			$newNode->addAttribute ('iso_code', $iso_code);
			$newNode->addAttribute ('rate', $frate);
		}
		
		
		return $xml;
	}
	
	static private function getPrestaXml()
	{
		$presta_xml = 'http://www.prestashop.com/xml/currencies.xml';
		
		/*
		<currencies>
			<source iso_code='EUR' />
			<list>
				<currency iso_code='USD' rate='1.44' />
				<currency iso_code='HUF' rate='272.44' />
			</list>
		</currencies>
		*/
		
		if (!$feed = @simplexml_load_file($presta_xml)){
			return Tools::displayError('Cannot parse presta feed!');
		}	
					
		return $feed;
	
	}
}

?>