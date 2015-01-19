<form id="postback" method="post" action="none"></form>
<h1>Approve Addition</h1><hr />

<script type="text/javascript">var wysiwyg=false;</script>
<script type="text/javascript" src="<?= $config['dir'] ?>VLib/js/lib_MultiTabs.js"></script>

<style type="text/css">@import url(<?= $config['dir'] ?>css/calendar-blue.css);</style>
<script type="text/javascript" src="<?= $config['dir'] ?>lib/calendar/calendar.js"></script>
<script type="text/javascript" src="<?= $config['dir'] ?>lib/calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="<?= $config['dir'] ?>lib/calendar/calendar-setup.js"></script>

<div id="tabpane">

<div class="legend">Page Details</div>
<div class="form">
	<label for="name">Page Name</label>
	<input type="text" id="name" name="name" value="<?= safe($page->fields['name']) ?>" disabled="disabled" /><br />

	<label for="layoutid">Layout</label>
	<input type="text" id="name" name="name" value="<?= $page->fields['layout_name'] ?>" disabled="disabled" /><br />

	<label for="pagetype">Page Type</label>
	<span><?= ($pagetype==1) ? "Normal Page" : "Placeholder"; ?></span><br />

	<label for="valid_from">Valid From</label>
	<input type="text" id="valid_from" name="valid_from" value="<?= format_date($page->fields['valid_from']) ?>" disabled="disabled" /><br />

	<label for="valid_to">Valid To</label>
	<input type="text" id="valid_to" name="valid_to" value="<?= format_date($page->fields['valid_to']) ?>" disabled="disabled" /><br />
</div>

<? if($page->fields['pagetype']==1) : ?>
<div class="legend">Content</div>
<div class="form">
<?
	$sections=explode("\n",trim($layout['sections']));
	$pagecontent=explode("<!--[#content#]-->",$content->fields['content']);
	$count=0;
	foreach($sections as $section)
	{
		echo "<div class=\"legend\">Content: {$section}</div>
				<div class=\"form\">";
		echo $pagecontent[$count];
		echo "</div>\n";
		$count++;
	}
?>
</div>
<? endif; ?>

<? if($page->fields['pagetype']==1) : ?>
<div class="legend">Meta Tags</div>
<div class="form">
	<label for="meta_title">META Title</label>
	<input type="text" id="meta_title" name="meta_title" value="<?= safe($content->fields['meta_title']) ?>" disabled="disabled" /><br />

	<label for="meta_keywords">META Keywords</label>
	<textarea id="meta_keywords" name="meta_keywords" disabled="disabled"><?= safe($content->fields['meta_keywords']) ?></textarea><br />

	<label for="meta_description">META Description</label>
	<textarea id="meta_description" name="meta_description" disabled="disabled"><?= safe($content->fields['meta_description']) ?></textarea><br /></div>
<? endif; ?>

</div>

<div class="formRight">
	<button class="submit" onclick="return postbackConf(
					this
					,'approveAdd'
					,['pageid','parent_id','act']
					,[<?= $_REQUEST['pageid'] ?>,<?= $_REQUEST['parent_id'] ?>,'approve']
					,'approve'
					,'page')">Approve</button>
	<button class="delete" onclick="return postbackConf(
					this
					,'approveAdd'
					,['pageid','parent_id','act']
					,[<?= $_REQUEST['pageid'] ?>,<?= $_REQUEST['parent_id'] ?>,'reject']
					,'reject'
					,'page')">Reject</button>
	<button class="finished" onclick="window.location='<?= $config['dir'] ?>index.php?fuseaction=admin.pages&amp;parent_id=<?= $_REQUEST['parent_id'] ?>';">Cancel</button>
</div>