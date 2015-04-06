<!-- MODULE Block new products -->
<div id="new-products_block_right" class="block products_block">
	<h4><a href="{$base_dir}new-products.php" title="{l s='New products' mod='blocknewproducts'}">{l s='New products' mod='blocknewproducts'}</a></h4>
	<div class="block_content">
	{if $new_products|@count > 0}
{if $new_products.0.reserved > 0 AND $new_products.0.reserved >= $new_products.0.quantity}
{assign var=isReserved0 value=1}
{else}
{assign var=isReserved0 value=0}
{/if}
		<ul class="product_images">
			<li><a href="{$new_products.0.link}" title="{$new_products.0.legend|escape:htmlall:'UTF-8'}">
			<img src="{$link->getImageLink($new_products.0.link_rewrite, $new_products.0.id_image, 'medium')}" alt="{$new_products.0.legend|escape:htmlall:'UTF-8'}" {if $isReserved0}class="reserved"{/if}/>
			</a></li>
			{if $new_products|@count > 1}
{if $new_products.1.reserved > 0 AND $new_products.1.reserved >= $new_products.1.quantity}
{assign var=isReserved1 value=1}
{else}
{assign var=isReserved1 value=0}
{/if}			
			<li><a href="{$new_products.1.link}" title="{$new_products.1.legend|escape:htmlall:'UTF-8'}">
			<img src="{$link->getImageLink($new_products.1.link_rewrite, $new_products.1.id_image, 'medium')}" height="{$mediumSize.height}" width="{$mediumSize.width}" alt="{$new_products.1.legend|escape:htmlall:'UTF-8'}" {if $isReserved1}class="reserved"{/if}/>
			</a></li>
			{/if}
		</ul>
		<dl class="products">
		{foreach from=$new_products item=newproduct name=myLoop}
			<dt class="{if $smarty.foreach.myLoop.first}first_item{elseif $smarty.foreach.myLoop.last}last_item{else}item{/if}"><a href="{$newproduct.link}" title="{$newproduct.name|escape:htmlall:'UTF-8'}">{$newproduct.name|strip_tags|escape:htmlall:'UTF-8'}</a></dt>
			{if $newproduct.description_short}<dd class="{if $smarty.foreach.myLoop.first}first_item{elseif $smarty.foreach.myLoop.last}last_item{else}item{/if}"><a href="{$newproduct.link}">{t text=$newproduct.description_short length='50' strip='true' encode='true'}</a>&nbsp;<a href="{$newproduct.link}"><img alt=">>" src="{$img_dir}bullet.gif"/></a></dd>{/if}
		{/foreach}
		</dl>
		<p><a href="{$base_dir}new-products.php" title="{l s='All new products' mod='blocknewproducts'}" class="button_large">{l s='All new products' mod='blocknewproducts'}</a></p>
	{else}
		<p>{l s='No new product at this time' mod='blocknewproducts'}</p>
	{/if}
	</div>
</div>
<!-- /MODULE Block new products -->
