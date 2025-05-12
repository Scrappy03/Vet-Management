<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

// Include the Composer autoloader
require_once(__DIR__ . '/includes/boot.include.php');

if($_GET['p']) {
    $Smarty->assign('view_name', $_GET['p']);
    require_once('controllers/'.$_GET['p'].'.php');
    $Smarty->display('pages/'.$_GET['p'].'.tpl');
}else{
    $Smarty->assign('view_name', 'home');
    require_once('controllers/home.php');
    $Smarty->display('pages/home.tpl');
}