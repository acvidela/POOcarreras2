<?php
require_once('menues' . DIRECTORY_SEPARATOR . 'menu.php');
require_once('datos' . DIRECTORY_SEPARATOR . 'conexion.php');  

//MAIN

$menu = new Menu();

$menu->cls();
$menu->pantallaBienvenida('Es-Tan-Dil');

$db = Conexion::getConexion();
 
$menu->operacionesAdmin();  //0 salir, 1 carreras, 2 atletas, 3 inscripciones

$menu->pantallaDespedida();

$db = Conexion::closeConexion();
