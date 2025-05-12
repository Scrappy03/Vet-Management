<?php
// Include Smarty
require_once(__DIR__ . '/../vendor/autoload.php');

$Smarty = new \Smarty\Smarty();
$Smarty->setTemplateDir(__DIR__.'/../views')
->setPluginsDir(array(__DIR__.'/../smarty/plugins'))
->setCompileDir(__DIR__.'/../smarty/templates_c')
->setCacheDir(__DIR__.'/../smarty/cache')
->setConfigDir(__DIR__.'/../smarty/configs');

if($_GET['p']) {
    require_once('controllers/'.$_GET['p'].'.php');
$Smarty->display('pages/'.$_GET['p'].'.tpl');
}else{
    require_once('controllers/home.php');
$Smarty->display('pages/home.tpl');
}