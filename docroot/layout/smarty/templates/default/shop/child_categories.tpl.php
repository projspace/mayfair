<table class="categories" cellspacing="1">
{foreach from=$children item=row}
	<tr>
		{foreach from=$row item=child}
		<td>
			<a href="{$config.dir}index.php/fuseaction/shop.category/category_id/{$child.id}{$sid}">{$child.name|htmlentities}</a>
		</td>
		{/foreach}
	</tr>
{/foreach}
</table>