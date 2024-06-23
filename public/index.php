<?php
if( !session_id() ) {
    session_start();
}
require '../vendor/autoload.php';
use League\Plates\Engine;
use Aura\SqlQuery\QueryFactory;
use \Tamtamchik\SimpleFlash\Flash;

$containerBuilder = new DI\ContainerBuilder();
$containerBuilder->addDefinitions([
    Engine::class => function(){
        return new Engine(__DIR__ .'/../app/views');
    },

    PDO::class => function() {
        return new PDO('mysql:host=localhost;dbname=diplom;', 'root', '');
    },

    QueryFactory::class => function(){
        return new QueryFactory('mysql');
    }

]);



$container = $containerBuilder->build();




$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/welcome/marlindev/diplom2/public/', ['App\HomeController','index']);
    $r->addRoute('GET', '/welcome/marlindev/diplom2/public/index.php', ['App\HomeController','index']);
    $r->addRoute('GET', '/welcome/marlindev/diplom2/public/index', ['App\HomeController','index']);
    $r->addRoute('GET', '/welcome/marlindev/diplom2/public/users', ['App\HomeController','index']);
    $r->addRoute('GET', '/welcome/marlindev/diplom2/public/users.php', ['App\HomeController','index']);




    $r->addRoute('GET', '/welcome/marlindev/diplom2/public/register', ['App\HomeController','page_register']);
    $r->addRoute('POST', '/welcome/marlindev/diplom2/public/registration', ['App\UserController','register_user']);
    $r->addRoute('GET', '/welcome/marlindev/diplom2/public/login', ['App\HomeController','login']);
    $r->addRoute('POST', '/welcome/marlindev/diplom2/public/login_form', ['App\UserController','login_user']);
    $r->addRoute('GET', '/welcome/marlindev/diplom2/public/logout', ['App\UserController','logoutUser']);

    $r->addRoute('GET', '/welcome/marlindev/diplom2/public/setuserasadmin', ['App\UserController','setUserAsAdmin']);

    $r->addRoute('GET', '/welcome/marlindev/diplom2/public/user/{id:\d+}', ['App\HomeController','page_profile']);
    $r->addRoute('GET', '/welcome/marlindev/diplom2/public/user/edit/{id:\d+}', ['App\HomeController','edit']);
    $r->addRoute('POST', '/welcome/marlindev/diplom2/public/edit/user/{id:\d+}', ['App\UserController','edit_user']);
    $r->addRoute('GET', '/welcome/marlindev/diplom2/public/user/add', ['App\HomeController','create_user']);
    $r->addRoute('POST', '/welcome/marlindev/diplom2/public/add/newuser', ['App\HomeController','createNewUser']);
    $r->addRoute('GET', '/welcome/marlindev/diplom2/public/user/security/{id:\d+}', ['App\HomeController','security']);
    $r->addRoute('GET', '/welcome/marlindev/diplom2/public/user/delete/{id:\d+}', ['App\UserController','deleteUser']);

    $r->addRoute('GET', '/welcome/marlindev/diplom2/public/user/status/{id:\d+}', ['App\HomeController','status']);
    $r->addRoute('GET', '/welcome/marlindev/diplom2/public/user/media/{id:\d+}', ['App\HomeController','media']);

    $r->addRoute('POST', '/welcome/marlindev/diplom2/public/status/user/{id:\d+}', ['App\UserController','changeStatus']);
    $r->addRoute('POST', '/welcome/marlindev/diplom2/public/media/user/{id:\d+}', ['App\HomeController','changeMedia']);
    $r->addRoute('POST', '/welcome/marlindev/diplom2/public/security/user/{id:\d+}', ['App\UserController','changeSecurity']);





});


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
        echo "404";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        echo "405";
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $controller = $handler[0];
        $action = $handler[1];
        $vars = $routeInfo[2];



        $container->call([$controller, $action],[$vars]);


        break;
}


