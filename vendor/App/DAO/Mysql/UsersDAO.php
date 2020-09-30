<?php

namespace App\DAO\Mysql;

class UsersDAO extends Connection
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getUsersByEmail(string $email) : array
    {   
		
        $this->db();
        $stmt = $this->pdo->prepare("
            SELECT
                assjo.*
            FROM assjo,ctrjo
            WHERE 
				assjo.cd_assjo = ctrjo.cd_assjo and
				(
					(NOW() BETWEEN dt_ctrjo_incio_vigen AND dt_ctrjo_fim_vigen) OR
					(NOW() > dt_ctrjo_incio_vigen and dt_ctrjo_fim_vigen is null)
				) and
				(
					(ds_assjo_email = :ds_assjo_email) or
					(ds_ctrjo_email = :ds_assjo_email) or 
					(ds_assjo_username = :ds_assjo_email) or 
					(ds_assjo_cpf_cnpj = :ds_assjo_email) or 
					(ds_assjo_cpf_cnpj = :ds_assjo_email)
				)
			ORDER BY 
				IFNULL(ctrjo.dt_ctrjo_fim_vigen, NOW()) DESC
			"); 
        $stmt->execute(['ds_assjo_email'=>$email]);
        $usuarios = $stmt->fetchAll(\PDO::FETCH_ASSOC);      
        return (count($usuarios) === 0 ? null : $usuarios[0] );
    }

}