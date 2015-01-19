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
<h1>Setup</h1><hr>
<form method="post" action="<?= $config['dir'] ?>index.php?fuseaction=admin.setup&act=save">

<div id="tabpane">

<div class="legend">Database</div>
<div class="form">
	<label for="db_driver">Database Driver</label>
	<select id="db_driver" name="config[db][driver]">
		<option value="mysql"<? if($config['db']['driver']=="mysql") echo " selected=\"selected\""; ?>>PHP 4 MySQL</option>
		<option value="mysqli"<? if($config['db']['driver']=="mysqli") echo " selected=\"selected\""; ?>>PHP 5 MySQLi</option>
		<option value="postgres"<? if($config['db']['driver']=="postgres") echo " selected=\"selected\""; ?>>PostgreSQL</option>
		<option value="mssql"<? if($config['db']['driver']=="mssql") echo " selected=\"selected\""; ?>>Microsoft SQL Server</option>
	</select><br />

	<label for="db_server">Database Server</label>
	<input type="text" id="db_server" name="config[db][server]" value="<?= $config['db']['server'] ?>" /><br />

	<label for="db_username">Database Username</label>
	<input type="text" id="db_username" name="config[db][username]" value="<?= $config['db']['username'] ?>" /><br />

	<label for="db_password">Database Password</label>
	<input type="password" id="db_password" name="config[db][password]" value="<?= $config['db']['password'] ?>" /><br />

	<label for="db_database">Database Name</label>
	<input type="text" id="db_database" name="config[db][database]" value="<?= $config['db']['database'] ?>" /><br />
</div>

<div class="legend">Company Details</div>
<div class="form">

	<label for="company">Company Name</label>
	<input type="text" id="" name="config[company]" value="<?= $config['company'] ?>" /><br />

	<label for="companyshort">Short Name/Initials</label>
	<input type="text" id="companyshort" name="config[companyshort] value="<?= $config['companyshort'] ?>" /><br />

	<label for="defaultcountry">Default Country</label>
	<select id="defaultcountry" name="config[defaultcountry_id]">
<?
	while($row=$countries->FetchRow())
	{
		echo "<option id=\"{$row['id']}\"";
		if($row['id']==$config['defaultcountry_id'])
			echo " selected=\"selected\"";
		echo ">{$row['name']}</option>\n";
	}
?>
	</select><br />
</div>

<div class="legend">Invoice</div>
<div class="form">
	<label for="invoice_companyname">Company Name</label>
	<input type="text" id="invoice_companyname" name="config[invoice][companyname]" value="<?= $config['invoice']['companyname'] ?>" /><br />

	<label for="invoice_address">Address</label>
	<textarea rows="4" cols="30" id="invoice_address" name="config[invoice][address]"><?= $config['invoice']['address1']."\n".$config['invoice']['address2']."\n".$config['invoice']['address3']."\n".$config['invoice']['address4'] ?></textarea><br />

	<label for="invoice_tel">Telephone</label>
	<input type="text" id="invoice_tel" name="config[invoice][tel]" value="<?= $config['invoice']['tel'] ?>" /><br />

	<label for="invoice_fax">Fax</label>
	<input type="text" id="invoice_fax" name="config[invoice][fax]" value="<?= $config['invoice']['fax'] ?>" /><br />

	<label for="invoice_email">Email</label>
	<input type="text" id="invoice_email" name="config[invoice][email]" value="<?= $config['invoice']['email'] ?>" /><br />
</div>

<div class="legend">Images</div>
<div class="form">
	<label for="admin_resize">Resize Method</label>
	<select id="admin_resize" name="config[admin][resize]">
			<option value="ImageMagick"<? if($config['admin']['resize']=="ImageMagick") echo " selected=\"selected\""; ?>>Image Magick</option>
			<option value="GD"<? if($config['admin']['resize']=="GD") echo " selected=\"selected\""; ?>>GD 2</option>
	</select><br />

	<label for="size_product_thumb_x">Product Thumbnails</label>
	<input size="4" type="text" id="size_product_thumb_x" name="config[size][product][thumb][x]" value="<?= $config['size']['product']['thumb']['x'] ?>" /><span>x</span><input size="4" type="text" id="size_product_thumb_y" name="config[size][product][thumb][y]" value="<?= $config['size']['product']['thumb']['y'] ?>" /><br />

	<label for="size_product_image_x">Product Images</label>
	<input size="4" type="text" id="size_product_image_x" name="config[size][product][image][x]" value="<?= $config['size']['product']['image']['x'] ?>" /><span>x</span><input size="4" type="text" id="size_product_image_y" name="config[size][product][image][y]" value="<?= $config['size']['product']['image']['y'] ?>" /><br />

	<label for="size_category_thumb_x">Category Thumbnails</label>
	<input size="4" type="text" id="size_category_thumb_x" name="config[size][category][thumb][x]" value="<?= $config['size']['category']['thumb']['x'] ?>" /><span>x</span><input size="4" type="text" id="size_category_thumb_y" name="config[size][category][thumb][y]" value="<?= $config['size']['category']['thumb']['y'] ?>" /><br />

	<label for="size_category_image_x">Category Images</label>
	<input size="4" type="text" id="size_category_image_x" name="config[size][category][image][x]" value="<?= $config['size']['category']['image']['x'] ?>" /><span>x</span><input size="4" type="text" id="size_category_image_y" name="config[size][category][image][y]" value="<?= $config['size']['category']['image']['y'] ?>" /><br />

	<label for="size_brand_thumb_x">Brand Thumbnails</label>
	<input size="4" type="text" id="size_brand_thumb_x" name="config[size][brand][thumb][x]" value="<?= $config['size']['brand']['thumb']['x'] ?>" /><span>x</span><input size="4" type="text" id="size_brand_thumb_y" name="config[size][brand][thumb][y]" value="<?= $config['size']['brand']['thumb']['y'] ?>" /><br />

	<label for="size_brand_image_x">Brand Images</label>
	<input size="4" type="text" id="size_brand_image_x" name="config[size][brand][image][x]" value="<?= $config['size']['brand']['image']['x'] ?>" /><span>x</span><input size="4" type="text" id="size_brand_image_y" name="config[size][brand][image][y]" value="<?= $config['size']['brand']['image']['y'] ?>" /><br />
