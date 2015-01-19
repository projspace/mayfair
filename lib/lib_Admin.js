/**
 * e-Commerce System
 * Copyright (c) 2002-2006 Philip John, All Rights Reserved.
 * Author	: Philip John
 * Version	: 6.0
 *
 * PROPRIETARY/CONFIDENTIAL.  Use is subject to license terms.
 */

/**
 * Form postback methods
 */

function postbackPrompt(button,url,vars,vals,question,param)
{
	button.setAttribute('action','none');
	var answer = prompt(question);
    if(answer !== null)
    {
        answer = new String(answer);
        answer = answer.replace(/^\s+|\s+$/g, ''); // trim
        if(answer != '')
        {
            vars[vars.length] = param;
            vals[vals.length] = answer;
            form=document.getElementById('postback');
            form.action=dir+"index.php?fuseaction=admin."+url;
            for(var i=0;i<vars.length;i++)
            {
                node=document.createElement('input');
                node.setAttribute('type','hidden');
                node.setAttribute('name',vars[i]);
                node.setAttribute('value',vals[i]);
                form.appendChild(node);
                node=null;
            }
            form.submit();
        }
        else
        {
            alert('You have entered an empty value. Please try again.');
            postbackPrompt(button,url,vars,vals,question,param);
        }
    }
	form=null;
	return false;
}

function formPromptAct(form,url,question,param)
{
    var answer = prompt(question);
    if(answer !== null)
    {
        answer = new String(answer);
        answer = answer.replace(/^\s+|\s+$/g, ''); // trim
        if(answer != '')
        {
            node=document.createElement('input');
            node.setAttribute('type','hidden');
            node.setAttribute('name',param);
            node.setAttribute('value',answer);
            form.appendChild(node);
            node=null;
            form.action=dir+url;
            return true;
        }
        else
        {
            alert('You have entered an empty value. Please try again.');
            formPromptAct(form,url,question,param)
        }
    }
    form=null;
    return false;
}

function postback(button,url,vars,vals)
{
	button.setAttribute('action','none');
	form=document.getElementById('postback');
	form.action=dir+"index.php?fuseaction=admin."+url;
	for(var i=0;i<vars.length;i++)
	{
		node=document.createElement('input');
		node.setAttribute('type','hidden');
		node.setAttribute('name',vars[i]);
		node.setAttribute('value',vals[i]);
		form.appendChild(node);
		node=null;
	}
	form.submit();
	form=null;
	return false;
}

function postbackConf(button,url,vars,vals,verb,noun)
{
	button.setAttribute('action','none');
	var answer = confirm("Are you sure you want to "+verb+" this "+noun+"?");
	if(answer)
	{
		form=document.getElementById('postback');
		form.action=dir+"index.php?fuseaction=admin."+url;
		for(var i=0;i<vars.length;i++)
		{
			node=document.createElement('input');
			node.setAttribute('type','hidden');
			node.setAttribute('name',vars[i]);
			node.setAttribute('value',vals[i]);
			form.appendChild(node);
			node=null;
		}
		form.submit();
	}
	form=null;
	return false;
}

function postbackConf2(button,url,vars,vals,verb,noun,adj)
{
	if(adj == undefined)
		adj = 'this';
	button.setAttribute('action','none');
	var answer = confirm("Are you sure you want to "+verb+" "+adj+" "+noun+"?");
	if(answer)
	{
		form=document.getElementById('postback');
		form.action=dir+"index.php?fuseaction=admin."+url;
		for(var i=0;i<vars.length;i++)
		{
			node=document.createElement('input');
			node.setAttribute('type','hidden');
			node.setAttribute('name',vars[i]);
			node.setAttribute('value',vals[i]);
			form.appendChild(node);
			node=null;
		}
		form.submit();
	}
	form=null;
	return false;
}

function postbackMulti(button,url)
{
	button.setAttribute('action','none');
	form=document.getElementById('postback_multi');
	form.action=dir+"index.php?fuseaction=admin."+url;
	form.submit();
	form=null;
	return false;
}

function changeState(checkbox,vars,count)
{
	for(var i=0;i<vars.length;i++)
		for(var j=0;j<count[i];j++)
			document.getElementById(vars[i]+"_"+j).checked=checkbox.checked;
	checkbox=null;
}

/**
 * Confirmation functions
 */

function confAct(location)
{
	var answer = confirm ("Are you sure?")
	if (answer)
		window.location=location;
}

function formConfAct(form,url,verb,noun)
{
	var answer = confirm("Are you sure you want to "+verb+" this "+noun+"?");
	if(answer)
		form.action=dir+url;
	form=null;
	return answer;
}


