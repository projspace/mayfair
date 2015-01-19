<?
	function smarty_function_country_options($params, &$smarty)
	{
		$values=$params["values"];
		$selected=$params["selected"];
		$ret="";
		foreach($values as $value)
		{
			$ret.="<option value=\"".$value['id']."\"";
			if($value['id']==$selected)
				$ret.=" selected=\"selected\"";
			$ret.=">".$value['name']."</option>";
		}
		return $ret;
	}
?>