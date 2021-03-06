<?php

$route->get('/user/create', 'user@UserController@create');

$route->get('user/{id}', 'user@UserController@index');

$route->get('user/{id}/show/{hash}', function($id, $hash) {
    echo "id: $id<br>";
    echo "hash: $hash<br>";
});



$route->post('login', 'user@UserController@login');

$route->get('cpanel', 'PageController@cpanel');

$route->get('redirect', 'PageController@redirect');

$route->any('/', function() use($response, $request) {

    if($request->isJson())
        $response->write('Hello Json Statham!');
    else if($request->isMedia())
        $response->write('Files: ' . print_r($request->getUploadedFiles(), true));
    else
        $response->write('Hello!');
    
});

$route->get('cookie', function() use($response, $request) {

//    $response->status(404);
//    $response->cookie('name', 'vla');
//    $response->cookie('a', 'b', ['expires' => time()+3600, 'path' => 'cookie']);
});

$route->get('template/1', function() use($response) {
    $response->view('rr.test', ['title' => 'Page Title']);
});

$route->get('template/2', 'PageController@show');