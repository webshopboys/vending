<?php


/**
 *
 * A fooldalon megjeleno beerkezo tetelek szabad szerkesztéseu mezoje.
 * Ez egy formázott tartalom, ami képes excel cellákat is befogadni.
 *
 * @see /administer/tabs/AdminModules.php
 * @see /footer.php
 *
 * @author petnehazi
 *
 */
class BlockArrival extends Module
{
	/** @var max image size */
	protected $maxImageSize = 307200;

	function __construct()
	{
		$this->name = 'blockarrival';
		$this->tab = 'Tools';
		$this->version = '1.0';
		$this->visible = true;

		parent::__construct();

		$this->displayName = $this->l('Arrival items information block');
		$this->description = $this->l('A text editor module for arrivals');
	}

	function install()
	{
		if (!parent::install())
			return false;
		return $this->registerHook('home') && $this->registerHook('rightColumn');
	}



	function getContent()
	{
		/* display the module name */
		$this->_html = '<h2>'.$this->displayName.'</h2>';
		$errors = '';

		/* update the  xml */
		if (isset($_POST['submitUpdate']))
		{
			// Forbidden key
			$forbidden = array('submitUpdate');

			foreach ($_POST AS $key => $value)
				if (!Validate::isCleanHtml($_POST[$key]))
				{
					$this->_html .= $this->displayError($this->l('Invalid html field, javascript is forbidden'));
					$this->_displayForm();
					return $this->_html;
				}

			// Generate new XML data
			$newXml = '<?xml version=\'1.0\' encoding=\'utf-8\' ?>'."\n";
			$newXml .= '<arrivals>'."\n";
			$newXml .= '	<header>\n';

			// Making header data
			foreach ($_POST AS $key => $field)
				if ($line = $this->putContent($newXml, $key, $field, $forbidden, 'header'))
					$newXml .= $line;

			$newXml .= "\n";
			$newXml .= "        <arrival_path>".__PS_BASE_URI__ ."modules/blockarrival/</arrival_path>\n";
			$newXml .= '	</header>'."\n";

			$newXml .= '	<body>';
			// Making body data
			foreach ($_POST AS $key => $field)
				if ($line = $this->putContent($newXml, $key, $field, $forbidden, 'body'))
					$newXml .= $line;
			$newXml .= "\n".'	</body>'."\n";
			$newXml .= '</arrivals>'."\n";

			/* write it into the  xml file */
			if ($fd = @fopen(dirname(__FILE__).'/blockarrival.xml', 'w'))
			{
				if (!@fwrite($fd, $newXml))
					$errors .= $this->displayError($this->l('Unable to write to the editor file.'));
				if (!@fclose($fd))
					$errors .= $this->displayError($this->l('Can\'t close the editor file.'));
			}
			else
				$errors .= $this->displayError($this->l('Unable to update the editor file.<br />Please check the editor file\'s writing permissions.'));


			$this->_html .= $errors == '' ? $this->displayConfirmation('Settings updated successfully') : $errors;
		}

		/* display the arrivals form */
		$this->_displayForm();

		return $this->_html;
	}

	function putContent($xml_data, $key, $field, $forbidden, $section)
	{
		foreach ($forbidden AS $line)
			if ($key == $line)
			return 0;
		if (!preg_match('/^'.$section.'_/i', $key))
			return 0;

		// Itt lecsereljuk a body_ es a header_ html param prefixeket, amik az inputban vannak. Ezzel lett megszurve az altalanos parameterektol ami marad
		$key = preg_replace('/^'.$section.'_/i', '', $key);


		//$field = str_replace('[villog]', '<span class="blinking_content">', $field);
		//$field = str_replace('[/villog]', '</span>', $field);

		//$this->_html .= '['.$key.'='.$field.']<br>';

		$field = htmlspecialchars($field);


		if (!$field)
			return 0;

		return ("\n".'		<'.$key.'>'.$field.'</'.$key.'>');
	}

