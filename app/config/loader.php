<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: hovercat
 * Date: 13.03.2019
 * Time: 12:04.
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
