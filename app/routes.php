<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use App\Controllers\LeadsController;

return function (App $app) {

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    // Leads routes
    $app->get('/leads', [LeadsController::class, 'showForm']);
    $app->post('/leads', [LeadsController::class, 'create']);
    $app->get('/leads/success', [LeadsController::class, 'success']);
};
