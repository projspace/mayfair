<script type="text/javascript">var wysiwyg=false;</script>
<script type="text/javascript" src="<?= $config['dir'] ?>VLib/js/lib_MultiTabs.js"></script>

<form id="postback" method="post" action="none"></form>
<h1>Compare Revisions</h1><hr />

<div id="tabpane">

<div class="legend">Revision <?= $new_page->fields['revision'] ?></div>
<div class="form">
	<div class="label">Page Name</div>
	<div class="input"><?= $new_page->fields['name'] ?></div><br />

	<div class="label">Editor</div>
	<div class="input"><?= $new_page->fields['username'] ?></div><br />

	<div class="label">Timestamp</div>
	<div class="input"><?= $new_page->fields['time'] ?></div><br />

	<div class="label">Revision</div>
	<div class="input"><?= $new_page->fields['revision'] ?></div><br />

	<div class="label">Layout</div>
	<div class="input"><?= $new_page->fields['layout_name'] ?></div><br />

	<div class="label">Valid From</div>
	<div class="input"><?= format_date($new_page->fields['valid_from']) ?></div><br />

	<div class="label">Valid To</div>
	<div class="input"><?= format_date($new_page->fields['valid_to']) ?></div><br />

	<div class="label">Page Type</div>
	<div class="input"><?= ($new_page->fields['pagetype']==1) ? "Content Page" : "Placeholder"; ?></div><br />
</div>

<div class="legend">Live Page</div>
<div class="form">
	<div class="label">Page Name</div>
	<div class="input"><?= $page->fields['name'] ?></div><br />

	<div class="label">Editor</div>
	<div class="input"><?= $page->fields['username'] ?></div><br />

	<div class="label">Timestamp</div>
	<div class="input"><?= $page->fields['time'] ?></div><br />

	<div class="label">Revision</div>
	<div class="input"><?= $page->fields['revision'] ?></div><br />

	<div class="label">Layout</div>
	<div class="input"><?= $page->fields['layout_name'] ?></div><br />

	<div class="label">Valid From</div>
	<div class="input"><?= format_date($page->fields['valid_from']) ?></div><br />

	<div class="label">Valid To</div>
	<div class="input"><?= format_date($page->fields['valid_to']) ?></div><br />

	<div class="label">Page Type</div>
	<div class="input"><?= ($page->fields['pagetype']==1) ? "Content Page" : "Placeholder"; ?></div><br />
</div>
<? if($page->fields['pagetype']==1) : ?>
<div class="legend">Differences</div>
<div class="form">
<?
	$diff=new Diff($content->fields['content'],$new_content->fields['content'],$config);
	$diff->setRevision1($page->fields['revision']);
	$diff->setRevision2($new_page->fields['revision']);
	echo $diff->format();
?>
</div>
<? endif; ?>

</div>

<div class="formRight">
	<button class="submit" onclick="return postbackConf(
					this
					,'rollback'
					,['pageid','parent_id','act','revision']
					,[<?= $_REQUEST['pageid'] ?>,<?= $_REQUEST['parent_id'] ?>,'rollback',<?= $_REQUEST['revision'] ?>]
					,'rollback'
					,'page')">Rollback</button>
	<button class="finished" onclick="window.location='<?= $config['dir'] ?>index.php?fuseaction=admin.rollback&amp;pageid=<?= $_REQUEST['pageid'] ?>&amp;parent_id=<?= $_REQUEST['parent_id'] ?>';">Overview</button>
</div>