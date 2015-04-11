<ul>
{foreach from=$brands item=brand}
	<li><a href="{$brand.url}{$sid}">{$brand.name}</a></li>
{/foreach}
</ul>