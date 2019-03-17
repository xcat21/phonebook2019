<?php

declare(strict_types=1);
/**
 * Loader config file for phonebook application.
 *
 * Contains all the paths to application layers
 */
$loader = new \Phalcon\Loader();
$loader->registerNamespaces(
    [
        'App\Services' => realpath(__DIR__.'/../services/'),
        'App\Controllers' => realpath(__DIR__.'/../controllers/'),
        'App\Models' => realpath(__DIR__.'/../models/'),
    ]
);

$loader->register();
