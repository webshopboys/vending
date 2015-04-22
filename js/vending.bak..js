/**
 * A korábban kódba égetett de kiemelhető scriptek.
 */

jQuery().ready(function(){
	__init();
});

var $ps_language = "hu"; // en, ru


var logos = new Array();
var logosCount = 5;
var imgtemplate = jQuery(".homepage_logo").attr("src");


function __init(){
	
	_initLogos();
	_initWatermark();
	_initListeners();
	
	var newstext = jQuery("#extranews").text();
	jQuery("#extranews").html("<center><a href=\"/lang-'.$ps_language->iso_code.'/content/11-szallitasi-ajanlat\" class=\"extranews-item\">"+newstext+"</a></center>").show();
	jQuery(".extranews-item").css("font-size","1.1em").css("font-weight","bold").css("color","blue");

	insertBlocks();
}

function _initWatermark(){
	jQuery(document).watermark({className:"reserved", path:"http://www.vendingoutlet.org/img/reserved-"+$ps_language+".png"});
	setTimeout(function(){
		jQuery(window).resize(function() {
			jQuery(".watermarker-div").remove();
			jQuery(document).watermark({className:"reserved", path:"http://www.vendingoutlet.org/img/reserved-"+$ps_language+".png"});
		});
	},1000);
}

function _initListeners(){
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
}

function _initLogos(){
	if(imgtemplate && imgtemplate != "undefined"){
		// push default
		logos.push(imgtemplate);
		//logic changes max 5 logos (4+default)
		checkLogo(1);
	}
	
	fadeLogo();
}


function __vendingJsInitLanguage(ps_language){
	if(ps_language)
		$ps_language = ps_language;
}

function insertBlocks(){
	jQuery("#right_column").append("<div id=\'c1\'/><div id=\'c2\' class=\"\"/>");
	// jQuery("#c1").load("http://www.vendingoutlet.org/static/components-block.php");
	jQuery("#c2").load("http://www.vendingoutlet.org/static/packages-block.php");
	
	blink(".blinking_content");
	blink(".pulsing_content > *");
}


function sendTransportMail(){
	var formData = jQuery("#ajanlat-form").serialize();
	jQuery.ajax({
	    type: "POST",
	    url: "http://www.vendingoutlet.org/classes/Util.php?call_method=sendTransportMail&lang="+$ps_language,
	    data: formData,
	    cache: false,
	    success: function()
	        {
	            jQuery(window.location).attr("href", "http://www.vendingoutlet.org/index.php?call_method=mailSent&lang="+$ps_language);
	        }
	});
};



function pulse(selector){
    jQuery(selector).animate({opacity:0.2}, 800, "linear");
	jQuery(selector).animate({opacity:1.0}, 800, "linear");
	pulse(selector);
}

function blink(selector){
    jQuery(selector).fadeOut("slow", function(){
        jQuery(selector).fadeIn("slow", function(){
            blink(selector);
        });
    });
}

 function fadeLogo(){
 	jQuery(".logo1").fadeOut(5000, function(){
		jQuery(".logo2").fadeIn(1000, function(){
			jQuery(".logo2").fadeOut(5000, function(){
				jQuery(".logo3").fadeIn(1000, function(){
					jQuery(".logo3").fadeOut(5000, function(){
						jQuery(".logo1").fadeIn(1000, fadeLogo);
					});
				});
			 });
		});
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
 
 
//check index+1.gif and push logos if exist than call recursive
 function checkLogo(gifindex){
  	var imgurl = "http:/www.vendingoutlet.org"+imgtemplate.replace(".gif",gifindex+".gif");
//  	 jQuery.ajax({url:imgurl, type:"HEAD",
// 	    error: function(){
// 	            //arr.splice(index, 1);
// 	            alert(imgurl+" not exist");
// 	            if(gifindex+1<logosCount)
// 	            	checkLogo(gifindex+1);
// 	        },
// 	    success: function(){
// 	            alert(imgurl+" is exists");
// 	            if(gifindex+1<logosCount)
// 	            	checkLogo(gifindex+1);
// 	        }
// 	});
 	jQuery(new Image()).attr("src", imgurl)
 	.load(function(){

 	})
 	.error(function(){

 	});
 }
