<?php

use App\Controllers\UserController;
use App\Controllers\PostController;

// Cors
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

// Cors
$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

// API group
$app->group('/api', function () {
    // User routes
    $this->post('/signup', UserController::class . ':signUp');
    $this->post('/signin', UserController::class . ':signIn');
    $this->patch('/user', UserController::class . ':update');
    $this->delete('/user/{userId:[0-9]+}', UserController::class . ':delete');
    $this->get('/user', UserController::class . ':info');

    // Blog post routes
    $this->post('/post', PostController::class . ':create');
    $this->put('/post/{postId:[0-9]+}', PostController::class . ':update');
    $this->delete('/post/{postId:[0-9]+}', PostController::class . ':delete');
    $this->get('/posts[/{id:[0-9]+}]', PostController::class . ':getOwn');
    $this->get('/posts/all[/{id:[0-9]+}]', PostController::class . ':get');
});

// Cors
$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function($req, $res) {
    $handler = $this->notFoundHandler;
    return $handler($req, $res);
});
