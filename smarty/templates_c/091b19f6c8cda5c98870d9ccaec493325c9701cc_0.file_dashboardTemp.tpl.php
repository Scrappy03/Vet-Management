<?php
/* Smarty version 5.5.0, created on 2025-06-05 13:34:43
  from 'file:layouts/dashboardTemp.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.0',
  'unifunc' => 'content_68419cf326ecc0_77011842',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '091b19f6c8cda5c98870d9ccaec493325c9701cc' => 
    array (
      0 => 'layouts/dashboardTemp.tpl',
      1 => 1749081764,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_68419cf326ecc0_77011842 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/Users/callum/Documents/University/AWD/VetCare/views/layouts';
$_smarty_tpl->getInheritance()->init($_smarty_tpl, false);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Vet Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/styles.min.css">
    <link rel="stylesheet" href="css/dashboard.css">

</head>

<body id="page-<?php echo $_smarty_tpl->getValue('view_name');?>
">
    <?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_58345943768419cf326dee0_70465310', "body");
?>

    <?php echo '<script'; ?>
 src="./js/scripts-vendor.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="./js/scripts.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="./js/toast-utils.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="./js/dashboard.js"><?php echo '</script'; ?>
><?php }
/* {block "body"} */
class Block_58345943768419cf326dee0_70465310 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/Users/callum/Documents/University/AWD/VetCare/views/layouts';
}
}
/* {/block "body"} */
}
