<?php

declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {

    // Global Settings Object
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            return new Settings([
                'displayErrorDetails' => true, // Should be set to false in production
                'logError'            => false,
                'logErrorDetails'     => false,
                'logger' => [
                    'name' => $_ENV['DB_HOST'] ?? 'slim-app',
                    'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                    'level' => Logger::DEBUG,
                ],
                'db' =>[
                    'host' => $_ENV['DB_HOST'] ?? 'db',
                    'dbname' => $_ENV['DB_NAME'] ?? 'leads_db',
                    'user' => $_ENV['DB_USER'] ?? 'root',
                    'password' => $_ENV['DB_PASS'] ?? 'root',
                    'port' => $_ENV['DB_PORT'] ?? '3306',
                ],
                'template_path' => __DIR__ . '/../src/Views',
                'twig_cache' => false,
                'notificatioService' => [
                    'base_uri' => $_ENV['EXTERNAL_API_BASE_URI'] ?? 'https://mock-api.com/',
                    'notify_ednpoint' => $_ENV['EXTERNAL_API_ENDPOINT'] ?? 'marketing-webhooks',
                ]
            ]);
        }
    ]);
};
