<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	function filter_submit(){	
		var html = '';
		html += '<input type="hidden" name="fuseaction" value="admin.addSimilarProduct"/>';
		html += '<input type="hidden" name="product_id" value="<?=$_REQUEST['product_id'] ?>"/>';
		html += '<input type="hidden" name="category_id" value="<?=$_REQUEST['category_id'] ?>"/>';
		html += '<input type="hidden" name="filter[category_id]" value="'+$(this).val()+'"/>';
		html += '<input type="hidden" name="filter[keyword]" value="'+$('#filter_keyword').val()+'"/>';
		$('#postback').html(html).attr({'action':'<?=$config['dir'] ?>index.php', 'method':'get'}).submit();
	}
	$(document).ready(function(){
		$('#filter_category_id').change(filter_submit);
		$('#filter_keyword').keydown(function(event){
			if(event.keyCode == 13)
			{
				filter_submit();
				return false;
			}
		});
	});
/* ]]> */
</script>

<form id="postback" method="post" action="none"></form>
<h1>Add Product</h1>

<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.addSimilarProduct&amp;product_id=<?=$_REQUEST['product_id'] ?>&amp;category_id=<?=$_REQUEST['category_id'] ?>&amp;act=add">
	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
		</ul>
		<div id="tabs-1">
			<div class="filters dashboard-filters clearfix">
				<label>
					Category
					<select id="filter_category_id">
						<option value="">Please Select</option>
					<?
						while($row = $categories->FetchRow())
						{
							$name = array();
							foreach((array)unserialize($row['trail']) as $key=>$item)
							{
								if($key < 2)
									continue;
								$name[] = $item['name'];
							}
							//$name[] = $row['name'];
							if($row['id'] == $_REQUEST['filter']['category_id'])
								echo '<option value="'.$row['id'].'" selected="selected">'.implode(' &gt; ', $name).'</option>';
							else
								echo '<option value="'.$row['id'].'">'.implode(' &gt; ', $name).'</option>';
						}
					?>
					</select>
				</label>
				<label>
					Keyword
					<input type="text" class="text medium" value="<?=$_REQUEST['filter']['keyword'] ?>" id="filter_keyword" />
				</label>
			</div>
			<div class="form-field clearfix">
				<label for="name">Name</label>
				<input type="text" id="name" name="name" value="" />
			</div>
			<table class="values nocheck">
				<tr>
					<th></th>
					<th>Name</th>
					<th>Style</th>
				</tr>
			<?
				while($row=$products->FetchRow())
				{
					if($class=="light")
						$class="dark";
					else
						$class="light";

					echo "<tr class=\"$class\">
						<td><input type=\"checkbox\" name=\"product_ids[]\" value=\"{$row['id']}\" /></td>
						<td>{$row['name']}</td>
						<td>{$row['code']}</td>
					</tr>";
				}
			?>
			</table>
		</div>
	</div>
	<div class="tab-panel-buttons clearfix">
		<span class="button button-small submit">
			<input class="submit" type="submit" value="Continue" />
		</span>
		<a class="button button-grey" href="<?= $config['dir'] ?>index.php?fuseaction=admin.similarProducts&category_id=<?=$_REQUEST['category_id'] ?>&product_id=<?=$_REQUEST['product_id'] ?>"><span>Cancel</span></a>
	</div>
</form>		