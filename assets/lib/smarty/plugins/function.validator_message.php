<?
	function smarty_function_validator_message($params, &$smarty)
	{
		global $validator;
		
		return $validator->displayMessage();
	}
?>