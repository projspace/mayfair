<?
	function smarty_function_validator($params, &$smarty)
	{
		global $validator;
		
		$type=$params['type'];
		
		switch($type)
		{
			case "required":
				$validator->addRequired($params['field'],$params['name']);
				break;
			case "compare":
				$validator->addCompare($params['field'],$params['name'],$params['field2'],$params['name2']);
				break;
			case "regex":
				$validator->addRegex($params['field'],$params['name'],$params['regex']);
				break;
			case "range":
				$validator->addRange($params['field'],$params['name'],$params['from'],$params['to']);
				break;
		}
		return $validator->display($params['field']);
	}
?>