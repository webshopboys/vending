/*
 *	watermark.js jQuery plugin
 *	Watermarked images with javascript and htmlcanvas	
 *
 *	author: Patrick Wied ( http://www.patrick-wied.at )
 *	version: 1.0
 *	license: MIT - feel free to use, modify, redistribute
 *	http://letmein.at/software/how-to-correctly-use-code-you-didnt-write/
 */

/**
	http://benalman.com/projects/jquery-resize-plugin/
*/ 
(function($,h,c){var a=$([]),e=$.resize=$.extend($.resize,{}),i,k="setTimeout",j="resize",d=j+"-special-event",b="delay",f="throttleWindow";e[b]=250;e[f]=true;$.event.special[j]={setup:function(){if(!e[f]&&this[k]){return false}var l=$(this);a=a.add(l);$.data(this,d,{w:l.width(),h:l.height()});if(a.length===1){g()}},teardown:function(){if(!e[f]&&this[k]){return false}var l=$(this);a=a.not(l);l.removeData(d);if(!a.length){clearTimeout(i)}},add:function(l){if(!e[f]&&this[k]){return false}var n;function m(s,o,p){var q=$(this),r=$.data(this,d);r.w=o!==c?o:q.width();r.h=p!==c?p:q.height();n.apply(this,arguments)}if($.isFunction(l)){n=l;return m}else{n=l.handler;l.handler=m}}};function g(){i=h[k](function(){a.each(function(){var n=$(this),m=n.width(),l=n.height(),o=$.data(this,d);if(m!==o.w||l!==o.h){n.trigger(j,[o.w=m,o.h=l])}});g()},e[b])}})(jQuery,this);
 
(function($){
	$.fn.watermark = function(cfg){
		var doc = this,
		gcanvas = {},
		gctx = {},
		imgQueue = [],
		className = "reserved",
		addHref = "#",
		watermark = false,
		watermarkPosition = "bottom-right",
		watermarkPath = "img/watermark.png?"+(+(new Date())),
		opacity = (255/(100/50)), // 50%
		initCanvas = function(){
			alert("initCanvas");
			gcanvas = $('<canvas style="display:none" class="watermarker-canvas"></canvas>');
			gctx = gcanvas[0].getContext("2d");
			$('body').append(gcanvas);
			
		},
		initWatermark = function(){
			watermark = $('<img src="'+watermarkPath+'" class="watermarker-img"/>');

			if(opacity != 255){
				if(!watermark[0].complete)
					watermark[0].onload = function(){	
						applyTransparency();
					};
				else
					applyTransparency();
				

			}else{
				applyWatermarks();
			}
			
		},
		// function for applying transparency to the watermark
		applyTransparency = function(){
			var w = watermark[0].width || watermark[0].offsetWidth,
			h = watermark[0].height || watermark[0].offsetHeight;
			
			setCanvasSize(w, h);
			gctx.drawImage(watermark[0], 0, 0);
					
			var image = gctx.getImageData(0, 0, w, h);
			var imageData = image.data,
			length = imageData.length;
			for(var i=3; i < length; i+=4){  
				imageData[i] = (imageData[i]<opacity)?imageData[i]:opacity;
			}
			image.data = imageData;
			gctx.putImageData(image, 0, 0);
			watermark[0].onload = null;
			watermark.attr("src", "");
			watermark.attr("src", gcanvas[0].toDataURL());
			// assign img attributes to the transparent watermark
			// because browsers recalculation doesn't work as fast as needed
			watermark.width(w);
			watermark.height(h);

			applyWatermarks();
		},
		configure = function(config){
			if(config){
				
				if(config["watermark"])
					watermark = config["watermark"];
				if(config["path"])
					watermarkPath = config["path"];
				if(config["position"])
					watermarkPosition = config["position"];
				if(config["opacity"])
					opacity = (255/(100/config["opacity"]));
				if(config["className"])
					className = config["className"];
				if(config["addHref"])
					addHref = config["addHref"];
			}
			
			if($('img.'+className).length>0){
				//initCanvas();
				//initWatermark();
				initLayer();
				
			}
		},
		initLayer = function(){
			setTimeout(function(){
				var els = $('.'+className);

				els.each(function(){
	                
					var img = $(this);
					
					if(img[0].tagName.toUpperCase() != "IMG")
						return;
	
					if(!img[0].complete){

						img[0].onload = function(){
							applyLayer(img);
						};
					}else{
						applyLayer(img);
					}
				});
				
				
				
			},100);
		},
		applyLayer = function(img){
			if(img.position()){
				var p = img;
				var w = img[0].width || img[0].offsetWidth,
				h = img[0].height || img[0].offsetHeight;
				
				var x = p.position().left,
				y = p.position().top;
				var wdiv = $('<div class="watermarker-div"></div>');
				var wrapperAnchor = $('<a href="'+addHref+'"></a>');
				wrapperAnchor.append(wdiv);
				$('body').append(wrapperAnchor);
				
				wdiv.css("background-image", "url("+watermarkPath+")");
				wdiv.css({'left':x,'top':y,'width':w,'height':h});  
				
			}			
		},
		setCanvasSize = function(w, h){
			gcanvas[0].width = w;
			gcanvas[0].height = h;
		},
		applyWatermark = function(img){

			setCanvasSize(img[0].width || img[0].offsetWidth, img[0].height || img[0].offsetHeight);
			gctx.drawImage(img[0], 0, 0);

			var position = watermarkPosition,
			x = 0,
			y = 0;
			if(position.indexOf("top")!=-1)
				y = 10;
			else
				y = gcanvas.height()-watermark.height()-10;
			
			if(position.indexOf("left")!=-1)
				x = 10;
			else
				x = gcanvas.width()-watermark.width()-10;
	        
			gctx.drawImage(watermark[0], x, y);
			img[0].onload = null;
	
			
			img.attr("src", gcanvas[0].toDataURL());
			
			
			
			
		},
		applyWatermarks = function(){
			setTimeout(function(){
				
				var els = $('.'+className);
				els.each(function(){
	                
					var img = $(this);
					
					if(img[0].tagName.toUpperCase() != "IMG")
						return;
	
					if(!img[0].complete){

						img[0].onload = function(){
							applyWatermark(img);
						};
					}else{
						applyWatermark(img);
					}
				});
			},100);
		};
		configure(cfg);
	};
})(jQuery);