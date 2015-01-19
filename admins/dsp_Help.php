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
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr height="50">
		<td colspan="2" class="logo"><img src="<?= $config['dir'] ?>images/admin/helpheading.jpg" width="640" height="50" alt="BWD Shop 4.0 Help"></td>
	</tr>
	<tr>
		<td class="nav" width="1">
			<div class="center">
<?
	if($prev->RecordCount()>0)
	{
		$prevrow=$prev->FetchRow();
		echo "<a href=\"{$config['dir']}index.php?fuseaction=admin.help&id={$prevrow[0]}\">Prev</a>";
	}
	else
		echo "<a>Prev</a>";
	echo "&nbsp;|&nbsp;<a href=\"{$config['dir']}index.php?fuseaction=admin.help\">Home</a>&nbsp;|&nbsp;";
	if($next->RecordCount()>0)
	{
		$nextrow=$next->FetchRow();
		echo "<a  href=\"{$config['dir']}index.php?fuseaction=admin.help&id={$nextrow[0]}\">Next</a>";
	}
	else
		echo "<a>Next</a>";
?>
			<br><br>
			<table border="0" cellspacing="0" cellpadding="0" width="180"><tr><td>
<?
	if(!$id)
		$id="1";
	dispContents($contents->GetRows(),$contents->GetKeys(),1,0,$id);
?>
			</td></tr></table>
			</div>
		</td>
		<td width="*" class="bottomBorder">
			<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
				<tr>
					<td class="title">
						<?= str_replace("#name#",PRODUCT_NAME,str_replace("#title#",PRODUCT_NAME." ".PRODUCT_VERSION,$row[$keys['util_help.name']])); ?>
					</td>
				</tr>
				<tr>
					<td class="content">
						<?= str_replace("#name#",PRODUCT_NAME,str_replace("#title#",PRODUCT_NAME." ".PRODUCT_VERSION,$row[$keys['util_help.content']])); ?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>