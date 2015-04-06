<!-- Block informations module -->
<div id="informations_block_left" class="block">
	<h4>{l s='Information' mod='blockcms'}</h4>
	<ul class="block_content">
		{foreach from=$cmslinks item=cmslink}
			<li><a href="{$cmslink.link}" title="{$cmslink.meta_title}">{$cmslink.meta_title}</a></li>
		{/foreach}
	</ul>
</div>
<!-- /Block informations module -->