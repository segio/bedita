<?php /* Smarty version Smarty-3.1.12, created on 2012-09-26 16:37:37
         compiled from "/home/bato/workspace/github/bedita/bedita-app/views/areas/inc/menucommands.tpl" */ ?>
<?php /*%%SmartyHeaderCode:170136730450535c50620825-42958174%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd8bd11d97382412cac3abeb60ce0deea7e464631' => 
    array (
      0 => '/home/bato/workspace/github/bedita/bedita-app/views/areas/inc/menucommands.tpl',
      1 => 1347894656,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '170136730450535c50620825-42958174',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_50535c506beba2_83450047',
  'variables' => 
  array (
    'fixed' => 0,
    'html' => 0,
    'currentModule' => 0,
    'moduleName' => 0,
    'back' => 0,
    'view' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_50535c506beba2_83450047')) {function content_50535c506beba2_83450047($_smarty_tpl) {?><?php if (!is_callable('smarty_function_assign_concat')) include '/home/bato/workspace/github/bedita/bedita-app/vendors/_smartyPlugins/function.assign_concat.php';
if (!is_callable('smarty_block_t')) include '/home/bato/workspace/github/bedita/bedita-app/vendors/_smartyPlugins/block.t.php';
?>


<div class="secondacolonna <?php if (!empty($_smarty_tpl->tpl_vars['fixed']->value)){?>fixed<?php }?>">
	

	<?php echo smarty_function_assign_concat(array('var'=>"back",1=>$_smarty_tpl->tpl_vars['html']->value->url('/'),2=>$_smarty_tpl->tpl_vars['currentModule']->value['url']),$_smarty_tpl);?>



	<div class="modules">
		<label class="<?php echo $_smarty_tpl->tpl_vars['moduleName']->value;?>
" rel="<?php echo $_smarty_tpl->tpl_vars['back']->value;?>
"><?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
<?php echo $_smarty_tpl->tpl_vars['currentModule']->value['label'];?>
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</label>
	</div> 
	
	<?php if (!empty($_smarty_tpl->tpl_vars['view']->value->action)){?>
	
	<div class="insidecol">
		<input class="bemaincommands" type="button" value=" <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
save<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 " name="save" id="saveBEObject" />
		<input class="bemaincommands" style="display:inline" type="button" value="<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
delete<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
" name="delete" id="delBEObject" />
	</div>
	
	<?php }?>
</div>


<?php }} ?>