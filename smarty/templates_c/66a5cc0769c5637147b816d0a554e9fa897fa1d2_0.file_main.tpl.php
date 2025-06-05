<?php
/* Smarty version 5.5.0, created on 2025-06-05 13:34:38
  from 'file:layouts/main.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.0',
  'unifunc' => 'content_68419ceed40641_33475294',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '66a5cc0769c5637147b816d0a554e9fa897fa1d2' => 
    array (
      0 => 'layouts/main.tpl',
      1 => 1749081755,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_68419ceed40641_33475294 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/Users/callum/Documents/University/AWD/VetCare/views/layouts';
$_smarty_tpl->getInheritance()->init($_smarty_tpl, false);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- The head contains metadata and linked resources for the page. -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="VetCare - Professional veterinary management system for staff and administrators">
    <meta name="keywords" content="veterinary, clinic, pet care, animal hospital, staff portal">
    <title>Veterinary Clinic</title>
    <link rel="stylesheet" href="./css/styles.min.css">
    <link rel="icon" type="image/png" href="./images/Monogram Black HQ.png">
    <link rel="stylesheet" href="css/transitions.css">
</head>

<body id="page-<?php echo $_smarty_tpl->getValue('view_name');?>
">
    <!-- Header section with logo and branding. -->
    <header class="bg-custom-primary text-white py-4">
        <div class="container">
            <img src="./images/Logo White.webp" alt="VetCare Logo" class="img-fluid" style="max-height: 80px;">
        </div>
    </header>

    <?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_149860518868419ceed3f552_91700521', "body");
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
 src="./js/page-transitions.js"><?php echo '</script'; ?>
>
</body>

</html><?php }
/* {block "body"} */
class Block_149860518868419ceed3f552_91700521 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/Users/callum/Documents/University/AWD/VetCare/views/layouts';
}
}
/* {/block "body"} */
}
