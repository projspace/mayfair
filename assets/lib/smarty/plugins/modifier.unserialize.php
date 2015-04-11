<?php
	function smarty_modifier_unserialize($var)
	{
		return unserialize($var);
	}
?>