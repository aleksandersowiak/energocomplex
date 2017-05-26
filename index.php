<?php
defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/'));
defined('APPLICATION_ENV')
|| define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
define('DEFAULT_LANG', 'pl');
spl_autoload_register(function ($class_name) {
    include_once '/Classes/' . $class_name . '.php';
});
foreach (glob("models/*.php") as $filename) {
    require_once($filename);
}
foreach (glob("controllers/*.php") as $filename) {
    require_once($filename);
}

$loader = new Loader($_GET);
$controller = $loader->CreateController();
$controller->ExecuteAction();

function __($text, $pl = false)
{
    global $base_lang;
    $base_lang = DEFAULT_LANG;
    if($pl == false) {
        $base_lang = ($_GET['language'] == '') ? DEFAULT_LANG : $_GET['language'];
    }

    if (isset($base_lang) AND file_exists(APPLICATION_PATH . "/Languages/{$base_lang}.php") && strlen($base_lang) <= 3) {
        include(APPLICATION_PATH . "/Languages/{$base_lang}.php");
        if (isset($lang[$text]) && !empty($lang[$text])) {
            return $lang[$text];
        }
    }
    return $text;
}

function createUrl($controller = '', $action = '')
{
    if ($controller == '' || $controller == NULL || $controller == false) {
        $controller = 'home';
    }
    if ($action == '' || $action == NULL || $action == false) {
        $action = 'index';
    }
    if ($_GET['language'] == "") {
        $language = "pl";
    } else {
        $language = $_GET['language'];
    }
    return "/" . $language . "/" . $controller . "/" . $action;
}
