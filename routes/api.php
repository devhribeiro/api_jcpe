<?php

use function \src\slimConfiguration;
use function \src\basicAuth;
use function \src\jwtAuth;
use \App\Controllers\AssjoController;
use \App\Controllers\AuthController;
use \App\Controllers\NotiaController;
use \App\Controllers\EdcaoController;
use \App\Middlewares\JwtDateTimeMiddleware;

$app = new \Slim\App(slimConfiguration());

$app->group('', function() use ($app)
{
    $app->post('/login',                AuthController::class. ':login');
    $app->post('/refresh-token',        AuthController::class . ':refreshToken');    
});

$app->group('/notia', function() use ($app)
{    
    $app->post('/busca',                NotiaController::class . ':searchNotia');
    $app->get('/all',                   NotiaController::class . ':getNotia');
    $app->get('/capa',                  NotiaController::class . ':getNotiaCapa');
    $app->post('/site',                 NotiaController::class . ':getSiteEditoria');  
});

$app->group('/search', function() use ($app)
{    
    $app->post('/dia',                  EdcaoController::class . ':getEdcaoDia');
    $app->post('/mes',                  EdcaoController::class . ':getEdcaoMes');
    $app->post('/between',              EdcaoController::class . ':getEdcaoBetween');
    $app->post('/edria',                EdcaoController::class . ':getEdcaoEdria');
    $app->post('/edriafter',            EdcaoController::class . ':getEdcaoEdriaNodate');
});

$app->group('/edcao', function() use ($app)
{    
    $app->post('/pagin',                EdcaoController::class . ':getEdcaoPagin');
    $app->post('/matia',                EdcaoController::class . ':getEdcaoMatia');
});

// rota com middware
// $app->group('/edcao', function() use ($app)
// {    
//     $app->post('/pagin',                EdcaoController::class . ':getEdcaoPagin');
//     $app->post('/matia',                EdcaoController::class . ':getEdcaoMatia');
// })->add(new JwtDateTimeMiddleware())
// ->add(jwtAuth());

$app->run();
