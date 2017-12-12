<?php
$controller = null;
$method = null;

// bootstrap & init everything
include __DIR__.'/../bootstrap/start.php';
Dotenv::load(__DIR__.'/../');
include __DIR__.'/../bootstrap/dependencies.php';
include __DIR__.'/../bootstrap/functions.php';
include __DIR__.'/../bootstrap/db.php';

// load the routes file & search for matching route
include __DIR__.'/../routes.php';
$match = $router->match();

// are we calling a controller?
if (is_string($match['target']))
    list($controller, $method) = explode('@', $match['target']);

if (($controller != null) && (is_callable(array($controller, $method)))) {
    // controller
    //$object = new $controller();
    $object = $injector->make($controller);
    call_user_func_array(array($object, $method), array($match['params']));
} else if ($match && is_callable($match['target'])) {
    // closure
    call_user_func_array($match['target'], $match['params']);
} else {
    // nothing matches
    echo "Cannot find $controller -> $method";
    exit();
}
