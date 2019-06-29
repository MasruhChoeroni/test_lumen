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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// Aplication Generate Key
$router->get('/key', function () {
    return str_random(32);
});

$router->post('/register', 	'AuthController@register');
$router->post('/login', 	'AuthController@login');
$router->group(['middleware' => 'auth'], function () use ($router) {
	$router->get('/check',	'AuthController@check');

	// Template
	$router->post('/template', 				'ApiController@template_create'		);
	$router->patch('/template', 			'ApiController@template_update'		);
	$router->delete('/template', 			'ApiController@template_delete'		);
	$router->get('/template', 				'ApiController@template_index'		);
	$router->get('template_data_id/{id}', 	'ApiController@template_data_id'	);

	// Checklist
	$router->post('/checklist', 			'ApiController@checklist_create'	);
	$router->patch('/checklist', 			'ApiController@checklist_update'	);
	$router->delete('/checklist', 			'ApiController@checklist_delete'	);
	$router->get('/checklist', 				'ApiController@checklist_index'		);
	$router->get('checklist_data_id/{id}', 	'ApiController@checklist_data_id'	);

	// Checklist
	$router->post('/item', 				'ApiController@item_create'		);
	$router->patch('/item', 			'ApiController@item_update'		);
	$router->delete('/item', 			'ApiController@item_delete'		);
	$router->get('/item', 				'ApiController@item_index'		);
	$router->get('item_data_id/{id}', 	'ApiController@item_data_id'	);

	$router->post('/item/completed', 	'ApiController@item_completed'	);
	$router->post('/item/incompleted', 	'ApiController@item_incompleted');

});