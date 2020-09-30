<?php

namespace App\DAO\Mysql;

class AssjoDAO extends Connection
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAssjos() : array
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM assjo
            WHERE ds_assjo_email IS NOT NULL  
            ORDER BY dt_assjo_incl DESC"); 
        $stmt->execute();       
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAssjoByEmail($email) : array
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM assjo
            WHERE ds_assjo_email = :ds_assjo_email"); 
        $stmt->execute([":ds_assjo_email" => $email]);       
        return $stmt->fetch();
    }
}