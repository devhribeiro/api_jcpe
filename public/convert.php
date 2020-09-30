<?php 

    $u = $_GET['url'];
    $x = file_get_contents($u);
    $x = mb_convert_encoding($x,'HTML-ENTITIES', "UTF-8");
    $string = simplexml_load_string($x, 'SimpleXMLElement', LIBXML_NOCDATA);


    $string->ds_matia = html_entity_decode(utf8_decode($string->ds_matia));
    $string->ds_matia = strip_tags($string->ds_matia, '<p><a><h1><h2><h3><h4><h5><h6><blockquote><small><div><img><span><iframe><script><em>');
    $string->ds_matia = preg_replace('/\s+/',' ',$string->ds_matia);
    $string->ds_matia = str_replace('"',"'",$string->ds_matia);
    $string->ds_matia = str_replace('\\',"",$string->ds_matia);
    $json = json_encode($string);
    $array = json_decode($json, TRUE);
    echo $json;

?>