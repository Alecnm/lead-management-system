<?php

namespace App\Controllers;

use App\Models\LeadsModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as v;
use Slim\Views\Twig;
use App\Services\ExternalNotifier;

class LeadsController extends BaseController
{
    // Inject the LeadsModel into the controller
    private LeadsModel $leadsModel;
    private ExternalNotifier $notifier;

    public function __construct(Twig $view, LeadsModel $leadsModel, ExternalNotifier $notifier)
    {
        parent::__construct($view);
        $this->leadsModel = $leadsModel;
        $this->notifier = $notifier;
    }

    /**
     * Show the form for creating a new lead.
     */
    public function showForm(Request $request, Response $response, array $args): Response
    {
        return $this->view->render($response, 'leads/form.twig');
    }

    /**
     * Handle form submission for creating a new lead.
     */
    public function create(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();

        // Validations
        $nameValidator = v::stringType()->length(3, 50)->notEmpty();
        $emailValidator = v::email()->notEmpty();
        $sourceValidator = v::in(['facebook', 'google', 'linkedin', 'manual']);

        $errors = [];

        if (!$nameValidator->validate($data['name'] ?? '')) {
            $errors['name'] = 'Name must be between 3 and 50 characters.';
        }
        if (!$emailValidator->validate($data['email'] ?? '')) {
            $errors['email'] = 'Invalid email address.';
        }
        if (!$sourceValidator->validate($data['source'] ?? '')) {
            $errors['source'] = 'Source must be one of: facebook, google, linkedin, manual.';
        }

        // If there are errors, re-render the form with errors and old input
        if (!empty($errors)) {
            return $this->view->render($response, 'leads/form.twig', [
                'errors' => $errors,
                'old' => $data,
            ]);
        }

        // Save to database (example only, use a model/service in real apps)
        $leadId = $this->leadsModel->create($data);

        if (!$leadId) {
            // Handle database error
            return $response->withHeader('Location', '/leads/failure')->withStatus(302);
        }

        $notificationData = [
            'lead_id' => $leadId,
            'name' => $data['name'],
            'email' => $data['email'],
            'source' => $data['source'],
        ];

        // Notify external service
        $this->notifyExternalService($notificationData);

        // Redirect or show success message
        return $response->withHeader('Location', '/leads/success')->withStatus(302);
    }

    /**
     * Show success message.
     */
    public function success(Request $request, Response $response, array $args): Response
    {
        return $this->view->render($response, 'leads/success.twig');
    }

    public function failure(Request $request, Response $response, array $args): Response
    {
        return $this->view->render($response, 'leads/failure.twig');
    }

    public function defaultAction(Request $request, Response $response, array $args): Response
    {
        return $this->showForm($request, $response, $args);
    }

    /**
     * Notifies an external service with the provided data.
     *
     * @param array $data The data to be sent to the external service.
     *
     * @return void
     */
    private function notifyExternalService(array $data): void
    {
        $this->notifier->notify($data);
    }
}
