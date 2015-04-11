var postback_ids = [];

function refreshPostbackIds(){
	postback_ids = [];
	$(".list-item").each(function(){
		if($(this).attr('checked')){
			postback_ids.push($(this).val());
		}
	})
}

function initCheckboxes(){

	$('table.values').not('.nocheck').each(function(){
		$('tr', this).not('.nocheck').each(function(){
			if(!$(this).children('td.checked').length && !$(this).children('th.checked').length){
				var id = $(this).attr("rel");
				var addition;
				if(id && !$(this).hasClass('nocheck2')){
					addition = '<td width="10" class="checked"><input type="checkbox" name="dummy" value="' + id + '" class="list-item" /></td>';
				}else if(!$(this).hasClass('nocheck2')){
					addition = '<th width="10" class="checked"><input type="checkbox" name="dummy" value="" class="select-all" /></th>';
				}else{
					addition = '<td width="10">&nbsp;</td>';
				}
				$(this).prepend(addition);
			}
		});	
	});

}

(function($){
	$(function(){
		initCheckboxes();
		$('a.ask').live('click', function(){
			var q = $(this).attr('title');
			if(q=='') q = 'Are you sure ?';

			return confirm(q);
		});
		$('input.tick').each(function(){
			$(this).attr('rel', $(this).val());
			$(this).focus(function(){
				if($(this).val() == $(this).attr('rel'))
					$(this).val('');
			})
			$(this).blur(function(){
				if($(this).val() == '')
					$(this).val($(this).attr('rel'));
			})
		})
		if($('#tabs').length){
			$('#tabs').tabs({theme: 'Redmond'});
		}
		$('#language_sel').change(function(){
			document.location = '?changeLanguage='+$(this).val();
		});
		$('ul#menu > li > a').not('.clickable').click(function(){return false;});
		$('input.calendar').each(function(){
			$(this).datepicker({dateFormat: 'dd/mm/yy'});
		});

		$('table.values th .select-all').live('click', function(){
			$(this).attr("checked") ? $('table.values .list-item').attr("checked","checked") : $('table.values .list-item').removeAttr("checked");
			$(this).attr("checked") ? $('.select-all').attr("checked","checked") : $('.select-all').removeAttr("checked");
			refreshPostbackIds();
		})
		$('.list-item').live('click', function(){
			refreshPostbackIds();
		})

		$('table.values').each(function(){
            $(this).find('th:not(.not-rounded):first').addClass('first').css({'border-radius':'5px 0 0 0','-moz-border-radius':'5px 0 0 0'});
            $(this).find('th:not(.not-rounded):last').addClass('last').css({'border-radius':'0 5px 0 0','-moz-border-radius':'0 5px 0 0'});
        })
        $('.tree li a').css({'border-radius':'3px','-moz-border-radius':'3px'})

		$('.bulk-actions').change(function(){
			$('#postback_action').val($(this).val());
		})
		$('.submit-postback').click(function(){
			if(postback_ids.length == 0){
				alert('Please select at least 1 item');
			}else if($('#postback_action').val() == ""){
				alert('Please select an action to execute');
			}else{
				if($('#postback_action').val() == 'delete'){
					var meta = $('#postback').attr('rel');
					if(meta && meta.indexOf('()') > -1){
						$('#postback_ids').val(postback_ids.join(','));
						return eval(meta);
					}else{
						if(!confirm('Are you sure you wish to delete ?')){
							return false;
						}
					}
				}
				$('#postback_ids').val(postback_ids.join(','));
				$('#postback').submit();
			}
			return false;
		});


		$('.yes-no-select').each(function() {

            $this = $(this);
            $this.hide();

            var yesValue = 1;
            var noValue = 0;
            $this.find('option').each(function(){
                switch( this.label.toLowerCase() ) {
                    case 'yes':
                        yesValue = this.value;
                    break;

                    case 'no':
                        noValue = this.value;
                    break;
                }
            });

            var className = 'yes-no-select-selected';
            var wrapper = $('<div class="yes-no-select-wrapper">');


            if( $this.val() == yesValue ) {
                wrapper.addClass(className);
            }


            wrapper.data('$select', $this).click(function(){

                var $this = $(this);
                var $select = $this.data('$select');

                if( $this.hasClass(className) ) {
                    $select.val(noValue);

                    $this.removeClass(className);
                } else {
                    $select.val(yesValue);

                    $this.addClass(className);
                }
            });

            $this.after(wrapper);

        });



		$('.yes-no-checkbox').each(function() {

			$this = $(this);
			$this.hide();

			var className = 'yes-no-select-selected';
			var wrapper = $('<div class="yes-no-select-wrapper">');


			if( $this.attr('checked') ) {
				wrapper.addClass(className);
			}


			wrapper.click(function(){
				if( $(this).hasClass(className) ) {
					$this.attr('checked', '');
					$(this).removeClass(className);
				} else {
					$this.attr('checked', 'checked');
					$(this).addClass(className);
				}
			});

			$this.after(wrapper);

		});

		var openedSelect = false;
		var closeOpenedSelect = function() {
			if( openedSelect ) {
				openedSelect.fadeOut('fast');
				openedSelect.removeClass('opened');

				openedSelect.data('handel').removeClass('opened');

				openedSelect = false;
			}
		}

		$('.bulk-actions-container select, select.custom-skin').not('.yes-no-select').each(function(){

			var $this = $(this);

			if( !$this.data('skined') && ( !$('html').hasClass('ie') || !$this.parents('.products').length ) ) {


				$this.data('skined', true);
				var options = $this.find('option');

				//$this.removeClass('skin-select');

				$this.hide();

				var wrapper = $('<div class="skined-select"></div>');
				var handel = $('<div class="skined-select-handel"><span><em></em></span></div>');
				var list = $('<ul class="skined-select-list">');

				list.data('handel', handel);

				handel.find('em').html( this.options[this.selectedIndex].innerHTML );

				wrapper.append(handel);
				wrapper.data('list', list);

				options.each(function() {
					var li = $('<li>');
					li.html(this.innerHTML).data('value', this.value);
					list.append(li);

					if(this.disabled) {
						li.addClass('disabled');
					} else {

						li.click(function() {

							$this.val($(this).data('value')).change();
							handel.find('em').html( this.innerHTML );

							closeOpenedSelect();

							return false;
						});
					}
				});

				handel.click(function(){
					if( list.hasClass('opened') ) {
						closeOpenedSelect();
					} else {

						closeOpenedSelect();

						handel.addClass('opened');

						$('body').append(list.css({'top': 0, 'left': 0}));

						list.fadeIn('fast');

						setTimeout(function(){
							list.css({
								'top': handel.offset().top + handel.height() - 3,
								'left': handel.offset().left - handleLeftMargin
							});
						}, 1);

						list.addClass('opened');

							
						if( list.width() != handel.width() ) {
							var width = handel.width()
								- parseInt(list.css('padding-left')) - parseInt(list.css('padding-right'))
								- parseInt(list.css('border-left-width')) - parseInt(list.css('border-right-width'))
								+ handleLeftMargin
							;
							list.width( width );

						}

						list.css({
							'top': handel.offset().top + handel.height() - 3,
							'left': handel.offset().left - handleLeftMargin
						});

						openedSelect = list;
					}
				});

				$this.after(wrapper);
				//DD_roundies.addRule('.skined-select-list', '0 0 5px 5px', true);
				var handleLeftMargin = Math.abs(parseInt( handel.css('margin-left') ));

			}
		});

		$(document).click(function(ev){

			var select = $(ev.target).parents('.skined-select:first');

			if( !select.length ) {

				closeOpenedSelect();

			}

		});

		$('.button-slide').click(function(){
			var id = $(this).attr('rel');
			$('#'+id).slideToggle();
			return false;
		})


		$('#flash-message a.close').click(function(){
			$('#flash-message').slideUp('fast');
			return false;
		})
		if(!$('#flash-message').hasClass('error')){
			setTimeout(function() {
				$('#flash-message').slideUp('slow');
			}, 5000);
		}

		$('#menu ul>li:last-child ').addClass('last');


	});

})(jQuery);
