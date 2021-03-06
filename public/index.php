<?php

declare(strict_types=1);

use App\Controllers\AbstractHttpException;

/*
 * Main index file - base end-point for phonebook application
 *
 * Contains general application cycle
 *
 * @author Roman Smirnov
 *
 */

try {
    // Loading Configs
    $config = require __DIR__.'/../app/config/config.php';

    // Autoloading classes
    require __DIR__.'/../app/config/loader.php';
    require __DIR__.'/../vendor/autoload.php';

    // Initializing DI container
    /** @var \Phalcon\DI\FactoryDefault $di */
    $di = require __DIR__.'/../app/config/di.php';

    // Initializing application
    $app = new \Phalcon\Mvc\Micro();

    // Setting DI container
    $app->setDI($di);

    // Setting up routing
    require __DIR__.'/../app/config/routes.php';

    // Making the correct answer after executing based on data received from controller
    $app->after(
        function () use ($app) {
            // Getting the return value of method
            $return = $app->getReturnedValue();

            if (is_array($return)) {
                // Check if it is a creation  TODO: Auto generation of location link
                if (!empty($return['location'])) {
                    $app->response->setStatusCode('201', 'Created');
                    $app->response->setHeader('Location', $return['location']);
                } else {
                    // Check if $result array is empty to response with 204
                    if (!empty($return)) {
                        // Transforming arrays to JSON
                        $app->response->setContent(json_encode($return));
                    } else {
                        // Answer with empty 204 No content
                        $app->response->setStatusCode('204', 'No Content');
                    }
                }

                // Check if we got response to delete operation
                if (isset($return['deleted'])) {
                    $app->response->setStatusCode('200', 'Ok');
                    $app->response->setContent(null);
                }
            } elseif (!strlen($return)) {
                // Successful response without any content
                $app->response->setStatusCode('204', 'No Content');
            } else {
                // Unexpected response
                throw new Exception('Bad Response');
            }

            // Sending response to the client
            $app->response->send();
        }
    );

    // Processing request
    $app->handle();
} catch (AbstractHttpException $e) {
    $response = $app->response;
    $response->setStatusCode($e->getCode(), $e->getMessage());
    $response->setJsonContent($e->getAppError());
    $response->send();
} catch (\Phalcon\Http\Request\Exception $e) {
    $app->response->setStatusCode(400, 'Bad request')
        ->setJsonContent([
            AbstractHttpException::KEY_CODE => 400,
            AbstractHttpException::KEY_MESSAGE => 'Bad request',
        ])
        ->send();
} catch (\Exception $e) {
    // Standard error format

    $result = [
        AbstractHttpException::KEY_CODE => 500,
        AbstractHttpException::KEY_MESSAGE => 'Some error occurred on the server.',
    ];

    // Sending error response
    $app->response->setStatusCode(500, 'Internal Server Error')
        ->setJsonContent($result)
        ->send();
}
