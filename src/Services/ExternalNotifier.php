<?php

namespace App\Services;

use GuzzleHttp\Client;
use Throwable;
use Psr\Log\LoggerInterface;

class ExternalNotifier
{
    private Client $client;
    private LoggerInterface $logger;

    public function __construct(Client $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }
   
    /**
     * Sends a notification to the marketing webhooks endpoint.
     *
     * This method attempts to send a POST request with the provided data to the
     * 'marketing-webhooks' URL. It will retry up to 3 times in case of a
     * RequestException, waiting 2 seconds between each attempt.
     *
     * @param array $data The data to be sent in the notification.
     * @return bool Returns true if the notification was successfully sent, false otherwise.
     */
    public function notify(array $data): bool
    {
        $url = 'marketing-webhooks';
        $attempts = 0;

        while ($attempts < $_ENV['NOTIFY_MAX_RETRIES'] ?? 3) {
            try {
                $this->client->post($url, [
                    'json' => $data,
                ]);

                // Log success
                $this->logger->info('Notification sent successfully.', ['data' => $data]);

                return true;
            } catch (Throwable $e) {
                $attempts++;
                $this->logger->warning('Notification attempt failed.', [
                    'attempt' => $attempts,
                    'error' => $e->getMessage(),
                    'data' => $data,
                ]);

                sleep($_ENV['NOTIFY_RETRY_DELAY'] ?? 2);
            }
        }

        // Log final failure
        $this->logger->error('Notification failed after 3 attempts.', ['data' => $data]);

        return false;
    }
}
