<?php

    $dbHost = 'mainline.proxy.rlwy.net';
    $dbUsername = 'root';
    $dbPassword = 'VlrJHGcHBKAmmYTrKfrUjGDoSkHlOHEs';
    $dbName = 'railway';
    $port = 48238;

    $conexao = new mysqli($dbHost,$dbUsername,$dbPassword,$dbName,$port);
    // if($conexao->connect_errno)
    //     {
    //     echo "Erro";
    // }
    // else
    // {
    //     echo "Certo";
    // }
?>