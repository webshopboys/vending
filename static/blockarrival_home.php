<?php

	global $cookie, $smarty;

	include(dirname(__FILE__).'/../config/config.inc.php' );
    include(dirname(__FILE__).'/../header.php' );


    $xml = "";

    if (file_exists('../modules/blockarrival/blockarrival.xml'))
    {

    	if ($xml = simplexml_load_file('../modules/blockarrival/blockarrival.xml'))
    	{

    		$content_tag = 'paragraph_'.$cookie->id_lang;

    		$c = $xml->xpath('/arrivals/body/'.$content_tag);

    		$s = (string)$c[0];

    		$s = str_replace('[blink]', '<span class="blinking_content">', $s);
    		$s = str_replace('[/blink]', '</span>', $s);

    		$s = str_replace('[pulse]', '<span class="pulsing_content">', $s);
    		$s = str_replace('[/pulse]', '</span>', $s);

    		$c[0][0] = $s;

    		$smarty->assign(array(
    				'xmlarr' => $xml,
    				'arrival_title' => $xml->body->{'title_'.$cookie->id_lang},
    				'paragraph' => 'paragraph_'.$cookie->id_lang,
    				'blinking_to' => $xml->header->blinking_to,
    				'link_to' => $xml->header->link_to,
    				'arrival_path' => $xml->header->arrival_path
    		));

    	}
    }

    $smarty->display( dirname(__FILE__).'/../modules/blockarrival/blockarrival_home.tpl' );


	include(dirname(__FILE__).'/../footer.php');

?>
