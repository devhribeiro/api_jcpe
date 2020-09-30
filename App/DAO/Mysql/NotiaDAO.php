<?php

namespace App\DAO\Mysql;

class NotiaDAO extends Connection
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getNotiaAll() : array
    {
        // $stmt = $this->pdo->prepare("
        // SELECT
        //     matia.cd_matia as id,
        //     matia.id_matia_impre_trava as impresso_trava,
        //     site.ds_site_arvor as channel,
        //     ds_matia_assun as subject,
        //     matia.ds_matia_titlo as title,  
        //     ds_matia_chape as description,
        //     replace(midia.ds_midia_link, 'https:\/\/www.correiobraziliense.com.br', 'http:\/\/correio.cbnet.net.br') as image,
        //     concat_ws('', 'http:\/\/correio.cbnet.net.br', matia.ds_matia_path) as link,
        //     replace(concat_ws('', 'http:\/\/correio.cbnet.net.br', '/_conteudo', matia.ds_matia_path), '.html', '.json') as json,
        //     matia.dt_matia_publi as published
        // FROM 
        //     matia, poral
        //     JOIN site ON matia.cd_site = site.cd_site
        //     JOIN poral ON site.cd_poral = poral.cd_poral
        //     LEFT JOIN midia on matia.cd_midia = midia.cd_midia
        // WHERE 
        //     matia.cd_matia_statu in (2) and
        //     matia.cd_matia_pai is null and
        //     matia.cd_pdcao is null and
        //     matia.dt_matia_publi is not null and
        //     matia.dt_matia_publi < now() and
        //     site.cd_poral = 1
        // ORDER BY matia.dt_matia_publi desc LIMIT 150"); 
        
        $this->db();
        
        $stmt = $this->pdo->prepare("
        SELECT
            matia.cd_matia as id,
            ds_matia_assun as channel,
            matia.ds_matia_titlo as title,
            publi.ds_publi_titlo as titleCapa,
            ds_matia_chape as description,
            midia.ds_midia_link as image,
            matia.ds_matia_link as link,
            concat_ws('', ds_poral_url, matia.ds_matia_path) as linkMateria,
            replace(concat_ws('', ds_poral_url, '/_conteudo', matia.ds_matia_path), '.html', '.json') as json,
            matia.dt_matia_publi as published
        FROM 
            matia
            JOIN site ON matia.cd_site = site.cd_site
            JOIN poral ON site.cd_poral = poral.cd_poral
            JOIN publi ON matia.cd_matia = publi.cd_matia
            LEFT JOIN midia on matia.cd_midia = midia.cd_midia
        WHERE 
            matia.cd_matia_statu in (2) and
            matia.cd_matia_pai is null and
            matia.cd_pdcao is null and
            matia.dt_matia_publi is not null and
            matia.dt_matia_publi < now() and
            site.cd_poral = 1
        ORDER BY matia.dt_matia_publi desc LIMIT 100;");

        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getNotiaCapa() : array
    {
        
        $this->db();
        
        $stmt = $this->pdo->prepare("
        SELECT
            matia.cd_matia as id,
            ds_matia_assun as channel,
            matia.ds_matia_titlo as title,
            publi.ds_publi_titlo as titleCapa,
            ds_matia_chape as description,
            midia.ds_midia_link as image,
            matia.ds_matia_link as link,
            concat_ws('', ds_poral_url, matia.ds_matia_path) as linkMateria,
            replace(concat_ws('', ds_poral_url, '/_conteudo', matia.ds_matia_path), '.html', '.json') as json,
            matia.dt_matia_publi as published
        FROM 
            matia 
            JOIN site ON matia.cd_site = site.cd_site
            JOIN poral ON site.cd_poral = poral.cd_poral
            JOIN publi ON matia.cd_matia = publi.cd_matia
            JOIN sesit ON publi.cd_sesit = sesit.cd_sesit	
            LEFT JOIN midia on matia.cd_midia = midia.cd_midia
        WHERE 
            matia.cd_matia_statu IN (2) AND
            publi.dt_publi_ini < now() and
            (publi.dt_publi_fim is null or publi.dt_publi_fim > now()) and
            sesit.id_sesit <= 6 and
            publi.cd_site = 2
        ORDER BY
            sesit.id_sesit ASC,
            publi.dt_publi_ini ASC");

        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function getSiteEditoria($cd_site) : array
    {   
        $this->db();

        $stmt = $this->pdo->prepare("
        SELECT
            matia.cd_matia as id,
            ds_matia_assun as channel,
            matia.ds_matia_titlo as title,
            ds_matia_chape as description,
            replace(midia.ds_midia_link, 'https:\/\/www.correiobraziliense.com.br', 'http:\/\/correio.cbnet.net.br') as image,
            matia.ds_matia_link as link,
            concat_ws('', ds_poral_url, matia.ds_matia_path) as linkMateria,
            replace(concat_ws('', 'http:\/\/correio.cbnet.net.br', '/_conteudo', matia.ds_matia_path), '.html', '.json') as json,
            matia.dt_matia_publi as published
        FROM 
            matia 
            JOIN site ON matia.cd_site = site.cd_site
            JOIN poral ON site.cd_poral = poral.cd_poral
            LEFT JOIN midia on matia.cd_midia = midia.cd_midia
        WHERE 
            matia.cd_matia_statu IN (2) and
            matia.cd_matia_pai is null and
            matia.cd_pdcao is null and
            matia.cd_jonal is null and
            matia.dt_matia_publi is not null and
            matia.dt_matia_publi < now() and
                site.cd_site = :cd_site
        ORDER BY matia.dt_matia_publi desc LIMIT 100"); 

        $stmt->bindValue( ":cd_site", $cd_site);

        $stmt->execute();       
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function searchNotiaAll($q) : array
    {   
        $this->dbS();

        $map = $this->pdo->prepare("
            SELECT 
                cd_matia 
            FROM 
                matia 
            WHERE
                match('@ds_matia_titlo ". $q ."')
        ");
        $map->execute();
        $cd_matia = $map->fetchAll(\PDO::FETCH_ASSOC);
        $arr = [];

        foreach($cd_matia as $matia) {
            $arr[] = $matia['cd_matia'];           
        }
        
        $res = implode(',', $arr);

        $this->db();
        $stmt = $this->pdo->prepare("
        SELECT
            matia.cd_matia as id,
            matia.id_matia_impre_trava as impresso_trava,
            ds_matia_assun as channel,
            matia.ds_matia_titlo as title,
            ds_matia_chape as description,
            replace(midia.ds_midia_link, 'https:\/\/www.correiobraziliense.com.br', 'http:\/\/correio.cbnet.net.br') as image,
            matia.ds_matia_link as link,
            concat_ws('', ds_poral_url, matia.ds_matia_path) as linkMateria,
            replace(concat_ws('', 'http:\/\/correio.cbnet.net.br', '/_conteudo', matia.ds_matia_path), '.html', '.json') as json,
            matia.dt_matia_publi as published
        FROM 
            matia 
            JOIN site ON matia.cd_site = site.cd_site
            JOIN poral ON site.cd_poral = poral.cd_poral
            LEFT JOIN midia on matia.cd_midia = midia.cd_midia
        WHERE 
            matia.cd_matia in (". $res . ") and
            matia.cd_matia_statu in (2) and
            matia.cd_matia_pai is null and
            matia.cd_pdcao is null and
            matia.dt_matia_publi is not null and
            matia.dt_matia_publi < now() and
            site.cd_poral = 1
        ORDER BY matia.dt_matia_publi desc");

        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
}