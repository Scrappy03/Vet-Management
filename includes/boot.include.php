<?php
// Initialize session handling
session_start();

// Load core dependencies
require_once(__DIR__.'/config.include.php');
require_once(__DIR__.'/db.include.php');
require_once(__DIR__ . '/../vendor/autoload.php');

// Import authentication framework
require_once(__DIR__.'/auth.include.php');

// Configure template engine
$Smarty = new \Smarty\Smarty();
$Smarty->setTemplateDir(__DIR__.'/../views')
->setPluginsDir(array(__DIR__.'/../smarty/plugins'))
->setCompileDir(__DIR__.'/../smarty/templates_c')
->setCacheDir(__DIR__.'/../smarty/cache')
->setConfigDir(__DIR__.'/../smarty/configs');

// Prepare user context for templates if authenticated
if (function_exists('is_logged_in') && is_logged_in()) {
    $Smarty->assign('is_logged_in', true);
    $Smarty->assign('user', $_SESSION['user_data']);
    $Smarty->assign('user_name', $_SESSION['user_name']);
}

require_once(__DIR__.'/autoloader.include.php');