<?php /* Smarty version 2.6.20, created on 2012-05-16 00:19:36
         compiled from /web/vendingoutlet/vendingoutlet.org/modules/blockcategories/category-tree-branch.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'strip_tags', '/web/vendingoutlet/vendingoutlet.org/modules/blockcategories/category-tree-branch.tpl', 2, false),array('modifier', 'truncate', '/web/vendingoutlet/vendingoutlet.org/modules/blockcategories/category-tree-branch.tpl', 2, false),array('modifier', 'count', '/web/vendingoutlet/vendingoutlet.org/modules/blockcategories/category-tree-branch.tpl', 3, false),)), $this); ?>
<li<?php if ($this->_tpl_vars['last'] == 'true'): ?> class="last" <?php endif; ?>>
	<a href="<?php echo $this->_tpl_vars['node']['link']; ?>
" <?php if ($this->_tpl_vars['node']['id'] == $this->_tpl_vars['currentCategoryId']): ?>class="selected"<?php endif; ?> title="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['node']['desc'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('truncate', true, $_tmp, 200, '...') : smarty_modifier_truncate($_tmp, 200, '...')); ?>
"><?php echo $this->_tpl_vars['node']['name']; ?>
</a>
	<?php if (count($this->_tpl_vars['node']['children']) > 0): ?>
		<ul>
		<?php $_from = $this->_tpl_vars['node']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['categoryTreeBranch'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['categoryTreeBranch']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['child']):
        $this->_foreach['categoryTreeBranch']['iteration']++;
?>
			<?php if (($this->_foreach['categoryTreeBranch']['iteration'] == $this->_foreach['categoryTreeBranch']['total'])): ?>
						<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['tpl_dir'])."./category-tree-branch.tpl", 'smarty_include_vars' => array('node' => $this->_tpl_vars['child'],'last' => 'true')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			<?php else: ?>
						<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['tpl_dir'])."./category-tree-branch.tpl", 'smarty_include_vars' => array('node' => $this->_tpl_vars['child'],'last' => 'false')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
		
		</ul>
	<?php endif; ?>
</li>

<?php if ($this->_tpl_vars['last'] == 'true' && $this->_tpl_vars['cookie']->isLogged()): ?>
<li>
<a href="product_download.php?type=excel&home_category_name=<?php echo $this->_tpl_vars['home_category_name']; ?>
" title=""><font color="red"><?php echo $this->_tpl_vars['product_download_text']; ?>
</font></a>
</li>
<?php endif; ?>
