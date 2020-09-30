<?php 

namespace App\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\DAO\Mysql\EdcaoDAO;

final class EdcaoController {
    public function getEdcaoDia(Request $request, Response $response, array $args): Response
    {
        $req      = $request->getParsedBody();
        
        $dt_edcao  = $req['date'];

        try{
            $dao = new EdcaoDAO();
            $req = $dao->getEdcaoDia($dt_edcao);
            return $response->withJson($req, 201)->withHeader('Content-type', 'application/json');
        } catch(\InvalidArgumentException $ex) {
            return $response->withJson([
                'error' => \InvalidArgumentException::class,
                'status' => 400,
                'developerMessage' => $ex->getMessage()
            ], 400);
        } catch(\Exception | \Throwable $ex) {
            return $response->withJson([
                'error' => \Exception::class,
                'status' => 500,
                'developerMessage' => $ex->getMessage()
            ], 500);
        }
    }
    public function getEdcaoMes(Request $request, Response $response, array $args): Response
    {
        $req      = $request->getParsedBody();
        
        $mes  = $req['mes'];
        $ano  = $req['ano'];

        try{
            $dao = new EdcaoDAO();
            $req = $dao->getEdcaoMes($mes, $ano);
            return $response->withJson($req, 201)->withHeader('Content-type', 'application/json');
        } catch(\InvalidArgumentException $ex) {
            return $response->withJson([
                'error' => \InvalidArgumentException::class,
                'status' => 400,
                'developerMessage' => $ex->getMessage()
            ], 400);
        } catch(\Exception | \Throwable $ex) {
            return $response->withJson([
                'error' => \Exception::class,
                'status' => 500,
                'developerMessage' => $ex->getMessage()
            ], 500);
        }
    }
    public function getEdcaoBetween(Request $request, Response $response, array $args): Response
    {
        $req      = $request->getParsedBody();
        
        $inicio  = $req['inicio'];
        $fim  = $req['fim'];

        try{
            $dao = new EdcaoDAO();
            $req = $dao->getEdcaoBetween($inicio, $fim);
            return $response->withJson($req, 201)->withHeader('Content-type', 'application/json');
        } catch(\InvalidArgumentException $ex) {
            return $response->withJson([
                'error' => \InvalidArgumentException::class,
                'status' => 400,
                'developerMessage' => $ex->getMessage()
            ], 400);
        } catch(\Exception | \Throwable $ex) {
            return $response->withJson([
                'error' => \Exception::class,
                'status' => 500,
                'developerMessage' => $ex->getMessage()
            ], 500);
        }
    }

    public function getEdcaoEdria(Request $request, Response $response, array $args): Response
    {
        $req      = $request->getParsedBody();
        
        $edcao  = $req['edcao'];

        try{
            $dao = new EdcaoDAO();
            $req = $dao->getEdcaoEdria($edcao);
            return $response->withJson($req, 201)->withHeader('Content-type', 'application/json');
        } catch(\InvalidArgumentException $ex) {
            return $response->withJson([
                'error' => \InvalidArgumentException::class,
                'status' => 400,
                'developerMessage' => $ex->getMessage()
            ], 400);
        } catch(\Exception | \Throwable $ex) {
            return $response->withJson([
                'error' => \Exception::class,
                'status' => 500,
                'developerMessage' => $ex->getMessage()
            ], 500);
        }
    }

    public function getEdcaoEdriaNodate(Request $request, Response $response, array $args): Response
    {
        $req      = $request->getParsedBody();
        try{
            $dao = new EdcaoDAO();
            $req = $dao->getEdcaoEdriaNodate();
            return $response->withJson($req, 201)->withHeader('Content-type', 'application/json');
        } catch(\InvalidArgumentException $ex) {
            return $response->withJson([
                'error' => \InvalidArgumentException::class,
                'status' => 400,
                'developerMessage' => $ex->getMessage()
            ], 400);
        } catch(\Exception | \Throwable $ex) {
            return $response->withJson([
                'error' => \Exception::class,
                'status' => 500,
                'developerMessage' => $ex->getMessage()
            ], 500);
        }
    }

    public function getEdcaoPagin(Request $request, Response $response, array $args): Response
    {
        $req      = $request->getParsedBody();
        
        $edcao  = $req['edcao'];

        try{
            $dao = new EdcaoDAO();
            $req = $dao->getEdcaoEdria($edcao);
            return $response->withJson($req, 201)->withHeader('Content-type', 'application/json');
        } catch(\InvalidArgumentException $ex) {
            return $response->withJson([
                'error' => \InvalidArgumentException::class,
                'status' => 400,
                'developerMessage' => $ex->getMessage()
            ], 400);
        } catch(\Exception | \Throwable $ex) {
            return $response->withJson([
                'error' => \Exception::class,
                'status' => 500,
                'developerMessage' => $ex->getMessage()
            ], 500);
        }
    }

    public function getEdcaoMatia(Request $request, Response $response, array $args): Response
    {
        $req      = $request->getParsedBody();
        
        $edcao  = $req['edcao'];
        $edria  = $req['edria'];
        $pdcao  = $req['pdcao'];
        
        try{
            $dao = new EdcaoDAO();
            $req = $dao->getEdcaoMatia($edcao, $edria, $pdcao);
            return $response->withJson($req, 201)->withHeader('Content-type', 'application/json');
        } catch(\InvalidArgumentException $ex) {
            return $response->withJson([
                'error' => \InvalidArgumentException::class,
                'status' => 400,
                'developerMessage' => $ex->getMessage()
            ], 400);
        } catch(\Exception | \Throwable $ex) {
            return $response->withJson([
                'error' => \Exception::class,
                'status' => 500,
                'developerMessage' => $ex->getMessage()
            ], 500);
        }
    }
}