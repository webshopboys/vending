<?php /* Smarty version 2.6.20, created on 2012-05-16 00:26:22
         compiled from /web/vendingoutlet/vendingoutlet.org/modules/sendtoafriend/product_page.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'intval', '/web/vendingoutlet/vendingoutlet.org/modules/sendtoafriend/product_page.tpl', 1, false),array('function', 'l', '/web/vendingoutlet/vendingoutlet.org/modules/sendtoafriend/product_page.tpl', 1, false),)), $this); ?>
<li><a href="<?php echo $this->_tpl_vars['this_path']; ?>
sendtoafriend-form.php?id_product=<?php echo ((is_array($_tmp=$_GET['id_product'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
"><?php echo smartyTranslate(array('s' => 'Send to a friend','mod' => 'sendtoafriend'), $this);?>
</a></li>