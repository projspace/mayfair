<form class="catSearch" method="post" action="{$config.dir}categorySearch">
	{$sid_form}
	<input type="hidden" name="category_id" value="{$category.id}" />

	<label for="search_keyword">Name</label>
	<input type="text" id="search_keyword" name="keyword" /><br />

	{foreach from=$search_keys item=key}
	<label for="search_{$key}">{$key}</label>
	<select id="search_{$key}" name="search[{$key}]">
		<option value="">Select</option>
		{foreach from=$search_params[$key] item=param}
			<option value="{$param.value}">{$param.value}</option>
		{/foreach}
	</select><br />
	{/foreach}

	<input class="submit" type="submit" value="Search" /><br />
</form>
