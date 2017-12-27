<?php
/**
 * Created by PhpStorm.
 * User: Gabriel
 * Date: 22/12/2017
 * Time: 02:24 PM
 */


include "autoload.php";


$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {

    $r->addRoute(['GET', 'POST'], '/stratus/{controller}/{action}','controllerHandler');
    $r->addRoute(['GET', 'POST'], '/stratus/{controller}/{action}/{id}','controllerHandler');



});

function controllerHandler($vars)
{


    $controllerName = ucfirst($vars["controller"])."Controller";

    $daoName = ucfirst($vars["controller"])."DAO";

    $modelName=ucfirst($vars["controller"]);

    $exceptionName=ucfirst($vars["controller"])."Exception";

    include ROOT_DIR."app/modules/{$vars["controller"]}/controller/{$controllerName}.php";

    include ROOT_DIR."/app/modules/{$vars["controller"]}/dao/{$daoName}.php";

    include ROOT_DIR."/app/modules/{$vars["controller"]}/exceptions/{$exceptionName}.php";

    include ROOT_DIR."/app/modules/{$vars["controller"]}/model/{$modelName}.php";

    $views = scandir(ROOT_DIR."/app/modules/{$vars["controller"]}/views/");

    foreach ($views as $k=>$v)
    {
        if($v!="." && $v!= "..")
        {
            include ROOT_DIR."/app/modules/{$vars["controller"]}/views/{$v}";
        }
    }

    $action = $vars["action"];

    $mongoConnection = new MongoConnection("stratus");

    $DAO =  new $daoName($mongoConnection);

    if($vars["httpMethod"] == "GET")
    {
        $_GET["_type"]  = $modelName;

        if(!empty($vars["id"]))
        {
            $_GET["_id"] =  new MongoId($vars["id"]);
        }

    }
    else
    {
        $_POST["_type"] = $modelName;
        if(!empty($vars["id"]))
        {
            $_POST["_id"] =  new MongoId($vars["id"]);
        }
    }





    $controller = new $controllerName($DAO,$_POST,$_GET);

    $controller->$action();


}

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found

        var_dump(404);

        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $vars["httpMethod"] = $httpMethod;
        // ... call $handler with $vars

        $handler($vars);
        break;
}
/*

$mongoConnection = new MongoConnection("stratus");

$mongoConnection->connect();

$DAO = new BeanDAO($mongoConnection);


$beans = $DAO->read(new Bean());

$view =new BeanList($beans);

echo $view->getHTML();
*/