	{if !$content_only}
			</div>

<!-- Right -->
			<div id="right_column" class="column">
				{$HOOK_RIGHT_COLUMN}
			</div>




{if ($page_name eq 'index') && (!isset($smarty.request.call_method))}

<div id="popupContact">
<div id='new-special'>



<div id="languages_block_top">
	<ul id="first-languages">
		{foreach from=$languages key=k item=language name="languages"}
			<li {if $language.iso_code == $lang_iso}class="selected_language"{/if}>
				{if $language.iso_code != $lang_iso}<a href="{$link->getLanguageLink($language.id_lang, $language.name)}" title="{$language.name}">{/if}
					<img src="{$img_lang_dir}{$language.id_lang}.jpg" alt="{$language.name}" />
				{if $language.iso_code != $lang_iso}</a>{/if}
			</li>
		{/foreach}
	</ul>
</div>



<a id="popupContactClose">bezárás (x)</a>



<div id="editorial_block_center" class="editorial_block">
{if $xml->body->$paragraph}<div class="rte">{$xml->body->$paragraph|stripslashes}</div>{/if}
</div>




<table class="products" width='600'>
<tr valign="top">

{if $new_products|@count > 0}

<td>
<table class="products" width='300'>

<caption><a href="{$base_dir}new-products.php" title="{l s='New products' mod='blocknewproducts'}">
Frissen érkezett automaták</a></caption>

{foreach from=$new_products item=newproduct name=myLoop}
<tr class="{if $smarty.foreach.myLoop.first}first_item{elseif $smarty.foreach.myLoop.last}last_item{else}item{/if}" valign="top">

<td width='82'>
<a href="{$newproduct.link}" title="{$newproduct.name|escape:htmlall:'UTF-8'}">
<img src="{$link->getImageLink($newproduct.link_rewrite, $newproduct.id_image, 'medium', $newproduct.name.jpg)}" alt="{$newproduct.legend|escape:htmlall:'UTF-8'}" class='aling_left' /></a>
</td>

<td width='218'>
<b><a href="{$newproduct.link}" title="{$newproduct.name|escape:htmlall:'UTF-8'}">
{$newproduct.name|strip_tags|escape:htmlall:'UTF-8'}</a></b><br />

{if $newproduct.description_short}<a href="{$newproduct.link}">{t text=$newproduct.description_short length='100' strip='true' encode='true'}</a>&nbsp;<a href="{$newproduct.link}"><img alt=">>" src="{$img_dir}bullet.gif"/></a>{/if}
</td>

</tr>
{/foreach}

</table>
		</td>
		{/if}




{if $special}
<td>
<table width='300'>

<caption><a href="{$base_dir}prices-drop.php" title="{l s='Specials' mod='blockspecials'}">Akciós automaták</a></caption>


<tr valign="top">
<td width='82'>

<a href="{$special.link}"><img src="{$link->getImageLink($special.link_rewrite, $special.id_image, 'medium')}" alt="{$special.legend|escape:htmlall:'UTF-8'}" height="{$mediumSize.height}" width="{$mediumSize.width}" title="{$special.name|escape:htmlall:'UTF-8'}" /></a>
{/if}
</td>


<td>
{if $special}
<b><a href="{$special.link}" title="{$special.name|escape:htmlall:'UTF-8'}">{$special.name|escape:htmlall:'UTF-8'}</a></b><br /><br />

<span class="price-discount">{displayWtPrice p=$special.price_without_reduction}</span><br />
{if $special.reduction_percent}<span class="reduction">(-{$special.reduction_percent}%)</span><br />{/if}
{if !$priceDisplay || $priceDisplay == 2}<br /><h4 class="price">{displayWtPrice p=$special.price}</h4>{if $priceDisplay == 2} {l s='+Tx'}{/if}{/if}
{if $priceDisplay == 2}<br />{/if}
{if $priceDisplay}<br /><h4 class="price">{displayWtPrice p=$special.price_tax_exc}</h4>{if $priceDisplay == 2} {l s='-Tx'}{/if}{/if}


</td>
</tr>
</table>
</td>
{/if}



</tr>
</table>

</div><!-- new-special -->
<div id="extranews" style="display: none;">{l s='New! Inquire about the shipping possibilities!'}</div>

</div><!-- popupContactClose -->
<div id="backgroundPopup"></div>

{/if}



<!-- Footer -->
			<div id="footer">{$HOOK_FOOTER}</div>
		</div>
	{/if}
	</body>
</html>