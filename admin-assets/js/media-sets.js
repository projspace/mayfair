$(function(){
	var currentItem = null;
	var currentTable = null;
	var currentEdit = null;
	
	$("table.sets-table tr").hover(
		function(){$(this).find('.drag-media-set span').show()},
		function(){if(!$(this).hasClass('dragged')) $(this).find('.drag-media-set span').hide()}
	);
	
	$("table.sets-table").tableDnD({
		onDragClass: 'dragged',
		dragHandle: 'drag-media-set',
		onDrop: function(t,r){
			var r = $(r);
			var id = parseInt(r.attr("rel"));
			if(r.next().length){
				var where = 'before';
				var ref = parseInt(r.next().attr("rel"));
			}else{
				var where = 'after';
				var ref = parseInt(r.prev().attr("rel"));
			}
			$.post(mediaUrls.moveset, {id: id, ref: ref, where: where});
		}
	});
	
	$("table.draggable tr").live('mouseover', function(){$(this).find('.item-actions').show();});
	$("table.draggable tr").live('mouseout', function(){$(this).find('.item-actions').hide();});
	
	$('.item-actions a.delete').live('click', function(){
		if(currentEdit){
			cancelMetadata($(this).closest('table').find('.metadata'));
		}
		$.get(this.href);
		$(this).closest('tr').fadeOut(500, function(){
			if($(this).closest('tbody.items').children().length == 1){
				$(this).closest('table').find('tr.empty').show();
			}
			$(this).remove();
		});
		return false;
	})
	
	$('.item-actions a.edit').live('click', function(){		
		currentTable = $(this).closest('table');
		currentEdit = $(this).closest('tr').attr('rel');
		
		$.getJSON(this.href, function(data){
			initMetadata(data);
			for(var i in data.meta){
				var elem = currentTable.find('input[name="meta['+i+']"],textarea[name="meta['+i+']"],select[name="meta['+i+']"]').val(data.meta[i]);
			}
			currentTable.find('.media-autocomplete-imitate').html(buildAutocompleteItem(data.li))
		})
		
		return false;
	});
	
	$('.media-set-cancel').click(function(){
		cancelMetadata($(this));
	})
	$('.media-set-submit').unbind('click').click(function(e){
		submitMetadata();
		e.stopPropagation();
		e.preventDefault();
		return false;
	})
	
	$('.media-autocomplete li').live('mouseover', function(){autocompleteHoverItem($(this))})
	$('.media-autocomplete li').live('click', function(){autocompleteHoverItem($(this));autocompleteEnter()})
	
	$('.autocomplete-media').click(function(){
		this.value = '';
		stopAutocomplete();
	})
	$('.autocomplete-media').keyup(function(e){
		var $this = $(this);
		switch(e.keyCode){
			default:
				if(this.value.length >= 1){
					$.post(mediaUrls.autocomplete, {keyword: this.value}, function(data){
						if(data.length){
							list = $('.media-autocomplete').empty();
							for(var i in data){
								buildAutocompleteItem(data[i]).appendTo(list);
							}
							list.show();
							
							var pos = $this.position();
							list.css({left: pos.left, top: pos.top + $this.height() + 10});
						}else{
							stopAutocomplete(true);
						}
					}, 'json');
				}
			break;
			case 40://down
				nextItem();
			break;
			case 38://down
				prevItem();
			break;
			case 13:
				//nothing
			break;
		}
	})
	
	function buildAutocompleteItem(data){
		var li = $('<li>').data('info', data);
		var img = $('<img>').attr("src", data.image);
		var p = $('<p>').text(data.name).append('<br />').append($('<span>').text('Filetype: '+data.type));
		
		return li.append(img).append(p);
	}
	
	$('.autocomplete-media').keydown(function(e){
		if(e.keyCode == 13){
			autocompleteEnter();
			e.stopPropagation();
			e.preventDefault();
		}
		currentTable = $(this).closest('table');
	})
	
	$('body').click(function(){stopAutocomplete();})
	
	function stopAutocomplete(keepText){
		$('.media-autocomplete').hide();
		if(currentTable && !keepText)
			currentTable.find('.autocomplete-media').val('');
		currentItem = null;
	}
	
	function autocompleteHoverItem(item){
		$('.media-autocomplete li').removeClass('hover');
		item.addClass('hover');
		
		currentItem = item;
	}
	
	function nextItem(){
		var item;
		if(!currentItem || !currentItem.next().is('li')){
			item = $('.media-autocomplete li:first');
		}else{
			item = currentItem.next();
		}
		autocompleteHoverItem(item);
	}
	
	function prevItem(){
		var item;
		if(!currentItem || !currentItem.prev().is('li')){
			item = $('.media-autocomplete li:last');
		}else{
			item = currentItem.prev();
		}
		autocompleteHoverItem(item);
	}
	
	function autocompleteEnter(){
		if(currentItem){
			var data = currentItem.data('info');
			initMetadata(data);
			stopAutocomplete();
		}
	}
	
	function initMetadata(data){
		if(currentTable && currentTable.length){
			currentTable.find('.edit-set-partial').show().prev().hide();
			currentTable.find('.media-autocomplete-imitate').html(currentItem);
			currentTable.find('input[name="media_id"]').val(data.id);
		}
	}
	
	function cancelMetadata(item){
		item.parents('.edit-set-partial').hide().prev().show();
		item.parents('.edit-set-partial').find('input[type=text],select,textarea').val('');
		item.parents('.edit-set-partial').find('input[type=checkbox],input[type=radio]').removeAttr('checked');
		currentEdit = null;
	}
	
	function submitMetadata(){
		data = {media_set_id: currentTable.attr("rel")};
		currentTable.find('input,select,textarea').each(function(){
			var $this = $(this);
			data[$this.attr("name")] = $this.val();
		})
		if(currentEdit){
			data.item_id = currentEdit;
		}
		loading(true);
		$.post(mediaUrls.saveitem, data, function(d){
			loading(false);
			if(d != 'ERROR' && d != 'OK'){
				currentTable.find('tbody.items').append(d);
				currentTable.find('tr.empty').hide();
				currentTable.tableDnDUpdate();
			}
			cancelMetadata(currentTable.find('li'));
		})
	}
	
	function loading(val){
		if(currentTable){
			if(val){
				currentTable.find('.buttons').hide();
				currentTable.find('.loading').show();
			}else{
				currentTable.find('.buttons').show();
				currentTable.find('.loading').hide();
			}
		}
	}
})