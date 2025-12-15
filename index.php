<?php

use App\Controller\LoginController;



try {
    error_reporting(E_ALL);
    include 'vendor/autoload.php';

    session_start();
    
    $path = $_GET['path'];
    
    $routes = include 'routes.php';

    $allowedRoutes = $routes['guest'];
    
    if ($_SESSION['user']) {
        $allowedRoutes = array_merge($allowedRoutes, $routes['auth']);
    }

    foreach ($allowedRoutes as $url => $route) {
        
        if (strpos($path ,$url) !== false) {
            $currentRoute = $route;
            break;
        }
    }

    if(!$currentRoute) {
        echo (new LoginController())->index();
        exit;
    }

    
    $controllerName = $currentRoute[0];
    $action = $currentRoute[1];

    $controller = new $controllerName();
    echo $controller->$action();

} catch (Throwable $e) {
    echo "<pre>";
    var_dump($e);
    echo "</pre>";
}
