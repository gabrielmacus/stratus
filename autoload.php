<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 24/12/2017
 * Time: 16:14
 */

define("ROOT_DIR",dirname(__FILE__)."/");

define("BASE_DIR",ROOT_DIR."base/");

define("CURRENT_URL",(isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");

function base_autoload($class)
{

    switch (true)
    {
        case (file_exists(BASE_DIR."models/{$class}.php")):



            $includeClass = BASE_DIR."models/{$class}.php";
            break;
        case (file_exists(BASE_DIR."daos/{$class}.php")):

            $includeClass = BASE_DIR."daos/{$class}.php";

            break;

        case (file_exists(BASE_DIR."services/{$class}.php")):

            $includeClass = BASE_DIR."services/{$class}.php";

            break;

        case (file_exists(BASE_DIR."exceptions/{$class}.php")):

            $includeClass = BASE_DIR."exceptions/{$class}.php";

            break;
        case (file_exists(BASE_DIR."traits/{$class}.php")):

            $includeClass = BASE_DIR."traits/{$class}.php";

            break;
        case (file_exists(BASE_DIR."gui-components/{$class}/{$class}.php")):

            $includeClass = BASE_DIR."gui-components/{$class}/{$class}.php";



            break;
        case (file_exists(BASE_DIR."interfaces/{$class}.php")):

            $includeClass = BASE_DIR."interfaces/{$class}.php";

            break;
        case (file_exists(BASE_DIR."controllers/{$class}.php")):

            $includeClass = BASE_DIR."controllers/{$class}.php";

            break;
    }

    if(!empty($includeClass))
    {

        require($includeClass);
    }

}

spl_autoload_register('base_autoload');

include(ROOT_DIR."/vendor/autoload.php");