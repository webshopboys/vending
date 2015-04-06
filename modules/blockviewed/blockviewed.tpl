<!-- Block Viewed products -->
<div id="viewed-products_block_left" class="block products_block">
	<h4 style="white-space:nowrap;">{l s='Viewed products' mod='blockviewed'}</h4>
	<div class="block_content">
		<ul class="products">
		{foreach from=$productsViewedObj item=viewedProduct name=myLoop}
{if $viewedProduct->reserved > 0 AND $viewedProduct->reserved >= $viewedProduct->quantity}
{assign var=isReservedb value=1}
{else}
{assign var=isReservedb value=0}
{/if}
			<li class="{if $smarty.foreach.myLoop.last}last_item{elseif $smarty.foreach.myLoop.first}first_item{else}item{/if}">
				<a href="{$link->getProductLink($viewedProduct)}" title="{l s='More about' mod='blockviewed'} {$viewedProduct->name|escape:htmlall:'UTF-8'}">
				<img src="{$link->getImageLink($viewedProduct->link_rewrite, $viewedProduct->cover, 'medium')}" height="{$mediumSize.height}" width="{$mediumSize.width}" alt="{$viewedProduct->legend|escape:htmlall:'UTF-8'}" {if $isReservedb}class="reserved"{/if}/>
				</a>
				<h5><a href="{$link->getProductLink($viewedProduct)}" title="{l s='More about' mod='blockviewed'} {$viewedProduct->name|escape:htmlall:'UTF-8'}">{$viewedProduct->name|escape:htmlall:'UTF-8'|truncate:25}</a></h5>
				<p>{m s=$viewedProduct->description_short|strip_tags:'UTF-8'|truncate:44 n=12}<a href="{$link->getProductLink($viewedProduct)}" title="{l s='More about' mod='blockviewed'} {$viewedProduct->name|escape:htmlall:'UTF-8'}"><img src="{$img_dir}bullet.gif" alt="&gt;&gt;"  /></a></p>
			</li>
		{/foreach}
		</ul>
	</div>
</div>