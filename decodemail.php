<?php
	
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

	if(isset($_REQUEST["email"])){
		$text = $_REQUEST["email"];
		echo 'A kodolt e-mail cim: '.$text."<br/>";
		$needle = "[@@@@@@";
	    $needle_len = strlen($needle);
	    $pos1 = strpos($text,$needle)+ strlen($needle);
	    $result_string = substr($text,$pos1);
	    $needle2 = "@@@@@@]";
	    $pos2 = strpos($result_string,$needle2);
	    $result_string = substr($text, $pos1, $pos2);
	    $client_email = base64_decode ( $result_string );
		echo 'Az eredeti e-mail cim: '.$client_email."<br/>";	
	}else{
		echo "A mukodeshez a vendingoutlet.org/decodemail.php?email=[@@@@@@.....@@@@@@] alakban adja at a kodolt e-mail belyeget a ?email= utan es usson entert.";
	}
	

?>
