<?php
session_start();

// load default controller
spl_autoload_register(function($className) {
    $class = str_replace('\\',DIRECTORY_SEPARATOR,$className);
    $file = "$class.php";
    @include_once $file;
});

$control = 'defaultAction';
if(isset($_REQUEST['control']) && !empty($_REQUEST['control'])) $control = $_REQUEST['control'];

$action = 'defaultAction';
if(isset($_REQUEST['action']) && !empty($_REQUEST['action'])) $action = $_REQUEST['action'];

$controllerName = 'controllers\Controller';
if(class_exists($controllerName)) {
    $myControl = new $controllerName($control, $action);
    $myControl->execute();
}