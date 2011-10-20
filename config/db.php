<?php
    require ("libs/adodb5/adodb.inc.php"); 
    $hostDB = '';
    $usuarioDB = '';
    $passDB = '';
    $tablaDB = '';
    $db = NewADOConnection("");
    $db->Connect($hostDB, $usuarioDB, $passDB, $tablaDB);
    $db->Execute("set names 'utf8'");
    //$db->debug = true;
?>