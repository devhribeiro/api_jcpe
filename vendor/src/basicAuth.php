<?php

namespace src;
use Tuupola\Middleware\HttpBasicAuthentication;

function basicAuth() : HttpBasicAuthentication 
{
    return new HttpBasicAuthentication([
        "secure" => true,
        "relaxed" => ["localhost", getenv("APP_URL")],
        "users" => [
            "admin" => getenv("ADMIN_PASSWORD")
        ]
    ]);
}