<?php

if (isset($smarty))
{
	$smarty->assign(array(
		'HOOK_RIGHT_COLUMN' => Module::hookExec('rightColumn'),
		'HOOK_FOOTER' => Module::hookExec('footer'),
		'content_only' => intval(Tools::getValue('content_only'))));
	$smarty->display(_PS_THEME_DIR_.'footer.tpl');
}

global $cookie;
$ps_language = new Language(intval($cookie->id_lang));

echo
'

<script type="text/javascript">
    jQuery().ready(function(){

    	
		jQuery(document).watermark({className:"reserved", path:"http://www.vendingoutlet.org/img/reserved-'.$ps_language->iso_code.'.png"});
		setTimeout(function(){
			jQuery(window).resize(function() {
				jQuery(".watermarker-div").remove();
				jQuery(document).watermark({className:"reserved", path:"http://www.vendingoutlet.org/img/reserved-'.$ps_language->iso_code.'.png"});
			});
		},1000);
		// kategoria leiras div blokkja
		//maybeExcel = jQuery(".cat_desc").val();
		/*arr = maybeExcel.split(/[\n\f\r]/);
	   	for(i=0; i<arr.length; i++)
	   	{
	      	row = arr[i].split(/[\t]/);
	      	alert(row);
	   	}*/
		
		jQuery("#ajanlat-submit").click(function() {
			if(validate())
			{
				jQuery(this).attr("disabled", "true"); 
  				sendTransportMail();
  			}
		});
		
		jQuery("#ajanlat-form input").keyup(function() {
			if(this.name=="ajanlat-contact-email"){
				if(jQuery("input[name=\"ajanlat-contact-email\"]").val().indexOf("@")>=0){
					jQuery(this).css("border-color", "#bdc2c9");
				}
			}
			else{
				jQuery(this).css("border-color", "#bdc2c9");
			}
		}); 
		
		
		if(imgtemplate && imgtemplate != "undefined"){
			// push default
			logos.push(imgtemplate);
			//logic changes max 5 logos (4+default) 
			checkLogo(1);
		}
	    
		
	});
	
	var logos = new Array();
	var logosCount = 5;
	var imgtemplate = jQuery(".homepage_logo").attr("src");
	
	// check index+1.gif and push logos if exist than call recursive  
	function checkLogo(gifindex){
	 	var imgurl = "http:/www.vendingoutlet.org"+imgtemplate.replace(".gif",gifindex+".gif");
//	 	 jQuery.ajax({url:imgurl, type:"HEAD",
//		    error: function(){
//		            //arr.splice(index, 1);
//		            alert(imgurl+" not exist");
//		            if(gifindex+1<logosCount)
//		            	checkLogo(gifindex+1);
//		        },
//		    success: function(){
//		            alert(imgurl+" is exists");
//		            if(gifindex+1<logosCount)
//		            	checkLogo(gifindex+1);
//		        }
//		});
		jQuery(new Image()).attr("src", imgurl)
		.load(function(){ 
			
		})
		.error(function(){ 
			
		});
	}
	
	function validate(){
		var valid = true;
		if(jQuery.trim(jQuery("input[name*=\"ajanlat-typename\"]").val())=="")
		{ 
			jQuery("input[name*=\"ajanlat-typename\"]").css("border-color", "red");
			valid = false;
		}
		if(jQuery.trim(jQuery("input[name*=\"ajanlat-count\"]").val())=="")
		{ 
			jQuery("input[name=\"ajanlat-count\"]").css("border-color", "red");
			valid = false;
		}
		if(jQuery.trim(jQuery("input[name=\"ajanlat-country\"]").val())=="")
		{ 
				
			jQuery("input[name=\"ajanlat-country\"]").css("border-color", "red");
			valid = false;
		}
		if(jQuery.trim(jQuery("input[name=\"ajanlat-address\"]").val())=="")
		{ 
		
			jQuery("input[name=\"ajanlat-address\"]").css("border-color", "red");
			valid = false;
		}
		if(jQuery.trim(jQuery("input[name=\"ajanlat-contact-nev\"]").val())=="")
		{ 
		
			jQuery("input[name=\"ajanlat-contact-nev\"]").css("border-color", "red");
			valid = false;
		}
		if(jQuery.trim(jQuery("input[name=\"ajanlat-contact-phone\"]").val())=="")
		{ 
		
			jQuery("input[name=\"ajanlat-contact-phone\"]").css("border-color", "red");
			valid = false;
		}
		if(jQuery("input[name=\"ajanlat-contact-email\"]").val()=="" || jQuery("input[name=\"ajanlat-contact-email\"]").val().indexOf("@")<=0)
		{ 
		
			jQuery("input[name=\"ajanlat-contact-email\"]").css("border-color", "red");
			valid = false;
		}
		return valid;
	}
	
	function sendTransportMail(){
	 	
		var formData = jQuery("#ajanlat-form").serialize();
		jQuery.ajax({
		    type: "POST",
		    url: "http://www.vendingoutlet.org/classes/Util.php?call_method=sendTransportMail&lang='.$ps_language->iso_code.'",
		    data: formData,
		    cache: false,
		    success: function()
		        {
		            jQuery(window.location).attr("href", "http://www.vendingoutlet.org/index.php?call_method=mailSent&lang='.$ps_language->iso_code.'");
		        }
    	});
	};
	var newstext = jQuery("#extranews").text();
	jQuery("#extranews").html("<center><a href=\"/lang-'.$ps_language->iso_code.'/content/11-szallitasi-ajanlat\" class=\"extranews-item\">"+newstext+"</a></center>").show();
	jQuery(".extranews-item").css("font-size","1.1em").css("font-weight","bold").css("color","blue");
</script>';

?>