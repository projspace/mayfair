<form id="postback" method="post" action="none"></form>
<h1>Approve Removal</h1>

<form method="post" action="#">
	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Page Details</a></li>
			<? if($page->fields['pagetype']==1) : ?><li><a href="#tabs-2">Content</a></li><? endif; ?>
			<? if($page->fields['pagetype']==1) : ?><li><a href="#tabs-3">Meta Tags</a></li><? endif; ?>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="name">Page Name</label>
				<input type="text" id="name" name="name" value="<?= safe($page->fields['name']) ?>" disabled="disabled" />
			</div>
			<div class="form-field clearfix">
				<label for="layoutid">Layout</label>
				<input type="text" id="name" name="name" value="<?= $page->fields['layout_name'] ?>" disabled="disabled" />
			</div>
			<div class="form-field clearfix">
				<label for="pagetype">Page Type</label>
				<span><?= ($pagetype==1) ? "Normal Page" : "Placeholder"; ?></span>
			</div>
			<div class="form-field clearfix">
				<label for="valid_from">Valid From</label>
				<input type="text" id="valid_from" name="valid_from" value="<?= format_date($page->fields['valid_from']) ?>" disabled="disabled" />
			</div>
			<div class="form-field clearfix">
				<label for="valid_to">Valid To</label>
				<input type="text" id="valid_to" name="valid_to" value="<?= format_date($page->fields['valid_to']) ?>" disabled="disabled" />
			</div>
		</div>
		<? if($page->fields['pagetype']==1) : ?>
		<div id="tabs-2">
			<?
				$sections=explode("\n",trim($layout['sections']));
				$pagecontent=explode("<!--[#content#]-->",$content->fields['content']);
				$count=0;
				foreach($sections as $section)
				{
					echo '<div class="form-field clearfix"><label for="valid_from">Content: '.$section.'</label><br />';
					echo $pagecontent[$count];
					echo '</div>';
					$count++;
				}
			?>
		</div>
		<div id="tabs-3">
			<div class="form-field clearfix">
				<label for="meta_title">META Title</label>
				<input type="text" id="meta_title" name="meta_title" value="<?= safe($content->fields['meta_title']) ?>" disabled="disabled" />
			</div>
			<div class="form-field clearfix">
				<label for="meta_keywords">META Keywords</label>
				<textarea id="meta_keywords" name="meta_keywords" disabled="disabled"><?= safe($content->fields['meta_keywords']) ?></textarea>
			</div>
			<div class="form-field clearfix">
				<label for="meta_description">META Description</label>
				<textarea id="meta_description" name="meta_description" disabled="disabled"><?= safe($content->fields['meta_description']) ?></textarea>
			</div>
		</div>
		<? endif; ?>
	</div>
	<div class="tab-panel-buttons clearfix">
		<span class="button button-small submit">
			<input class="submit" type="button" value="Approve" onclick="return postbackConf(
					this
					,'approveRemove'
					,['pageid','parent_id','act']
					,[<?= $_REQUEST['pageid'] ?>,<?= $_REQUEST['parent_id'] ?>,'approve']
					,'approve'
					,'removal')"/>
		</span>
		<span class="button button-small submit">
			<input class="submit" type="button" value="Reject" onclick="return postbackConf(
					this
					,'approveRemove'
					,['pageid','parent_id','act']
					,[<?= $_REQUEST['pageid'] ?>,<?= $_REQUEST['parent_id'] ?>,'reject']
					,'reject'
					,'removal')"/>
		</span>
		<a class="button button-grey" href="<?=$config['dir'] ?>index.php?fuseaction=admin.pages&amp;parent_id=<?= $_REQUEST['parent_id'] ?>"><span>Cancel</span></a>
	</div>
</form>		