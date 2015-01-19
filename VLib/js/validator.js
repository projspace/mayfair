/**
* Validator
* Author	: Plesnicute Marian
* Version	: 1.0
*/

function Validator(errorHandler) 
{
	this.errorHandler = errorHandler;
	this.fields = [];
	this.field_types = [];
	this.error_fields = [];
	
	if (typeof Validator._initialized == "undefined") 
	{
		Validator.prototype.addField = function (field_id, field_name, field_types) {
			field_id = jQuery.trim(field_id);
			if(!(typeof field_id == "string" && field_id != ''))
				return false;
			field_types = jQuery.trim(field_types);
			if(!(typeof field_types == "string" && field_types != ''))
				return false;
				
			var types = field_types.split('|');
			
			for(var i=0; i < types.length; i++)
			{
				field_type = jQuery.trim(types[i]);
				if(field_type == '')
					continue;
				this.fields[this.fields.length] = {
					id: field_id
					,name: field_name
					,type: field_type.toLowerCase()
				};
			}
		}
		
		Validator.prototype.addFieldType = function (name, fnValidation, errorCode, errorMsg) {
			name = jQuery.trim(name);
			if(!(typeof name == "string" && name != ''))
				return false;
				
			if(typeof fnValidation != "function")
				return false;
				
			this.field_types[this.field_types.length] = {
				name: name.toLowerCase()
				,fnValidation: fnValidation
				,errorCode: errorCode
				,errorMsg: errorMsg
			};
		}
		
		Validator.prototype.validate = function () {
			var global_this = this;
			var type = false;
			var ret = false;

			this.error_fields = [];
			
			for(var i=0;i<this.fields.length;i++)
			{
				if(!$('#'+this.fields[i].id).length)
					continue;
					
				type = false;
				for(var index=0;index<this.field_types.length;index++)
					if(this.field_types[index].name == this.fields[i].type)
					{
						type = this.field_types[index];
						break;
					}
				if(!type)
					continue;
					
				var dom = $('#'+this.fields[i].id).get(0);
				dom.fnValidation = type.fnValidation;
				ret = dom.fnValidation();
				if(!ret)
					this.error_fields[this.error_fields.length] = { 
						dom: $('#'+this.fields[i].id).get(0)
						,errorCode: type.errorCode
						,errorMsg: type.errorMsg
						,field_name: this.fields[i].name
					};
			}

			if(this.error_fields.length > 0)
			{
				this.errorHandler(this.error_fields);
				return false;
			}
			else
				return true;
		}
		
		Validator.prototype.validateInput = function (id) {
			var global_this = this;
			var type = false;
			var ret = false;

			this.error_fields = [];
			
			for(var i=0;i<this.fields.length;i++)
			{
				if(this.fields[i].id != id)
					continue;

				if(!$('#'+this.fields[i].id).length)
					continue;
					
				type = false;
				for(var index=0;index<this.field_types.length;index++)
					if(this.field_types[index].name == this.fields[i].type)
					{
						type = this.field_types[index];
						break;
					}
				if(!type)
					continue;
					
				var dom = $('#'+this.fields[i].id).get(0);
				dom.fnValidation = type.fnValidation;
				ret = dom.fnValidation();
				if(!ret)
					this.error_fields[this.error_fields.length] = { 
						dom: $('#'+this.fields[i].id).get(0)
						,errorCode: type.errorCode
						,errorMsg: type.errorMsg
						,field_name: this.fields[i].name
					};
			}

			if(this.error_fields.length > 0)
			{
				return {status: false, details: this.error_fields[0]};
			}
			else
				return {status: true, details: false};
		}
	
		Validator._initialized = true;
	}
	
	this.addFieldType(
		'required'
		, function(){ 
			if(jQuery.trim($(this).val()) == '')
				return false;
			else
				return true;
		}
		, 'E1'
		, 'This field is required.'
	);
	
	this.addFieldType(
		'float'
		, function(){
			if(jQuery.trim($(this).val()) == '')
				return true;
			if(jQuery.trim($(this).val()).match(/^[-+]?\d+\d*(\.\d*)?$/))
				return true;
			else
				return false;
		}
		, 'E2'
		, 'This field is not a number. Please use numbers only and no spaces.'
	);
	
	this.addFieldType(
		'integer'
		, function(){
			if(jQuery.trim($(this).val()) == '')
				return true;
			if(jQuery.trim($(this).val()).match(/^[-+]?\d+\d*$/))
				return true;
			else
				return false;
		}
		, 'E3'
		, 'This field is not an integer.'
	);
	
	this.addFieldType(
		'email'
		, function(){
			if(jQuery.trim($(this).val()) == '')
				return true;
			if(jQuery.trim($(this).val()).match(/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/i))
				return true;
			else
				return false;
		}
		, 'E4'
		, 'Please enter a valid email address.'
	);
	
	this.addFieldType(
		'phone'
		, function(){
			if(jQuery.trim($(this).val()) == '')
				return true;
			if(jQuery.trim($(this).val()).match(/^[+]?[0-9\(\)\[\]\. -]{4,}$/))
				return true;
			else
				return false;
		}
		, 'E5'
		, 'This field is not a valid phone number.'
	);
	
	this.addFieldType(
		'postcode'
		, function(){
			if(jQuery.trim($(this).val()) == '')
				return true;
			/*if(jQuery.trim($(this).val()).match(/^[A-Z0-9]{2,4} ?[0-9][A-Z]{2}$/i))*/
			if(jQuery.trim($(this).val()).match(/^[0-9]{5}$/i))
				return true;
			else
				return false;
		}
		, 'E6'
		, 'Please enter valid 5 digits US zip code.'
	);
	
	this.addFieldType(
		'password'
		, function(){
			if(jQuery.trim($(this).val()) == '')
				return true;
			if(jQuery.trim($(this).val()).length >= 6)
				return true;
			else
				return false;
		}
		, 'E7'
		, 'Please enter at least 6 characters.'
	);
	this.addFieldType(
		'name'
		, function(){
			if(jQuery.trim($(this).val()) == '')
				return true;
			if(jQuery.trim($(this).val()).length >= 4)
				return true;
			else
				return false;
		}
		, 'E8'
		, 'Value must be at least 5 characters.'
	);
}