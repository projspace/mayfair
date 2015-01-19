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
<?
	//Set proper parent_id
	if(!isset($_REQUEST['parent_id']))
		$parent_id=0;
	else
		$parent_id=$_REQUEST['parent_id'];
?>
<form id="postback" method="post" action="none"></form>
<h1>Pages</h1>

<a href="<?= $config['dir'] ?>index.php?fuseaction=admin.pages">Pages</a> &gt;
<?
	while($row=$trail->FetchRow())
	{
		echo " <a href=\"{$config['dir']}index.php?fuseaction=admin.pages&amp;parent_id={$row['id']}\">{$row['name']}</a> &gt;";
	}
?>
<hr />


<?
	//Advanced layout
	$advanced=$acl->check("advancedView");
	if($advanced):
?>
<script type="text/javascript" src="<?= $config['dir'] ?>lib/treemenu/dtree.js"></script>
<link rel="stylesheet" type="text/css" href="<?= $config['dir'] ?>lib/treemenu/dtree.css"></script>


<div id="col-left">	
	<script type="text/javascript">
	<?
		echo $mptt->makeDTree();
		if($parent_id>0)
			echo "a.openTo({$parent_id}, true);";
	?>
	</script>
</div>


<div id="col-right">
<div id="table">
<? endif; ?>


