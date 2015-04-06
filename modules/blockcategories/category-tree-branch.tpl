<li{if $last == 'true'} class="last" {/if}>
	<a href="{$node.link}" {if $node.id == $currentCategoryId}class="selected"{/if} title="{$node.desc|strip_tags:false|truncate:200:'...'}">{$node.name}</a>
	{if $node.children|@count > 0}
		<ul>
		{foreach from=$node.children item=child name=categoryTreeBranch}
			{if $smarty.foreach.categoryTreeBranch.last}
						{include file=$tpl_dir./category-tree-branch.tpl node=$child last='true' }
			{else}
						{include file=$tpl_dir./category-tree-branch.tpl node=$child last='false'}
			{/if}
		{/foreach}
		
		</ul>
	{/if}
</li>

{if $last == 'true' && $cookie->isLogged()}
<li>
<a href="product_download.php?type=excel&home_category_name={$home_category_name}" title=""><font color="red">{$product_download_text}</font></a>
</li>
{/if}