/**
 * Popup callback window functions
 */

function popup_move(pageid,parentid)
{
	var popup=window.open(dir+'index.php?fuseaction=admin.movePage&pageid='+pageid+'&parentid='+parentid,'new','status=no,width=300,height=500,scrollbars=yes');
}


/**
 * Assignment functions
 */

function assign_move(from,to,button)
{
	button.action='none';
	from=document.getElementById(from);
	to=document.getElementById(to);
	if(from.selectedIndex>-1)
	{
		to.options[to.options.length]=from.options[from.selectedIndex];
	}
	return false;
}

function assign_moveall(from,to,button)
{
	button.action='none';
	from=document.getElementById(from);
	to=document.getElementById(to);
	var len=from.options.length;
	for(var i=0;i<len;i++)
	{
		to.options[to.options.length]=from.options[0];
	}
	return false;
}

/**
 * Popup callback window functions
 */

function moveProduct(product_id,refid,category_id)
{
	var popup=window.open(dir+'index.php?fuseaction=admin.move&refid='+refid+'&product_id='+product_id+'&category_id='+category_id,'new','status=no,width=600,height=500,scrollbars=yes');
}

function moveCategory(category_id)
{
	var popup=window.open(dir+'index.php?fuseaction=admin.moveCategory&category_id='+category_id,'new','status=no,width=600,height=500,scrollbars=yes');
}

function help()
{
	var popup=window.open(dir+'index.php?fuseaction=admin.help','help','scrollbars=yes,status=no,width=660,height=550');
}

function moveReference(refid,id)
{
	var popup=window.open(dir+'index.php?fuseaction=admin.move&refid='+refid+'&product_id=0&id='+id,'new','status=no,width=600,height=500');
}

/**
 * Product options functions
 */

function addOptionRow(optionid)
{
	if(!options[optionid])
		options[optionid]=0;

	var rowid=options[optionid];
	
	var node=document.getElementById('option'+optionid);
	
	//Create table row
	var row=node.insertRow(-1);
	row.setAttribute('id','opt_'+optionid+'_row_'+rowid);

	//Create table cells
	var cell1=row.insertCell(-1);
	cell1.setAttribute('id','opt_'+optionid+'_cell1_'+rowid);

	var cell2=row.insertCell(-1);
	cell2.setAttribute('id','opt_'+optionid+'_cell2_'+rowid);

	var cell3=row.insertCell(-1);
	cell3.setAttribute('id','opt_'+optionid+'_cell3_'+rowid);

	var cell4=row.insertCell(-1);
	cell4.setAttribute('id','opt_'+optionid+'_cell4_'+rowid);
	
	var cell6=row.insertCell(-1);
	cell6.setAttribute('id','opt_'+optionid+'_cell6_'+rowid);

	var cell5=row.insertCell(-1);
	cell5.setAttribute('id','opt_'+optionid+'_cell5_'+rowid);

	//Create up link
	var up_node=document.createElement('img');
	up_node.setAttribute('id','opt_'+optionid+'_up_'+rowid);
	up_node.onclick=function()
	{
		moveOptionRowUp(optionid,rowid);
	};
	up_node.setAttribute('src',dir+'images/admin/up.png');
	up_node.setAttribute('width',16);
	up_node.setAttribute('height',16);
	up_node.setAttribute('align','top');
	up_node.setAttribute('alt','/\\');
	up_node.setAttribute('title','Up');
	cell1.appendChild(up_node);

	//Create down link
	var down_node=document.createElement('img');
	down_node.setAttribute('id','opt_'+optionid+'_down_'+rowid);
	down_node.onclick=function()
	{
		moveOptionRowDown(optionid,rowid);
	};
	down_node.setAttribute('src',dir+'images/admin/down.png');
	down_node.setAttribute('width',16);
	down_node.setAttribute('height',16);
	down_node.setAttribute('align','top');
	down_node.setAttribute('alt','\\/');
	down_node.setAttribute('title','Down');
	cell1.appendChild(down_node);

	//Create name element
	var val_node=document.createElement('input');
	val_node.setAttribute('name','shopopt['+optionid+'][value][]');
	val_node.setAttribute('id','opt_'+optionid+'_val_'+rowid);
	val_node.setAttribute('class','optionName');
	val_node.className='optionName';
	cell2.appendChild(val_node);

	//Create price element
	var price_node=document.createElement('input');
	price_node.setAttribute('name','shopopt['+optionid+'][price][]');
	price_node.setAttribute('id','opt_'+optionid+'_price_'+rowid);
	price_node.setAttribute('class','priceDiff');
	price_node.className='priceDiff';
	cell3.appendChild(price_node);

	//Create weight element
	var weight_node=document.createElement('input');
	weight_node.setAttribute('name','shopopt['+optionid+'][weight][]');
	weight_node.setAttribute('id','opt_'+optionid+'_weight_'+rowid);
	weight_node.setAttribute('class','weightDiff');
	weight_node.className='weightDiff';
	cell4.appendChild(weight_node);
	
	//Create stock element
	var stock_node=document.createElement('input');
	stock_node.setAttribute('name','shopopt['+optionid+'][stock][]');
	stock_node.setAttribute('id','opt_'+optionid+'_stock_'+rowid);
	stock_node.setAttribute('class','weightDiff');
	stock_node.className='weightDiff';
	cell6.appendChild(stock_node);

	//Create delete link
	var del_node=document.createElement('a');
	del_node.setAttribute('id','opt_'+optionid+'_del_'+rowid);
	del_node.onclick=function()
	{
		removeOptionRow(optionid,rowid);
	};
	var delinode=document.createElement('img');
	delinode.setAttribute('src',dir+'images/admin/delete.png');
	delinode.setAttribute('width',16);
	delinode.setAttribute('height',16);
	delinode.setAttribute('align','top');
	delinode.setAttribute('alt','X');
	delinode.setAttribute('title','Delete');
	del_node.appendChild(delinode);
	cell5.appendChild(del_node);

	options[optionid]++;

	//Cleanup
	node=null; row=null; cell1=null; cell2=null; cell3=null; cell4=null; cell6=null; cell5=null; up_node=null; down_node=null; val_node=null; price_node=null; weight_node=null; stock_node=null; del_node=null; delinode=null;
}

