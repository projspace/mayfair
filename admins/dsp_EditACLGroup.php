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
<h1>Edit Access Group</h1>
<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.editACLGroup&amp;act=update">

	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="name">Group Name</label>
				<input type="text" id="name" name="name" value="<?= $group['name'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="description">Group Description</label>
				<textarea name="description" id="description" rows="5" cols="40"><?= $group['description'] ?></textarea>
			</div>
			
			<?
				$category_id=0;
				while($row=$actions->FetchRow())
				{
					if($row['category_id']!=$category_id)
					{
						if($category_id!=0)
							echo "</table></div>";
						echo "
						<div class=\"form-field clearfix\">
						
						<label for=\"description\">{$row['category_name']}</label>
							
								<table class=\"values nocheck\" style=\"width:200px;clear:none;\">";
						$category_id=$row['category_id'];
						$class="";
					}
					if($class=="dark")
						$class="light";
					else
						$class="dark";
					echo "<tr>
							<td class=\"{$class} thin\"><input type=\"checkbox\" id=\"acl_action_{$row['id']}\" name=\"acl_action[]\" value=\"{$row['id']}\"";
					if($row['group_action_id']!="")
						echo " checked=\"checked\"";
					echo " /></td>
							<td class=\"{$class}\"><label for=\"acl_action_{$row['id']}\">{$row['name']}</label></td>
						</tr>";
				}
				if($category_id!=0)
					echo "</table>
						</div>";
			?>
			
		</div>
		<div class="tab-panel-buttons clearfix">
			<span class="button button-small submit">
				<input class="submit" type="submit" value="Continue" />
				<input type="hidden" name="group_id" value="<?= $group['id'] ?>" />
			</span>
		</div>
	</div>

</form>