	private function _displayForm()
	{
		global $cookie;
		/* Languages preliminaries */
		$defaultLanguage = intval(Configuration::get('PS_LANG_DEFAULT'));
		$languages = Language::getLanguages();
		$iso = Language::getIsoById($defaultLanguage);
		$divLangName = 'title¤cpara';

		/* xml loading */
		$xml = false;
		if (file_exists(dirname(__FILE__).'/blockarrival.xml'))
				if (!$xml = simplexml_load_file(dirname(__FILE__).'/blockarrival.xml'))
					$this->_html .= $this->displayError($this->l('Your editor file is empty.'));

		$this->_html .= '
		<script type="text/javascript" src="'.__PS_BASE_URI__.'js/tinymce/jscripts/tiny_mce/jquery.tinymce.js"></script>
		<script type="text/javascript">
		function tinyMCEInit(element)
		{
			$().ready(function() {
				$(element).tinymce({
					// Location of TinyMCE script
					script_url : \''.__PS_BASE_URI__.'js/tinymce/jscripts/tiny_mce/tiny_mce.js\',
					// General options
					theme : "advanced",
					plugins : "safari,pagebreak,style,layer,table,advimage,advlink,inlinepopups,media,searchreplace,contextmenu,paste,directionality,fullscreen",
					entity_encoding: "raw",
					paste_auto_cleanup_on_paste: true,
					paste_remove_styles: false,
					// Theme options
					theme_advanced_buttons1 : "bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,fontsizeselect,forecolor,backcolor,|,bullist,numlist,|,link,unlink,sub,sup,charmap,|,undo,redo,cut,copy,paste,code",
					theme_advanced_buttons2 : "",
					theme_advanced_buttons3 : "",
					theme_advanced_buttons4 : "",
					theme_advanced_toolbar_location : "top",
					theme_advanced_toolbar_align : "left",
					theme_advanced_statusbar_location : "bottom",
					theme_advanced_resizing : true,
					content_css : "'.__PS_BASE_URI__.'themes/'._THEME_NAME_.'/css/global.css",
					// Drop lists for link/image/media/template dialogs
					template_external_list_url : "lists/template_list.js",
					external_link_list_url : "lists/link_list.js",
					external_image_list_url : "lists/image_list.js",
					media_external_list_url : "lists/media_list.js",
					elements : "nourlconvert",
					convert_urls : false,
					language : "'.(file_exists(_PS_ROOT_DIR_.'/js/tinymce/jscripts/tiny_mce/langs/'.$iso.'.js') ? $iso : 'en').'"
				});
			});
		}
		tinyMCEInit(\'textarea.rte\');
		</script>
		<script type="text/javascript">id_language = Number('.$defaultLanguage.');</script>

		<form method="post" action="'.$_SERVER['REQUEST_URI'].'">
			<fieldset style="width: 900px;">
				<legend><img src="'.$this->_path.'logo.gif" alt="" title="" />'.$this->l('Arrival items block').'</legend>

				<label>'.$this->l('Block title').'</label>
				<div class="margin-form" name="atitle">';

				foreach ($languages as $language)
				{
					$this->_html .= '
					<div id="title_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').';float: left;">
						<input type="text" name="body_title_'.$language['id_lang'].'" id="body_title_'.$language['id_lang'].'" size="64" value="'.($xml ? stripslashes(htmlspecialchars($xml->body->{'title_'.$language['id_lang']})) : '').'" />
					</div>';
				 }
				$this->_html .= $this->displayFlags($languages, $defaultLanguage, $divLangName, 'title', true);


		$this->_html .= '
					<p class="clear"></p>
				</div>
				<!-- /atitle -->';


		$this->_html .= '
				<label>'.$this->l('Blinking content').'</label>
				<div class="margin-form" name="blinking">
						<p>'.$this->l('You have to wrap "should be blink" text with [blink]should be blink[/blink] or [pulse]should be pulse[/pulse]. ').'</p>';

		/*
		  $this->_html .= '<p>'.$this->l('Blinking before').'
						<input type="text" name="header_blinking_to" id="header_blinking_to" size="10" value="'.($xml ? stripslashes(htmlspecialchars($xml->header->blinking_to)) : '').'" /></p>';

		 */

		$this->_html .= '
					<p class="clear"></p>
				</div>
				<!-- /blinking -->';

		$this->_html .= '
				<label>'.$this->l('Block content').'</label>
				<div class="margin-form" name="acontent">';

				foreach ($languages as $language)
				{
					$this->_html .= '
					<div id="cpara_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').';float: left;">
						<textarea class="rte" cols="70" rows="70" id="body_paragraph_'.$language['id_lang'].'" name="body_paragraph_'.$language['id_lang'].'">'.($xml ? stripslashes(htmlspecialchars($xml->body->{'paragraph_'.$language['id_lang']})) : '').'</textarea>
					</div>';
				 }

				$this->_html .= $this->displayFlags($languages, $defaultLanguage, $divLangName, 'cpara', true);

				$this->_html .= '
					<p class="clear"></p>
				</div>
				<!-- /acontent -->';

		$this->_html .= '
				<label>'.$this->l('Main content url').'</label>
				<div class="margin-form" name="amlink">
					<input type="text" name="header_link_to" id="header_link_to" size="100" value="'.($xml ? stripslashes(htmlspecialchars($xml->header->link_to)) : '').'" />
					<p class="clear"></p>
				</div>
				<!-- /amlink -->';

		$this->_html .= '
				<div class="clear pspace"></div>
				<div class="margin-form clear"><input type="submit" name="submitUpdate" value="'.$this->l('Update the editor').'" class="button" /></div>
			</fieldset>
		</form>';
	}


	/**
	* Left column hook
	*/
	public function hookLeftColumn($params)
	{
		return $this->hookRightColumn($params);
	}

	/**
	* Right column hook. Menu to main content.
	*/
	public function hookRightColumn($params)
	{
		$xml =  $this->prepareXml();

		if ($xml){

			global $cookie, $smarty;
			$smarty->assign(array(
				'blinking_to' => $xml->header->blinking_to,
				'arrival_title' => $xml->body->{'title_'.$cookie->id_lang},
				'link_to' => $xml->header->link_to,
				'arrival_path' =>  $xml->header->arrival_path
			));

			return $this->display(__FILE__, 'blockarrival_right.tpl');
		}

	}

	function hookHome($params)
	{
		$xml =  $this->prepareXml();

		if ($xml){

			global $cookie, $smarty;
			$smarty->assign(array(
				'xmlarr' => $xml,
				'title' => 'title_'.$cookie->id_lang,
				'arrival_title' => $xml->body->{'title_'.$cookie->id_lang},
				'paragraph' => 'paragraph_'.$cookie->id_lang,
				'blinking_to' => $xml->header->blinking_to,
				'link_to' => $xml->header->link_to
			));
			return $this->display(__FILE__, 'blockarrival_home.tpl');

		}
		return false;
	}

	/**
	 * Az xmlt dolgozza fel kicserélve benne a villogást span tagekre.
	 * Kitolti az xml->header->blinking_to es az xml->header->link_to értékeket.
	 */
	function prepareXml(){

		if (file_exists('modules/blockarrival/blockarrival.xml'))
		{
			if ($xml = simplexml_load_file('modules/blockarrival/blockarrival.xml'))
			{
				global $cookie, $smarty;

				$content_tag = 'paragraph_'.$cookie->id_lang;

				$c = $xml->xpath('/arrivals/body/'.$content_tag);

				$s = (string)$c[0];

				$s = str_replace('[blink]', '<span class="blinking_content">', $s);
    			$s = str_replace('[/blink]', '</span>', $s);

	    		$s = str_replace('[pulse]', '<span class="pulsing_content">', $s);
	    		$s = str_replace('[/pulse]', '</span>', $s);

				$c[0][0] = $s;


				return $xml;
			}
		}

		return false;
	}

}
