<?php

namespace src;

function slimConfiguration() //@TODO : \Slim\Container
{
    $configuration = [
        'settings' => [
            'displayErrorDetails' => getenv('APP_DEBUG')
        ],
    ];
    return new \Slim\Container($configuration);
}