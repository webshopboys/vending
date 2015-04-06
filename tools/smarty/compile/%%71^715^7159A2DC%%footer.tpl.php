<?php /* Smarty version 2.6.20, created on 2012-08-20 18:37:33
         compiled from /web/vendingoutlet/vendingoutlet.org/themes/prestashop/footer.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'stripslashes', '/web/vendingoutlet/vendingoutlet.org/themes/prestashop/footer.tpl', 38, false),array('modifier', 'count', '/web/vendingoutlet/vendingoutlet.org/themes/prestashop/footer.tpl', 47, false),array('modifier', 'escape', '/web/vendingoutlet/vendingoutlet.org/themes/prestashop/footer.tpl', 59, false),array('modifier', 'strip_tags', '/web/vendingoutlet/vendingoutlet.org/themes/prestashop/footer.tpl', 65, false),array('function', 'l', '/web/vendingoutlet/vendingoutlet.org/themes/prestashop/footer.tpl', 52, false),array('function', 't', '/web/vendingoutlet/vendingoutlet.org/themes/prestashop/footer.tpl', 67, false),array('function', 'displayWtPrice', '/web/vendingoutlet/vendingoutlet.org/themes/prestashop/footer.tpl', 99, false),)), $this); ?>
	<?php if (! $this->_tpl_vars['content_only']): ?>
			</div>

<!-- Right -->
			<div id="right_column" class="column">
				<?php echo $this->_tpl_vars['HOOK_RIGHT_COLUMN']; ?>

			</div>




<?php if (( $this->_tpl_vars['page_name'] == 'index' ) && ( ! isset ( $_REQUEST['call_method'] ) )): ?>

<div id="popupContact">
<div id='new-special'>



<div id="languages_block_top">
	<ul id="first-languages">
		<?php $_from = $this->_tpl_vars['languages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['languages'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['languages']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['language']):
        $this->_foreach['languages']['iteration']++;
?>
			<li <?php if ($this->_tpl_vars['language']['iso_code'] == $this->_tpl_vars['lang_iso']): ?>class="selected_language"<?php endif; ?>>
				<?php if ($this->_tpl_vars['language']['iso_code'] != $this->_tpl_vars['lang_iso']): ?><a href="<?php echo $this->_tpl_vars['link']->getLanguageLink($this->_tpl_vars['language']['id_lang'],$this->_tpl_vars['language']['name']); ?>
" title="<?php echo $this->_tpl_vars['language']['name']; ?>
"><?php endif; ?>
					<img src="<?php echo $this->_tpl_vars['img_lang_dir']; ?>
<?php echo $this->_tpl_vars['language']['id_lang']; ?>
.jpg" alt="<?php echo $this->_tpl_vars['language']['name']; ?>
" />
				<?php if ($this->_tpl_vars['language']['iso_code'] != $this->_tpl_vars['lang_iso']): ?></a><?php endif; ?>
			</li>
		<?php endforeach; endif; unset($_from); ?>
	</ul>
</div>



<a id="popupContactClose">bezárás (x)</a>



<div id="editorial_block_center" class="editorial_block">
<?php if ($this->_tpl_vars['xml']->body->{(($_var=$this->_tpl_vars['paragraph']) && substr($_var,0,2)!='__') ? $_var : $this->trigger_error("cannot access property \"$_var\"")}): ?><div class="rte"><?php echo ((is_array($_tmp=$this->_tpl_vars['xml']->body->{(($_var=$this->_tpl_vars['paragraph']) && substr($_var,0,2)!='__') ? $_var : $this->trigger_error("cannot access property \"$_var\"")})) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
</div><?php endif; ?>
</div>




<table class="products" width='600'>
<tr valign="top">

<?php if (count($this->_tpl_vars['new_products']) > 0): ?>

<td>
<table class="products" width='300'>

<caption><a href="<?php echo $this->_tpl_vars['base_dir']; ?>
new-products.php" title="<?php echo smartyTranslate(array('s' => 'New products','mod' => 'blocknewproducts'), $this);?>
">
Frissen érkezett automaták</a></caption>

<?php $_from = $this->_tpl_vars['new_products']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['myLoop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['myLoop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['newproduct']):
        $this->_foreach['myLoop']['iteration']++;
?>
<tr class="<?php if (($this->_foreach['myLoop']['iteration'] <= 1)): ?>first_item<?php elseif (($this->_foreach['myLoop']['iteration'] == $this->_foreach['myLoop']['total'])): ?>last_item<?php else: ?>item<?php endif; ?>" valign="top">

<td width='82'>
<a href="<?php echo $this->_tpl_vars['newproduct']['link']; ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['newproduct']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
">
<img src="<?php echo $this->_tpl_vars['link']->getImageLink($this->_tpl_vars['newproduct']['link_rewrite'],$this->_tpl_vars['newproduct']['id_image'],'medium',$this->_tpl_vars['newproduct']['name']['jpg']); ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['newproduct']['legend'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
" class='aling_left' /></a>
</td>

<td width='218'>
<b><a href="<?php echo $this->_tpl_vars['newproduct']['link']; ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['newproduct']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
">
<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['newproduct']['name'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
</a></b><br />

<?php if ($this->_tpl_vars['newproduct']['description_short']): ?><a href="<?php echo $this->_tpl_vars['newproduct']['link']; ?>
"><?php echo smartyTruncate(array('text' => $this->_tpl_vars['newproduct']['description_short'],'length' => '100','strip' => 'true','encode' => 'true'), $this);?>
</a>&nbsp;<a href="<?php echo $this->_tpl_vars['newproduct']['link']; ?>
"><img alt=">>" src="<?php echo $this->_tpl_vars['img_dir']; ?>
bullet.gif"/></a><?php endif; ?>
</td>

</tr>
<?php endforeach; endif; unset($_from); ?>

</table>
		</td>
		<?php endif; ?>




<?php if ($this->_tpl_vars['special']): ?>
<td>
<table width='300'>

<caption><a href="<?php echo $this->_tpl_vars['base_dir']; ?>
prices-drop.php" title="<?php echo smartyTranslate(array('s' => 'Specials','mod' => 'blockspecials'), $this);?>
">Akciós automaták</a></caption>


<tr valign="top">
<td width='82'>

<a href="<?php echo $this->_tpl_vars['special']['link']; ?>
"><img src="<?php echo $this->_tpl_vars['link']->getImageLink($this->_tpl_vars['special']['link_rewrite'],$this->_tpl_vars['special']['id_image'],'medium'); ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['special']['legend'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
" height="<?php echo $this->_tpl_vars['mediumSize']['height']; ?>
" width="<?php echo $this->_tpl_vars['mediumSize']['width']; ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['special']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
" /></a>
<?php endif; ?>
</td>


<td>
<?php if ($this->_tpl_vars['special']): ?>
<b><a href="<?php echo $this->_tpl_vars['special']['link']; ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['special']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['special']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
</a></b><br /><br />

<span class="price-discount"><?php echo Product::displayWtPrice(array('p' => $this->_tpl_vars['special']['price_without_reduction']), $this);?>
</span><br />
<?php if ($this->_tpl_vars['special']['reduction_percent']): ?><span class="reduction">(-<?php echo $this->_tpl_vars['special']['reduction_percent']; ?>
%)</span><br /><?php endif; ?>
<?php if (! $this->_tpl_vars['priceDisplay'] || $this->_tpl_vars['priceDisplay'] == 2): ?><br /><h4 class="price"><?php echo Product::displayWtPrice(array('p' => $this->_tpl_vars['special']['price']), $this);?>
</h4><?php if ($this->_tpl_vars['priceDisplay'] == 2): ?> <?php echo smartyTranslate(array('s' => '+Tx'), $this);?>
<?php endif; ?><?php endif; ?>
<?php if ($this->_tpl_vars['priceDisplay'] == 2): ?><br /><?php endif; ?>
<?php if ($this->_tpl_vars['priceDisplay']): ?><br /><h4 class="price"><?php echo Product::displayWtPrice(array('p' => $this->_tpl_vars['special']['price_tax_exc']), $this);?>
</h4><?php if ($this->_tpl_vars['priceDisplay'] == 2): ?> <?php echo smartyTranslate(array('s' => '-Tx'), $this);?>
<?php endif; ?><?php endif; ?>


</td>
</tr>
</table>
</td>
<?php endif; ?>



</tr>
</table>

</div><!-- new-special -->
<div id="extranews" style="display: none;"><?php echo smartyTranslate(array('s' => 'New! Inquire about the shipping possibilities!'), $this);?>
</div>

</div><!-- popupContactClose -->
<div id="backgroundPopup"></div>

<?php endif; ?>



<!-- Footer -->
			<div id="footer"><?php echo $this->_tpl_vars['HOOK_FOOTER']; ?>
</div>
		</div>
	<?php endif; ?>
	</body>
</html>