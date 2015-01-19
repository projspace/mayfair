<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty lower modifier plugin
 *
 * Type:     modifier<br>
 * Name:     lower<br>
 * Purpose:  convert string to lowercase
 * @link http://smarty.php.net/manual/en/language.modifier.lower.php
 *          lower (Smarty online manual)
 * @param string
 * @return string
 */
function smarty_modifier_icons($custom)
{
	global $config;
	$custom=unserialize($custom);
	if(is_array($custom['icons']))
	{
    	$keys=array_keys($custom['icons']);
    	$ret="";
    	foreach($keys as $key)
    	{
		$ret.="<li><img src=\"{$config['dir']}layout/templates/partridges/images/icons/{$key}.gif\" alt=\"{$key}\" /></li>";
	}
    }
    return $ret;
}

?>
