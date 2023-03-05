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

// Register Eloquent
$capsule = new Capsule;
$capsule->addConnection($settings['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Register routes
require_once '../config/routes/api.php';

$app->run();