</div>

<div class="legend">Mail</div>
<div class="form">
	<label for="mail_fromaddress">From Address</label>
	<input type="text" id="mail_fromaddress" name="config[mail][fromaddress]" value="<?= $config['mail']['fromaddress'] ?>" /><br />

	<label for="mail_fromname">From Name</label>
	<input type="text" id="mail_fromname" name="config[mail][fromname]" value="<?= $config['mail']['fromname'] ?>" /><br />

	<label for="mail_method">Mail Method</label>
	<select id="mail_method" name="config[mail][method]">
		<option value="mail"<? if($config['mail']['method']=="mail") echo " selected=\"selected\""; ?>>PHP mail()</option>
		<option value="sendmail"<? if($config['mail']['method']=="sendmail") echo " selected=\"selected\""; ?>>Send Mail</option>
		<option value="smtp"<? if($config['mail']['method']=="smtp") echo " selected=\"selected\""; ?>>SMTP</option>
	</select><br />

	<label for="mail_server">SMTP Server</label>
	<input size="30" type="text" id="mail_server" name="config[mail][server]" value="<?= $config['mail']['server'] ?>" /><br />
</div>

<div class="legend">Sessions</div>
<div class="form">
	<label for="p3p">Compact Privacy Policy (p3p)</label>
	<input size="50" type="text" id="p3p" name="config[p3p]" value="<?= str_replace("\"","&quot;",$config['p3p']) ?>" /><br />

	<label for="shop_session_id">Shop Cookie Name</label>
	<input type="text" id="shop_session_id" name="config[shop][session_id]" value="<?= $config['shop']['session_id'] ?>" /><br />

	<label for="shop_timeout">Shop Timeout (0=Never)</label>
	<input type="text" id="shop_timeout" name="config[shop][timeout]" value="<?= $config['shop']['timeout'] ?>" /><span>seconds</span><br />

	<label for="shop_session_id">Admin Cookie Name</label>
	<input type="text" id="admin_session_id" name="config[admin][session_id]" value="<?= $config['admin']['session_id'] ?>" /><br />

	<label for="admin_timeout">Admin Timeout</label>
	<input type="text" id="admin_timeout" name="config[admin][timeout]" value="<?= $config['admin']['timeout'] ?>" /><span>seconds</span><br />
</div>

<div class="legend">Site Settings</div>
<div class="form">
	<label for="protocol">Protocol</label>
	<select id="protocol" name="config[protocol]">
		<option value="http://"<? if($config['protocol']=="http://") echo " selected=\"selected\""; ?>>HTTP</option>
		<option value="https://"<? if($config['protocol']=="https://") echo " selected=\"selected\""; ?>>HTTPS</option>
	</select><br />

	<label for="url">URL</label>
	<input size="50" type="text" id="url" name="config[url]" value="<?= $config['url'] ?>" /><br />

	<label for="dir">Directory</label>
	<input type="text" id="dir" name="config[dir]" value="<?= $config['dir'] ?>" /><br />

	<label for="path">Shop Path</label>
	<input size="50" type="text" id="path" name="config[path]" value="<?= $config['path'] ?>" /><br />
</div>

<div class="legend">Appearance</div>
<div class="form">
	<label for="template">Shop Template</label>
	<select id="template" name="config[template]">
<?
	while($row=$templates->FetchRow())
	{
		echo "<option value=\"{$row['path']}\"";
		if($row['path']==$config['template'])
			echo " selected=\"selected\"";
		echo ">{$row['name']}</option>\n";
	}
?>
	</select><br />

	<label for="display_children">Categories Per Row</label>
	<input size="4" type="text" id="display_children" name="config[display][children]" value="<?= $config['display']['children'] ?>" /><br />

	<label for="display_products">Products Per Page (0=unlimited)</label>
	<input size="4" type="text" id="display_products" name="config[display][products]" value="<?= $config['display']['products'] ?>" /><br />
</div>

<div class="legend">Meta Tags</div>
<div class="form">
	<label for="meta_title">META Title</label>
	<input type="text" id="meta_title" name="config[meta][title]" value="<?= $config['meta']['title'] ?>" /><br />

	<label for="meta_keywords">META Keywords</label>
	<textarea id="meta_keywords" name="config[meta][keywords]"><?= $config['meta']['keywords'] ?></textarea><br />

	<label for="meta_description">META Description</label>
	<textarea id="meta_description" name="config[meta][description]"><?= $config['meta']['description'] ?></textarea><br />
</div>

</div>

<script type="text/javascript">tabs_setup();</script>
<?
/*
	 [PATHS]
	$config['progpath']['mysqldump']="c:/mysql/bin/";
	$config['progpath']['mogrify']="";
	$config['progpath']['gpg']="";

	 [PSP]
	$config['psp']['driver']="Local";


	 [WYSIWYG]
	$config['admin']['editor']="fckeditor";

	 [CUSTOMVARS]
	//$config['admin']['product']['customvars'][0]="kelkoo";

	 [APPEARANCE]

	$config['template']="default";
	$config['display']['children']=4;
	$config['display']['products']=10;*/
?>

</form>