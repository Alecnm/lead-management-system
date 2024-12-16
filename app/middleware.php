<?php

declare(strict_types=1);

use App\Application\Middleware\SessionMiddleware;
use Slim\App;
use Slim\Views\TwigMiddleware;
use Slim\Views\Twig;

return function (App $app) {
    $app->add(SessionMiddleware::class);
    $app->add(TwigMiddleware::createFromContainer($app, Twig::class));
    $app->addRoutingMiddleware();
    $app->addErrorMiddleware(true, true, true);
};
