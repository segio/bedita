<?php /* Smarty version Smarty-3.1.12, created on 2012-09-26 16:37:47
         compiled from "/home/bato/workspace/github/bedita/bedita-app/views/elements/texteditor.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1156058892504dfd98ab7bc4-70084703%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '762fecf53f65618bd01e12b148494cca7cddf9b3' => 
    array (
      0 => '/home/bato/workspace/github/bedita/bedita-app/views/elements/texteditor.tpl',
      1 => 1347894656,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1156058892504dfd98ab7bc4-70084703',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_504dfd98b87ce2_97430769',
  'variables' => 
  array (
    'conf' => 0,
    'html' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_504dfd98b87ce2_97430769')) {function content_504dfd98b87ce2_97430769($_smarty_tpl) {?><?php if (((($tmp = @$_smarty_tpl->tpl_vars['conf']->value->mce)===null||$tmp==='' ? false : $tmp))){?>
	
	<?php echo $_smarty_tpl->tpl_vars['html']->value->script("tiny_mce/tiny_mce",false);?>

	<?php echo $_smarty_tpl->tpl_vars['html']->value->script("tiny_mce/tiny_mce_default_init",false);?>


<?php }elseif(((($tmp = @$_smarty_tpl->tpl_vars['conf']->value->wymeditor)===null||$tmp==='' ? false : $tmp))){?>

	<?php echo $_smarty_tpl->tpl_vars['html']->value->script("wymeditor/jquery.wymeditor.pack",false);?>

	<?php echo $_smarty_tpl->tpl_vars['html']->value->script("wymeditor/wymeditor_default_init",false);?>


<?php }elseif(((($tmp = @$_smarty_tpl->tpl_vars['conf']->value->ckeditor)===null||$tmp==='' ? false : $tmp))){?>

	<?php echo $_smarty_tpl->tpl_vars['html']->value->script("ckeditor/ckeditor",false);?>

	<?php echo $_smarty_tpl->tpl_vars['html']->value->script("ckeditor/adapters/jquery",false);?>

	<?php echo $_smarty_tpl->tpl_vars['html']->value->script("ckeditor/ckeditor_default_init",false);?>

	
<?php }?><?php }} ?>