$(function(){
    if(validationFieldErrors){
        for(var i in validationFieldErrors){
            var input = $('input[name="'+i+'"]');
            if(!input.length){
                input = $('select[name="'+i+'"]');
            }
            if(!input.length){
                input = $('textarea[name="'+i+'"]');
            }
            input.addClass('error').after('<label class="error" for="'+input.attr("id")+'">'+validationFieldErrors[i]+'</label>');
        }
        
        if(!validationErrors.length && $('#tabs').length){
			for(var i = 0;i < $('#tabs').tabs("length");i++){
				if($('#tabs-'+(i+1)).find('input.error,select.error,textarea.error').length){
					$('#tabs').tabs("select", i);
					break;
				}
			}
        }
    }
    if(validationErrors){
        for(var form in validationErrors){
            var list = validationErrors[form];
            var html1 = [];
            var theForm = $('#'+form);
            
            if(theForm.length){
                for(var i = 0;i < list.length;i++){
                    html1.push(list[i]);
                }
                var div = $('<div>').addClass('error_div');
                div.html(html1.join("\n")).prependTo(theForm);
            }            
        }
    }
})