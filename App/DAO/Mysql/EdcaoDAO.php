<?php

namespace App\DAO\Mysql;

class EdcaoDAO extends Connection
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getEdcaoDia($dt_edcao) : array
    {
        $this->db();
        $stmt = $this->pdo->prepare("
        select
            edcao.cd_edcao as cd_edcao,
            edcao.dt_edcao as dt_edcao,
            prded.ds_prded as prded,
            jonal.ds_jonal as jonal
        from
            jonal, prded, mstre, edcao
        where
            jonal.cd_jonal = prded.cd_jonal and
            prded.cd_prded = mstre.cd_prded and
            mstre.cd_mstre = edcao.cd_mstre and
			edcao.id_edcao_final = 2 and
            
	    DATE(edcao.dt_edcao) = :dt_edcao
        order by
			prded.id_prded_ordem,
            edcao.dt_edcao desc"); 

        $stmt->bindValue( ":dt_edcao", $dt_edcao);

        /** SQL NOVO */
        // $stmt = $this->pdo->prepare("
        // select
        //     edcao.cd_edcao as cd_edcao,
        //     edcao.dt_edcao as dt_edcao,
        //     prded.ds_prded as prded,
        //     jonal.ds_jonal as jonal
        // from
        //     jonal, prded, mstre, edcao
        // where
        //     jonal.cd_jonal = prded.cd_jonal and
        //     prded.cd_prded = mstre.cd_prded and
        //     mstre.cd_mstre = edcao.cd_mstre and
        //     edcao.id_edcao_final = 2 and
        //     prded.cd_catpr = 1 and
	    // DATE(edcao.dt_edcao) = :dt_edcao
        // order by
        //     IFNULL(prded.id_prded_ordem, 999) asc"); 

        // $stmt->bindValue( ":dt_edcao", $dt_edcao);
        $stmt->execute();       
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getEdcaoMes($mes, $ano) : array
    {   
        $this->db();
        $stmt = $this->pdo->prepare("
        select
            edcao.cd_edcao as cd_edcao,
            edcao.dt_edcao dt_edcao,
            prded.ds_prded prded,
            jonal.ds_jonal as jonal
        from
            jonal, prded, mstre, edcao
        where
            jonal.cd_jonal = prded.cd_jonal and
            prded.cd_prded = mstre.cd_prded and
            mstre.cd_mstre = edcao.cd_mstre and
			edcao.id_edcao_final = 2 and
            MONTH(edcao.dt_edcao) = :mes and
            YEAR(edcao.dt_edcao) = :ano
        order by
            edcao.dt_edcao desc"); 

        $stmt->bindValue( ":mes", $mes);
        $stmt->bindValue( ":ano", $ano);

        $stmt->execute();       
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getEdcaoBetween($inicio , $fim) : array
    {
        $this->db();
        $stmt = $this->pdo->prepare("
        select
            edcao.cd_edcao as cd_edcao,
            edcao.dt_edcao dt_edcao,
            prded.ds_prded prded,
            jonal.ds_jonal as jonal
        from
            jonal, prded, mstre, edcao
        where
            jonal.cd_jonal = prded.cd_jonal and
            prded.cd_prded = mstre.cd_prded and
            mstre.cd_mstre = edcao.cd_mstre and
			edcao.id_edcao_final = 2 and
            edcao.dt_edcao between :inicio and :fim
        order by
            edcao.dt_edcao desc"); 

        $stmt->bindValue( ":inicio", $inicio);
        $stmt->bindValue( ":fim", $fim);

        $stmt->execute();       
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getEdcaoEdria($edcao, $allPages = true) : array
    {   
        
        if ($edcao == "") {
            $this->db();
            $sql = "select edcao.cd_edcao 
                        from edcao, mstre, prded
                        where 
                        edcao.cd_mstre = mstre.cd_mstre and
                        mstre.cd_prded = prded.cd_prded and
                        edcao.id_edcao_final = 2 and
                        date_format(edcao.dt_edcao, '%Y-%m-%d') = (select date_format(max(e.dt_edcao), '%Y-%m-%d') from edcao e where e.id_edcao_final = 2)
                        order by
                        prded.id_prded_ordem
                        limit 1
            ";
            $stmt = $this->pdo->prepare($sql); 
            $stmt->execute();       
            $row = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            // print_r($row[0]);
            $edcao = $row[0]["cd_edcao"];
        }

        if ($allPages == false) 
        {   
            $sql = "
            select 
            edcao.cd_edcao as edcao,
            edcao.dt_edcao as date,
            prded.ds_prded as caderno,
            jonal.ds_jonal as jornal,
            p.vl_pdcao_pagin as page,
            p.cd_edria as cd_editoria,
            p.nm_edria as editoria,
            concat_ws('', replace(edcao.ds_edcao_url, 'http://edicao.intra.jc.com.br', 'http://fivenews.sjcc.com.br/jornal'), '/thumb/', pdcao.cd_pdcao, '.jpg') as thumb,
            concat_ws('', replace(edcao.ds_edcao_url, 'http://edicao.intra.jc.com.br', 'http://fivenews.sjcc.com.br/jornal'), '/pdf/', pdcao.ds_pdcao_arquv) as pdf,
            -- concat_ws('', replace(edcao.ds_edcao_url, 'http://edicao.intra.jc.com.br', 'http://fivenews.sjcc.com.br/jornal'), '/issue', pdcao.cd_edcao, '.pdf') as pdf_completo,
            concat_ws('', replace(edcao.ds_edcao_url, 'http://edicao.intra.jc.com.br', 'http://fivenews.sjcc.com.br/jornal'), '/../all.pdf') as pdf_completo,
            concat_ws('', replace(edcao.ds_edcao_url, 'http://edicao.intra.jc.com.br', 'http://fivenews.sjcc.com.br/jornal'), '/jpgdevice/', pdcao.cd_pdcao, '.jpg') as device,
            concat_ws('', replace(edcao.ds_edcao_url, 'http://edicao.intra.jc.com.br', 'http://fivenews.sjcc.com.br/jornal'), '/jpg/', pdcao.cd_pdcao, '.jpg') as imageAlta,
            concat_ws('', replace(edcao.ds_edcao_url, 'http://edicao.intra.jc.com.br', 'http://fivenews.sjcc.com.br/jornal'), '/thumb/', pdcao.cd_pdcao, '.jpg') as image
            from
            jonal, prded, mstre, edcao,
            (
                select
                edcao.cd_edcao,
                min(pdcao.vl_pdcao_pagin) as vl_pdcao_pagin,
                    edria.cd_edria,
                    edria.nm_edria
                    from
                        jonal, prded, mstre, edcao, pdcao, edria
                        where
                        jonal.cd_jonal = prded.cd_jonal and
                        prded.cd_prded = mstre.cd_prded and
                        mstre.cd_mstre = edcao.cd_mstre and
                        edcao.cd_edcao = pdcao.cd_edcao and
                        pdcao.cd_edria = edria.cd_edria
                        group by
                        edcao.cd_edcao,
                        edria.nm_edria,
                        edria.cd_edria
                    order by
                        min(pdcao.vl_pdcao_pagin) 
                        ) as p,
                pdcao,
				edcao dia
                where
                jonal.cd_jonal = prded.cd_jonal and
                prded.cd_prded = mstre.cd_prded and
                mstre.cd_mstre = edcao.cd_mstre and
                edcao.cd_edcao = p.cd_edcao and
                p.vl_pdcao_pagin = pdcao.vl_pdcao_pagin and
                pdcao.cd_edcao = edcao.cd_edcao and
                date_format(edcao.dt_edcao, '%Y-%m-%d') = date_format(dia.dt_edcao, '%Y-%m-%d') and
                dia.cd_edcao = :edcao
                order by prded.id_prded_ordem, p.vl_pdcao_pagin desc"; 
        }
        else 
        {   
            $sql = "
            select 
                edcao.cd_edcao as edcao,
                edcao.dt_edcao as date,
                prded.ds_prded as caderno,
                jonal.ds_jonal as jornal,
                pdcao.vl_pdcao_pagin as page,
                edria.cd_edria as cd_editoria,
                edria.nm_edria as editoria,
                concat_ws('', replace(edcao.ds_edcao_url, 'http://edicao.intra.jc.com.br', 'http://fivenews.sjcc.com.br/jornal'), '/thumb/', pdcao.cd_pdcao, '.jpg') as thumb,
                concat_ws('', replace(edcao.ds_edcao_url, 'http://edicao.intra.jc.com.br', 'http://fivenews.sjcc.com.br/jornal'), '/pdf/', pdcao.ds_pdcao_arquv) as pdf,
                -- concat_ws('', replace(edcao.ds_edcao_url, 'http://edicao.intra.jc.com.br', 'http://fivenews.sjcc.com.br/jornal'), '/issue', pdcao.cd_edcao, '.pdf') as pdf_completo,
                concat_ws('', replace(edcao.ds_edcao_url, 'http://edicao.intra.jc.com.br', 'http://fivenews.sjcc.com.br/jornal'), '/../all.pdf') as pdf_completo,
                concat_ws('', replace(edcao.ds_edcao_url, 'http://edicao.intra.jc.com.br', 'http://fivenews.sjcc.com.br/jornal'), '/jpgdevice/', pdcao.cd_pdcao, '.jpg') as device,
                concat_ws('', replace(edcao.ds_edcao_url, 'http://edicao.intra.jc.com.br', 'http://fivenews.sjcc.com.br/jornal'), '/jpg/', pdcao.cd_pdcao, '.jpg') as imageAlta,
                concat_ws('', replace(edcao.ds_edcao_url, 'http://edicao.intra.jc.com.br', 'http://fivenews.sjcc.com.br/jornal'), '/thumb/', pdcao.cd_pdcao, '.jpg') as image
            from
                jonal, prded, mstre, edcao, edria,
                pdcao, edcao dia
            where
                jonal.cd_jonal = prded.cd_jonal and
                prded.cd_prded = mstre.cd_prded and
                mstre.cd_mstre = edcao.cd_mstre and
                pdcao.cd_edcao = edcao.cd_edcao and
                pdcao.cd_edria = edria.cd_edria and

                date_format(edcao.dt_edcao, '%Y-%m-%d') = date_format(dia.dt_edcao, '%Y-%m-%d') and
                dia.cd_edcao = :edcao
            order by  
                prded.id_prded_ordem, pdcao.vl_pdcao_pagin";
        }
        $this->db();
        $stmt = $this->pdo->prepare($sql); 
        $stmt->bindValue( ":edcao", $edcao);
        $stmt->execute();       
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}

    public function getEdcaoEdriaNodate() : array
    {
        return $this->getEdcaoEdria("");
    }
    
    public function getEdcaoPagin($edcao) : array
    {   
        $this->db();
        $stmt = $this->pdo->prepare("
        select 
            edcao.cd_edcao,
            edcao.dt_edcao,
            prded.ds_prded,
            jonal.ds_jonal,
            pdcao.vl_pdcao_pagin,
            edria.nm_edria,
            replace(concat_ws('', poral.ds_poral_url, '/_conteudo', matia.ds_matia_path), '.html', '.json') as json,
            replace(concat_ws('', 'https://app.fivenews.com.br/convert.php?url=', poral.ds_poral_url, '/_conteudo', matia.ds_matia_path), '.html', '.xml') as xml,
            concat_ws('', replace(edcao.ds_edcao_url, 'http://edicao.intra.jc.com.br', 'http://fivenews.sjcc.com.br/jornal'), '/thumb/', pdcao.cd_pdcao, '.jpg') as thumb   ,
            concat_ws('', replace(edcao.ds_edcao_url, 'http://edicao.intra.jc.com.br', 'http://fivenews.sjcc.com.br/jornal'), '/pdf/', pdcao.ds_pdcao_arquv) as pdf,
            -- concat_ws('', replace(edcao.ds_edcao_url, 'http://edicao.intra.jc.com.br', 'http://fivenews.sjcc.com.br/jornal'), '/issue', pdcao.cd_edcao, '.pdf') as pdf_completo,
            concat_ws('', replace(edcao.ds_edcao_url, 'http://edicao.intra.jc.com.br', 'http://fivenews.sjcc.com.br/jornal'), '/../all.pdf') as pdf_completo,
            concat_ws('', replace(edcao.ds_edcao_url, 'http://edicao.intra.jc.com.br', 'http://fivenews.sjcc.com.br/jornal'), '/jpgdevice/', pdcao.cd_pdcao, '.jpg') as device,
            concat_ws('', replace(edcao.ds_edcao_url, 'http://edicao.intra.jc.com.br', 'http://fivenews.sjcc.com.br/jornal'), '/jpg/', pdcao.cd_pdcao, '.jpg') as imageAlta,
                concat_ws('', replace(edcao.ds_edcao_url, 'http://edicao.intra.jc.com.br', 'http://fivenews.sjcc.com.br/jornal'), '/thumb/', pdcao.cd_pdcao, '.jpg') as image
        from
            jonal, prded, mstre, edcao,
            pdcao, edria
        where
            jonal.cd_jonal = prded.cd_jonal and
            prded.cd_prded = mstre.cd_prded and
            mstre.cd_mstre = edcao.cd_mstre and
            edcao.cd_edcao = pdcao.cd_edcao and
            pdcao.cd_edria = edria.cd_edria and
            edcao.cd_edcao = :edcao"); 

        $stmt->bindValue( ":edcao", $edcao);

        $stmt->execute();       
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getEdcaoMatia($edcao, $edria, $pdcao = null) : array
    {   
        $this->db();
        $sql = "
        select 
            matia.cd_matia as id,
            edcao.cd_edcao as cd_edcao,
            edcao.dt_edcao as dt_edcao,
            edria.nm_edria as editoria,
            ds_matia_assun as subject,
            matia.ds_matia_titlo as title,
            ds_matia_chape as description,
            concat_ws('', poral.ds_poral_url, matia.ds_matia_path) AS link,
            replace(concat_ws('', 'https://app.fivenews.com.br/convert.php?url=', poral.ds_poral_url, '/_conteudo', matia.ds_matia_path), '.html', '.xml') as xml,
            matia.dt_matia_publi as published,
            case when matia.cd_midia > 0 then (select ds_midia_link from midia where cd_midia = matia.cd_midia limit 1) else
            null end as image
            from
                jonal, prded, mstre, edcao,
                pdcao, edria, pdmat, matia,
				site, poral
            where
                jonal.cd_jonal = prded.cd_jonal and
                prded.cd_prded = mstre.cd_prded and
                mstre.cd_mstre = edcao.cd_mstre and
                edcao.cd_edcao = pdcao.cd_edcao and
                pdcao.cd_edria = edria.cd_edria and
                pdcao.cd_pdcao = pdmat.cd_pdcao and
                pdmat.cd_matia = matia.cd_matia and
				matia.cd_site = site.cd_site and
				site.cd_poral = poral.cd_poral and
				poral.cd_poral = 1 and
                edcao.cd_edcao = :edcao and edria.cd_edria = :edria ";
        if ($pdcao != "") {
            $sql .= " and pdcao.vl_pdcao_pagin = " . $pdcao;
        }
        $stmt = $this->pdo->prepare($sql); 
        $stmt->bindValue( ":edcao", $edcao);
        $stmt->bindValue( ":edria", $edria);

        /** sql provisorio  */
        // $sql = "
        // SELECT DISTINCT
        //     matia.cd_matia as id,
        //     edcao.cd_edcao as cd_edcao,
        //     edcao.dt_edcao as dt_edcao,
        //     edria.nm_edria as editoria,
        //     ds_matia_assun as channel,
        //     matia.ds_matia_titlo as title,
        //     ds_matia_chape as description,
        //     concat_ws('', poral.ds_poral_url, matia.ds_matia_path) AS link,
        //     replace(concat_ws('', poral.ds_poral_url, '/_conteudo', matia.ds_matia_path), '.html', '.json') as json,
        //     replace(concat_ws('', 'https://app.fivenews.com.br/convert.php?url=', poral.ds_poral_url, '/_conteudo', matia.ds_matia_path), '.html', '.xml') as xml,
        //     matia.dt_matia_publi as published,
        //     case when matia.cd_midia > 0 then (select ds_midia_link from midia where cd_midia = matia.cd_midia limit 1) else
        //     null end as image
        // FROM matia
        //     INNER JOIN prded ON (prded.cd_prded = matia.cd_prded)
        //     INNER JOIN jonal ON (jonal.cd_jonal = prded.cd_jonal)
        //     INNER JOIN poral ON (poral.cd_poral = jonal.cd_jonal)
        //     INNER JOIN edcao ON (date_format(edcao.dt_edcao, '%Y-%m-%d') = date_format(matia.dt_matia_edcao, '%Y-%m-%d'))
        //     INNER JOIN mstre ON (mstre.cd_mstre = edcao.cd_mstre)
        //     INNER JOIN pdcao ON (edcao.cd_edcao = pdcao.cd_edcao)
        //     INNER JOIN edria ON (pdcao.cd_edria = edria.cd_edria and matia.cd_edria = edria.cd_edria)
        // WHERE 
        //     edcao.cd_edcao = :edcao AND
        //     edria.cd_edria = :edria";
        
        // $stmt = $this->pdo->prepare($sql); 
        // $stmt->bindValue( ":edcao", $edcao);
        // $stmt->bindValue( ":edria", $edria);

        $stmt->execute();       
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
