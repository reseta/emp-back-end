<?php

use App\Controllers\UserController;

// User group
$app->group('/user', function () {
    $this->post('/signup', UserController::class . ':signUp');
});
