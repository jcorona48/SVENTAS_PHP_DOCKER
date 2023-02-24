<?php

function Conectar(){
    $user = "root";
    $pass = "17574886";
    $port = "mariadb2";
    $db = "DBVentas";
    $con = mysqli_connect($port, $user, $pass, $db);

    return $con;
}



?>