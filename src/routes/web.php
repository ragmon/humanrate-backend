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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/documentation', fn() => view('swagger'));

$router->group([
    'middleware' => ['auth'],
    'prefix' => 'api',
], function (\Laravel\Lumen\Routing\Router $router) {
    // Contacts
    $router->post('/contacts', ['uses' => 'ContactController@syncContacts']);
    $router->get('/contacts', ['uses' => 'ContactController@getContacts']);

    // Skills
    $router->get('/skills', ['uses' => 'SkillController@index']);
    $router->post('/skills', ['uses' => 'SkillController@store']);
    $router->delete('/skills/{skillId}', ['uses' => 'SkillController@delete']);

    // Evaluations
    $router->get('/user/{userId}/evaluations', ['uses' => 'EvaluationController@index']);
    $router->post('/skills/{skillId}/evaluation', ['uses' => 'EvaluationController@evaluation']);
});
