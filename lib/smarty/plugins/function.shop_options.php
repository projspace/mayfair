<?
	function smarty_function_shop_options($params, &$smarty)
	{
		$name=$params["value"];
		$price=$params["price"];
		$base=$params["base"];
		$num=max(count($name),count($price));
		$ret="";
		for($i=0;$i<$num;$i++)
		{
			if($price[$i]=="")
				$price[$i]=0;

			$p=trim($price[$i]);
			$n=trim($name[$i]);
			$ret=$ret."<option value=\"$i\"";
			if($params["selected"]==$i)
				$ret=$ret." selected=\"selected\"";
			$ret=$ret.">".$n;
			if($p<0)
				$ret=$ret." (- &pound;".number_format($p,2).")";
			else if($p==0)
				$ret=$ret;
			else
				$ret=$ret." (+ &pound;".number_format($p,2).")";
			$ret=$ret."</option>";
		}
		return $ret;
	}
?>