function removeOptionRow(optionid,rowid)
{
	//Get nodes
	var node=document.getElementById('option'+optionid);
	var row=document.getElementById('opt_'+optionid+'_row_'+rowid);
	var cell1=document.getElementById('opt_'+optionid+'_cell1_'+rowid);
	var cell2=document.getElementById('opt_'+optionid+'_cell2_'+rowid);
	var cell3=document.getElementById('opt_'+optionid+'_cell3_'+rowid);
	var cell4=document.getElementById('opt_'+optionid+'_cell4_'+rowid);
	var cell6=document.getElementById('opt_'+optionid+'_cell6_'+rowid);
	var cell5=document.getElementById('opt_'+optionid+'_cell5_'+rowid);

	//Remove items from cells
	var up_node=cell1.removeChild(document.getElementById('opt_'+optionid+'_up_'+rowid));
	var down_node=cell1.removeChild(document.getElementById('opt_'+optionid+'_down_'+rowid));
	var val_node=cell2.removeChild(document.getElementById('opt_'+optionid+'_val_'+rowid));
	var price_node=cell3.removeChild(document.getElementById('opt_'+optionid+'_price_'+rowid));
	var weight_node=cell4.removeChild(document.getElementById('opt_'+optionid+'_weight_'+rowid));
	var stock_node=cell6.removeChild(document.getElementById('opt_'+optionid+'_stock_'+rowid));
	var del_node=cell5.removeChild(document.getElementById('opt_'+optionid+'_del_'+rowid));

	//Remove cells from row
	row.removeChild(cell1);
	row.removeChild(cell2);
	row.removeChild(cell3);
	row.removeChild(cell4);
	row.removeChild(cell6);
	row.removeChild(cell5);

	//Remove row from table
	node.removeChild(row);

	//Safari ghost node fix
	val_node.setAttribute('name','');
	price_node.setAttribute('name','');
	weight_node.setAttribute('name','');
	stock_node.setAttribute('name','');

	//Cleanup
	node=null; row=null; cell1=null; cell2=null; cell3=null; cell4=null; cell6=null; cell5=null; up_node=null; down_node=null; val_node=null; price_node=null; weight_node=null; stock_node=null; del_node=null;
}

