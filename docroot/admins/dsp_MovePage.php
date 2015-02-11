<script type="text/javascript" src="<?= $config['dir'] ?>lib/treemenu/dtree.js"></script>
<link rel="stylesheet" type="text/css" href="<?= $config['dir'] ?>lib/treemenu/dtree.css"></script>
<script type="text/javascript">
	a = new dTree('a');
	a.config.useCookies=true;
	a.add(0,-1,'Pages','javascript:confMove(\'Top Level\',0);');

<?
	while($row=$result->FetchRow())
	{

		if($row['lft']>=$page->fields['lft'] && $row['rgt']<= $page->fields['rgt'])
			echo "a.add({$row['id']},{$row['parent_id']},'{$row['name']}','javascript:alert(\'You cannot move this page here.\');',false);\n";
		else
			echo "a.add({$row['id']},{$row['parent_id']},'{$row['name']}','javascript:confMove(\'{$row['name']}\',{$_REQUEST['pageid']},{$row['id']});',true);\n";
	}
	echo "document.write(a);\n";
?>
</script>