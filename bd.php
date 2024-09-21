<?php
//realizamos la conexion con la base de datos
$servidor="localhost";
$basedeDatos="sis_venta";
$usuario = "****"; //cambiar por su usuario
$contrasenia="******";//cambiar por su contraseña

//se almacena la conexion 
try {
    $conexion = new PDO("mysql:host=$servidor;dbname=$basedeDatos", $usuario, $contrasenia);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(Exception $ex) {
    echo $ex->getMessage();
}

