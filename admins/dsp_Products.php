<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

<script>
var initial_order = [];
$(function() {
	$( "#sortable tbody" ).sortable({
		handle: '.drag'
		,start: function(event, ui) {
            ui.item.data('start_pos', ui.item.index());
        }
		,update: function(event, ui){
			var start_pos = ui.item.data('start_pos');
            var index = ui.item.index();
			$.ajax({
				url: '<?=$config['dir'] ?>index.php?fuseaction=admin.sortProduct&item_id='+ui.item.find('.drag').attr('item_id')+'&category_id=<?=$_REQUEST['category_id'] ?>&steps='+(index-start_pos),
				type: 'get',
			});
		}
	});
	$( "#sortable tbody" ).disableSelection();
});
</script>

<form id="postback" method="post" action="none"></form>
<form method="get" action="<?=$config['dir'] ?>index.php">
	<input type="hidden" name="fuseaction" value="admin.productSearch" />
	<input type="hidden" name="category_id" value="<?=$_GET['category_id'] ?>" />
<h1>
	<span style="float: right; margin-top: 5px;">
		<label style="width: auto; margin-right: 10px;">Name/ Style</label>
		<input type="text" name="keyword" value="<?=$_GET['keyword'] ?>"/>
	</span>
	Products
</h1>	
</form>
<?
	$width=0;
	//History
	echo "<div id=\"col-left\" class=\"\">\n";
	
	/**
	 * [ACL:addProduct]
	 */
	if($acl->check("addProduct")):

	?><div class="buttons clearfix">
		<a class="button button-small-add add" href="<?= $config['dir'] ?>index.php?fuseaction=admin.addProduct&amp;category_id=<?= $_REQUEST['category_id']; ?>"><span>Add Product</span></a>
	</div><?
	
	endif;
	
	if($history)
	{
		for($i=1;$i<count($history);$i++)
		{
			echo "<div style=\"margin-left: {$width}px;margin-bottom:0.5em;\">
					<a href=\"{$config['dir']}index.php?fuseaction=admin.products&amp;category_id={$history[$i]['id']}\" class=\"white\"><img src=\"{$config['dir']}images/admin/folder_open.png\" width=\"24\" height=\"21\" alt=\"Open Folder\" /> {$history[$i]['name']}</a>
				</div>\n";
			$width+=16;
		}
	}

	//Child categories
	while($row=$children->FetchRow())
	{
		echo "<div style=\"margin-left: {$width}px; margin-bottom:0.5em;\">
				<a class=\"white\" href=\"{$config['dir']}index.php?fuseaction=admin.products&amp;category_id={$row['id']}\"><img src=\"{$config['dir']}images/admin/folder_closed.png\" width=\"24\" height=\"21\" alt=\"Close Folder\" /> {$row['name']}</a>
			</div>\n";
	}
	echo "</div>\n<div id=\"col-right\" class=\"\">\n";

	$nprods=$products->RecordCount();
	$nrefs=$refs->RecordCount();
	if($nprods>0 || $nrefs>0)
	{
		?>
		<!--<form id="" method="get" action="<?=$config['dir'] ?>index.php">
			<input type="hidden" name="fuseaction" value="admin.products"/>
			<div class="bulk-actions-container" style="width: 410px;">
				<span style="float: left; margin: 5px 5px 0 0;">Name/PLU:</span>
				<input type="text" id="keyword" name="keyword" value="<?=$_GET['keyword'] ?>" style="margin-right: 5px;" />
				<span class="button button-small submit">
					<input class="submit" type="submit" value="GO" />
				</span>
			</div>
		</form>-->
		<?
		
		/*echo '
			<form method="get" action="'.$config['dir'].'index.php">
				<input type="hidden" name="fuseaction" value="admin.products" />
				<input type="hidden" name="category_id" value="'.$_GET['category_id'].'" />
				<div class="bulk-actions-container float-left" style="width: 400px;">
					<label style="width: auto; margin-right: 10px;">Name/ Style</label>
					<input type="text" name="keyword" value="'.$_GET['keyword'].'"/>
				</div>
			</form>';*/
		
		/**
		 * [ACL:massMove]
		 * [ACL:massDelete]
		 */
		if($acl->check("massMove") || $acl->check("massDelete"))
			echo "<form id=\"postback_multi\" method=\"post\" action=\"none\"><input type=\"hidden\" name=\"category_id\" value=\"{$category->fields['id']}\"/> ";
		
		echo '<div class="bulk-actions-container float-right">';
		/**
		 	 * [ACL:massMove]
		 	 */
			if($acl->check("massMove"))
				echo "<a href=\"\" class=\"button button-grey move\" onclick=\"return postbackMulti(
							this
							,'massMove')\"><span>Move</span></a>";
			/**
		 	 * [ACL:massDelete]
		 	 */
			if($acl->check("massDelete"))
				echo "<a href=\"\" class=\"button button-grey delete\" style=\"margin-right:10px;\" onclick=\"return postbackMulti(
							this
							,'massDelete')\"><span>Delete</span></a>";
		echo '</div>';
		
		echo "<table class=\"products values nocheck\" id=\"sortable\">";

		/**
		 * Mass move/mass delete buttons
		 * [ACL:massMove]
		 * [ACL:massDelete]
		 */
		if($acl->check("massMove") || $acl->check("massDelete"))
		{
			echo "<tr>";
			if($category->fields['productord']=='manual' && $acl->check("orderProduct"))
				echo "<th class=\"dark fit\">&nbsp;</th>";
			echo "
					<th class=\"dark thin\" style=\"width: 15px;\">
						<input class=\"nb\" type=\"checkbox\" id=\"all\" onclick=\"changeState(
							this
							,['product','ref']
							,[{$nprods},{$nrefs}])\" />
					</th>
					<th>Details</th>
					<th style=\"width:50px;\">Price</th>
					<th style=\"width:50px;\">Stock</th>
					<th style=\"width:150px;\"></th>
					";
			
			echo "</th>
				</tr>";
		}
	}
	if($nprods>0)
	{
		$count=0;
		while($row=$products->FetchRow())
		{
			$class = $row['parent_id']?' class="highlight"':'';
			echo '<tr'.$class.'>';
			//Move up and down buttons
			if($category->fields['productord']=='manual' && $acl->check("orderProduct"))
			{
				echo "<td class=\"fit\" style=\"width: 20px;\">
					<a href=\"none\" class=\"drag\" item_id=\"{$row['id']}\" title=\"Drag & Drop\" onclick=\"return false;\"><img src=\"{$config['dir']}images/admin/sort.png\" width=\"16\" height=\"16\" alt=\"/\\\" /></a>
					</td>";
				/*echo "<td class=\"dark fit\"> 
						<a href=\"none\" title=\"Move Up\" onclick=\"return postback(
							this
							,'orderProduct'
							,['product_id','category_id','dir']
							,[{$row['id']},{$_REQUEST['category_id']},'up'])\"><span style=\"width:16px;height:16px;background:url({$config['dir']}images/admin/up.png);display:block;float:left;\" ></span></a><a href=\"none\" title=\"Move Down\" onclick=\"return postback(
							this
							,'orderProduct'
							,['product_id','category_id','dir']
							,[{$row['id']},{$_REQUEST['category_id']},'down'])\"><span style=\"width:16px;height:16px;background:url({$config['dir']}images/admin/down.png);display:block;float:left;\" ></span></a></td>";*/
			}

			/**
			 * [ACL:massMove]
			 * [ACL:massDelete]
			 */
			if($acl->check("massMove") || $acl->check("massDelete"))
				echo "<td class=\"dark thin\"><input class=\"nb\" type=\"checkbox\" id=\"product_{$count}\" name=\"product[]\" value=\"{$row['id']}\" /></td>";
			echo "<td class=\"dark thin\">
						<!--<div class=\"product-icon\"><span style=\"width:17px;height:20px;background:url({$config['dir']}images/admin/".(($row['parent_id']==0) ? "product" : "copy").".png);display:block;\" ></span></div>-->
						<strong class=\"product-name\"><a href=\"{$config['dir']}index.php?fuseaction=admin.editProduct&amp;category_id={$_REQUEST['category_id']}&amp;product_id=".(($row['parent_id']==0) ? $row['id'] : $row['parent_id'])."\">{$row['name']}</a></strong>
						<div class=\"product-code\">{$row['code']}</div>
						<!--<div class=\"product-description\">".truncate($row['description'],50)."...</div>-->
					</td>
					<td class=\"dark right\"><strong>".price($row['price'])."</strong></td>
					<td class=\"dark right\"><strong>{$row['stock']}</strong></td>
					<td>";
					
					echo "<select class=\"custom-skin row-actions\">";
					
						echo "<option value=\"\">Select Action</option>";
						
						if($acl->check("makeCopy")) {
							echo "<option value=\"make-copy\">Make Copy</option>";
						}
						if($acl->check("makeReference")) {
							echo "<option value=\"make-reference\">Make Reference</option>";
						}
						if($row['parent_id']>0 && $acl->check("unlinkCopy"))
						{
							echo "<option value=\"unlink-copy\">Unlink Copy</option>";					
						}
						if($acl->check("similarProducts")) {
							echo "<option value=\"product-you-might-like\">You Might Like</option>";					
						}
						//if($acl->check("productReviews")) {
						//	echo "<option value=\"reviews\">Reviews</option>";
						//}
						if($row['parent_id']==0) {
							echo "<option value=\"delete\">Delete</option>";					
						} else {
							if($acl->check("deleteCopy")) {
								echo "<option value=\"delete-copy\">Delete</option>";					
							}
						}
						if($acl->check("productState")) {
							echo "<option value=\"product-state\">".(($row['hidden']==1) ? "Show" : "Hide")."</option>";					
						}
						if($acl->check("move")) {
							echo "<option value=\"move\">Move</option>";					
						}
						if($acl->check("editProduct")) {
							echo "<option value=\"edit\">Edit</option>";					
							echo "<option value=\"edit_full\">Edit Full Page</option>";					
						}
			echo "</select>
					
					
					
					<div style=\"display:none;\">
					";
			/**
		 	 * [ACL:makeCopy]
		 	 */
			if($acl->check("makeCopy"))
				echo "<a class=\"icon-button make-copy\" title=\"Make Copy\" onclick=\"return postback(
							this
							,'makeCopy'
							,['category_id','product_id']
							,[{$_REQUEST['category_id']},{$row['id']}])\">copy</a>";
			/**
		 	 * [ACL:makeReference]
		 	 */
			if($acl->check("makeReference"))
				echo "<a class=\"icon-button make-reference\" title=\"Make Reference\" onclick=\"return postback(
							this
							,'makeReference'
							,['category_id','product_id']
							,[{$_REQUEST['category_id']},{$row['id']}])\">reference</a>";
			/**
		 	 * [ACL:unlinkCopy]
		 	 */
			if($row['parent_id']>0 && $acl->check("unlinkCopy"))
			{
				echo "
				<a class=\"icon-button unlink-copy\" title=\"Unlink Copy\" onclick=\"return postbackConf(
					this
					,'unlinkCopy'
					,['category_id','product_id']
					,[{$_REQUEST['category_id']},{$row['id']}]
					,'unlink'
					,'copy')\">unlink</a>\n";
			}
			echo "";
			if($acl->check("similarProducts"))
				echo "<a class=\"icon-button product-you-might-like\" title=\"Product You Might Like\" href=\"{$config['dir']}index.php?fuseaction=admin.similarProducts&amp;category_id={$_REQUEST['category_id']}&amp;product_id=".$row['id']."\">similar</a>\n";
			//if($acl->check("productReviews"))
			//	echo "<a class=\"icon-button reviews\" title=\"Reviews\" href=\"{$config['dir']}index.php?fuseaction=admin.productReviews&amp;category_id={$_REQUEST['category_id']}&amp;product_id=".$row['id']."\">reviews</a>\n";
			echo '<br/>';
			//Not a linked copy
			if($row['parent_id']==0)
			{
				/**
			 	 * [ACL:deleteProduct]
			 	 */
				if($acl->check("deleteProduct"))
					echo "<a class=\"icon-button delete\" title=\"Delete\" onclick=\"return postbackConf(
							this
							,'deleteProduct'
							,['category_id','product_id']
							,[{$_REQUEST['category_id']},{$row['id']}]
							,'delete'
							,'product')\">delete product</a>\n";
			}
			else
			{
				/**
			 	 * [ACL:deleteCopy]
			 	 */
				if($acl->check("deleteCopy"))
					echo "<a class=\"icon-button delete-copy\" title=\"Delete\" onclick=\"return postbackConf(
							this
							,'deleteCopy'
							,['category_id','product_id']
							,[{$_REQUEST['category_id']},{$row['id']}]
							,'delete'
							,'copy')\">delete copy</a>\n";
			}
			/**
		 	 * [ACL:productState]
		 	 */
			if($acl->check("productState"))
				echo "<a class=\"icon-button product-state\" title=\"".(($row['hidden']==1) ? "Show" : "Hide")."\" onclick=\"return postbackConf(
						this
						,'productState'
						,['category_id','product_id','hidden']
						,[{$_REQUEST['category_id']},{$row['id']},".(($row['hidden']==1) ? 0 : 1)."]
						,'".(($row['hidden']==1) ? "show" : "hide")."'
						,'".(($row['parent_id']==0) ? "product" : "copy")."')\">visible/hidden</a>\n";
			/**
		 	 * [ACL:move]
		 	 */
			if($acl->check("move"))
				echo "<a class=\"move\" title=\"Move\" onclick=\"moveProduct({$row['id']},0,{$_REQUEST['category_id']})\">move</a>\n";
			/**
		 	 * [ACL:editProduct]
		 	 */
			if($acl->check("editProduct"))
			{
				echo "<a class=\"edit\" title=\"Edit\" href=\"{$config['dir']}index.php?fuseaction=admin.editProduct&amp;category_id={$_REQUEST['category_id']}&amp;product_id=".(($row['parent_id']==0) ? $row['id'] : $row['parent_id'])."\">edit</a>\n";
				echo "<a class=\"edit_full\" title=\"Edit\" href=\"{$config['dir']}index.php?fuseaction=admin.editProduct&amp;category_id={$_REQUEST['category_id']}&amp;full=1&amp;product_id=".(($row['parent_id']==0) ? $row['id'] : $row['parent_id'])."\">edit</a>\n";
			}
			echo "</div>";
			echo "</td>
				</tr>";
			$count++;
		}
	}
	if($nrefs>0)
	{
		$count=0;
		while($row=$refs->FetchRow())
		{
			echo "<tr>";
			/**
			 * [ACL:massMove]
			 * [ACL:massDelete]
			 */
			if($category->fields['productord']=='manual' && $acl->check("orderProduct"))
				echo "<td class=\"light fit\">&nbsp;</td>";
			if($acl->check("massMove") || $acl->check("massDelete"))
				echo "<td class=\"light thin\"><input class=\"nb\" type=\"checkbox\" id=\"ref_{$count}\" name=\"ref[]\" value=\"{$row['id']}\" /></td>";
			echo "<td class=\"dark thin\">
						<div class=\"product-icon\"><span style=\"width:17px;height:20px;background:url({$config['dir']}images/admin/".(($row['parent_id']==0) ? "product" : "copy").".png);display:block;\" ></span></div>
						<strong class=\"product-name\">{$row['name']}</strong>
						<div class=\"product-code\">{$row['code']}</div>
						<div class=\"product-description\">".truncate($row['description'],50)."...</div>
					</td>
					<td class=\"dark right\"><strong>".price($row['price'])."</strong></td>
					<td class=\"dark right\"><strong>{$row['stock']}</strong></td>
					<td>
					
					<select class=\"custom-skin row-actions\">";
						echo "<option value=\"\">Select Action</option>";
						
						if($acl->check("deleteReference")) {
							echo "<option value=\"delete\">Delete</option>";					
						}
						if($acl->check("move")) {
							echo "<option value=\"move\">Move</option>";					
						}
						if($acl->check("editProduct")) {
							echo "<option value=\"edit\">Edit</option>";					
						}
					echo "</select>";
					echo "<div style=\"display:none;\">";
			/**
		 	 * [ACL:deleteReference]
		 	 */
			if($acl->check("deleteReference"))
				echo "<a class=\"icon-button delete\" onclick=\"return postbackConf(
							this
							,'deleteReference'
							,['category_id','referenceid']
							,[{$_REQUEST['category_id']},{$row['id']}]
							,'delete'
							,'reference')\">delete reference</a>\n";
			/**
		 	 * [ACL:move]
		 	 */
			if($acl->check("move"))
				echo "<a title=\"Move\" class=\"move\" onclick=\"moveProduct(0,{$row['id']},{$_REQUEST['category_id']})\"><img src=\"{$config['dir']}images/admin/move.png\" width=\"16\" height=\"16\" alt=\"Move\" border=\"0\" /></a>\n";
			/**
		 	 * [ACL:editProduct]
		 	 */
			if($acl->check("editProduct"))
				echo "<a title=\"Edit\" class=\"edit\" href=\"{$config['dir']}index.php?fuseaction=admin.editProduct&amp;category_id={$_REQUEST['category_id']}&amp;product_id={$row['product_id']}\"><img src=\"{$config['dir']}images/admin/edit.png\" width=\"16\" height=\"16\" alt=\"Edit\" border=\"0\" /></a>\n";
			echo "</div></td>
				</tr>";
			$count++;
		}
	}
	if($nprods>0 || $nrefs>0)
	{
		echo "</table>";
		/**
		 * [ACL:massMove]
		 * [ACL:massDelete]
		 */
		if($acl->check("massMove") || $acl->check("massDelete"))
			echo "</form>";
	}

?>

<?
	/**
	 * [ACL:categories]
	 */
	if($acl->check("categories")):
?>
<div class="bulk-actions-container right">
	<a class="button button-grey view right" href="<?= $config['dir'] ?>index.php?fuseaction=admin.categories&amp;category_id=<?= $_REQUEST['category_id']; ?>"><span>Go to Category</span></a>
</div>
<? endif; ?>


</div>
<style type="text/css">
	.highlight td strong.product-name{
		color:#Fa0 !important; font-style:italic;
	}
</style>
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
