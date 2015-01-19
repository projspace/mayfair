<div class="product">
	<form method="get" action="{$config.dir}add">
	{$sid_form}	
	<input type="hidden" name="category_id" value="{$category_id}" />
	<input type="hidden" name="product_id" value="{$product.id}" />

	<div class="heading">
		<h1>{$product.brand_name} :: {$product.name}</h1>
		<span class="price">${$product.price|price}</span>
		<br />
	</div>

	<div class="body">

		{if $images || $product.imagetype}
		<div class="images">
			<span class="imagelabel">Click on image(s) to enlarge</span>
			{if $product.imagetype}
				<a onclick="shop_ImagePopup('{$product.id}','product');"><img src="{$config.dir}images/product/thumbs/{$product.id}.{$product.imagetype}" alt="{$product.name}" /></a>
			{/if}
			{if $images}
				{foreach from=$images item=image}
					<a onclick="shop_ImagePopup('{$image.id}','image');"><img src="{$config.dir}images/product/thumbs/image{$image.id}.{$image.imagetype}" alt="{$product.name}" /></a>
				{/foreach}
			{/if}
		</div>
		{/if}

		<div class="description">
			{$product.description}
		</div>

		<table class="specs">
			{assign var=class value="light"}
			{foreach from=$product.specs item=spec}
			{if $class=="light"}
				{assign var=class value="dark"}
			{else}
				{assign var=class value="light"}
			{/if}
			<tr>
				<td class="{$class} name"><strong>{$spec.name}</strong></td>
				<td class="{$class}">{$spec.value}</td>
			</tr>
			{/foreach}
		</table>

		{if $product.options}
		<div class="options">
			{foreach name=optiosn from=$product.options item=option}
			<label for="option{$smarty.foreach.options.iteration}">{$option.name}</label>
			<select id="option{$smarty.foreach.options.iteration}" name="option[]">
				{shop_options
					value=$option.value
					price=$option.price
					base=$product.price
				}
			</select><br />
			{/foreach}
		</div>
		{/if}

		{if $product.soldout==1}
			<strong>Sold Out</strong>
		{else}
			<input type="image" class="add" src="{$config.dir}images/addtocart.png" alt="Add to Cart" width="82" height="22" />
		{/if}
	</div>
	</form>
</div>