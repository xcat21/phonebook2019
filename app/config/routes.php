<?php
/**
 * Created by PhpStorm.
 * User: hovercat
 * Date: 13.03.2019
 * Time: 12:05
 */

$recordCollection = new \Phalcon\Mvc\Micro\Collection();
$recordCollection->setHandler('\App\Controllers\RecordController', true);
$recordCollection->setPrefix('/v1/phonebook');
$recordCollection->get('/{id:[1-9][0-9]*}', 'getItemByIdAction');
$recordCollection->get('/', 'getItemListAction');
$recordCollection->get('/search', 'getItemListSearchAction');

// $recordCollection->get('/docs', 'getDoc');

// $usersCollection->post('/add', 'addAction');
// $usersCollection->get('/list', 'getUserListAction');
// $usersCollection->put('/{userId:[1-9][0-9]*}', 'updateUserAction');
// $usersCollection->delete('/{userId:[1-9][0-9]*}', 'deleteUserAction');
$app->mount($recordCollection);

// not found URLs
$app->notFound(
    function () use ($app) {
        $exception =
            new \App\Controllers\HttpExceptions\Http404Exception(
                _('URI not found or error in request.'),
                \App\Controllers\AbstractController::ERROR_NOT_FOUND,
                new \Exception('URI not found: ' . $app->request->getMethod() . ' ' . $app->request->getURI())
            );
        throw $exception;
    }
);
