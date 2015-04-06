<?php

include (PS_ADMIN_DIR .'/../modules/gsitemap/gsitemap.php');
include ('../index.php');
if(isset($_REQUEST["call_method"]))
{
	if($_REQUEST["call_method"]=="sendTransportMail"&& trim ($_POST["ajanlat-typename"])!="" && trim ($_POST["ajanlat-count"])!=""
		&& trim ($_POST["ajanlat-contact-nev"])!=""&&trim ($_POST["ajanlat-contact-phone"])!=""	)
	{
		Util::sendTransportMail($_REQUEST["lang"]);
	}
	if($_REQUEST["call_method"]=="poolTransportMail")
	{
		Util::poolTransportMail();
	}
	if($_REQUEST["call_method"]=="reservedUnlock")
	{
		Util::reservedUnlock();
	}
}

/**
 * Special extensions and util procedures.
 *
 * @author karl
 *
 */
class Util //extends ObjectModel
{
	/**
	 * 
	 * A foglalt jelzesek levetele a lejart hataridos termekekrol. 
	 */
	static public function reservedUnlock() {
	
		$sqlscript = 'UPDATE ' . _DB_PREFIX_ . 'product set reserved = 0, reserved_date = null WHERE (reserved_date is not null AND reserved_date <= DATE_SUB(NOW(), INTERVAL 1 DAY))';
		$rowcount = Db::getInstance()->Execute($sqlscript);
		echo 'Updated '.$rowcount.' record in ('.$sqlscript.')<BR>';
		$sqlscript = 'DELETE FROM ' . _DB_PREFIX_ . 'category_product WHERE id_category = 99 AND `id_product` not in (SELECT id_product FROM ' . _DB_PREFIX_ . 'product WHERE reserved > 0 AND reserved_date IS NOT NULL)';
		$rowcount = Db::getInstance()->Execute($sqlscript); 
		echo 'Deleted '.$rowcount.' record from ('.$sqlscript.')';
	}

	const ACTION_RESERVEDUNLOCK = "reservedUnlock";
	const ACTION_NEWSLETTER = "newsletter";
	const ACTION_TRANSPORTPOOLING = "transportpooling";
	const PS_MAIL_TRANSPORTERS = "PS_MAIL_TRANSPORTERS";
	const PS_MAIL_TRANSPORT_REPLY = "PS_MAIL_TRANSPORT_REPLY";
	const PS_MAIL_TRANSPORT_OFFICE = "PS_MAIL_TRANSPORT";

	
	/**
	 * Send emails to transporters.
	 * Prepare client e-mail address and put transport-offer reply address.
	 *
	 *
	----------------- AZONOSÍTÓ BÉLYEG: NE MÓDOSÍTSA VAGY TÖRÖLJE! -----------------
	[@@@@@@VGhpcyBpcyBhbiBlbmNvZGVkIHN0cmluZw==@@@@@@] (email)
	[||||||bmNvZGVkIHN0cmluZw==VGhpcyBpcyBhbiBl||||||] (customerid)
	----------------- AUTHENTICATION AREA: DON'T EDIT OR DELETE IT -----------------
	 */
	static public function sendTransportMail($lang) {

		foreach($_POST as $form_key => $form_val){
			echo $form_key."=".$form_val."<br>";
		}

		$machineTypename = $_POST["ajanlat-typename"];
		$machineCount = $_POST["ajanlat-count"];
		$machineDimesion = $_POST["ajanlat-dimesion"];
		$machineWeight = $_POST["ajanlat-weight"];
		$contactCountry = $_POST["ajanlat-country"];
		$contactAddress = $_POST["ajanlat-address"];
		$contactEutax = $_POST["ajanlat-eutax"];
		$contactName = $_POST["ajanlat-contact-nev"];
		$contactPhone = $_POST["ajanlat-contact-phone"];
		$contactEmail = $_POST["ajanlat-contact-email"];
		$question = $_POST["ajanlat-question"];

		// Send an e-mail to transporters

		$transporters = Configuration::get(Util::PS_MAIL_TRANSPORTERS);
		if ( $transporters )
		{
			$autoreply =  Configuration::get(Util::PS_MAIL_TRANSPORT_REPLY); // belepesi adatokkal!
			$autoreply = explode("#", $autoreply);
			$autoreply = $autoreply[0];

			$emailcode = base64_encode($contactEmail);

				if($lang != "hu")
				{
					$lang = "Az ügyfél nyelve angol!";
				}
				else
				{
					$lang="";
				}
			$data = array(
				'{machineTypename}' => $machineTypename,
				'{machineCount}' => $machineCount,
				'{machineDimesion}' => $machineDimesion,
				'{machineWeight}' => $machineWeight,
				'{contactCountry}' => $contactCountry,
				'{contactAddress}' => $contactAddress,
				'{contactEutax}' => $contactEutax,
				'{contactName}' => $contactName,
				'{contactPhone}' => $contactPhone,
				'{contactEmail}' => $contactEmail.", ".$autoreply,
				'{contactEmailCode}' => $emailcode,
				'{contactId}' => "0",
				'{question}' => $question,

				'{lang}'=> $lang
			);


			$tokens = explode("\n", $transporters);
			foreach ($tokens AS $email){
				if(Validate::isEmail(trim($email))){
					Mail::Send(3,
					'transporter',
					'Szállítási ajánlat kérése',
					$data,
					trim($email),
					'Szállító',
					$autoreply,
					'Vending Outlet Webshop');
				}
			}
		}
		}

