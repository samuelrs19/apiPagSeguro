<?php

session_start();

if (isset($_REQUEST['token'])) {

    $token = $_REQUEST['token'];

    if (!empty($token)) {

        require_once __DIR__ . "/../controle/PagSeguro.php";
        $objPagSeguro = new PagSeguro();

        $objPagSeguro->criarPlano();
    } else {
        echo '<b style="color: red;">Token não informado</b>';
    }
} else {
    echo "<b style='color: red;'>Parametros não enviado de forma correta.</b>";
}
