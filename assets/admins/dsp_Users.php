<script language="javascript" type="text/javascript">
	/* <![CDATA[ */
		$(document).ready(function(){
			$("select").change(function(){	
				$('#frmFilter').submit();
			});
		});
	/* ]]> */
	</script>
	
<form id="postback" method="post" action="none"></form>
<form method="get" action="<?=$config['dir'] ?>index.php" id="frmFilter">
	<input type="hidden" name="fuseaction" value="admin.users" />
<h1>
	<span style="float: right; margin-top: 5px;">
		<label style="width: auto; margin-right: 10px;">Search</label>
		<input type="text" name="keyword" value="<?=$_GET['keyword'] ?>"/>
		
		
		<label style="width: auto; clear: none; margin:0 10px;">Customer type</label>
		<select name="customer_type" style="width: 150px;">
			<option value="">All</option>		
			<option value='student'  <?php print $_GET['customer_type']=='student'?"selected":""?>>Student</option>
			<option value='confirmed_student' <?php print $_GET['customer_type']=='confirmed_student'?"selected":""?> >Confirmed student</option>
			<option value="teacher" <?php print $_GET['customer_type']=='teacher'?"selected":""?> >Teacher</option>
			<option value="shop" <?php print $_GET['customer_type']=='shop'?"selected":""?> >Shop</option>
		</select>
			
		<?php /* ?>	
		<label style="width: auto; margin-right: 10px; margin-left: 10px; clear: none;">Student</label>
		<input type="checkbox" id="student" name="student" value="1" <?=($_GET['student']+0)?'checked="checked"':'' ?>/>
		
		<label style="width: auto; margin-right: 10px; margin-left: 10px; clear: none;">Confirmed<br />Student</label>
		<input type="checkbox" id="confirmed_student" name="confirmed_student" value="1" <?=($_GET['confirmed_student']+0)?'checked="checked"':'' ?>/>
	<? */ ?>
	
	</span>
	Manage Customers
</h1>
</form>
<table class="values nocheck">
	<tr>
		<th>Email</th>
		<th>First Name</th>
		<th>Last Name</th>
		<th>Type</th>
		<th style="width:170px;">&nbsp;</th>
	</tr>
<?
	while($row=$users->FetchRow())
	{
		if($class=="light")
			$class="dark";
		else
			$class="light";

		echo "<tr class=\"$class\">
			<td>{$row['email']}</td>
			<td>{$row['firstname']}</td>
			<td>{$row['lastname']}</td>
			<td>";
		if($row['student']) echo "Student";
		elseif($row['confirmed_student']) echo "Confirmed student";
		elseif($row['teacher']) echo "Teacher";
		elseif($row['shop']) echo "Shop";
		else echo "Normal client";
		
		echo "</td>
			<td class=\"right\">";
		if($acl->check("removeUser"))
			echo "<span class=\"button button-grey\"><input type=\"button\" title=\"Remove\" onclick=\"return postbackConf(
				this
				,'removeUser'
				,['user_id']
				,[{$row['id']}]
				,'remove'
				,'user')\" value=\"Remove\" /></span>\n";
		if($acl->check("editUser"))
			echo "<a class=\"button button-grey\" href=\"{$config['dir']}index.php?fuseaction=admin.editUser&amp;user_id={$row['id']}\"><span>Edit</span></a>\n";
		echo "</td>
		</tr>";
	}
?>
</table>

<?
	$nr_pages = ceil($item_count / $items_per_page);
	$max_page_links = 10;
	
	if($nr_pages > 1)
	{
		echo '<div style="float: left; width: 100%; text-align: center;"><br/>'.$item_count.' users<br /><br />';
		
		
		echo '<div style="width:100%;" class="paginator"><ul>';
		
			$results_page = array();
			$results_page[] = 'fuseaction=admin.users';
			if(trim($_GET['keyword']) != '')
				$results_page[] = 'keyword='.$_GET['keyword'];
			if(trim($_GET['customer_type']) != '')
				$results_page[] = 'customer_type='.$_GET['customer_type'];
			$results_page = $config['dir'].'index.php?'.implode('&amp;', $results_page).'&amp;page=';
				
			echo '<li><a href="'.$results_page.'1">&lt;&lt; First</a></li>';
				
			if($page == 1)
				echo '<li class="next"><a href="#">&lt; Back</a></li>';
			else
				echo '<li class="next"><a href="'.$results_page.($page - 1).'">&lt; Back</a></li>';
			
			for($i = $page - floor($max_page_links/2); $i < $page + ceil($max_page_links/2); $i++)
				if(($i > 0)&&($i <= $nr_pages))
				{
					if($i == $page)
						echo '<li><span>'.$i.'</span></li>';
					else
						echo '<li><a href="'.$results_page.$i.'">'.$i.'</a></li>';
				}
			
			if($page == $nr_pages)
				echo '<li class="prev"><a href="#">Next &gt;</a></li>';
			else
				echo '<li class="prev"><a href="'.$results_page.($page + 1).'">Next &gt;</a></li>';
				
			echo '<li><a href="'.$results_page.$nr_pages.'">Last &gt;&gt;</a></li>';
		
			echo '</ul></div>';
		echo '</div>';
	}
?>
<? if($acl->check("addUser")): ?>
<div class="tab-panel-buttons clearfix">
	<a class="button button-small-add right" href="<?= $config['dir'] ?>index.php?fuseaction=admin.addUser">
		<span>Add Customer</span>
	</a>
    <a class="button button-grey button-slide right" title="Export" href="<?= $config['dir'] ?>index.php?fuseaction=admin.users&act=newsletter_export"><span>Newsletter CSV</span></a>
</div>
<? endif; ?>