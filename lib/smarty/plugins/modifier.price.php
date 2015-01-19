<?php
	function smarty_modifier_price($price)
	{
		return number_format($price,2,".","");
	}
?>