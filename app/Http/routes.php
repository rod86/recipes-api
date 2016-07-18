<?php

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


$app->group(['prefix' => 'api/v1', 'namespace' => 'App\Http\Controllers'], function () use ($app) {

    // List all recipes
    $app->get('/recipes', 'RecipesController@index');

    // Get recipe by id
    $app->get('/recipe/{id}', 'RecipesController@view');

    // Rate recipe by id
    $app->post('/recipe/{id}/rate', 'RecipesController@rate');

    // Add new recipe
    $app->post('/recipe', 'RecipesController@add');

    // Update existing recipe
    $app->put('/recipe/{id}', 'RecipesController@update');

});


