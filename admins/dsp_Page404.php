<script type="text/javascript">var wysiwyg=true;</script>
<script type="text/javascript" src="<?= $config['dir'] ?>VLib/js/lib_MultiTabs.js"></script>

<h1>Page 404</h1>

<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.page404&amp;act=save">

	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Content</a></li>
			<li><a href="#tabs-2">Meta Tags</a></li>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<div class="form">
				<?
					echo $wysiwyg->editor($page404['content']['value']);
				?>
				</div>
			</div>
		</div>
		<div id="tabs-2">
			<div class="form-field clearfix">
				<label for="meta_title">META Title</label>
				<input type="text" id="meta_title" name="meta_title" value="<?=$page404['title']['value'] ?>" />
			</div>
			<div class="form-field clearfix">
				<label for="meta_keywords">META Keywords</label>
				<textarea id="meta_keywords" name="meta_keywords"><?=$page404['keywords']['value'] ?></textarea>
			</div>
			<div class="form-field clearfix">
				<label for="meta_description">META Description</label>
				<textarea id="meta_description" name="meta_description"><?=$page404['description']['value'] ?></textarea>
			</div>
		</div>
		
		<div class="tab-panel-buttons clearfix">
			<span class="button button-small submit">
				<input class="submit" type="submit" value="Continue" />
			</span>
			<a class="button button-grey" href="<?= $config['dir'] ?>index.php?fuseaction=admin.start"><span>Cancel</span></a>
		</div>
		
	</div>

</form>