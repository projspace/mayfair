<?
	class Validation
	{
		var $_conditional;
		var $_customConditional;
		var $_groups;

		var $_errordiv;
		var $_valid;
		var $_fields;
		var $_invalidFields;
		var $_config;
		var $_errorMsg;
		
		var $_js;
		
		var $_jsGroups;
		var $_groupCount;

		function Validation($divid)
		{
			global $config;
			$this->_config&=$config;
			$this->_errordiv=$divid;

			$this->_fields=array();
			$this->_invalidFields=array();
			$this->_errorMsg=array();
			$this->_groups=array();
			$this->_jsGroups=array();
			$this->_groupCount=0;

			$this->_valid=true;
			$this->_js=array();
		}
		
		function _getJSGroup($group)
		{
			if(!isset($this->_jsGroups[$group]))
			{
				$this->_jsGroups[$group]=$this->_groupCount;
				$this->_groupCount++;
			}
			return $this->_jsGroups[$group];
		}

		function addConditional($group,$field,$value="",$test="==")
		{
			$this->_conditional[$group]=array(
				"field"		=> $field
				,"value"	=> $value
				,"test"		=> $test
			);
			$this->_js[]=".addConditional({group:'".$this->_getJSGroup($group)."', field:'".addslashes($field)."', value:'".addslashes($value)."', test:'".addslashes($test)."'});";
		}
		
		function addCustomConditional($group,$field,$function)
		{
			$this->_customConditional[$group]=array(
				"field"		=> $field
				,"function"	=> $function
			);
			$this->_js[]=".addCustomConditional({group:'".$this->_getJSGroup($group)."', field:'".addslashes($field)."', method:'".addslashes($function)."'});";
		}

		function addCustom($field,$name,$function,$message=false,$group="main")
		{
			if($message===false)
				$message="{$name} is not valid";
				
			$this->_groups[$group]['custom'][]=array(
				"field"		=> $field
				,"name"		=> $name
				,"function"	=> $function
				,"message"	=> $message
				,"group"	=> $group
			);
			
			$this->_fields[]=$field;
			$this->_js[]=".addCustom({field:'".addslashes($field)."', name:'".addslashes($name)."', method:'".addslashes($function)."', message:'".addslashes($message)."', group:'".$this->_getJSGroup($group)."'});";
		}
		
		function addFileType($field,$name,$types,$message=false,$group="main")
		{
			if($message===false)
				$message="{$name} is not an allowed file type.  Only ".implode(",",$types)." are allowed";
				
			$this->_groups[$group]['fileType'][]=array(
				"field"		=> $field
				,"name"		=> $name
				,"types"	=> $types
				,"message"	=> $message
				,"group"	=> $group
			);
			
			$this->_fields[]=$field;
			$this->_js[]=".addFileType({field:'".addslashes($field)."', name:'".addslashes($name)."', types:'".addslashes(implode(",",$types))."', message:'".addslashes($message)."', group:'".$this->_getJSGroup($group)."'});";
		}
		
		function addFileError($field,$name,$message=false,$group="main")
		{
			if($message===false)
				$message="{$name} presented an error on upload: ";
				
			$this->_groups[$group]['fileError'][]=array(
				"field"		=> $field
				,"name"		=> $name
				,"message"	=> $message
				,"group"	=> $group
			);
			
			$this->_fields[]=$field;
		}
		
		function addFileRequired($field,$name,$message=false,$group="main")
		{
			if($message===false)
				$message="{$name} requires a file to be supplied";
				
			$this->_groups[$group]['fileRequired'][]=array(
				"field"		=> $field
				,"name"		=> $name
				,"message"	=> $message
				,"group"	=> $group
			);
			
			$this->_fields[]=$field;
			$this->_js[]=".addRequired({field:'".addslashes($field)."', name:'".addslashes($name)."', message:'".addslashes($message)."', group:'".$this->_getJSGroup($group)."'});";
		}

		function addRegex($field,$name,$regex,$message=false,$group="main",$regex_flag=false)
		{
			if($message===false)
				$message="{$name} is in an incorrect format";

			$this->_groups[$group]['regex'][]=array(
				"field"		=> $field
				,"name"		=> $name
				,"regex"	=> '/'.$regex.'/'.$regex_flag
				,"message"	=> $message
				,"group"	=> $group
			);
			
			if($regex_flag)
				$regex_flag = ", '".addslashes($regex_flag)."'";
			else
				$regex_flag = "";
			
			$this->_fields[]=$field;
			$this->_js[]=".addRegex({field:'".addslashes($field)."', name:'".addslashes($name)."', regex: new RegExp('".addslashes($regex)."'".$regex_flag."), message:'".addslashes($message)."', group:'".$this->_getJSGroup($group)."'});";
		}

		function addRange($field,$name,$from,$to,$message=false,$group="main")
		{
			if($message===false)
				$message="{$name} must be between {$from} and {$to}";
				
			$this->_groups[$group]['range'][]=array(
				"field"		=> $field
				,"name"		=> $name
				,"from"		=> $from
				,"to"		=> $to
				,"message"	=> $message
				,"group"	=> $group
			);
			
			$this->_fields[]=$field;
			$this->_js[]=".addRange({field:'".addslashes($field)."', name:'".addslashes($name)."', from:'".addslashes($from)."', to:'".addslashes($to)."', message:'".addslashes($message)."', group:'".$this->_getJSGroup($group)."'});";
		}
		
		function addMoreThan($field,$name,$from,$message=false,$group="main")
		{
			if($message===false)
				$message="{$name} must be at least ".($from+1);

			$this->_groups[$group]['gt'][]=array(
				"field"		=> $field
				,"name"		=> $name
				,"from"		=> $from
				,"message"	=> $message
				,"group"	=> $group
			);
			
			$this->_fields[]=$field;
			$this->_js[]=".addMoreThan({field:'".addslashes($field)."', name:'".addslashes($name)."', from:'".addslashes($from)."', message:'".addslashes($to)."', group:'".$this->_getJSGroup($group)."'});";
		}
		
		function addLessThan($field,$name,$to,$message=false,$group="main")
		{
			if($message===false)
				$message="{$name} must be less than or equal to ".($to-1);

			$this->_groups[$group]['lt'][]=array(
				"field"		=> $field
				,"name"		=> $name
				,"to"		=> $to
				,"message"	=> $message
				,"group"	=> $group
			);
			
			$this->_fields[]=$field;
			$this->_js[]=".addLessThan({field:'".addslashes($field)."', name:'".addslashes($name)."', to:'".addslashes($to)."', message:'".addslashes($message)."', group:'".$this->_getJSGroup($group)."'});";
		}

		function addRequired($field,$name,$message=false,$group="main")
		{
			if($message===false)
				$message="{$name} is a required field";
			$this->_groups[$group]['required'][]=array(
				"field"		=> $field
				,"name"		=> $name
				,"message"	=> $message
				,"group"	=> $group
			);
			
			$this->_fields[]=$field;
			$this->_js[]=".addRequired({field:'".addslashes($field)."', name:'".addslashes($name)."', message:'".addslashes($message)."', group:'".$this->_getJSGroup($group)."'});";
		}
		
		function addCompare($field1,$name1,$field2,$name2,$message=false,$group="main")
		{
			if($message===false)
				$message="{$name1} and {$name2} must match";

			$this->_groups[$group]['compare'][]=array(
				"field1"		=> $field1
				,"name1"		=> $name1
				,"field2"	=> $field2
				,"name2"	=> $name2
				,"message"	=> $message
				,"group"	=> $group
			);
			
			$this->_fields[]=$field1;
			$this->_fields[]=$field2;
			$this->_js[]=".addCompare({field1:'".addslashes($field1)."', name1:'".addslashes($name1)."', field2:'".addslashes($field2)."', name2:'".addslashes($name2)."', message:'".addslashes($message)."', group:'".$this->_getJSGroup($group)."'});";
		}

		function displayMessage($class="error")
		{
			/*$ret="<a name=\"topError\" style=\"font-weight: normal;\"><div id=\"{$this->_errordiv}\" class=\"{$class}\"";
			if($this->_valid)
				$ret.=" style=\"display: none;\"";
			$ret.="><h3>Problem with form</h3><ul id=\"{$this->_errordiv}_ul\">";
			foreach($this->_errorMsg as $message)
				$ret.="<li>{$message}</li>\n";
			$ret.="</ul></div></a>";
			return $ret;
			*/
			if(!$this->_valid)
				$ret = '<div class="message error"><h3>Problem with form</h3><p>'.implode('<br />', $this->_errorMsg).'</p></div>';
			else
				$ret = '';
			return $ret;
		}

		function display($field)
		{
			$ret="<span class=\"errorDisplay\" id=\"{$field}_error\"";
			if($this->_isValid($field))
				$ret.=" style=\"display: none;\"";
			$ret.=">*</span>";
			return $ret;
		}

		function _isValid($field)
		{
			return !in_array($field,$this->_invalidFields);
		}

		function form()
		{
			return " onsubmit=\"return val_{$this->_errordiv}.validate();\" ";
		}
		
		function invalidate($field,$name=false,$message=false)
		{
			$this->_valid=false;
			$this->_invalidFields[]=$field;
			if($name!==false || $message!==false)
			{
				if($message===false)
					$this->_errorMsg[]="{$field} is not correct";
				else
					$this->_errorMsg[]=$message;
			}
		}
	
		function validate($fields)
		{
			$this->_valid=true;

			//Iterate through our validation groups
			$keys=array_keys($this->_groups);
			foreach($keys as $key)
			{
				//Check if there is a conditional validator for this field
				if(isset($this->_conditional[$key]))
				{
					if(!$this->_condTest($fields[$this->_conditional[$key]['field']],$this->_conditional[$key]['value'],$this->_conditional[$key]['test']))
						continue;
				}
				//Check if there is a custom conditional validator for this field
				if(isset($this->_customConditional[$key]))
				{
					$func="val_".$this->_customConditional[$key]['function'];
					if(function_exists($func))
					{
						if(!$func($fields[$this->_customConditional[$key]['field']]))
							continue;
					}
				}
				
				//Custom validators
				if(isset($this->_groups[$key]['custom']))
				{
					foreach($this->_groups[$key]['custom'] as $custom)
					{
						$func="val_".$custom['function'];
						if(function_exists($func))
						{
							if(is_array($fields[$custom['field']]))
								$variable = $fields[$custom['field']];
							else
								$variable = trim($fields[$custom['field']]);
							if(!$func($variable))
							{
								$this->_invalidFields[]=$custom['field'];
								$this->_valid=false;
								$this->_errorMsg[]=$custom['message'];
							}
						}
					}
				}
				
				//Required validators
				if(isset($this->_groups[$key]['required']))
				{
					foreach($this->_groups[$key]['required'] as $required)
					{
						if(trim($fields[$required['field']])=="")
						{
							$this->_invalidFields[]=$required['field'];
							$this->_valid=false;
							$this->_errorMsg[]=$required['message'];
						}
					}
				}
				
				//Regex validators
				if(isset($this->_groups[$key]['regex']))
				{
					foreach($this->_groups[$key]['regex'] as $regex)
					{
						if(trim($fields[$regex['field']])!="")
						{
							if(!preg_match($regex['regex'],$fields[$regex['field']]))
							{
								$this->_invalidFields[]=$regex['field'];
								$this->_valid=false;
								$this->_errorMsg[]=$regex['message'];
							}
						}
					}
				}

				//fileType validators
				if(isset($this->_groups[$key]['fileType']))
				{
					foreach($this->_groups[$key]['fileType'] as $file)
					{
						if(isset($_FILES[$file['field']]) && $_FILES[$file['field']]['error']!=UPLOAD_ERR_NO_FILE)
						{
							$ext=substr($_FILES[$file['field']]['name'],strrpos($_FILES[$file['field']]['name'],".")+1);
							if(!in_array($ext,$file['types']))
							{
								$this->_invalidFields[]=$file['field'];
								$this->_valid=false;
								$this->_errorMsg[]=$file['message'];
							}
						}
					}
				}
				
				//fileRequired validators
				if(isset($this->_groups[$key]['fileRequired']))
				{
					foreach($this->_groups[$key]['fileRequired'] as $file)
					{
						if(!isset($_FILES[$file['field']]))
						{
							$this->_invalidFields[]=$file['field'];
							$this->_valid=false;
							$this->_errorMsg[]=$file['message'];
						}
					}
				}
				
				//fileError validators
				if(isset($this->_groups[$key]['fileError']))
				{
					foreach($this->_groups[$key]['fileError'] as $file)
					{
						if(isset($_FILES[$file['field']]))
						{
							if($_FILES[$file['field']]['error']>0 && $_FILES[$file['field']]['error']!=UPLOAD_ERR_NO_FILE)
							{
								$this->_invalidFields[]=$regex['field'];
								$this->_valid=false;
								
								switch($_FILES[$file['field']]['error'])
								{
									case UPLOAD_ERR_INI_SIZE:
										$message="file is too large";
										break;
									case UPLOAD_ERR_FORM_SIZE:
										$message="file is too large";
										break;
					 				case UPLOAD_ERR_PARTIAL:
										$message="the file only partially uploaded";
										break;
									/*case UPLOAD_ERR_NO_FILE:
										$message="no file was uploaded";
										break;*/
									case UPLOAD_ERR_NO_TMP_DIR:
										$message="temp document folder unavailable";
										break;
									case UPLOAD_ERR_CANT_WRITE:
										$message="cannot write file do disk";
										break;
									case UPLOAD_ERR_EXTENSION:
										$message="upload stopped by extension";
										break; 
								}
								
								$this->_errorMsg[]=$file['message']." ".$message;
							}
						}
					}
				}
				
				//Range validators
				if(isset($this->_groups[$key]['range']))
				{
					foreach($this->_groups[$key]['range'] as $range)
					{
						if($fields[$range['field']]<$range['from'] || $fields[$range['field']]>$range['to'])
						{
							$this->_invalidFields[]=$range['field'];
							$this->_valid=false;
							$this->_errorMsg[]=$range['message'];
						}
					}
				}
				
				//More than validators
				if(isset($this->_groups[$key]['gt']))
				{
					foreach($this->_groups[$key]['gt'] as $gt)
					{
						if($fields[$gt['field']]<=$gt['from'])
						{
							$this->_invalidFields[]=$gt['field'];
							$this->_valid=false;
							$this->_errorMsg[]=$gt['message'];
						}
					}
				}
				
				//Less than validators
				if(isset($this->_groups[$key]['lt']))
				{
					foreach($this->_groups[$key]['lt'] as $lt)
					{
						if($fields[$lt['field']]>=$lt['to'])
						{
							$this->_invalidFields[]=$lt['field'];
							$this->_valid=false;
							$this->_errorMsg[]=$lt['message'];
						}
					}
				}
				
				//Compare validators
				if(isset($this->_groups[$key]['compare']))
				{
					foreach($this->_groups[$key]['compare'] as $compare)
					{
						if($fields[$compare['field1']]!=$fields[$compare['field2']])
						{
							$this->_invalidFields[]=$compare['field1'];
							$this->_valid=false;
							$this->_errorMsg[]=$compare['message'];
						}
					}
				}
			}
			$this->_invalidFields=array_unique($this->_invalidFields);
			return $this->_valid;
		}

		function clientValidate()
		{
			$val="val_".$this->_errordiv;
			$ret="\n<script type=\"text/javascript\">\n"
				."	<!--\n"
				."		var {$val}=new Validation('{$this->_errordiv}');\n";
			foreach($this->_js as $js_line)
			{
				$ret.="		{$val}{$js_line}\n";
			}
			$ret.="	-->\n"
				."</script>\n";
			return $ret;
		}

		function _condTest($val1,$val2,$test)
		{
			$ret=false;
			switch($test)
			{
				default:
				case "==":
					if($val1==$val2)
						$ret=true;
					break;
				case "===":
					if($val1===$val2)
						$ret=true;
					break;
				case "!=":
					if($val1!=$val2)
						$ret=true;
					break;
				case "!==":
					if($val1!==$val2)
						$ret=true;
					break;
				case ">":
					if($val1>$val2)
						$ret=true;
					break;
				case ">=":
					if($val1>=$val2)
						$ret=true;
					break;
				case "<":
					if($val1<$val2)
						$ret=true;
					break;
				case "<=":
					if($val1<=$val2)
						$ret=true;
					break;
			}
			return $ret;
		}
	}
?>
