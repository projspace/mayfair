<div class="heading">
	<h1>{if $category.imagetype}<img src="{$config.dir}images/category/{$category.id}.{$category.imagetype}" alt="{$category.name}" />{/if}{$category.name}</h1>
</div>
{if $category.content}
<div class="shopContent">
	{$category.content}
</div>
{/if}