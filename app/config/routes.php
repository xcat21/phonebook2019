<?php

declare(strict_types=1);

/**
 * Main router config file for phonebook application.
 *
 * Contains all the routes (end-points) for API via Micro\Collection
 */
$recordCollection = new \Phalcon\Mvc\Micro\Collection();
$recordCollection->setHandler('\App\Controllers\RecordController', true);
$recordCollection->setPrefix('/v1/phonebook');
$recordCollection->get('/{id:[1-9][0-9]*}', 'getItemByIdAction');
$recordCollection->get('/', 'getItemListAction');
$recordCollection->get('/search', 'getItemListSearchAction');
$recordCollection->post('/add', 'addItemAction');
$recordCollection->put('/{userId:[1-9][0-9]*}', 'updateRecordAction');
$recordCollection->delete('/{userId:[1-9][0-9]*}', 'deleteRecordAction');

$app->mount($recordCollection);

// not found URLs handler
$app->notFound(
    function () use ($app) {
        $exception =
            new \App\Controllers\HttpExceptions\Http404Exception(
                _('URI not found or error in request.'),
                \App\Controllers\AbstractController::ERROR_NOT_FOUND,
                new \Exception('URI not found: '.$app->request->getMethod().' '.$app->request->getURI())
            );
        throw $exception;
    }
);
