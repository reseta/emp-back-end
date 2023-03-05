<?php

use App\Controllers\UserController;

// API group
$app->group('/api', function () {
    $this->post('/signup', UserController::class . ':signUp');
    $this->post('/signin', UserController::class . ':signIn');
    $this->patch('/user', UserController::class . ':update');
    $this->delete('/user/{userId:[0-9]+}', UserController::class . ':delete');
});
