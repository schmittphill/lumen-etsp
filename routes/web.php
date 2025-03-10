<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Illuminate\Http\Request;

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/invoice', 'InvoiceController@index');
$router->post('/invoice', 'InvoiceController@store');
$router->get('/invoice/{ref}', 'InvoiceController@show');
$router->put('/invoice/{ref}', 'InvoiceController@update');
$router->delete('/invoice/{ref}', 'InvoiceController@cancel');

