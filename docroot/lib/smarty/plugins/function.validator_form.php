<?
	function smarty_function_validator_form($params, &$smarty)
	{
		global $validator;
		
		return $validator->form();
	}
?>