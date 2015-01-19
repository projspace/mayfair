<script type="text/javascript" src="<?=$config['dir'] ?>VLib/js/validator.js"></script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
	$(document).ready(function(){
		$('#mainForm').submit(function(){
            var validation = new Validator(function(errors){
                for(i=0;i<errors.length;i++)
                {
                    $(errors[i].dom).css('border', '1px solid #F00');
                }
            });

            validation.addField('title', "Title", 'required');
            validation.addField('date', "Date", 'required');
            validation.addField('summary', "Summary", 'required');
            return validation.validate();
        })
	});
/* ]]> */
</script>

<h1><?= $press ? 'Edit' : 'Add' ?> press/ad</h1>
<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.editPress&press_id=<?= $press['id'] ?>" id="mainForm" enctype="multipart/form-data">
	<input type="hidden" name="press_id" value="<?=$press['id'] ?>"/>
	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
			<li><a href="#tabs-2">Content</a></li>
			<li><a href="#tabs-3">List Image</a></li>
			<li><a href="#tabs-4">Image Gallery</a></li>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="type">Type</label>
				<select name="type" id="type">
                    <option value="press"<?= $press['type'] == 'press' ? ' selected' : '' ?>>Press</option>
                    <option value="ads"<?= $press['type'] == 'ads' ? ' selected' : '' ?>>Ad</option>
				</select>
			</div>
			<div class="form-field clearfix">
				<label for="title">Title</label>
				<input type="text" id="title" name="title" class="text" value="<?=$press['title'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="date">Date</label>
				<input type="text" id="date" name="date" class="text medium calendar" value="<?= $press['date'] ? date('d/m/Y', $press['date']) : '' ?>" />
			</div>
            <div class="form-field clearfix">
                <label for="release_title">Press Release Title</label>
                <input type="text" id="release_title" name="release_title" class="text" value="<?=$press['release_title'] ?>" />
            </div>
            <div class="form-field clearfix">
                <label for="summary">Summary</label>
                <textarea id="summary" name="summary" class="text"><?=$press['summary'] ?></textarea>
            </div>
            <div class="form-field clearfix">
                <label for="link">Link (optional)</label>
                <input type="text" id="link" name="link" class="text" value="<?=$press['link'] ?>" />
            </div>
            <? if($press['id'] && count($relations)): ?>
            <div class="form-field clearfix">
                <label for="related">Related items</label>
                <select id="related" name="related[]" class="text" multiple="multiple">
                    <? foreach($relations as $row): ?>
                    <option value="<?= $row['id'] ?>"<?= in_array($row['id'], (array)$selectedRelations) ? ' selected' : '' ?>><?= $row['title'] ?></option>
                    <? endforeach ?>
                </select>
            </div>
            <? endif ?>
		</div>
        <div id="tabs-2">
            <?= $wysiwyg->editor($press['content']); ?>
        </div>
        <div id="tabs-3">
            <div class="form-field clearfix">
                <label for="image">Upload image</label>
                <input type="file" id="image" name="image" class="text" />
            </div>

            <? if($press['imagetype']): ?>
            <div class="form-field clearfix">
                <label for="current">Current image (<a href="index.php?fuseaction=admin.deletePressImage&press_id=<?= $press['id'] ?>">Delete</a>)</label>
                <img src="<?= $config['dir'] ?>images/press/list/<?= $press['id'] ?>.<?= $press['imagetype'] ?>?t=<?= time() ?>" alt="" />
            </div>
            <? endif ?>
        </div>
        <div id="tabs-4">
            <? if($images): $i = 0;while($img = $images->FetchRow()): if($img['imagetype']): ?>
            <div class="form-field clearfix">
                <label for="current<?= $i ?>">Current image (<a href="index.php?fuseaction=admin.deletePressImage&press_id=<?= $press['id'] ?>&id=<?= $img['id'] ?>">Delete</a>)</label>
                <img src="<?= $config['dir'] ?>images/press/big/<?= $img['id'] ?>.<?= $img['imagetype'] ?>" alt="" />
            </div>
            <? $i++;endif;endwhile;endif; ?>
            <div class="form-field clearfix multiple-images">
                <label for="image2">Upload image</label>
                <input type="file" id="image2" name="images[]" class="text" />
            </div>
            <br />
            <a href="#" class="add-images" style="float:right;">+ Add more</a>
        </div>
	</div>


	<div class="tab-panel-buttons clearfix">
		<span class="button button-small submit">
			<input class="submit" type="submit" value="Continue" />
			<input type="hidden" name="press_id" value="<?=$press['id'] ?>" />
		</span>
	</div>
</form>
<script type="text/javascript">
    $(function(){
        $('.add-images').click(function(){
            var $div = $('.multiple-images:last');
            $div.after($div.clone());
            return false;
        })
    })
</script>