function moveOptionRowDown(optionid,rowid)
{
	for(var i=rowid+1;i<options[optionid];i++)
	{
		if(document.getElementById('opt_'+optionid+'_val_'+i)!=null)
		{
			var val_node_dst=document.getElementById('opt_'+optionid+'_val_'+i);
			var price_node_dst=document.getElementById('opt_'+optionid+'_price_'+i);
			var weight_node_dst=document.getElementById('opt_'+optionid+'_weight_'+i);
			var stock_node_dst=document.getElementById('opt_'+optionid+'_stock_'+i);

			var val_node_src=document.getElementById('opt_'+optionid+'_val_'+rowid);
			var price_node_src=document.getElementById('opt_'+optionid+'_price_'+rowid);
			var weight_node_src=document.getElementById('opt_'+optionid+'_weight_'+rowid);
			var stock_node_src=document.getElementById('opt_'+optionid+'_stock_'+rowid);

			var v=val_node_dst.value;
			var p=price_node_dst.value;
			var w=weight_node_dst.value;
			var s=stock_node_dst.value;

			val_node_dst.value=val_node_src.value;
			price_node_dst.value=price_node_src.value;
			weight_node_dst.value=weight_node_src.value;
			stock_node_dst.value=stock_node_src.value;

			val_node_src.value=v;
			price_node_src.value=p;
			weight_node_src.value=w;
			stock_node_src.value=s;

			i=options[optionid];

			//Cleanup
			val_node_dst=null; price_node_dst=null; weight_node_dst=null; stock_node_dst=null; val_node_src=null; price_node_src=null; weight_node_src=null; stock_node_src=null;
		}
	}
}

function moveOptionRowUp(optionid,rowid)
{
	for(var i=rowid-1;i>=0;i--)
	{
		if(document.getElementById('opt_'+optionid+'_val_'+i)!=null)
		{
			var val_node_dst=document.getElementById('opt_'+optionid+'_val_'+i);
			var price_node_dst=document.getElementById('opt_'+optionid+'_price_'+i);
			var weight_node_dst=document.getElementById('opt_'+optionid+'_weight_'+i);
			var stock_node_dst=document.getElementById('opt_'+optionid+'_stock_'+i);

			var val_node_src=document.getElementById('opt_'+optionid+'_val_'+rowid);
			var price_node_src=document.getElementById('opt_'+optionid+'_price_'+rowid);
			var weight_node_src=document.getElementById('opt_'+optionid+'_weight_'+rowid);
			var stock_node_src=document.getElementById('opt_'+optionid+'_stock_'+rowid);

			var v=val_node_dst.value;
			var p=price_node_dst.value;
			var w=weight_node_dst.value;
			var s=stock_node_dst.value;

			val_node_dst.value=val_node_src.value;
			price_node_dst.value=price_node_src.value;
			weight_node_dst.value=weight_node_src.value;
			stock_node_dst.value=stock_node_src.value;

			val_node_src.value=v;
			price_node_src.value=p;
			weight_node_src.value=w;
			stock_node_src.value=s;

			i=-1;

			//cleanup
			val_node_dst=null; price_node_dst=null; weight_node_dst=null; stock_node_dst=null; val_node_src=null; price_node_src=null; weight_node_src=null; stock_node_src=null;
		}
	}
}

/**
 * Category fields functions
 */

function addFieldRow()
{
	var rowid=fields;

	var node=document.getElementById('fields');

	//Create table row
	var row=node.insertRow(-1);
	row.setAttribute('id','field_row_'+rowid);

	//Create table cells
	var cell1=row.insertCell(-1);
	cell1.setAttribute('id','field_cell1_'+rowid);

	var cell2=row.insertCell(-1);
	cell2.setAttribute('id','field_cell2_'+rowid);

	var cell3=row.insertCell(-1);
	cell3.setAttribute('id','field_cell3_'+rowid);

	//Create up link
	var up_node=document.createElement('img');
	up_node.setAttribute('id','field_up_'+rowid);
	up_node.onclick=function()
	{
		moveFieldRowUp(rowid);
	};
	up_node.setAttribute('src',dir+'images/admin/up.png');
	up_node.setAttribute('width',16);
	up_node.setAttribute('height',16);
	up_node.setAttribute('align','top');
	up_node.setAttribute('alt','/\\');
	up_node.setAttribute('title','Up');
	cell1.appendChild(up_node);

	//Create down link
	var down_node=document.createElement('img');
	down_node.setAttribute('id','field_down_'+rowid);
	down_node.onclick=function()
	{
		moveFieldRowDown(rowid);
	};
	down_node.setAttribute('src',dir+'images/admin/down.png');
	down_node.setAttribute('width',16);
	down_node.setAttribute('height',16);
	down_node.setAttribute('align','top');
	down_node.setAttribute('alt','\\/');
	down_node.setAttribute('title','Down');
	cell1.appendChild(down_node);

	//Create name element
	var name_node=document.createElement('input');
	name_node.setAttribute('name','shopfield[name][]');
	name_node.setAttribute('id','field_name_'+rowid);
	name_node.setAttribute('class','fieldName');
	name_node.className='fieldName';
	cell2.appendChild(name_node);

	//Create delete link
	var del_node=document.createElement('a');
	del_node.setAttribute('id','field_del_'+rowid);
	del_node.onclick=function()
	{
		removeFieldRow(rowid);
	};
	var delinode=document.createElement('img');
	delinode.setAttribute('src',dir+'images/admin/delete.png');
	delinode.setAttribute('width',16);
	delinode.setAttribute('height',16);
	delinode.setAttribute('align','top');
	delinode.setAttribute('alt','X');
	delinode.setAttribute('title','Delete');
	del_node.appendChild(delinode);
	cell3.appendChild(del_node);

	fields++;

	//Cleanup
	node=null; row=null; cell1=null; cell2=null; cell3=null; up_node=null; down_node=null; name_node=null; del_node=null; delinode=null;
}

