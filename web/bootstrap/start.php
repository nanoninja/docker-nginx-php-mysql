<?php
require __DIR__.'/../vendor/autoload.php';

session_start();


$whoops = new \Whoops\Run();
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
$whoops->register();

$router = new AltoRouter();
