<?php 

namespace App\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\DAO\Mysql\NotiaDAO;

final class NotiaController {
    
    public function getNotia(Request $request, Response $response, array $args): Response
    {
        $req      = $request->getParsedBody();

        try{
            $dao = new NotiaDAO();
            $req = $dao->getNotiaAll();
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

    public function getNotiaCapa(Request $request, Response $response, array $args): Response
    {
        $req      = $request->getParsedBody();

        try{
            $dao = new NotiaDAO();
            $req = $dao->getNotiaCapa();
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

    public function searchNotia(Request $request, Response $response, array $args): Response
    {   
        $req      = $request->getParsedBody();
        $q       = $req['busca'];

        try{
            $dao = new NotiaDAO();
            $req = $dao->searchNotiaAll($q);
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

    public function getSiteEditoria(Request $request, Response $response, array $args): Response
    {
        $req      = $request->getParsedBody();
        $cd_site    = $req['editoria'];

        try{
            $dao = new NotiaDAO();
            $req = $dao->getSiteEditoria($cd_site);
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