function removeFieldRow(rowid)
{
	//Get nodes
	var node=document.getElementById('fields');
	var row=document.getElementById('field_row_'+rowid);
	var cell1=document.getElementById('field_cell1_'+rowid);
	var cell2=document.getElementById('field_cell2_'+rowid);
	var cell3=document.getElementById('field_cell3_'+rowid);

	//Remove items from cells
	var up_node=cell1.removeChild(document.getElementById('field_up_'+rowid));
	var down_node=cell1.removeChild(document.getElementById('field_down_'+rowid));
	var name_node=cell2.removeChild(document.getElementById('field_name_'+rowid));
	var del_node=cell3.removeChild(document.getElementById('field_del_'+rowid));

	//Remove cells from row
	row.removeChild(cell1);
	row.removeChild(cell2);
	row.removeChild(cell3);

	//Remove row from table
	//node.removeChild(row);

	//Safari ghost node fix
	name_node.setAttribute('name','');

	//Cleanup
	node=null; row=null; cell1=null; cell2=null; cell3=null; up_node=null; down_node=null; name_node=null; del_node=null;
}

function moveFieldRowDown(rowid)
{
	for(var i=rowid+1;i<fields;i++)
	{
		if(document.getElementById('field_name_'+i)!=null)
		{
			var name_node_dst=document.getElementById('field_name_'+i);
			var name_node_src=document.getElementById('field_name_'+rowid);

			var n=name_node_dst.value;

			name_node_dst.value=name_node_src.value;

			name_node_src.value=n;

			i=fields;

			//Cleanup
			name_node_dst=null;
			name_node_src=null;
		}
	}
}

function moveFieldRowUp(rowid)
{
	for(var i=rowid-1;i>=0;i--)
	{
		if(document.getElementById('field_name_'+i)!=null)
		{
			var name_node_dst=document.getElementById('field_name_'+i);
			var name_node_src=document.getElementById('field_name_'+rowid);

			var n=name_node_dst.value;

			name_node_dst.value=name_node_src.value;
			name_node_src.value=n;

			i=-1;

			//Cleanup
			name_node_dst=null;
			name_node_src=null;
		}
	}
}

/**
 * Product specifications functions
 */

