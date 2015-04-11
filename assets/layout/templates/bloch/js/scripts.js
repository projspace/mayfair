$(document).ready(function() {

	$('input[type=text].clearable, textarea.clearable').focus(function(){
		if($(this).attr('placeholder') == $(this).val())
			$(this).val('');														// Clear initial value

		$(this).blur(function(){
			var getNewVal = $(this).val();										// Read new input value
			if ( getNewVal == '') {
				$(this).val($(this).attr('placeholder'));										// Switch to initial value
			}
		});
	});
	
	$('.hover_item_info').live('click', function (){
		window.location = $('a.full-details', this).attr('href');
	});
	
	/*$('a').live('click', function (event)
	{      
	    var href = $(this).attr("href");
	
	    if (href.indexOf(location.hostname) > -1)
	    {
	        event.preventDefault();
	        window.location = href;
	    }
	});*/

	// input initial value
//	$("input[type=text]").initval();
	Cufon.replace('#review-content h1, #fitting-guide header h1, #account header h1, .accordion h1 a, #shopping-cart h1, #page-404 h1, #product section.info h1, .overlay div.header h1, #sidebar ul.pages li a, #story section h1, #content article header h1, .overlay div.header h1, ul.grid li a span.caption span',{hover: true, ignoreClass: 'nocufon'});
	Cufon.now();
	if ( typeof DD_belatedPNG != 'undefined' )
		DD_belatedPNG.fix("#page-footer a.webstars, #slideshow footer p.pager a, #search button em, #cart a.prev, #cart a.next, #product section.pics figcaption");
	var ie6 = $("html").is(".ie6");
/*  ---------------------------------------
	/header  */
	if ( $("#search").length ){
		var $search = $("#search");
//		var r = 154 - $search.find('span').position().left-$search.find('span').width();
		var r = 18;
		var $searchbutton = $search.find('button').css({right:r}).attr('disabled', 'disabled');
		$("#search input").focus(function(){
			$search.find('label > span').fadeOut('fast');
			$searchbutton.stop().animate({right: 0}).removeAttr('disabled');
		}).blur(function(){
			if ( $(this).val().match(/^\s*$/) ){
				$search.find('label>span').fadeIn('fast');
				$searchbutton.stop().animate({right:r}).attr('disabled', 'disabled');
			}
		});

		$.cart.init();

		$("#signupin a.in, #signupin a.up").fancybox({ 
			'width'				: 375
			,'height'			: '25%'
			,'type'				: 'iframe'
			,'modal'			: false
			//,'autoDimensions'	: false
			, onComplete: function(){
				Cufon.replace('.overlay .header h1');
			}
		});
		$(".postReviewBtn").fancybox({ 
			'width'				: 930
			,'height'			: 570
			,'type'				: 'iframe'
			,'modal'			: false
			//,'autoDimensions'	: false
			, onComplete: function(){
				Cufon.replace('.overlay .header h1');
			}
		});
		$("#fitting-guide-link, a.fancybox").fancybox({ ajax : { type:"GET"}, onComplete: function(){
			Cufon.replace('.overlay .header h1');
		} });
		
		$(".quick-view").live('click', function(){
			$.fancybox({
				'width'				: 576
				,'height'			: '25%'
				,'type'				: 'iframe'
				,'modal'			: false
				,'padding'			: 0
				,'overlayColor'		: '#EAE7DE'
				,'href'				: $(this).attr('href')
				//,'autoDimensions'	: false
				, onComplete: function(){
					Cufon.replace('.overlay .header h1');
				}
				, onStart: function(){
					$('#fancybox-close').css({top: '10px', right: '10px', width: '15px', height: '15px', background: '#fff url("'+config_dir+'layout/templates/bloch/css/fancybox/close.gif") no-repeat scroll'});
				}
				, onClosed: function(){
					$('#fancybox-close').css({top: '-15px', right: '-15px', width: '30px', height: '30px', background: 'url("'+config_dir+'layout/templates/bloch/css/fancybox/fancybox.png") repeat scroll -40px 0 transparent'});
				}
			});
			return false;
		});
	}

    $('a.fancybox').fancybox({
        'modal'			    : false
        ,'padding'			: 0
        ,'overlayColor'		: '#EAE7DE'
        , onStart: function(){
            $('#fancybox-close').css({top: '10px', right: '10px', width: '15px', height: '15px', background: '#fff url("'+config_dir+'layout/templates/bloch/css/fancybox/close.gif") no-repeat scroll'});
        }
        , onClosed: function(){
            $('#fancybox-close').css({top: '-15px', right: '-15px', width: '30px', height: '30px', background: 'url("'+config_dir+'layout/templates/bloch/css/fancybox/fancybox.png") repeat scroll -40px 0 transparent'});
        }
    });
	/*  ---------------------------------------
	/nav */

	$("#page-header nav div.sub > div.inner").each(function(){
		var w = $(this).find('ul').length * 150;
		$(this).width(w);
	});
	$("#page-header nav > ul > li").hover(function(){
		var l = $(this).position().left;
		var isMac = $('html').hasClass('mac');
		var isWin = $('html').hasClass('win');
		if ( $(this).children("div.sub").length )
		{
			if ( isMac ) {
				$(this).children("div.sub").stop().show().css({ opacity: 0}).animate({left: l-60, top: 36, opacity: 1}, 'fast');
			} else {
				$(this).children("div.sub").stop().show().css({ opacity: 0}).animate({left: l-60, top: 41, opacity: 1}, 'fast');
			}
			$(this).addClass('on');
		}
	}, function(e)
	{
		var $sub = $(this).children('div.sub');
		var x = e.clientX, y = e.clientY, xsign = '-', ysign = '-', xoff = 100, yoff = 100, sub = $sub.offset();;
		if ( x <= sub.left ) xsign = '+';
		if ( y <= sub.top ) ysign = '+';
		if ( x > sub.left && x < sub.left+$sub.width() ) xoff = 0;
		if ( y > sub.top && y < sub.top+$sub.height() ) yoff = 0;

		$(this).children("div.sub").stop().animate({left: xsign+'='+xoff, top: ysign+'='+yoff, opacity: 0},
		                                           'fast',
		                                           function(){ $(this).hide(); });
		$(this).removeClass('on');
	});

	if ( $("#page-header nav > ul > li > a.on").length )
		$("#page-header nav > ul > li > a").not('.on').addClass('off');

	$("#sidebar.filters ul.subcats:last").addClass('last');

	/*  ---------------------------------------
	/footer */
	$("#page-footer nav > ul").helpers('last').children('li').helpers('equal-height');
	$("#cats").helpers('last');


/*  ---------------------------------------
	/homepage sidebar */
	$("#cats li a").css('opacity',0);

	$("#cats li").hover(function(){
		$("a",this).stop().animate({opacity: 1},'medium');
	}, function(){
		$("a",this).stop().animate({opacity:0},1500);
	});

/*  ---------------------------------------
	/slideshow */
	var $slide = $("#slideshow");

	if ( $slide.length && $slide.find('section img').length > 1 ){
		var $pager = $("<p class='pager' />").prependTo($slide.find('footer'));
		var $desc = $("<p></p>").appendTo($slide.find('footer'));
		var $btn = $slide.find('footer a.btn-green');
		$slide.find('section').cycle({
			pager: $pager,
			before: function(){
				$desc.html($(this).attr('alt'));
				$desc.html($(this).attr('alt'));
				$btn.html($(this).attr('data-button-text')).attr('href', $(this).attr('data-button-url'));
			}
		});

	}
/*  ---------------------------------------
	/filters & product listing*/
	$("#filters").bind({
		'ajax-start' : function(){
			$(this).addClass('loading').children().fadeTo('fast',.25);
			$(this).find('.filter .range').slider('disable');
		},
		'ajax-stop': function(){
			$(this).find('.filter .range').slider('enable');
			$(this).removeClass('loading').children().fadeTo('fast', 1);
			$(this).find('.filter ul').each(function(){
				var $this = $(this);
				var deltay = -6;
				if(!$this.hasClass('single') || !$this.find('li a.on').length)
					$this.find('li').each(function() {deltay += $(this).outerHeight()});
				if ( deltay > 120 ){
					$this.wrap('<div class="overflow"></div>');
					var $div = $this.parent();
					var $scroll = $('<div class="scroll" />');
					deltay = Math.abs(deltay-$this.height());
					$div.append($scroll);
					$scroll.slider({
						orientation: 'vertical',
						value: 100,
						slide: function(ev, ui){
							$this.find('li').css('top', -(1-ui.value/100) * deltay );
						}
					});
					//157 - 36

				}
			})
		}
	});
	$("#view-options").bind({
		'ajax-start': function(){
			$(this).addClass('loading').find('.selectbox-wrapper, .page-view').fadeTo('fast',.25);
			$(this).find('select').selectBox('disable');
		},
		'ajax-stop': function(){
			$(this).removeClass('loading').find('.selectbox-wrapper, .page-view').fadeTo('fast', 1);
			$(this).find('select').selectBox('enable');
		}
	});

	$("#pagination").bind({
		'ajax-start': function(){ $(this).addClass('loading').fadeTo('fast', .1); },
		'ajax-stop': function(){ $(this).removeClass('loading').fadeTo('fast', 1); }
	});
	var itemHide = function($li){
		$li.fadeOut(100);
		if ( $li.prev().length ) setTimeout(function(){itemHide($li.prev())}, 25);
	};
	var itemShow = function($li){
		$li.fadeIn('fast');
		if ( $li.next().length ) setTimeout(function() {itemShow($li.next())}, 100);
	};
	$(".product-listing").bind({
		'ajax-start': function(){ itemHide($(this).children('li:last')); },
		'ajax-stop': function(){ $(this).children('li').hide(); itemShow($(this).children('li:first')); }
	});


/* cart */
	$("ul.cart-items li .box").helpers('equal-height');
	$("ul.taxes select").selectBox();
	
/*  ---------------------------------------
	/product view */
	//$("#product figure p a").fancybox({
	//	type: 'iframe' });
	$("#story aside ul a").fancybox({ type: 'image'});

	$("ul.related li a").hover(function(){
		$(this).find('img').stop().fadeTo('fast',.25);
		$(this).find('em').stop().fadeTo('fast',1);
	}, function(){
		$(this).find('img').stop().fadeTo('fast', 1);
		$(this).find('em').stop().fadeTo('fast', 0);
	}).find('em').css('opacity',0);

	var acClick = function(){
		if ( $(this).is('.on') ){
			$(this).removeClass('on').siblings('span').html('View');
			$(this).parents('.accordion').children().not('h1').slideUp('fast');
		}else{
			$(this).addClass('on').siblings('span').html('Hide');
			$(this).parents('.accordion').children().not('h1').slideDown('fast');
		}
		return false;
	};
	$("section.accordion").addClass('clearfix').each(function(){
		$(this).find('h1').prepend('<span class="nocufon">View</span>').find('a').bind('click.accordion', acClick);
		$(this).children().not('h1').hide();
	}).filter(':last').addClass('last');
	$("#product ul.reviews").helpers('last');

/*  ---------------------------------------
	/search-results */

	$("ul.results li").hover(function(){
		$(this).stop().animate({backgroundColor: '#fff'}, 750);
	}, function() {
		$(this).stop().animate({backgroundColor: '#f9f8eb'}, 750);
	});
	var searchDisplay = function(selector){
		$("#search-results ul.results").children(selector).stop().slideDown('fast');
		$("#search-results ul.results").children().not(selector).stop().slideUp('fast');
	};

	$("#search-results-filter a").click(function(){
		if ( $(this).is('.on') ) return false;
		$(this).parents('ul').find('a').removeClass('on');
		$(this).addClass('on');
		searchDisplay($(this).attr('data-filter'));
		var selector = $(this).attr('data-pagination');
		$("#search-results .pagination").each(function(){
			if($(this).is(selector))
				$(this).show();
			else
				$(this).hide();
		});
		Cufon.refresh();
		return false;
	}).filter('.on').removeClass('on').click();

/*  ---------------------------------------
	/forms */
	$("form.std-form select:not(.mobile)").selectBox().each(function(){
		$(this).selectBox('control').data('selectBox-options').addClass('selectBox-options-std-form');
	});

	$("form a.submit").helpers('submit');

/*  ---------------------------------------
	/fitting guides */
	$("#fitting-table tbody tr:odd").addClass('odd');
	$("#fitting-table tr").helpers('last');
	$(".accordion #fitting-guide-link").unbind('click.accordion');

/*  --------------------------------------- */

	/* edit address and personal details */
	$('#account table.data-table td a.edit').click(function(){
		var getTable = $(this).parents('.data-table');
		var getDisabledInputs = getTable.find('input,textarea');
		var toDo = $(this).text();
		var form = $(this).parents('form');

		// make inputs editable
		if ( toDo == 'Edit' ) {
			getDisabledInputs.each(function(){
				$(this).attr('disabled',false).addClass('editable');
			});
			if(getDisabledInputs.get(0) != undefined)
				getDisabledInputs.get(0).focus();
			$(this).text('Save');
		} else {
			var validation = $.trim(form.attr('validation'));
			var pass = true;
			if(validation != '')
				pass = validations[validation]();
			if(!pass)
				return false;
			
			var ret = false;
			$.ajax({
				async: false,
				url: form.attr('action'),
				type: 'post',
				data: form.serialize(),
				dataType: 'json',
				success: function(json){
					ret = json.status;
					if(!json.status)
						alert(json.message)
				}
			});
			if(!ret)
				return false;
			
			getDisabledInputs.each(function(){
				$(this).attr('disabled',true).removeClass('editable');
			});
			$(this).text('Edit');
		}

		return false;
	});

	/* add review */
	$('a.postReviewBtn').fancybox({
		'width': 930,
		'height': 552,
		'type': 'iframe'
	});
	$("p.ratingStars select").selectBox();
	
	$('ul.newcats li a').hover(
		function(){
			$("img.topimg",this).stop().animate({opacity:0},250);
		},
		function(){
			$("img.topimg",this).stop().animate({opacity:1},250);
		}
	);

	// homepage video
	$('a#closethis').click(function(){
		var readSpan = $(this).children().text();

		if ( readSpan == 'open' ) {
			$('#overlayer').animate({
				height: 120
			}, 300, function(){
				$('a#closethis span').text('close');
				$('#overlayer ul').fadeIn(300);
			});
		} else {
			$('#overlayer ul').fadeOut(100,function(){
				$('a#closethis span').text('open');
				$('#overlayer').animate({
					height: 0
				}, 300);
			});
		}

		return false;
	});
	
	// test for pointer-events compatibility
	var pointerEventsSupported = (function(){
		var element = document.createElement('x'),
			documentElement = document.documentElement,
			getComputedStyle = window.getComputedStyle,
			supports;
		if(!('pointerEvents' in element.style)){
			return false;
		}
		element.style.pointerEvents = 'auto';
		element.style.pointerEvents = 'x';
		documentElement.appendChild(element);
		supports = getComputedStyle && 
			getComputedStyle(element, '').pointerEvents === 'auto';
		documentElement.removeChild(element);
		return !!supports;
	})();
	if(pointerEventsSupported){
		$('#map').append('<div class="iframe-mask"></div>');
	}
	
	// zip code search filed
	if ( $("#zipSearch").length ){
		var $zipSearch = $("#zipSearch");
//		var r2 = 124 - $zipSearch.find('span').position().left-$zipSearch.find('span').width();
		var r2 = 23;
		var $zipSearchButton = $zipSearch.find('button').css({right:r2}).attr('disabled', 'disabled');
		$("#zipSearch input").focus(function(){
			$zipSearch.find('label > span').fadeOut('fast');
			$zipSearchButton.stop().animate({right: 0}).removeAttr('disabled');
		}).blur(function(){
			if ( $(this).val().match(/^\s*$/) ){
				$zipSearch.find('label>span').fadeIn('fast');
				$zipSearchButton.stop().animate({right:r2}).attr('disabled', 'disabled');
			}
		});
	}
	
	// country selector page redirect
	$('#country').change(function() {
		var countrySelect = $(this).val();
		var pathLocation = window.location.pathname;
		if ( countrySelect == 'UK' ){
			location.href = 'http://www.blochshop.co.uk' + pathLocation;
		}
		else if ( countrySelect == 'AU' ){
			location.href = 'http://www.bloch.com.au/';
		}
	});
	
	// highlight current page in nav
	$('header nav').find('a[href="' + window.location.href + '"]').each(function(){
		if ( $(this).parents('.sub').length ) {
			$(this).parents('.sub').siblings('a').removeClass('off').addClass('on');
		} else {
			$(this).removeClass('off').addClass('on');
		}
	});
	
	$('.word-limit').each(function(){
		var wrapper = $(this);
		var limit = wrapper.data('word-limit');
		var full = false;
		
		if( limit ) {
			var excerpt = wrapper.clone(true).insertAfter(wrapper);
			var trigger = $('<a href="">Read more</a>').click(function() {
				if( full ) {
					wrapper.hide();
					excerpt.show();
					full = false;
				} else {
					wrapper.show();
					excerpt.hide();
					full = true;
				}
				
				return false;
			});
			wrapper.hide();
			
			function trim (str, charlist) {
				// http://kevin.vanzonneveld.net
				var whitespace, l = 0,
				i = 0;
				str += '';

				if (!charlist) {
					// default list
					whitespace = " \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000";
				} else {
					// preg_quote custom list
					charlist += '';
					whitespace = charlist.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '$1');
				}

				l = str.length;
				for (i = 0; i < l; i++) {
					if (whitespace.indexOf(str.charAt(i)) === -1) {
						str = str.substring(i);
						break;
					}
				}

				l = str.length;
				for (i = l - 1; i >= 0; i--) {
					if (whitespace.indexOf(str.charAt(i)) === -1) {
						str = str.substring(0, i + 1);
						break;
					}
				}

				return whitespace.indexOf(str.charAt(0)) === -1 ? str : '';
			}
			
			var words = 0;
			var goDeep = function(node) {
				var delimiters = {
					' ': true,
					',': true,
					'.': true,
					';': true
				};
				for(var i = 0, c = node.childNodes.length; i < c; ++i) {
					var child = node.childNodes[i];
					if( child ) {
						if(child.nodeType == 3) {
							var text = trim(child.nodeValue);
							if(text) {
								//var nodeWords = text.split(' ');
								//words += nodeWords.length;
								//console.log(nodeWords.length, text);
								var p = 0;
								var l = text.length;
								var lastWordEnd = 0;
								var c = '';
								while( p < l ) {
									if( words < limit && delimiters[text.charAt(p)] ) {
										lastWordEnd = p;
										++words;
									}
									++p;
								}
								
								child.nodeValue = text.substr(0, lastWordEnd);
							}
						} else {
							if( words < limit ) {
								goDeep(child);
							} else {
								child.parentNode.removeChild(child);
							}
						}
					}
				}
			}
			
			goDeep( excerpt.get(0) );
			
			if( words >= limit ) {
				excerpt.find('*:last').append('... ').append(trigger);
				wrapper.find('*:last').append(' ').append(trigger.clone(true).html('Read less'));
			} else {
				wrapper.show();
				excerpt.hide();
			}
		}
	});
	
});