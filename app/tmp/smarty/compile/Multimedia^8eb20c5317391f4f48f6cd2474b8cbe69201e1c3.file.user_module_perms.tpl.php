<?php /* Smarty version Smarty-3.1.12, created on 2012-09-26 16:36:55
         compiled from "/home/bato/workspace/github/bedita/bedita-app/views/elements/user_module_perms.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1038902012504e103341f803-51672805%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8eb20c5317391f4f48f6cd2474b8cbe69201e1c3' => 
    array (
      0 => '/home/bato/workspace/github/bedita/bedita-app/views/elements/user_module_perms.tpl',
      1 => 1347894656,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1038902012504e103341f803-51672805',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_504e10334ee387_62992364',
  'variables' => 
  array (
    'BEAuthUser' => 0,
    'html' => 0,
    'view' => 0,
    'conf' => 0,
    'session' => 0,
    'key' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_504e10334ee387_62992364')) {function content_504e10334ee387_62992364($_smarty_tpl) {?><?php if (!is_callable('smarty_block_t')) include '/home/bato/workspace/github/bedita/bedita-app/vendors/_smartyPlugins/block.t.php';
?>
<div class="insidecol" style="margin-top:50px; padding-top:5px; padding-bottom:5px; border-top:5px solid gray; border-bottom:5px solid gray;">

<?php if (!empty($_smarty_tpl->tpl_vars['BEAuthUser']->value['userid'])){?>
<?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
User<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
: <span class="on"><?php echo $_smarty_tpl->tpl_vars['BEAuthUser']->value['realname'];?>
</span>
<?php }?>

<ul class="bordered" style="border-top:1px solid gray; border-bottom:1px solid gray; padding:2px 0px 0px 0px; margin:10px 0px 10px 0px">

	<li style="padding-left:0px"><a href="<?php echo $_smarty_tpl->tpl_vars['html']->value->url('/');?>
">› <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Home<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</a></li>
	<?php if (!empty($_smarty_tpl->tpl_vars['BEAuthUser']->value['userid'])){?>
		<li style="padding-left:0px"><a href="<?php echo $_smarty_tpl->tpl_vars['html']->value->url('/authentications/logout');?>
">› <?php $_smarty_tpl->smarty->_tag_stack[] = array('t', array()); $_block_repeat=true; echo smarty_block_t(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
Exit<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_t(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>
</a></li>
	<?php }?>

</ul>

<?php echo $_smarty_tpl->tpl_vars['view']->value->element('colophon');?>


<ul class="bordered" style="border-top:1px solid gray; border-bottom:1px solid gray; padding:2px 0px 0px 0px; margin:10px 0px 10px 0px">
	<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['conf']->value->langsSystem; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>	
	<li style="padding-left:0px"><a <?php if ($_smarty_tpl->tpl_vars['session']->value->read('Config.language')==$_smarty_tpl->tpl_vars['key']->value){?>class="on"<?php }?> href="<?php echo $_smarty_tpl->tpl_vars['html']->value->base;?>
/lang/<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
">› <?php echo $_smarty_tpl->tpl_vars['item']->value;?>
</a></li>
	<?php } ?>
</ul>

<div id="handlerChangeAlert"></div>

</div>


<?php }} ?>