function addSpecRow()
{
	var rowid=specs;

	var node=document.getElementById('specs');

	//Create table row
	var row=node.insertRow(-1);
	row.setAttribute('id','spec_row_'+rowid);

	//Create table cells
	var cell1=row.insertCell(-1);
	cell1.setAttribute('id','spec_cell1_'+rowid);

	var cell2=row.insertCell(-1);
	cell2.setAttribute('id','spec_cell2_'+rowid);

	var cell3=row.insertCell(-1);
	cell3.setAttribute('id','spec_cell3_'+rowid);

	var cell4=row.insertCell(-1);
	cell4.setAttribute('id','spec_cell4_'+rowid);

	//Create up link
	var up_node=document.createElement('img');
	up_node.setAttribute('id','spec_up_'+rowid);
	up_node.onclick=function()
	{
		moveSpecRowUp(rowid);
	};
	up_node.setAttribute('src',dir+'images/admin/up.png');
	up_node.setAttribute('width',16);
	up_node.setAttribute('height',16);
	up_node.setAttribute('align','top');
	up_node.setAttribute('alt','/\\');
	up_node.setAttribute('title','Up');
	cell1.appendChild(up_node);

	//Create down link
	var down_node=document.createElement('img');
	down_node.setAttribute('id','spec_down_'+rowid);
	down_node.onclick=function()
	{
		moveSpecRowDown(rowid);
	};
	down_node.setAttribute('src',dir+'images/admin/down.png');
	down_node.setAttribute('width',16);
	down_node.setAttribute('height',16);
	down_node.setAttribute('align','top');
	down_node.setAttribute('alt','\\/');
	down_node.setAttribute('title','Down');
	cell1.appendChild(down_node);

	//Create name element
	var name_node=document.createElement('input');
	name_node.setAttribute('name','shopspec[name][]');
	name_node.setAttribute('id','spec_name_'+rowid);
	name_node.setAttribute('class','specName');
	name_node.className='specName';
	cell2.appendChild(name_node);

	//Create value element
	var val_node=document.createElement('input');
	val_node.setAttribute('name','shopspec[value][]');
	val_node.setAttribute('id','spec_val_'+rowid);
	val_node.setAttribute('class','specValue');
	val_node.className='specValue';
	cell3.appendChild(val_node);

	//Create delete link
	var del_node=document.createElement('a');
	del_node.setAttribute('id','spec_del_'+rowid);
	del_node.onclick=function()
	{
		removeSpecRow(rowid);
	};
	var delinode=document.createElement('img');
	delinode.setAttribute('src',dir+'images/admin/delete.png');
	delinode.setAttribute('width',16);
	delinode.setAttribute('height',16);
	delinode.setAttribute('align','top');
	delinode.setAttribute('alt','X');
	delinode.setAttribute('title','Delete');
	del_node.appendChild(delinode);
	cell4.appendChild(del_node);

	specs++;

	//Cleanup
	node=null; row=null; cell1=null; cell2=null; cell3=null; cell4=null; up_node=null; down_node=null; name_node=null; val_node=null; del_node=null; delinode=null;
}

function removeSpecRow(rowid)
{
	//Get nodes
	var node=document.getElementById('specs');
	var row=document.getElementById('spec_row_'+rowid);
	var cell1=document.getElementById('spec_cell1_'+rowid);
	var cell2=document.getElementById('spec_cell2_'+rowid);
	var cell3=document.getElementById('spec_cell3_'+rowid);
	var cell4=document.getElementById('spec_cell4_'+rowid);

	//Remove items from cells
	var up_node=cell1.removeChild(document.getElementById('spec_up_'+rowid));
	var down_node=cell1.removeChild(document.getElementById('spec_down_'+rowid));
	var name_node=cell2.removeChild(document.getElementById('spec_name_'+rowid));
	var val_node=cell3.removeChild(document.getElementById('spec_val_'+rowid));
	var del_node=cell4.removeChild(document.getElementById('spec_del_'+rowid));

	//Remove cells from row
	row.removeChild(cell1);
	row.removeChild(cell2);
	row.removeChild(cell3);
	row.removeChild(cell4);

	//Remove row from table
	//node.removeChild(row);

	//Safari ghost node fix
	name_node.setAttribute('name','');
	val_node.setAttribute('name','');

	//Cleanup
	node=null; row=null; cell1=null; cell2=null; cell3=null; cell4=null; up_node=null; down_node=null; name_node=null; val_node=null; del_node=null;
}

function moveSpecRowDown(rowid)
{
	for(var i=rowid+1;i<specs;i++)
	{
		if(document.getElementById('spec_name_'+i)!=null)
		{
			var name_node_dst=document.getElementById('spec_name_'+i);
			var val_node_dst=document.getElementById('spec_val_'+i);

			var name_node_src=document.getElementById('spec_name_'+rowid);
			var val_node_src=document.getElementById('spec_val_'+rowid);

			var n=name_node_dst.value;
			var v=val_node_dst.value;

			name_node_dst.value=name_node_src.value;
			val_node_dst.value=val_node_src.value;

			name_node_src.value=n;
			val_node_src.value=v;

			i=specs;

			//Cleanup
			name_node_dst=null;
			val_node_dst=null;
			name_node_src=null;
			val_node_src=null;
		}
	}
}

function moveSpecRowUp(rowid)
{
	for(var i=rowid-1;i>=0;i--)
	{
		if(document.getElementById('spec_val_'+i)!=null)
		{
			var name_node_dst=document.getElementById('spec_name_'+i);
			var val_node_dst=document.getElementById('spec_val_'+i);

			var name_node_src=document.getElementById('spec_name_'+rowid);
			var val_node_src=document.getElementById('spec_val_'+rowid);

			var n=name_node_dst.value;
			var v=val_node_dst.value;

			name_node_dst.value=name_node_src.value;
			val_node_dst.value=val_node_src.value;

			name_node_src.value=n;
			val_node_src.value=v;

			i=-1;

			//Cleanup
			name_node_dst=null; val_node_dst=null; name_node_src=null; val_node_src=null;
		}
	}
}

