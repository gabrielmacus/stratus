<?php
/**
 * Created by PhpStorm.
 * User: Gabriel
 * Date: 22/12/2017
 * Time: 02:24 PM
 */


include "autoload.php";


$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/stratus/{controller}/{action}','controllerHandler');
});

function controllerHandler($vars)
{

    $controllerName = ucfirst($vars["controller"])."Controller";

    $daoName = ucfirst($vars["controller"])."DAO";

    include ROOT_DIR."app/modules/{$vars["controller"]}/controller/{$controllerName}.php";

    include ROOT_DIR."/app/modules/{$vars["controller"]}/dao/{$daoName}.php";

    $action = $vars["action"];

    $mongoConnection = new MongoConnection("stratus");

    $DAO =  new $daoName($mongoConnection);

    $controller = new $controllerName($DAO);



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

        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
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