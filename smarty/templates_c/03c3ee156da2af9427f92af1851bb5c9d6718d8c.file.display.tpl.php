<?php /* Smarty version Smarty-3.1.8, created on 2012-04-23 14:17:14
         compiled from "/home/image/public_html/templates/display.tpl" */ ?>
<?php /*%%SmartyHeaderCode:5447299664f957b92be6ae3-56737974%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '03c3ee156da2af9427f92af1851bb5c9d6718d8c' => 
    array (
      0 => '/home/image/public_html/templates/display.tpl',
      1 => 1335205030,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5447299664f957b92be6ae3-56737974',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_4f957b92c1cef4_75254478',
  'variables' => 
  array (
    'web_root' => 0,
    'image' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f957b92c1cef4_75254478')) {function content_4f957b92c1cef4_75254478($_smarty_tpl) {?><!DOCTYPE html>
<html>
<head>
<title>Images for All</title>
<link rel='shortcut icon' type='image/png' href='<?php echo $_smarty_tpl->tpl_vars['web_root']->value;?>
/img/Nyan-Cat-Original_032x032_32.png' />
</head>

<body>
<a href='../random'>
 <img id='main_image' src='<?php echo $_smarty_tpl->tpl_vars['image']->value;?>
' />
</a>
<p>Click picture for more, or <a href='http://brazzify.me/?s=<?php echo $_smarty_tpl->tpl_vars['image']->value;?>
'>Brazzify</a></p>
</body>
</html>
<?php }} ?>