function addFileRow(button, name)
{
	var total = 0;
	var count = 0;
	for(var i in files)
	{
		total += files[i];
		count++;
	}
	if(total+count >= 20)
		return false;
		
	if(files[name] == undefined)
		files[name] = 1;
	button.setAttribute('action','none');
	var rowid=files[name];

	parent_node=document.getElementById('files_'+name);

	label_node=document.createElement('label');
	label_node.setAttribute('for',name+'_'+rowid);
	label_node.innerHTML='Select Image '+(files[name]+1);

	file_node=document.createElement('input');
	file_node.setAttribute('type','file');
	file_node.setAttribute('id',name+'_'+rowid);
	file_node.setAttribute('name',name+'[]');

	br_node=document.createElement('br');

	parent_node.appendChild(label_node);
	parent_node.appendChild(file_node);
	parent_node.appendChild(br_node);

	//Cleanup
	br_node=null; file_node=null; label_node=null; parent_node=null; rowid=null; button=null;
	files[name]++;
	return false;
}
/**
 * Caps lock detection
 */

function caps(event,elem)
{
	if(!event)
		event=window.event;
	if(!event)
		return;
	if(event.charCode)
		keypress=event.charCode;
	else if(event.which)
		keypress=event.which;
	else if(event.keyCode)
		keypress=event.keyCode;
	else
		keypress=0;

	var shiftkey=event.shiftKey || (event.modifiers && (event.modifiers & 4));
	if((keypress>64 && keypress<91 && !shiftkey) || (keypress>96 && keypress<123 && shiftkey))
		elem.className='capslock';
	else
		elem.className='';
	event=null;
}

/**
 * Crop functions
 */

var isDown = 0;
var xSrc = 0;
var ySrc = 0;

function crop_mouseDown(event)
{
	isDown = 1;
	xSrc = event.clientX;
	ySrc = event.clientY;
}

function crop_mouseUp()
{
	isDown = 0;
}

function crop_mouseMove(event)
{
	if (isDown == 1)
	{
		crop_move(event.clientX - xSrc, event.clientY - ySrc);
		xSrc = event.clientX;
		ySrc = event.clientY;
	}
	return false;
}

function crop_move(offx, offy)
{
	var itemX;
	var picX;
	if (document.getElementById('sizer').clientLeft != null)
	{
		itemX = document.getElementById('sizer').offsetLeft;
		picX = document.getElementById('pic').offsetLeft;
	}
	else
	{
		itemX = document.getElementById('sizer').x;
		picX = document.getElementById('pic').x;
	}
	x = offx + itemX;
	if (x + document.getElementById('sizer').width > picX + document.getElementById('pic').width)
		x = picX + document.getElementById('pic').width - document.getElementById('sizer').width;
	if (x < picX)
		x = picX;
	document.getElementById('sizer').style.left = x + "px";

	var itemY;
	var picY;
	if (document.getElementById('sizer').clientTop != null)
	{
		itemY = document.getElementById('sizer').offsetTop;
		picY = document.getElementById('pic').offsetTop;
	}
	else
	{
		itemY = document.getElementById('sizer').y;
		picY = document.getElementById('pic').y;
	}
	y = offy + itemY;
	if (y + document.getElementById('sizer').height > picY + document.getElementById('pic').height)
		y = picY + document.getElementById('pic').height - document.getElementById('sizer').height;
	if (y < picY)
		y = picY;
	document.getElementById('sizer').style.top = y + "px";
	document.getElementById('sizerX').value = x - picX;
	document.getElementById('sizerY').value = y - picY;
}

/**
 * Generic functions
 */

function isArray(obj)
{
   if (obj.constructor.toString().indexOf("Array") == -1)
      return false;
   else
      return true;
}

function getElementsByClassName(oElm, strTagName, strClassName){
	var arrElements = (strTagName == "*" && document.all)? document.all :
	oElm.getElementsByTagName(strTagName);
	var arrReturnElements = new Array();
	strClassName = strClassName.replace(/\-/g, "\\-");
	var oRegExp = new RegExp("(^|\\s)" + strClassName + "(\\s|$)");
	var oElement;
	for(var i=0; i<arrElements.length; i++){
		oElement = arrElements[i];
		if(oRegExp.test(oElement.className)){
			arrReturnElements.push(oElement);
		}
	}
	return (arrReturnElements)
}

/**
 * Tab functions
 */
var tabs_selectedTab;
var tabs_panes;
var tabs_currentPane;
var tabs;

