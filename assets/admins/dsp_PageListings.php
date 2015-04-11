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
<form id="postback" method="post" action="none"></form>
<h1>Page Listings</h1><hr />
<table class="values">
	<tr>
		<th>Title</th>
		<th>Content</th>
		<th>&nbsp;</th>
	</tr>
<?
	while($row=$listings->FetchRow())
	{
		if($class=="light")
			$class="dark";
		else
			$class="light";

		if($row['featured']==1)
			$class="selected";

		echo "<tr class=\"$class\">
				<td>{$row['title']}</td>
				<td>".truncate($row['content'],50)."</a></td>
				<td class=\"right\">
					<button class=\"form\" title=\"Delete\" onclick=\"return postbackConf(
						this
						,'pageListings'
						,['pageid','parent_id','listingid','act']
						,[{$pageid},{$parent_id},{$row['id']},'removeListing']
						,'delete'
						,'listing')\"><img src=\"{$config['dir']}images/admin/delete.png\" width=\"16\" height=\"16\" alt=\"Delete\" /></button>
					<a title=\"Edit\" href=\"{$config['dir']}index.php?fuseaction=admin.pageListings&amp;pageid={$_REQUEST['pageid']}&amp;parent_id={$_REQUEST['parent_id']}&amp;listingid={$row['id']}&amp;act=editListing\"><img src=\"{$config['dir']}images/admin/edit.png\" width=\"16\" height=\"16\" alt=\"Edit\"></a>
				</td>
			</tr>";
	}
?>
</table><br />

<div class="formRight">
	<button class="add" onclick="window.location='<?= $config['dir'] ?>index.php?fuseaction=admin.pageListings&amp;pageid=<?= $_REQUEST['pageid'] ?>&amp;parent_id=<?= $_REQUEST['parent_id'] ?>&amp;act=addListing'; return false;">Add Listing</button>
	<button class="finished" onclick="window.location='<?= $config['dir'] ?>index.php?fuseaction=admin.pages&amp;pageid=<?= $_REQUEST['pageid'] ?>&amp;parent_id=<?= $_REQUEST['parent_id'] ?>'; return false;">Finished</button>
</div>