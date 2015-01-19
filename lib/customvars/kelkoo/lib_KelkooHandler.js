function kelkoo(category,vars)
{
	//Do XMLHTTPRequest stuff here
	var req=new XMLHttpRequest();
	if(req)
	{
		req.onreadystatechange=function()
		{
			if(req.readyState==4 && req.status==200)
			{
				kelkoo_clear();
				var div=document.getElementById('kelkoo');
				div.innerHTML=req.responseText;
			}
		};
		req.open('GET',dir+'lib/customvars/kelkoo/lib_KelkooHandler.php?category='+category+'&custom='+vars);
		req.send(null);
	}
}

function kelkoo_clear()
{
	var div=document.getElementById('kelkoo');
	while(div.hasChildNodes())
		div.removeChild(div.firstChild);
}