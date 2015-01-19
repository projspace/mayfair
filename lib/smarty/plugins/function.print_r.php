<?php
function smarty_function_print_r($params, &$smarty)
{
	echo print_r($params,true);
    //    return print_r($params["obj"],true);
}
?>
