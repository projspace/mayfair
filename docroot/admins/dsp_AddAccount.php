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
<h1>Add Administrator</h1>
<?
	if($reason)
		error($reason,"Error");
?>

<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.addAccount&act=add">
	<div id="tabs" class="form clearfix">
		<ul>
			<li><a href="#tabs-1">Details</a></li>
		</ul>
		<div id="tabs-1">
			<div class="form-field clearfix">
				<label for="username">Username</label>
				<input type="text" id="username" name="username" value="<?= make_safe($_POST['username']) ?>" autocomplete="off" />
			</div>
			<div class="form-field clearfix">
				<label for="email">Email</label>
				<input type="text" id="email" name="email" value="<?= make_safe($_POST['email']) ?>" autocomplete="off" />
			</div>
			<div class="form-field clearfix">
				<label for="password">Password</label>
				<input type="password" id="password" name="password" autocomplete="off" />
			</div>
			<div class="form-field clearfix">
				<label for="confirm">Confirm Password</label>
				<input type="password" id="confirm" name="confirm" autocomplete="off" />
			</div>
			<div class="form-field clearfix">
				<label for="aclgroup">Access Group</label>
				<select name="group_id" id="group_id">
					<?
						while($row=$groups->FetchRow())
							if($row['id'] == $_POST['group_id'])
								echo "<option value=\"{$row['id']}\" selected=\"selected\">{$row['name']}</option>";
							else
								echo "<option value=\"{$row['id']}\">{$row['name']}</option>";
					?>
				</select>
			</div>
		</div>
	</div>
	
	<div class="tab-panel-buttons clearfix">
		<span class="button button-small submit">
			<input class="submit" type="submit" value="Continue" />
		</span>
	</div>
	
</form>