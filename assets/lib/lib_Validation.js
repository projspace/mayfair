function Validation(divid)
{
	var errordiv=divid;
	var valid=true;

	var conditional=new Array();
	var customConditional=new Array();
	var groups=new Array();
	var fields=new Array();
	var custom=0;
	var regex=1;
	var range=2;
	var gt=3;
	var lt=4;
	var required=5;
	var compare=6;
	var filetype=7;
	
	this.addConditional=function(params)
	{
		conditional[params.group]=params;
	}
	
	this.addCustomConditional=function(params)
	{
		customConditional[params.group]=params;
	}

	this.addCustom=function(params)
	{
		if(params.group==null)
			params.group="main";
			
		if(params.message==null)
			params.message=params.name+" is not valid";
			
		this.initGroup(params.group,custom);
		
		groups[params.group][custom].push(params);
		
		fields.push(params.field);
	}
	
	this.addFileType=function(params)
	{
		if(params.group==null)
			params.group="main";
			
		if(params.message==null)
			params.message=params.name+" is not an allowed file type";

		this.initGroup(params.group,filetype);
	
		groups[params.group][filetype].push(params);
		
		fields.push(params.field);
	}

	this.addRegex=function(params)
	{
		if(params.group==null)
			params.group="main";
			
		if(params.message==null)
			params.message=params.name+" is in an incorrect format";

		this.initGroup(params.group,regex);
	
		groups[params.group][regex].push(params);
		
		fields.push(params.field);
	}

	this.addRange=function(params)
	{
		if(params.group==null)
			params.group="main";
			
		if(params.message==null)
			params.message=params.name+" must be between "+params.from+" and "+params.to;
			
		this.initGroup(params.group,range);
		
		groups[params.group][range].push(params);
		
		fields.push(params.field);
	}
	
	this.addMoreThan=function(params)
	{
		if(params.group==null)
			params.group="main";
			
		if(params.message==null)
			params.message=params.name+" must be at least "+(params.from+1);

		this.initGroup(params.group,gt);
		
		groups[params.group][gt].push(params);
		
		fields.push(params.field);
	}
	
	this.addLessThan=function(params)
	{
		if(params.group==null)
			params.group="main";
			
		if(params.message==null)
			params.message=params.name+" must be less than or equal to "+(params.to-1);

		this.initGroup(params.group,lt);
		
		groups[params.group][lt].push(params);
		
		fields.push(params.field);
	}

	this.addRequired=function(params)
	{
		if(params.group==null)
			params.group="main";
			
		if(params.message==null)
			params.message=params.name+" is a required field";
		
		this.initGroup(params.group,required);
					
		groups[params.group][required].push(params);
		
		fields.push(params.field);
	}
	
	this.addCompare=function(params)
	{
		if(params.group==null)
			params.group="main";
			
		if(params.message==null)
			params.message=params.name+" and "+params.name2+" must match";

		this.initGroup(params.group,compare);
		
		groups[params.group][compare].push(params);
		
		fields.push(params.field1);
		fields.push(params.field2);
	}
	
	this.validate=function()
	{
		valid=true;
		
		//Hide all error displays for this validator
		for(var i=0;i<fields.length;i++)
		{
			this.hideError(fields[i]);		
		}
		
		//Remove any error messages from the error display, and then hide it
		var cNodes=document.getElementById(errordiv+'_ul').childNodes;
		while(cNodes.length>0)
			document.getElementById(errordiv+'_ul').removeChild(cNodes[0]);

		//Iterate through our validation groups
		for(var key=0;key<groups.length;key++)
		{
			//Check if there is a conditional validator for this field
			if(key in conditional)
			{
				if(document.getElementById(conditional[key].field).type=='checkbox')
				{
					var ret;
					if(conditional[key].test=="==")
						ret=document.getElementById(conditional[key].field).checked;
					else
						ret=!document.getElementById(conditional[key].field).checked;
					if(ret)
						continue; //skip this group
				}					
				else if(!this.condTest(conditional[key].field,conditional[key].value,conditional[key].test))
					continue; //skip this group
			}
			//Check if there is a custom conditional validator for this field
			if(key in customConditional)
			{
				var func="val_"+customConditional[key].method;
				if(window[func])
				{
					if(!window[func](fields[customConditional[key].field]))
						continue; //skip this group
				}
			}
			
			//Custom validators
			if(groups[key][custom]!=null)
			{
				for(var i=0;i<groups[key][custom].length;i++)
				{
					var obj=groups[key][custom][i];
					
					var func="val_"+obj.method;
					if(window[func])
					{
						if(!window[func](obj.field))
						{
							this.showError(obj.field);
							this.appendError(obj.message);
							valid=false;
						}
					}
				}
			}
			
			//Required validators
			if(groups[key][required]!=null)
			{
				for(var i=0;i<groups[key][required].length;i++)
				{
					var obj=groups[key][required][i];
					if(this.required(obj.field))
					{
						this.showError(obj.field);
						this.appendError(obj.message);
						valid=false;
					}
				}
			}
			
			//File Type validators
			if(groups[key][filetype]!=null)
			{
				for(var i=0;i<groups[key][filetype].length;i++)
				{
					var obj=groups[key][filetype][i];
					
					var filename=document.getElementById(obj.field).value.toLowerCase();
					
					var ret=false;
					if(filename=="")
						ret=true;
						
					var extension=filename.substr(filename.lastIndexOf(".")+1);
					var allowed=obj.types.toLowerCase().split(",");

					for(i=0;i<allowed.length;i++)
						if(extension==allowed[i])
							ret=true;

					if(!ret)
					{
						this.showError(obj.field);
						this.appendError(obj.message);
						valid=false;
					}
				}			
			}
			
			//Regex validators
			if(groups[key][regex]!=null)
			{
				for(var i=0;i<groups[key][regex].length;i++)
				{
					var obj=groups[key][regex][i];
					
					if(this.trim(this.value(obj.field))!="")
					{
						if(!this.trim(this.value(obj.field)).match(obj.regex))
						{
							this.showError(obj.field);
							this.appendError(obj.message);
							valid=false;
						}
					}
				}
			}
			
			//Range validators
			if(groups[key][range]!=null)
			{
				for(var i=0;i<groups[key][range].length;i++)
				{
					var obj=groups[key][range][i];
					
					if(this.value(obj.field)<obj.from || this.value(obj.field)>obj.to)
					{
						this.showError(obj.field);
						this.appendError(obj.message);
						valid=false;
					}
				}
			}
			
			//More than validators
			if(groups[key][gt]!=null)
			{
				for(var i=0;i<groups[key][gt].length;i++)
				{
					var obj=groups[key][gt][i];
					
					if(parseInt(this.value(obj.field))<=obj.from)
					{
						this.showError(obj.field);
						this.appendError(obj.message);
						valid=false;
					}
				}
			}
			
			//Less than validators
			if(groups[key][lt]!=null)
			{
				for(var i=0;i<groups[key][lt].length;i++)
				{
					var obj=groups[key][lt][i];
					
					if(parseInt(this.value(obj.field))>=obj.to)
					{
						this.showError(obj.field);
						this.appendError(obj.message);
						valid=false;
					}
				}
			}
			
			//Compare validators
			if(groups[key][compare]!=null)
			{
				for(var i=0;i<groups[key][compare].length;i++)
				{
					var obj=groups[key][compare][i];
					
					if(this.value(obj.field1)!=this.value(obj.field2))
					{
						this.showError(obj.field2);
						this.showError(obj.field1);
						this.appendError(obj.message);
						valid=false;
					}
				}
			}
		}
		if(valid)
			document.getElementById(errordiv).style.display='none';
		else
		{
			document.getElementById(errordiv).style.display='block';
			window.scroll(document.getElementById(errordiv).offsetTop,document.getElementById(errordiv).offsetLeft);
		}
			
		return valid;
	}
	
	this.condTest=function(id,val2,test)
	{
		var ret=false;
		var val1=this.value(id);

		switch(test)
		{
			default:
			case "==":
				if(val1==val2)
					ret=true;
				break;
			case "===":
				if(val1==val2)
					ret=true;
				break;
			case "!=":
				if(val1!=val2)
					ret=true;
				break;
			case "!==":
				if(val1!=val2)
					ret=true;
				break;
			case ">":
				if(val1>val2)
					ret=true;
				break;
			case ">=":
				if(val1>=val2)
					ret=true;
				break;
			case "<":
				if(val1<val2)
					ret=true;
				break;
			case "<=":
				if(val1<=val2)
					ret=true;
				break;
		}
		return ret;
	}
	
	this.showError=function(id)
	{
		var item=document.getElementById(id+'_error');
		if(item!=null)
		{
			item.style.display='inline';
			item=null;
		}
		else
			alert(id+' is not a valid error display');
	}
	
	this.hideError=function(id)
	{
		var item=document.getElementById(id+'_error');
		if(item!=null)
		{
			item.style.display='none';
			item=null;
		}
		else
			alert(id+' is not a valid error display');
	}
	
	this.appendError=function(text)
	{
		if(text!='')
		{
			listNode = document.getElementById(errordiv+'_ul');
			itemNode = document.createElement('li');
			itemNode.innerHTML=text;
			listNode.appendChild(itemNode);
		}
	}
	
	this.isnum=function(id)
	{
		return (parseInt(document.getElementById(id).value)==document.getElementById(id).value);
	}
	
	this.value=function(id)
	{
		try
		{
			return document.getElementById(id).value;
		}
		catch(err)
		{
			alert(err);
		}	
	}
	
	this.required=function(id)
	{
		try
		{
			
			if(document.getElementById(id).type=='checkbox')
				return document.getElementById(id).checked;
			var input_value = '';
			if(document.getElementById(id).type=='select-one')
				input_value = document.getElementById(id).options[document.getElementById(id).selectedIndex].value;
			else
				input_value = document.getElementById(id).value;
			return (this.trim(input_value)=='');
			
		}
		catch(err)
		{
			alert(err);
			return false;
		}
	}
	
	this.trim=function(str)
	{
		while(''+str.charAt(0)==' ')
			str=str.substring(1,str.length);
		while(''+str.charAt(str.length-1)==' ')
			str=str.substring(0,str.length-1);
		return str;
	}
	
	this.initGroup=function(group,type)
	{
		if(groups[group]==null)
			groups[group]=new Array();
		if(groups[group][type]==null)
			groups[group][type]=new Array();
	}
}
