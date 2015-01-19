<h3>Categories</h3>
<ul class="categorynav">
{foreach from=$categories item=category}
	<li><a href="{$config.dir}index.php/fuseaction/shop.category/category_id/{$category.id}{$sid}">{$category.name}</a></li>
{/foreach}
</ul>