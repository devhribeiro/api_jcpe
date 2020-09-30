<?php
namespace App\DAO\Mysql;
use App\Models\Mysql\TokenModel;

class TokensDAO extends Connection
{
    public function __construct()
    {
        parent::__construct();
    }
    public function createToken(TokenModel $token): void
    {   
        $this->dbW();
        $statement = $this->pdo
            ->prepare('INSERT INTO tokens
                (
                    token,
                    refresh_token,
                    expired_at,
                    cd_assjo
                )
                VALUES
                (
                    :token,
                    :refresh_token,
                    :expired_at,
                    :cd_assjo
                );
            ');
        $statement->execute([
            'token' => $token->getToken(),
            'refresh_token' => $token->getRefresh_token(),
            'expired_at' => $token->getExpired_at(),
            'cd_assjo' => $token->getUser_id()
        ]);
    }
    public function verifyRefreshToken(string $refreshToken): bool
    {   
        $this->db();
        $statement = $this->pdo
            ->prepare('SELECT
                    id
                FROM tokens
                WHERE refresh_token = :refresh_token;
            ');
        $statement->bindParam('refresh_token', $refreshToken);
        $statement->execute();
        $tokens = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return count($tokens) === 0 ? false : true;
    }
}