<table class="values nocheck">
	<thead>
		<tr>
			<th></th>
			<th>Page Name</th>
			<th>Content</th>
			<th style="">&nbsp;</th>
		</tr>
	</thead>
	<tbody id="sortme">
		<?
			while($row=$pages->FetchRow())
			{
				if($class=="light")
					$class="dark";
				else
					$class="light";

				if($row['pendingedit']==1)
					$class="changed";
				if($row['pendingremove']==1)
					$class="removed";
				if($row['pendingadd']==1)
					$class="added";

				echo "<tr class=\"$class\">";

				//Page Ordering (up/down)
				if($acl->check("orderPage"))
				{
					echo "<td class=\"thinButton\">
							<a title=\"Move Up\" onclick=\"return postback(
								this
								,'orderPage'
								,['pageid','parent_id','dir']
								,[{$row['id']},{$parent_id},'up'])\"><img src=\"{$config['dir']}images/admin/up.png\" width=\"16\" height=\"16\" alt=\"Move Up\" /></a>
							<a title=\"Move Down\" onclick=\"return postback(
								this
								,'orderPage'
								,['pageid','parent_id','dir']
								,[{$row['id']},{$parent_id},'down'])\"><img src=\"{$config['dir']}images/admin/down.png\" width=\"16\" height=\"16\" alt=\"Move Down\" /></a>
						</td>";
				}
				else
					echo "<td class=\"thin\">&nbsp;</td>";

				//Main Display
				echo "<td><a href=\"{$config['dir']}index.php?fuseaction=admin.editPage&pageid={$row['id']}&parent_id=".($_REQUEST['parent_id']+0)."\">".truncate($row['name'],50)."</a></td>
						<td>".truncate($row['content'],50)."</td>
						<td>";

				$options = array();
				$actions = array();
				
				if($acl->check("approveAdd") && $row['pendingadd'])
				{
					$options[] = "<option value=\"pending-add\">Pending Add</option>";
					$actions[] = "<a class=\"pending-add\" title=\"Pending Add\" href=\"{$config['dir']}index.php?fuseaction=admin.approveAdd&amp;pageid={$row['id']}&amp;parent_id={$parent_id}\">Pending Add</a>\n";
				}
				
				if($acl->check("approveEdit") && $row['pendingedit'])
				{
					$options[] = "<option value=\"pending-edit\">Pending Edit</option>";
					$actions[] = "<a class=\"pending-edit\" title=\"Pending Edit\" href=\"{$config['dir']}index.php?fuseaction=admin.approveEdit&amp;pageid={$row['id']}&amp;parent_id={$parent_id}\">Pending Edit</a>\n";
				}
				
				if($acl->check("approveRemove") && $row['pendingremove'])
				{
					$options[] = "<option value=\"pending-remove\">Approve removal</option>";
					$actions[] = "<a class=\"pending-remove\" title=\"Approve removal\" href=\"{$config['dir']}index.php?fuseaction=admin.approveRemove&amp;pageid={$row['id']}&amp;parent_id={$parent_id}\">Approve removal</a>\n";
				}
				
				if($acl->check("rollback") && $row['max_revision']>1)
				{
					$options[] = "<option value=\"rollback\">Rollback</option>";
					$actions[] = "<a class=\"rollback\" title=\"Rollback\" href=\"{$config['dir']}index.php?fuseaction=admin.rollback&amp;pageid={$row['id']}&amp;parent_id={$parent_id}\">Rollback</a>\n";
				}
				
				if($acl->check("pageImages"))
				{
					$options[] = "<option value=\"images\">Images</option>";
					$actions[] = "<a class=\"images\" title=\"Images\" href=\"{$config['dir']}index.php?fuseaction=admin.pageImages&amp;pageid={$row['id']}&amp;parent_id={$parent_id}\">Images</a>\n";
				}
				
				if($acl->check("pageLayout"))
				{
					$options[] = "<option value=\"layout\">Page Layout</option>";
					$actions[] = "<a class=\"layout\" title=\"Page Layout\" href=\"{$config['dir']}index.php?fuseaction=admin.pageLayout&amp;pageid={$row['id']}&amp;parent_id={$parent_id}\">Page Layout</a>\n";
				}
				
				if($acl->check("removePage"))
				{
					$options[] = "<option value=\"delete\">Delete</option>";
					$actions[] = "<a class=\"icon-button delete\" title=\"Delete\" onclick=\"return postbackConf(
									this
									,'removePage'
									,['pageid','parent_id']
									,[{$row['id']},{$parent_id}]
									,'delete'
									,'page')\">Delete</a>\n";
				}
				
				if($acl->check("movePage"))
				{
					$options[] = "<option value=\"move\">Move</option>";
					$actions[] = "<a class=\"icon-button move\" title=\"Move\" onclick=\"popup_move({$row['id']},{$parent_id}); return false;\" href=\"{$config['dir']}index.php?fuseaction=admin.movePage&amp;pageid={$row['id']}&amp;parent_id={$parent_id}\">Move</a>\n";
				}
				
				if($acl->check("editPage"))
				{
					$options[] = "<option value=\"edit\">Edit</option>";
					$actions[] = "<a class=\"edit\" title=\"Edit\" href=\"{$config['dir']}index.php?fuseaction=admin.editPage&amp;pageid={$row['id']}&amp;parent_id={$parent_id}\">Edit</a>\n";
				}
				
				echo "<select class=\"custom-skin row-actions\"><option value=\"\">Select Action</option>".implode("", $options)."</select>";
				echo "<div style=\"display:none;\">".implode("", $actions)."</div>";
				
				echo "</tr>";
			}
		?>
	</tbody>
</table>
<!--
<script type="text/javascript">
	Sortable.create('sortme',{tag:'tr'});
</script>
-->
<? if($advanced): ?>
</div></div><br class="clear" />
<? endif; ?>


<? if($acl->check("addPage")): ?>
<div class="right">
	<a class="button button-small-add add" href="<?= $config['dir'] ?>index.php?fuseaction=admin.addPage&amp;parent_id=<?= intval($parent_id) ?>"><span>Add Page</span></a>
</div>
<? endif; ?>

<script type="text/javascript">
	$(function(){
		$('select.row-actions').each(function(){
			$this = $(this);
			var buttons = $this.parent();
			$this.change(function(){
				if( this.value ) {
					var button = buttons.find('.' + this.value);
					var node = button.get(0);
					if( node.nodeName.toLowerCase() == 'a' && !node.onclick ) {
						window.location = node.href;
					} else {
						button.attr('onclick').call(node);
					}
				}
			});
		});
	});
</script>