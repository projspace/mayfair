<?
	function smarty_function_validator_script($params, &$smarty)
	{
		global $validator;
		
		return $validator->clientValidate();
	}
?>