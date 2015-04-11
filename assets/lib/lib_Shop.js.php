<?
	/**
	 * e-Commerce System
	 * Copyright (c) 2002-2006 Philip John, All Rights Reserved.
	 * Author	: Philip John
	 * Version	: 6.0
	 *
	 * PROPRIETARY/CONFIDENTIAL.  Use is subject to license terms.
	 */
?>
<?
	header("Content-Type: text/javascript");
	include("cfg_Config.php");
?>
function shop_ImagePopup(id,type)
{
	var fs=window.open('<?= $config['dir'] ?>index.php/fuseaction/shop.image/type/'+type+'/imageid/'+id,'image','directories=no,height=<?= $config['size']['product']['image']['y']+30 ?>,width=<?= $config['size']['product']['image']['x'] ?>,location=no,menubar=no,resizable=no,scrollbars=no,status=no,toolbar=no');
	fs.focus();
}

function shop_UpdatePrice()
{
	var foo=base_price;
	for(var i=0;i<option_price.length;i++)
	{
		option=document.getElementById('option'+(i+1));
		foo=foo+option_price[i][option.selectedIndex];
	}
	document.getElementById('price').innerHTML='&pound;'+number_format(foo,2,".");
}

function shop_ConfAct(location,message)
{
	var answer = confirm (message)
	if (answer)
		window.location=location;
}

function shop_FormConfAct(messageid,verb,noun,actor)
{
	if(messageid==1)
		var message="Are you sure you want to "+verb+" this "+noun+" "+actor+"?";
	else if(messageid==2)
		var message="Are you sure you want to "+verb+" your "+noun;

	return confirm (message)
}

var delivery_name;
var delivery_address;
var delivery_country;
var delivery_postcode;
var delivery_message;

function shop_DeliveryCopy(id)
{
	delivery_name=document.getElementById('delivery_name_'+id).value;
	delivery_address=document.getElementById('delivery_address_'+id).value;
	country=document.getElementById('delivery_country_'+id);
	delivery_country=country.options[country.selectedIndex].value;
	delivery_postcode=document.getElementById('delivery_postcode_'+id).value;
	delivery_message=document.getElementById('delivery_message_'+id).value;
}

function shop_DeliveryPaste(id)
{
	document.getElementById('delivery_name_'+id).value=delivery_name;
	document.getElementById('delivery_address_'+id).value=delivery_address;

	country=document.getElementById('delivery_country_'+id);

	for(var i=0;i<country.options.length;i++)
		if(country.options[i].value==delivery_country)
			country.selectedIndex=i;

	document.getElementById('delivery_postcode_'+id).value=delivery_postcode;
	document.getElementById('delivery_message_'+id).value=delivery_message;
}

function hilite(elem)
{
	if(elem.className=='cat')
		elem.className='catOff';
	else
		elem.className='cat';
}

function number_format (number, decimals, dec_point)
{
	var exponent = "";
	var numberstr = number.toString ();
	var eindex = numberstr.indexOf ("e");
	if (eindex > -1)
	{
		exponent = numberstr.substring (eindex);
		number = parseFloat (numberstr.substring (0, eindex));
	}
	
	if (decimals != null)
	{
		var temp = Math.pow (10, decimals);
		number = Math.round (number * temp) / temp;
	}
	var sign = number < 0 ? "-" : "";
	var integer = (number > 0 ? 
			Math.floor (number) : Math.abs (Math.ceil (number))).toString ();
	
	var fractional = number.toString ().substring (integer.length + sign.length);
	dec_point = dec_point != null ? dec_point : ".";
	fractional = decimals != null && decimals > 0 || fractional.length > 1 ? 
							 (dec_point + fractional.substring (1)) : "";
	if (decimals != null && decimals > 0)
	{
		for (i = fractional.length - 1, z = decimals; i < z; ++i)
			fractional += "0";
	}
	
	return sign + integer + fractional + exponent;
}
