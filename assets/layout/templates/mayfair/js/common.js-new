$(function(){
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
	
	$('a').live('click', function() {
		
		if(window.localStorage) {
			if($(this).parents('.product-list').length) {
				window.localStorage.lastProduct = this.href;
			} else {
				window.localStorage.lastProduct = false;
			}
		}
		
		return true;
	});
	
	$(".right-container .product-list ul").bind('ajax-stop', function() {
		if(window.localStorage && window.localStorage.lastProduct) {
			var product = $('.product-list a[href="' + window.localStorage.lastProduct + '"]:first');
			if(product.length) {
				$('html, body').scrollTop(product.offset().top);
				window.localStorage.lastProduct = false;
			}
		}
	});
	
	

    $(".quick-view").live('click', function(){
        $.fancybox({
            'width'				: 504
            ,'height'			: '25%'
            ,'type'				: 'iframe'
            ,'modal'			: false
            ,'padding'			: 0
            ,'overlayColor'		: '#CCCCCC'
            ,'href'				: $(this).attr('href')
            //,'autoDimensions'	: false
            , onComplete: function(){
                //Cufon.replace('.overlay .header h1');
            }
            , onStart: function(){
                $('#fancybox-close').css({top: '10px', right: '10px', width: '15px', height: '15px', background: '#CCCCCC url("'+config_dir+'layout/templates/mayfair/css/fancybox/close.gif") no-repeat scroll'});
            }
            , onClosed: function(){
                $('#fancybox-close').css({top: '-15px', right: '-15px', width: '30px', height: '30px', background: 'url("'+config_dir+'layout/templates/mayfair/css/fancybox/fancybox.png") repeat scroll -40px 0 transparent'});
            }
        });
        return false;
    });

    $(".signIn").live('click', function(){
        $.fancybox({
            'width'				: 530
            ,'height'			: '25%'
            ,'type'				: 'iframe'
            ,'modal'			: false
            ,'padding'			: 0
            ,'overlayColor'		: '#CCCCCC'
            ,'href'				: $(this).attr('href')
            //,'autoDimensions'	: false
            , onComplete: function(){
                //Cufon.replace('.overlay .header h1');
            }
            , onStart: function(){
                $('#fancybox-close').css({top: '10px', right: '10px', width: '15px', height: '15px', background: '#CCCCCC url("'+config_dir+'layout/templates/mayfair/css/fancybox/close.gif") no-repeat scroll'});
            }
            , onClosed: function(){
                $('#fancybox-close').css({top: '-15px', right: '-15px', width: '30px', height: '30px', background: 'url("'+config_dir+'layout/templates/mayfair/css/fancybox/fancybox.png") repeat scroll -40px 0 transparent'});
            }
        });
        return false;
    });
    $(".signUp").live('click', function(){
        $.fancybox({
            'width'				: 530
            ,'height'			: '25%'
            ,'type'				: 'iframe'
            ,'modal'			: false
            ,'padding'			: 0
            ,'overlayColor'		: '#CCCCCC'
            ,'href'				: $(this).attr('href')
            //,'autoDimensions'	: false
            , onComplete: function(){
                //Cufon.replace('.overlay .header h1');
            }
            , onStart: function(){
                $('#fancybox-close').css({top: '10px', right: '10px', width: '15px', height: '15px', background: '#CCCCCC url("'+config_dir+'layout/templates/mayfair/css/fancybox/close.gif") no-repeat scroll'});
            }
            , onClosed: function(){
                window.location.reload(true);
            }
        });
        return false;
    });

    $("#main-nav > li").hover(function(){
        var l = $(this).position().left;
        var isMac = $('html').hasClass('mac');
        var isWin = $('html').hasClass('win');
        if ( $(this).children(".sub-menu").length )
        {
            if ( isMac ) {
                $(this).children("sub-menu").stop().show().css({ opacity: 0}).animate({opacity: 1}, 'fast');
            } else {
                $(this).children(".sub-menu").stop().show().css({ opacity: 0}).animate({opacity: 1}, 'fast');
            }
            $(this).addClass('on');
        }
    }, function(e){
        var $sub = $(this).children('.sub-menu');
        $(this).children(".sub-menu").stop().animate({ opacity: 0}, 'fast', function(){ $(this).hide(); });
        $(this).removeClass('on');
    });


    $("#slideshow").craftyslide({
        "width": 984,
        "height": 546,
        "pagination": true,
        "fadetime": 350,
        "delay": 2000
    });
    if($("#slideshow").length)
    {
        setInterval(function () {
            var li = $('#pagination a.active').closest('li');
            if(li.index()+1 == $('#pagination li').length)
                $('#pagination li:first a').click();
            else
                $('a', li.next()).click();
        }, 5000);
    }
		
    $(".btn").click(function(){
        $(".product-popup").fadeIn();
    })
			
    /*$(".Register").click(function(){
        $(".signIn").fadeIn();
    })


    $(".Sign").click(function(){
        $(".signUp").fadeIn();
    })*/
			
    $('#tabbing .tab-content').hide();
    $('#tabbin-a').show();


    $('.tab-nav li').click(function(){
		if($(this).hasClass('inactive'))
            return false;
        var url = $.trim($('a', this).attr('href'));
        if(url != '#')
            window.location = url;
        
        $('.tab-content').hide();
        var ind = $(this).index();
        $('.tab-nav li').removeClass('active');
        $('.tab-nav li').eq(ind).addClass('active');
        var tab_content = $('.tab-content').eq(ind);
        tab_content.show();

        if(tab_content.hasClass('iframe'))
            $('.iframe-container', tab_content).css('height', $('iframe', tab_content).contents().find('#content').height());
        return false;
    });
		
	$('#checkout').click(function(){
        $('.basket-pop').fadeIn();
    });
			
		  
/*				$('.input-box ').each(function() {
    var default_value = this.value;
    $(this).focus(function() {
        if(this.value == default_value) {
            this.value = '';
        }
    });
    $(this).blur(function() {
        if(this.value == '') {
            this.value = default_value;
        }
    });
});*/

    $(".txtDate").live("mousedown",function(){

        $("#tabbing .detail-section .btn.big-btn").animate({marginTop:"152px"},200)

        $(".txtDate").blur(function(){
            $("#tabbing .detail-section .btn.big-btn").animate({marginTop:"50px"},200)
        });

        $("#buttonContainer").animate({marginTop:"113px"},200)

        $(".txtDate").blur(function(){
            $("#buttonContainer").animate({marginTop:"50px"},200)
        });

        $(this).datepicker({
            showAnim: ''
            ,dateFormat: 'mm/dd/yy'
            ,onSelect: function() {
                $(this).change();
            }
        });
        //$(this).datepicker('setDate', 'today');
    })

    $(".ui-datepicker-header").live("click",function(){
        $("#tabbing .detail-section .btn.big-btn").animate({marginTop:"152px"},200)

        $(".txtDate").blur(function(){
            $("#tabbing .detail-section .btn.big-btn").animate({marginTop:"50px"},200)
        });
    });

    $("#txtDate2").live("mousedown",function(){
        $("#tabbing .detail-section .btn.big-btn").animate({marginTop:"152px"},200)
            
        $("#txtDate2").blur(function(){
            $("#tabbing .detail-section .btn.big-btn").animate({marginTop:"50px"},200)
        });
    });

    $('.product-images li a').click(function(){
        var index = $(this).closest('li').index();
        $('.main-view .img:eq('+index+')').show().siblings('.img').hide();
        return false;
    });
});