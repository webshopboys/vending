<?php /* Smarty version 2.6.20, created on 2013-12-13 14:00:39
         compiled from /web/vendingoutlet/vendingoutlet.org/modules/blockcms/blockcms.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'l', '/web/vendingoutlet/vendingoutlet.org/modules/blockcms/blockcms.tpl', 3, false),)), $this); ?>
<!-- Block informations module -->
<div id="informations_block_left" class="block">
	<h4><?php echo smartyTranslate(array('s' => 'Information','mod' => 'blockcms'), $this);?>
</h4>
	<ul class="block_content">
		<?php $_from = $this->_tpl_vars['cmslinks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['cmslink']):
?>
			<li><a href="<?php echo $this->_tpl_vars['cmslink']['link']; ?>
" title="<?php echo $this->_tpl_vars['cmslink']['meta_title']; ?>
"><?php echo $this->_tpl_vars['cmslink']['meta_title']; ?>
</a></li>
		<?php endforeach; endif; unset($_from); ?>
	</ul>
</div>
<!-- /Block informations module -->