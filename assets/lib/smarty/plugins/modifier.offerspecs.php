<?php
	function smarty_modifier_offerspecs($specs)
	{
		$specs=unserialize($specs);

		$num=min(count($specs['value']),4);
		if($num>0)
		{
			echo "<ul>";
			for($i=0;$i<$num;$i++)
			{
				echo "<li>".$specs['value'][$i]."</li>";
			}
			echo "</ul>";
		}
	}
?>