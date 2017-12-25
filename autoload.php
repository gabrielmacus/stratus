<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 24/12/2017
 * Time: 16:14
 */

define("ROOT_DIR",dirname(__FILE__)."/");

define("BASE_DIR",ROOT_DIR."base/");

define("BASE_CLASS_DIR",BASE_DIR."classes/");

define("CURRENT_URL",(isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");

function base_autoload($class)
{

    $includeClass = BASE_CLASS_DIR."{$class}.php";
    require($includeClass);
}

spl_autoload_register('base_autoload');
