<?php

// di
$injector = new \Auryn\Injector;

$signer = new Kunststube\CSRFP\SignatureGenerator(getenv('CSRF_SECRET'));
$injector->share($signer);

$blade = new duncan3dc\Laravel\BladeInstance(getenv('VIEWS_DIRECTORY'), getenv('CACHE_DIRECTORY'));
$injector->share($blade);

$injector->make('Acme\Http\Request');
$injector->make('Acme\Http\Response');
$injector->make('Acme\Http\Session');

$injector->share('Acme\Http\Request');
$injector->share('Acme\Http\Response');
$injector->share('Acme\Http\Session');

$injector->define('Acme\Auth\LoggedIn', ['session' => 'Acme\Http\Session']);
$injector->make('Acme\Auth\LoggedIn');

return $injector;
