<?php

declare(strict_types=1);
/*
 * Created by PhpStorm.
 * User: hovercat
 * Date: 16.03.2019
 * Time: 13:17.
 */
use League\FactoryMuffin\Faker\Facade as Faker;

$fm->define('Record')->setDefinitions([
    'fName' => Faker::firstNameMale(),
    'lName' => Faker::lastNameMale(),
    'phone' => function () {
        return '+11 22 333444555';
    },
    'countryCode' => 'GB',
    'timeZone' => 'Europe/Moscow',
]);