function tabs_highlight(elem,state)
{
	if(elem.className!="active")
	{
		if(state)
			tabs_position(elem,-50);
		else
			tabs_position(elem,0);
	}
	elem=null;
}

function tabs_position(elem,position)
{
	elem.style.backgroundPosition='right '+position+'px';
	elem.firstChild.style.backgroundPosition='left '+position+'px';
}

function tabs_activate(elem)
{
	var tab=elem.parentNode;
	if(tab!=tabs_selectedTab)
	{
		//swap around classes
		tabs_selectedTab.className='';
		tabs_position(tabs_selectedTab,0);
		tabs_position(tab,-100);
		tabs_selectedTab=tab;
		tab.className='active';

		var num=tab.firstChild.getAttribute('name');
		tabs_panes[num].style.display='block';
		tabs_currentPane.style.display='none';
		tabs_currentPane=tabs_panes[num];
	}
	tab=null;
	return false;
}

function tabs_onload()
{
	var pane=document.getElementById('tabpane');

	var brs=pane.getElementsByTagName('br');
	var count=0;
	for(i=0;i<brs.length;i++)
	{
		//alert(brs[i].parentNode.type);
		if(brs[i].parentNode.id=='tabpane')
		{
			pane.removeChild(brs[i]);
			count++;
		}
	}
	brs=null;

	var legends=getElementsByClassName(pane,'div','legend');
	tabs_panes=getElementsByClassName(pane,'div','form');
	for(var i=0;i<tabs_panes.length;i++)
		tabs_panes[i].className='pane';
	var tabHeader=document.createElement('div');
	tabHeader.setAttribute('id','tabHeader');

	var ul=document.createElement('ul');

	tabs=new Array(legends.length);

	for(var i=0;i<legends.length;i++)
	{
		var li=document.createElement('li');
		tabs[i]=document.createElement('a');
		tabs[i].setAttribute('href',false);
		tabs[i].innerHTML=legends[i].innerHTML;
		tabs[i].setAttribute('name',i);
		tabs[i].setAttribute('id','tab'+i);
		tabs[i].onclick=function()	{ tabs_activate(this); return false; };
		li.onmouseover=function()	{ tabs_highlight(this,true); };
		li.onmouseout=function()	{ tabs_highlight(this,false); };
		li.appendChild(tabs[i]);
		ul.appendChild(li);
		try
		{
			legends[i].parentNode.removeChild(legends[i]);
		}
		catch(e)
		{
			alert(e);
		}
		if(i==0)
		{
			tabs_position(li,-100);
			li.className='active';
			tabs_selectedTab=li;
		}
		li=null; legends[i]=null;
	}
	tabHeader.appendChild(ul);
	pane.insertBefore(tabHeader,pane.firstChild);
	ul=null; pane=null; tabHeader=null;
	window.setTimeout('tabs_hide();',1);
}

function tabs_onunload()
{
	for(var i=0;i<tabs.length;i++)
	{
		tabs[i].className=null;
		tabs[i].setAttribute('name',null);
		tabs[i].onclick=null;
		tabs[i].onmouseover=null;
		tabs[i].onmouseout=null;
		tabs[i]=null;
	}
	tabs_selectedTab=null; tabs_panes=null; tabs_currentPane=null; tabs=null;
}

function tabs_hide()
{
	for(var i=1;i<tabs_panes.length;i++)
	{
		tabs_panes[i].style.display='none';
	}
	tabs_currentPane=tabs_panes[0];
}

function tabs_setup()
{
	window.onload=tabs_onload;
	window.onunload=tabs_onunload;
}

/**
 * Validation related functions
 */

function showError(id)
{
	document.getElementById(id+'_error').style.visibility='visible';
}

function hideError(id)
{
	document.getElementById(id+'_error').style.visibility='hidden';
}

function appendError(text,errordiv)
{
	listNode = document.getElementById(errordiv+'_ul');
	itemNode = document.createElement('li');
	itemNode.appendChild(document.createTextNode(text));
	listNode.appendChild(itemNode);
}

function trim(str)
{
	while(''+str.charAt(0)==' ')
		str=str.substring(1,str.length);
	while(''+str.charAt(str.length-1)==' ')
		str=str.substring(0,str.length-1);
	return str;
}

function name2page(name)
{
	name = new String(name);
	name = name.replace(/^\s+|\s+$/g,'').replace(/ /g, '-').toLowerCase().replace(/[^a-z0-9-]*/ig, '').replace(/-+/g, '-');
	return name.toString();
}