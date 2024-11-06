<?php
$host="localhost";
$bd="prueba1";
$usuario="root";
$contrasena="";

try{
    $conexion=new PDO("mysql:host=$host;dbname=$bd",$usuario,$contrasena);
    if($conexion){echo "Conectado a Sistema";}
}catch(Exception $ex){
    echo $ex->getMessage();
}
?>