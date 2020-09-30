<?php

namespace src;

use Tuupola\Middleware\JwtAuthentication;

function jwtAuth(): JwtAuthentication
{
    return new JwtAuthentication([
        'secret' => getenv('JWT_SECRET_KEY'),
        "secure" => false,
        "relaxed" => ["localhost", getenv("APP_URL")],
        'attribute' => 'jwt'
    ]);
}