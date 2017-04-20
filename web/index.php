<?php

use Silex\Application;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Application();

// settings for the app
require_once __DIR__.'/../app/bootstrap.php';

// actions
require_once __DIR__.'/../app/controllers.php';

$app->run();
