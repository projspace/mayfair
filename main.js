(function($){
	$(document).ready(function() {
		$('input[type=text]').not('input[name=switchnumber],input[name=cv2]').focus(function(){
			$(this).attr('placeholder', $(this).val());
			$(this).val('');														// Clear initial value

			$(this).blur(function(){
				var getNewVal = $(this).val();										// Read new input value
				if ( getNewVal == '') {
					$(this).val($(this).attr('placeholder'));										// Switch to initial value
				}
			});
		});
		
		// fix PNG for IE6
		if ( $.browser.msie && $.browser.version == '6.0' )
			DD_belatedPNG.fix('div, a, img, li');
		
		// remove any borders on last LI element
		$("ul").each(function(){
			 $(this).children("li:last").css({borderRight:"0",borderBottom:"0",background:"none",paddingRight:"0",marginRight:"0"});
		});

		// smooth animation of internal links
		$('a[href*=#]').click(function() {
			if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
				var $target = $(this.hash);
				$target = $target.length && $target	|| $('[name=' + this.hash.slice(1) +']');

				if ($target.length) {
					var targetOffset = $target.offset().top;
					$('html,body').animate({scrollTop: targetOffset}, 1000);
					return false;
				}
			}
		});

		// image preloader
		// jQuery.preLoadImages("images/center/01.jpg", "images/center/02.jpg", "images/center/03.jpg");
		var cache = [];
		// Arguments are image paths relative to the current page.
		$.preLoadImages = function() {
			var args_len = arguments.length;
			for (var i = args_len; i--;) {
				var cacheImage = document.createElement('img');
				cacheImage.src = arguments[i];
				cache.push(cacheImage);
			}
		}
		
		var profiles = {
			register:{
				width: '786',
				height: '520',
				center: 1,
				createnew: 0
			}
		}

		//$('a#registerTrigger').popupwindow(profiles);
		
	});
})(jQuery);
	
// Define indexOf for IE
if (!Array.prototype.indexOf)
{
  Array.prototype.indexOf = function(elt /*, from*/)
  {
    var len = this.length;

    var from = Number(arguments[1]) || 0;
    from = (from < 0)
         ? Math.ceil(from)
         : Math.floor(from);
    if (from < 0)
      from += len;

    for (; from < len; from++)
    {
      if (from in this &&
          this[from] === elt)
        return from;
    }
    return -1;
  };
}

jQuery.fn.log = function (msg) {
  console.log("%s: %o", msg, this);
  return this;
};