<?php 

namespace App\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\DAO\Mysql\AssjoDAO;
use App\DAO\Mysql\TokensDAO;
use Firebase\JWT\JWT;
use Faker\Provider\ka_GE\DateTime;
use App\Models\Mysql\TokenModel;

final class AuthController {
    
    public function login(Request $request,Response $response,array $args) : Response
    {   
        $data = $request->getParsedBody();
        $email = $data['email'];
        $senha = $data['senha'];
        $expirar_data = $data['expirar_data'];

        $dao = new AssjoDAO();
        
        /** FUNÇÃO DE  VEIRIFCAÇÃO DE USUARIO METODO DE ENTRADA */
        if(
            $email == "" || 
            is_null($usuario = $dao->getAssjoByEmail($email)) || 
            $senha != $usuario['ds_assjo_senha'] 
        )
            return $response->withStatus(401);
        $tokenPayload = [
            "sub" => $usuario['cd_assjo'],
            "name" => $usuario['nm_assjo'],
            "email" => $usuario['ds_assjo_email'],
            "expired_at" => $expirar_data
        ];
        
        $token = JWT::encode($tokenPayload, getenv('JWT_SECRET_KEY'));
        $refreshTokenPayload = [
            "email" => $usuario['ds_assjo_email'],
            "ramdom" => uniqid()
        ];
        
        $refreshToken = JWT::encode($refreshTokenPayload, getenv('JWT_SECRET_KEY'));
        
        $tokenModel = new TokenModel();
        $tokenModel->setExpired_at($expirar_data)
            ->setRefresh_token($refreshToken)
            ->setToken($token)
            ->setUser_id($usuario['cd_assjo']);

        $tokenDAO = new TokensDAO();
        $tokenDAO->createToken($tokenModel);
        
        return $response->withJson([
            "nome" => $usuario['nm_assjo'],
            "email" => $usuario['ds_assjo_email'],
            "token" => $token
            // "refresh_token" => $refreshToken
        ], 201)->withHeader('Content-type', 'application/json');
    }

    //@TODO exlcuir refresh_token utilizado
    public function refreshToken(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();
        $refreshToken = $data['refresh_token'];
        $expireDate = $data['expire_date'];
        $refreshTokenDecoded = JWT::decode(
            $refreshToken,
            getenv('JWT_SECRET_KEY'),
            ['HS256']
        );
        
        $tokensDAO = new TokensDAO();
        $refreshTokenExists = $tokensDAO->verifyRefreshToken($refreshToken);
        
        if(!$refreshTokenExists)
            return $response->withStatus(401);
        
            $usuariosDAO = new UsersDAO();
        $usuario = $usuariosDAO->getUsersByEmail($refreshTokenDecoded->email);
        
        if(is_null($usuario))
            return $response->withStatus(401);
        
            $tokenPayload = [
            'sub' => $usuario['cd_assjo'],
            'name' => $usuario['nm_assjo'],
            'email' => $usuario['ds_assjo_email'],
            'expired_at' => $expireDate
        ];
        
        $token = JWT::encode($tokenPayload, getenv('JWT_SECRET_KEY'));
        
        $refreshTokenPayload = [
            'email' => $usuario['ds_assjo_email'],
            'ramdom' => uniqid()
        ];
        
        $refreshToken = JWT::encode($refreshTokenPayload, getenv('JWT_SECRET_KEY'));
        
        $tokenModel = new TokenModel();
        
        $tokenModel->setExpired_at($expireDate)
            ->setRefresh_token($refreshToken)
            ->setToken($token)
            ->setUser_id($usuario['cd_assjo']);
        
        $tokensDAO = new TokensDAO();
        $tokensDAO->createToken($tokenModel);
        $response = $response->withJson([
            "token" => $token,
            "refresh_token" => $refreshToken
        ]);
        return $response;
    }
}