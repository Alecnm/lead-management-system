<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

abstract class BaseController
{

    protected Twig $view;

    public function __construct(Twig $view)
    {
        $this->view = $view;
    }
    /**
     * Handle rendering JSON responses.
     */
    protected function jsonResponse(Response $response, array $data, int $status = 200): Response
    {
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }

    /**
     * Renders a Twig view.
     */
    protected function render(Response $response, string $template, array $data = []): Response
    {
        return $this->view->render($response, $template, $data);
    }

    /**
     * Abstract method to ensure child controllers implement a default action.
     */
    abstract protected function defaultAction(Request $request, Response $response, array $args): Response;
}
