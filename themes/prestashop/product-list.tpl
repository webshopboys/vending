{if isset($products)}
	
	{if not isset($priceDisplay) }
		{assign var=priceDisplay value=3}
	{/if}

	<!-- Products list v150416.1 priceDisplay={$priceDisplay} waterClass={$waterClass} -->
	<ul id="product_list" class="clear">
	{foreach from=$products item=product name=products}
		
		{assign var=isReserved value=0}
	  	{assign var=priceDP value=$priceDisplay}

		{if $product.reserved > 0 AND $product.reserved >= $product.quantity}
			{assign var=isReserved value=1}
	  	{/if}
		
		{if isset($product.price_date) AND $product.price_date|date_format:"%D" >= $smarty.now|date_format:"%D"}
				{assign var=priceDP value=0}
		{/if}

		<!-- Products list item priceDisplay={$priceDisplay} priceDP={$priceDP} priceDate={$product.price_date} -->

		<li class="ajax_block_product {if $smarty.foreach.products.first}first_item{elseif $smarty.foreach.products.last}last_item{/if} {if $smarty.foreach.products.index % 2}alternate_item{else}item{/if}">
			<div class="center_block">
				<span class="availability">
				  {if $isReserved}
					{l s='Reserved'}
				  {elseif ($product.allow_oosp OR $product.quantity > 0)}
					{l s='Available'}
				  {else}
					{l s='Out of stock'}
				  {/if}
				</span>
				<a href="{$product.link|escape:'htmlall':'UTF-8'}?pd={$priceDP}&wm={$waterClass}" class="product_img_link" title="{$product.name|escape:'htmlall':'UTF-8'}">
					<img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home')}" alt="{$product.legend|escape:'htmlall':'UTF-8'}" class="{if isset($waterClass)}{$waterClass}{/if} {if $isReserved}reserved{/if}" />
				</a>
				<h3>
					{if $product.new == 1}<span class="new">{l s='new'}</span>{/if}
					<a href="{$product.link|escape:'htmlall':'UTF-8'}?pd={$priceDP}&wm={$waterClass}" title="{$product.legend|escape:'htmlall':'UTF-8'}">{$product.name|truncate:35:'...'|escape:'htmlall':'UTF-8'}</a>
				</h3>
				<p class="product_desc">
					<a href="{$product.link|escape:'htmlall':'UTF-8'}?pd={$priceDP}&wm={$waterClass}">{$product.description_short|strip_tags:'UTF-8'|truncate:360:'...'}</a>
				</p>
			</div>
			<div class="right_block">
				{if $product.on_sale}
					<span class="on_sale">{l s='On sale!'}</span>
				{elseif ($product.reduction_price != 0 || $product.reduction_percent != 0) && ($product.reduction_from == $product.reduction_to 
					OR ($smarty.now|date_format:'%Y-%m-%d' <= $product.reduction_to && $smarty.now|date_format:'%Y-%m-%d' >= $product.reduction_from))}
					<span class="discount">{l s='Price lowered!'}</span>
				{/if}

				{if !isset($priceDP) || $priceDP == 3}
					<strong>{l s='Ask for an offer!'}</strong>
				{else}
					{if $priceDP == 0 || $priceDP == 2}
						<div>
							<span class="price 02" style="display: inline;">{convertPrice price=$product.price}</span>
							{l s='+Tx'}
						</div>
					{/if}
					{if $priceDP == 1 || $priceDP == 2}
						<!-- van priceDisplay -->
						<div>
							<span class="price" style="display: inline;">{convertPrice price=$product.price_tax_exc}</span>
							{l s='-Tx'}
						</div>
					{/if}
				{/if}
				
				{if ($product.allow_oosp OR $product.quantity > 0) && $product.customizable != 2}
					<!--a class="button ajax_add_to_cart_button exclusive" rel="ajax_id_product_{$product.id_product|intval}" href="{$base_dir}cart.php?add&amp;id_product={$product.id_product|intval}&amp;token={$static_token}">{l s='Add to cart'}</a-->
				{else}
					<span class="exclusive">{l s='Add to cart'}</span>
				{/if}
				<a class="button" href="{$product.link|escape:'htmlall':'UTF-8'}?pd={$priceDP}&wm={$waterClass}" title="{l s='View'}">{l s='View'}</a>
			</div>
			<br class="clear"/>
		</li>
	{/foreach}
	</ul>
	<!-- /Products list -->
{/if}