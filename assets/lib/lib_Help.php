<?
	/**
	 * e-Commerce System
	 * Copyright (c) 2002-2006 Philip John, All Rights Reserved.
	 * Author	: Philip John
	 * Version	: 6.0
	 *
	 * PROPRIETARY/CONFIDENTIAL.  Use is subject to license terms.
	 */
?>
<script language="javascript" type="text/javascript" src="lib/treemenu/TreeMenu.js"></script>
<?
	include("treemenu/TreeMenu.php");
	function dispContents($contents,$keys,$parent_id,$level,$id)
	{
		global $config;
		$icon="folder.gif";
		$expandedIcon="folder-expanded.gif";
		$menu=new HTML_TreeMenu();
		$nrows=count($contents);

		for($i=0;$i<$nrows;$i++)
		{
			if($contents[$i][$keys['util_help.parent_id']]==$parent_id)
			{
				if($id==$contents[$i][$keys['util_help.id']])
				{
					$visible=true;
					$text="<b>".$contents[$i][$keys['util_help.name']]."</b>";
				}
				else
				{
					$visible=false;
					$text=$contents[$i][$keys['util_help.name']];
				}
				$nodes[$i]=new HTML_TreeNode(array('text' => $text,
                                     'link' => $config['dir']."index.php?fuseaction=admin.help&id=".$contents[$i][$keys['util_help.id']],
                                     'icon' => $icon,
                                     'cssClass' => 'menu',
                                     'expandedIcon' => $expandedIcon,
                                     'ensureVisible' => $visible));
				$menu->addItem(recurseContents($contents,$keys,$contents[$i][$keys['util_help.id']],$nodes[$i],$id));
			}
		}

		$treeMenu=new HTML_TreeMenu_DHTML($menu, array('images' => 'images/help'));
		$treeMenu->printMenu();
	}

	function recurseContents($contents,$keys,$parent_id,$node,$id)
	{
		$nrows=count($contents);
		$icon="document.gif";
		for($i=0;$i<$nrows;$i++)
		{
			if($contents[$i][$keys['util_help.parent_id']]==$parent_id)
			{
				if($id==$contents[$i][$keys['util_help.id']])
				{
					$visible=true;
					$text="<b>".$contents[$i][$keys['util_help.name']]."</b>";
				}
				else
				{
					$visible=false;
					$text=$contents[$i][$keys['util_help.name']];
				}
				$nodes[$i]=new HTML_TreeNode(array('text' => $text,
                                     'link' => $config['dir']."index.php?fuseaction=admin.help&id=".$contents[$i][$keys['util_help.id']],
                                     'cssClass' => 'menu',
                                     'icon' => $icon,
                                     'ensureVisible' => $visible));
				$nodes[$i]=recurseContents($contents,$keys,$contents[$i][$keys['util_help.id']],$nodes[$i],$id);
				$node->addItem($nodes[$i]);
			}
		}
		return $node;
	}
?>