	/**
	 * Check transport answer emails.
	 * Called from blocknewsletter module but only technical reason.
	 * Sheduled from cron, public index.php via blocknewsletter.php.hookLeftColumn().
	 *
	 * Lekeri a szallitoi valaszleveleket es elkuldi oket az ugyfelnek.
	 * Az ugyfel e-mail cime kodova a leveltorzsben van.
	 * Ha nincs, akkor a level megy a transport mail cimre.
	 * Ha megvan az eredeti email, akkor oda megy a level, valaszcimnek megadva a transport mail cim.
	 *
	 */
	static public function poolTransportMail() {

		global $cookie, $link;
		try {

			$autoreply =  Configuration::get(Util::PS_MAIL_TRANSPORT_REPLY); // belepesi adatokkal!
			$autoreply = explode("#", $autoreply);
			$pop3server = $autoreply[2];
			$pop3pass = $autoreply[1];
			$pop3user = $autoreply[0];

			echo 'Csatlakozas ezekkel: '.$pop3server." ".$pop3user." ".$pop3pass."<br>";

			$mbox = imap_open("{".$pop3server."}", $pop3user, $pop3pass);

			echo "Connected: ".$mbox."<br>";

			$count = imap_num_msg($mbox);
			echo "Uj levelek szama:".$count."<br>";

			for($msgno = 1; $msgno <= $count; $msgno++) {

				$headers = imap_headerinfo($mbox, $msgno);
    			$body= imap_fetchbody($mbox , $msgno, 1.2);
    			$text = Util::ReplaceImap($body);
    			echo "Body: <br>".$body."<br>";
    			echo "Text1: <br>".$text."<br>";
    			//$text = imap_utf7_decode ( $body);
    			$text=quoted_printable_decode ($text);
    			echo "Text2: <br>".$text."<br>";
    			$needle = "[@@@@@@";
    			$needle_len = strlen($needle);
    			$pos1 = strpos($text,$needle)+ strlen($needle);
    			$result_string = substr($text,$pos1);
    			$needle2 = "@@@@@@]";
    			$pos2 = strpos($result_string,$needle2);
    			$result_string = substr($text, $pos1, $pos2);
    			$client_email = base64_decode ( $result_string );

				$office = Configuration::get(Util::PS_MAIL_TRANSPORT_OFFICE);

				$data = array(	'{body}' => $text);

				if(Validate::isEmail(trim($client_email))){
					Mail::Send(3,
					'transporter2',
					'Válasz: Szállítási ajánlat kérése',
					$data,
					trim($client_email),
					"",
					trim($office),
					'Vending Outlet Webshop');

					echo "Level elkuldve ide: ".$client_email."</br>";

				} else {
					Mail::Send(3,
					'transporter2',
					'Ügyfél e-mail ismeretlen!',
					$data,
					trim($office),
					"",
					"",
					'Webshop');

					echo "Nem sikerult azonositani: ".$client_email.". Ezert elkuldve az office-ba.</br>";
				}

				imap_delete ( $mbox,$msgno);

    		}

    		imap_expunge($mbox);

			imap_close($mbox);

		} catch (Exception $ex) {
			die($ex);
		}
	}
function delTableBr($groups) {
    return '<table'.$groups[1].'>'.preg_replace('/<br[^>]*?>/si', '',$groups[2]).'</table>';
}
function ReplaceImap($txt) {
  $carimap = array(
  "=C3=A9", "=C3=A8", "=C3=AA", "=C3=AB", "=C3=A7",
  "=C3=A0", "=20", "=C3=80", "=C3=89", "=C3=8D", 
  "=C3=93","=C3=96", "=C3=AD", "=C3=A1", "=C3=BC",
  "=C3=B6", "=C3=BA", "=C3=B3", 
  "=ED","=CD","=E9","=C9","=E1",
  "=C1","=D6","=F6","=D5","=F5",
  "=FC","=DB","=FA","=DA","=F3",
  "=D3","=FB","=DC","=A0");
  $carhtml = array(
  "é", "è", "ê", "ë", "ç",
  "à", "&nbsp;", "À", "É", "Í", 
  "Ó", "Ö", "í", "á", "ü", 
  "ö", "ú", "ó",
  "í","Í","é","É","á",
  "Á","Ö","ö","Ő","ő",
  "ü","Ű","ú","Ú","ó",
  "Ó","ű","Ü"," "
  );
  $txt = str_replace($carimap, $carhtml, $txt);

  return $txt;
}
	/*
	 * Create or refresh /sitemap.xml
	 *
	 */
	static public function createGsitemap() {

		$gsm = new Gsitemap();
		$_POST['Util_proc'] = 'createGsitemap()';
		$gsm->getContent();

	}
	/**
	 * Send emails of new products.
	 * Called from blocknewsletter module.
	 * Sheduled from cron, public index.php with NewProductLetterTime GET param.
	 *
	 * Funcion gets products whitch are older than last run time, default midnight.
	 * If product list exists, iterate on, and send emails to newsletter and customer addresses.
	 *
	 *
	 */
	static public function sendNewProductMail($hours, $id_lang = 1) {

		echo "classes/Util.php.sendNewProductMail(".$hours.", ".$id_lang.")";

		global $cookie, $link;
		try {
			$start = null;
			$limit = 0;
			$orderBy = 'name';
			$orderWay = 'ASC';
			$id_category = false;
			$only_active = true;
			$MAIL_BLOCK_SIZE = 10;
			$WAITING_MINUTES = 5;
			$newproductCount = 0;
			$subject = 'Vending Outlet Webshop';
			$list_en = '';
			$list_hu = '';
			$id_lang = 3; // magyarul eloszor

			$filter_where = 'AND p.date_add >= ADDDATE(now(), INTERVAL -'.$hours.' HOUR)';

			$newproducts = Product :: getProducts($id_lang, $start, $limit, $orderBy, $orderWay, $id_category, $only_active, $filter_where);

			if ($newproducts) { // magyar lista

				// eleg itt, az angol ugyanekkora
				$newproductCount = count($newproducts);

				$link = new Link();
				$defaultCurrency = new Currency(Configuration::get('PS_CURRENCY_DEFAULT'));

				foreach ($newproducts AS $record){

					$product = new Product(intval($record['id_product']), false, $id_lang);
					$productLink = $link->getProductLink($product);

					$price = Tools::displayPrice($product->getPrice(true, NULL, 2), $defaultCurrency);

					$list_hu = $list_hu.'<li><a href="'.$productLink.'">'.$product->name.'</a> - '.$product->description_short.' '.$price.'</li>';

				}
			}

			$id_lang = 1; // angol lista

			$newproducts = Product :: getProducts($id_lang, $start, $limit, $orderBy, $orderWay, $id_category, $only_active, $filter_where);

			if ($newproducts) { // angol lista

				$link = new Link();
				$defaultCurrency = new Currency(Configuration::get('PS_CURRENCY_DEFAULT'));

				foreach ($newproducts AS $record){

					$product = new Product(intval($record['id_product']), false, $id_lang);
					$productLink = $link->getProductLink($product);

					$price = Tools::displayPrice($product->getPrice(true, NULL, 2), $defaultCurrency);

					$list_en = $list_en.'<li><a href="'.$productLink.'">'.$product->name.'</a> - '.$product->description_short.' '.$price.'</li>';

				}
			}

			if ($newproducts) {

				echo '<ul>'.$list_hu.'</ul>';

				$maildata = array (
					'{$to}' => null,
					'{$toName}' => null,
					'{$from}' => null,
					'{$fromName}' => null,
					'{$shop_name}' => Configuration :: get('PS_SHOP_NAME'),
					'{$shop_url}' => __PS_BASE_URI__,
					'{$list_hu}' => '<ul>'.$list_hu.'</ul>',
					'{$list_en}' => '<ul>'.$list_en.'</ul>',
					'{$subject}' => $subject,
					'{$newproductCount}' => $newproductCount
				);


				echo '<BR/>-------------------------------------------------<BR/>';

				$template = 'newproduct';
				$sqlscript = 'SELECT `email`, `firstname`, `lastname` ' .
				'FROM ' . _DB_PREFIX_ . 'customer WHERE `newsletter` IN (2, 3) AND `active` = 1 AND `deleted` = 0';

				// felhasznaloi cimek
				$mails = Db :: getInstance()->ExecuteS($sqlscript);
				$blocksize = 0;

				if ($mails) {
					foreach ($mails AS $k => $mail) {
						$blocksize++;

						$maildata['{$to}'] = $mail['email'];
						$maildata['{$toName}'] = $mail['firstname'] . ' ' . $mail['lastname'];

						echo 'Címzett: '.$mail['email'].' '.$mail['firstname'] . ' ' . $mail['lastname'].'<BR/>';

						Mail :: send(1, $template, $maildata['{$subject}'], $maildata, $maildata['{$to}'], $maildata['{$toName}'],
						$maildata['{$from}'], $maildata['{$fromName}'], NULL, _PS_MAIL_DIR_);

						if ($blocksize == $MAIL_BLOCK_SIZE) {
							// wait x min before send next block
							sleep($WAITING_MINUTES);
							$blocksize = 0;
						}
					}
				}


				// kulsos feliratkozott cimek
				$sqlscript = 'SELECT `email` FROM ' . _DB_PREFIX_ . 'newsletter';
				//echo $sqlscript;
				$mails = Db :: getInstance()->ExecuteS($sqlscript);
				//var_dump($mails);
				$blocksize = 0;

				if ($mails) {
					foreach ($mails AS $mail) {
						$blocksize++;

						$maildata['{$to}'] = $mail['email'];
						$maildata['{$toName}'] = 'Ügyfelünk';

						echo 'Címzett: '.$mail['email'].'<BR/>';

						Mail :: send(1, $template, $maildata['{$subject}'], $maildata, $maildata['{$to}'], $maildata['{$toName}'],
						$maildata['{$from}'], $maildata['{$fromName}'], NULL, _PS_MAIL_DIR_);

						if ($blocksize == $MAIL_BLOCK_SIZE) {
							// wait x min before send next block
							sleep($WAITING_MINUTES);
							$blocksize = 0;
						}
					}
				}
			} // van kuldendo $newproducts list

		} catch (Exception $ex) {
			die($ex);
		}
	}



}


?>
