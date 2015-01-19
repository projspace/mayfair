<h1>Add Page</h1>

<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.addPage&amp;act=data">
	<input type="hidden" name="pagetype" value="1"/>
	
	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Select Layout</a></li>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="layoutid">Layout</label>
				<select id="layoutid" name="layoutid">
					<?
						while($row=$layouts->FetchRow())
						{
							echo "<option value=\"{$row['id']}\"";
							if($row['def']==1)
								echo " selected=\"selected\"";
							echo ">{$row['name']}</option>\n";
						}
					?>
				</select>
			</div>
			<!--<div class="form-field clearfix">
				<label for="pagetype">Page Type</label>
				<select id="pagetype" name="pagetype">
					<option value="1">Normal Page</option>
					<option value="0">Placeholder</option>
				</select>
			</div>-->
		</div>
	</div>
	<div class="tab-panel-buttons clearfix">
		<span class="button button-small submit">
			<input class="submit" type="submit" value="Continue" />
			<input type="hidden" name="parent_id" value="<?= safe($_REQUEST['parent_id'],1) ?>" />
		</span>
		<a class="button button-grey" href="<?= $config['dir'] ?>index.php?fuseaction=admin.pages&amp;parent_id=<?= $_REQUEST['parent_id'] ?>"><span>Cancel</span></a>
	</div>

</form>