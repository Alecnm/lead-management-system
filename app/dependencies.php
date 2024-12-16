<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use App\Controllers\LeadsController;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\Views\Twig;
use App\Models\LeadsModel;
use App\Services\ExternalNotifier;
use GuzzleHttp\Client;


return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        PDO::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class)->get('db');
    
            try {
                $dsn = sprintf(
                    'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                    $settings['host'],
                    $settings['port'], // Incluye el puerto desde la configuración
                    $settings['dbname']
                );
                $pdo = new PDO($dsn, $settings['user'], $settings['password']);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                throw new \Exception("Database connection error: " . $e->getMessage());
            }
    
            return $pdo;
        },
        Twig::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);
            $templatePath = $settings->get('template_path');
            return Twig::create($templatePath, ['cache' => $settings->get('twig_cache') ?? false]);
        },
        ExternalNotifier::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);
            $client = new Client([
                'base_uri' => $settings->get('notificatioService')['base_uri'],
                'timeout' => 5.0,
            ]);
            $logger = $c->get(LoggerInterface::class);
            return new ExternalNotifier($client, $logger);
        },
        LeadsModel::class => function (ContainerInterface $container) {
            return new LeadsModel($container->get(PDO::class));
        },
        LeadsController::class => function (ContainerInterface $c) {
            return new LeadsController(
                $c->get(Twig::class),
                $c->get(LeadsModel::class),
                $c->get(ExternalNotifier::class)
            );
        },
    ]);
};
