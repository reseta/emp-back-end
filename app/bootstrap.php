<?php

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;
use Slim\App;

require '../vendor/autoload.php';

// Load .env variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

// Instantiate the app
$settings = require '../config/app.php';

$app = new App($settings);

// Add JWT auth
$app->add(new Tuupola\Middleware\JwtAuthentication([
    'path' => ['/api'],
    'ignore' => ['/api/signup', '/api/signin', '/api/posts/all'],
    'secret' => $_ENV['APP_SECRET'],
    'error' => function ($response, $arguments) {
        $data['status'] = 'error';
        $data['message'] = $arguments['message'];

        $response->getBody()->write(
            json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
        );

        return $response->withHeader('Content-Type', 'application/json');
    }
]));

// Register Eloquent
$capsule = new Capsule();
$capsule->addConnection($settings['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Register routes
require_once '../config/routes/api.php';

$app->run();
