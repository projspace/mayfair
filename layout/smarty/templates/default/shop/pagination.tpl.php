{if $pagination}
<div class="pages">
	{if $showprev}<span><a href="{$config.dir}index.php/fuseaction/{$link}/start/{$start-$display}{$sid}">Prev</a></span>{/if}{section name=page loop=$pages}{assign var=curr value=$smarty.section.page.iteration*$display}{assign var=curr value=$curr-$display}{assign var=next value=$smarty.section.page.iteration+1}{assign var=next value=$next*$display}{assign var=next value=$next-$display}{if $start>=$curr && $start<$next}<span class="current"><strong>{$smarty.section.page.iteration}</strong></span>{else}<span><a href="{$config.dir}index.php/fuseaction/{$link}/start/{$smarty.section.page.iteration*$display-$display}{$sid}">{$smarty.section.page.iteration}</a></span>{/if}{/section}{if $shownext}<span class="last"><a href="{$config.dir}index.php/fuseaction/{$link}/start/{$start+$display}{$sid}">Next</a></span>{/if}
</div>
{/if}
