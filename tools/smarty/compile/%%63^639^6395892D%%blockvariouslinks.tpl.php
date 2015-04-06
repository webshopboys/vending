<?php /* Smarty version 2.6.20, created on 2012-05-16 00:19:36
         compiled from /web/vendingoutlet/vendingoutlet.org/modules/blockvariouslinks/blockvariouslinks.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'l', '/web/vendingoutlet/vendingoutlet.org/modules/blockvariouslinks/blockvariouslinks.tpl', 3, false),array('modifier', 'addslashes', '/web/vendingoutlet/vendingoutlet.org/modules/blockvariouslinks/blockvariouslinks.tpl', 8, false),)), $this); ?>
<!-- MODULE Block various links -->
<ul class="block_various_links" id="block_various_links_footer">
	<li class="first_item"><a href="<?php echo $this->_tpl_vars['base_dir']; ?>
prices-drop.php" title=""><?php echo smartyTranslate(array('s' => 'Specials','mod' => 'blockvariouslinks'), $this);?>
</a></li>
	<li class="item"><a href="<?php echo $this->_tpl_vars['base_dir']; ?>
new-products.php" title=""><?php echo smartyTranslate(array('s' => 'New products','mod' => 'blockvariouslinks'), $this);?>
</a></li>
	<li class="item"><a href="<?php echo $this->_tpl_vars['base_dir']; ?>
best-sales.php" title=""><?php echo smartyTranslate(array('s' => 'Top sellers','mod' => 'blockvariouslinks'), $this);?>
</a></li>
	<li class="item"><a href="<?php echo $this->_tpl_vars['base_dir_ssl']; ?>
contact-form.php" title=""><?php echo smartyTranslate(array('s' => 'Contact us','mod' => 'blockvariouslinks'), $this);?>
</a></li>
	<?php $_from = $this->_tpl_vars['cmslinks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['cmslink']):
?>
		<li class="item"><a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['cmslink']['link'])) ? $this->_run_mod_handler('addslashes', true, $_tmp) : addslashes($_tmp)); ?>
" title="<?php echo $this->_tpl_vars['cmslink']['meta_title']; ?>
"><?php echo $this->_tpl_vars['cmslink']['meta_title']; ?>
</a></li>
	<?php endforeach; endif; unset($_from); ?>
</ul>
<!-- /MODULE Block various links -->