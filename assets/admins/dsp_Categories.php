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
<form id="postback" method="post" action="none"></form>
<h1>Categories</h1>
<?
	$width=0;
	$margin = 15;
	//History
	echo "<div class=\"container buttons-right-margin\" style=\"margin-bottom:1em;\">\n";
	if($history)
	{
		for($i=1;$i<count($history);$i++)
		{
			echo "<div style=\"border: 1px solid #EAEAEB;border-bottom:none;padding:10px 20px 10px ", $width + 20 ,"px;\">
					<table style=\"width:100%;\">
						<tr>
							<td><a href=\"{$config['dir']}index.php?fuseaction=admin.categories&amp;category_id={$history[$i]['id']}\"><img src=\"{$config['dir']}images/admin/folder_open.png\" width=\"24\" height=\"21\" alt=\"Open Folder\" /> {$history[$i]['name']}</a></td>
							<td class=\"right\">";
			if($history[$i]['id']>1 && $acl->check("removeCategory"))
			{
				if($acl->check("removeCategory"))
					echo "
						<a class=\"button button-grey right\" title=\"Delete\" href=\"none\" onclick=\"return postbackConf(
							this
							,'removeCategory'
							,['category_id','return']
							,[{$history[$i]['id']},{$history[$i-1]['id']}]
							,'delete'
							,'category')\"><span>Delete</span></a>";
				if($acl->check("moveCategory"))
					echo " <a class=\"button button-grey right\" href=\"#\" title=\"Move\" onclick=\"moveCategory({$history[$i]['id']}); return false;\"><span>Move</span></a>";
			}
			if($acl->check("editCategory"))
				echo " <a class=\"button button-grey right\" title=\"Edit\" href=\"{$config['dir']}index.php?fuseaction=admin.editCategory&amp;category_id={$history[$i]['id']}&amp;parent_id={$_REQUEST['category_id']}\"><span>Edit</span></a>";
				
			echo "
							</td>
						</tr>
					</table>
				</div>\n";
			$width += $margin;
		}
	}
	//$width -= $margin;

	while($row=$children->FetchRow())
	{
		echo "<div style=\"border: 1px solid #EAEAEB;border-bottom:none;padding:10px 20px 10px ", $width + 20 ,"px;\">";


		echo "<table style=\"width:100%;\">
				<tr>";
		if($category->fields['childord']==1 && $acl->check("orderCategory"))
		{
			echo "<td class=\"fit\" style=\"width:32px;\">
					<a href=\"none\" title=\"Move Up\" onclick=\"return postback(
							this
							,'orderCategory'
							,['category_id','parent_id','dir']
							,[{$row['id']},{$_REQUEST['category_id']},'up'])\"><img src=\"{$config['dir']}images/admin/up.png\" width=\"16\" height=\"16\" alt=\"/\\\" /></a><a href=\"none\" title=\"Move Down\" onclick=\"return postback(
							this
							,'orderCategory'
							,['category_id','parent_id','dir']
							,[{$row['id']},{$_REQUEST['category_id']},'down'])\"><img src=\"{$config['dir']}images/admin/down.png\" width=\"16\" height=\"16\" alt=\"\\/\" /></a>";
		}
		echo "<td><a href=\"{$config['dir']}index.php?fuseaction=admin.categories&amp;category_id={$row['id']}\"><img src=\"{$config['dir']}images/admin/folder_closed.png\" width=\"24\" height=\"21\" /> {$row['name']}</a></td>
					<td class=\"right\">";
					
				if($acl->check("removeCategory"))
					echo "<a class=\"button button-grey right\" title=\"Delete\" href=\"none\" onclick=\"return postbackConf(
							this
							,'removeCategory'
							,['category_id','return']
							,[{$row['id']},{$_REQUEST['category_id']}]
							,'delete'
							,'category')\"><span>Delete</span></a>";
				if($acl->check("moveCategory"))
					echo " <a class=\"button button-grey right\" title=\"Move\" href=\"#\" onclick=\"moveCategory({$row['id']}); return false;\"><span>Move</span></a>";
				if($acl->check("editCategory"))
					echo " <a class=\"button button-grey right\" title=\"Edit\" href=\"{$config['dir']}index.php?fuseaction=admin.editCategory&amp;category_id={$row['id']}&amp;parent_id={$_REQUEST['category_id']}\"><span>Edit</span></a>";
				if($acl->check("categoryBoxes") && $row['parent_id'] == 1 && !$row['no_landing_page'])
					echo " <a class=\"button button-grey right\" title=\"Boxes\" href=\"{$config['dir']}index.php?fuseaction=admin.categoryBoxes&amp;category_id={$row['id']}\"><span>Boxes</span></a>";
				if($acl->check("categoryFilters") && !$row['children'] && !$row['link_category_id'])
					echo " <a class=\"button button-grey right\" title=\"Filters\" href=\"{$config['dir']}index.php?fuseaction=admin.categoryFilters&amp;category_id={$row['id']}\"><span>Filters</span></a>";
				
				echo "</td>
				</tr>
			</table>
		</div>\n";
	}
	echo '<div style="border-bottom: 1px solid #EAEAEB;height:0;font-size:1px;"></div>';
	
?>
</div>
<div class="tab-panel-buttons clearfix">
	<? if($acl->check("products")): ?><a class="right button button-grey view" href="<?= $config['dir'] ?>index.php?fuseaction=admin.products&amp;category_id=<?=$_REQUEST['category_id'] ?>"><span>Go to Products</span></a><? endif; ?>
	<? if($acl->check("addCategory")): ?><a class="right button button-small-add add" href="<?= $config['dir'] ?>index.php?fuseaction=admin.addCategory&amp;parent_id=<?=$_REQUEST['category_id'] ?>"><span>Add Category</span></a><? endif; ?>
</div>