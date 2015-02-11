/**
 * Multi Tab functions
* Author	: Plesnicute Marian
* Version	: 1.1
*/
var root;
var internal_id = 0;
//var wysiwyg=true;

function tabs_highlight(elem,state)
{
	if(elem.className!="active")
	{
		if(state)
			tab_position(elem,-50);
		else
			tab_position(elem,0);
	}
	elem=null;
}

function tab_position(elem,position)
{
	elem.style.backgroundPosition='right '+position+'px';
	elem.firstChild.style.backgroundPosition='left '+position+'px';
}

function Nod(dom_elem, name, parent) 
{
	this.domElement = dom_elem;
	this.html = $(dom_elem).html();
	this.name = name;
	this.children = new Array();
	this.selected = false;
	this.parent = parent;
	this.id = internal_id;

	internal_id++;
	if (typeof Nod._initialized == "undefined") 
	{
		Nod.prototype.addChildren = function () {
			var global_this = this;
			var global_names = new Array();
			
			$("> .legend", this.domElement).each(function (i) {
				global_names.push($(this).html());
				$(this).remove();
			});
			
			$("> .form", this.domElement).each(function (i) {
				nod = new Nod(this, global_names[i], global_this);
				$(this).attr("id", "pane"+nod.id);
				$(this).attr("class", "pane");
				$(this).hide();
				global_this.children.push(nod);
			});
			
			for(var i=0;i<this.children.length;i++)
				this.children[i].addChildren();
		};
		
		Nod._initialized = true;
	}
}

function build_header(nod)
{
	if(nod.children.length == 0)
		return false;
	
	var tabHeader=document.createElement('div');
	tabHeader.setAttribute('id','tabHeader');
	
	var ul=document.createElement('ul');

	for(var i=0;i<nod.children.length;i++)
	{
		var li = document.createElement('li');
		tab = document.createElement('a');
		tab.setAttribute('href',"#");
		tab.innerHTML = nod.children[i].name;
		tab.setAttribute('name',nod.children[i].id);
		tab.setAttribute('id','tab'+nod.children[i].id);
		tab.onclick=function(){
			nod = getNodeFromTabId(root, this.getAttribute('id'))
			if(nod != false)
			{
				path = new Array();
				while(nod != false)
				{
					path.push(nod.id);
					nod = nod.parent;
				}
				tab_activate(root, path);
			}
			return false; 
		};
		li.onmouseover=function()	{ tabs_highlight(this,true); };
		li.onmouseout=function()	{ tabs_highlight(this,false); };
		li.appendChild(tab);
		ul.appendChild(li);
		li = null;
		tab = null;
	}
	
	tabHeader.appendChild(ul);
	
	if(nod.id == 0)
		$("#tabpane").prepend(tabHeader);
	else
		$("#pane"+nod.id).prepend(tabHeader);
	ul=null; pane=null; tabHeader=null;
}

function build_headers(root)
{
	build_header(root);
	for(var i=0;i<root.children.length;i++)
		build_headers(root.children[i]);
}

function selectnode(nod)
{
	for(var i=0;i<nod.children.length;i++)
	{
		if(i == 0)
		{
			nod.children[i].selected = true;
			$(nod.children[i].domElement).show();
			
			tab_position($('#tab'+nod.children[i].id).parent().get(0), -100);
			$('#tab'+nod.children[i].id).parent().attr('class', 'active');
			
			if(wysiwyg)
			{
				try
				{
					if(typeof enableEditor == 'function')
						enableEditor("content[0]");
				}
				catch(e)
				{
					//alert(e);
					alert('Please wait until all the page is loaded');
				}
			}
		}
		else
		{
			nod.children[i].selected = false;
			$(nod.children[i].domElement).hide();
			
			tab_position($('#tab'+nod.children[i].id).parent().get(0), 0);
			$('#tab'+nod.children[i].id).parent().attr('class', '');
		}
		selectnode(nod.children[i]);
	}
}

function getNodeFromTabId(root, sTabId)
{
	if('tab'+root.id == sTabId)
	{
		return root;
	}
	else
	if(root.children.length == 0)	
	{
		return false;
	}
	else
	{
		for(var i=0;i<root.children.length;i++)
		{
			ret = getNodeFromTabId(root.children[i], sTabId);
			if(ret != false)
				return ret;
		}
		return false;
	}
}

function tab_activate(root, path)
{
	in_array = false;
	for(var i=0;i<path.length;i++)
		if(path[i] == root.id)
		{
			in_array = true;
			break;
		}

	end_path = false;
	//alert('Activate - '+root.id+': '+in_array+' path:'+path);
	if(root.id != 0)
	{

		if(in_array)
		{
			tab_position($('#tab'+root.id).parent().get(0), -100);
			$('#tab'+root.id).parent().attr('class', 'active');
			
			root.selected = true;
			$(root.domElement).show();
			//alert('In array - '+root.id);
			if(root.id == path[0])
			{
				//alert('Select');
				end_path = true;
				selectnode(root);
			}
		}
		else
		{
			tab_position($('#tab'+root.id).parent().get(0), 0);
			$('#tab'+root.id).parent().attr('class', '');

			root.selected = false;
			$(root.domElement).hide();
		}
	}

	if(!end_path)
		for(var i=0;i<root.children.length;i++)
			tab_activate(root.children[i], path);
}

$(document).ready(function(){ 
	root = new Nod($("#tabpane").get(0), '', false);
	root.addChildren();
	build_headers(root);
	selectnode(root);
 });