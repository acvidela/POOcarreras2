<?php
require_once('menues\menu.php');
require_once('datos\conexion.php');  

//MAIN

$menu = new Menu();

$menu->cls();
$menu->pantallaBienvenida('Es-Tan-Dil');

$db = Conexion::getConexion();
 
$menu->elegirUsuario();  //0 salir, 1 participante, 2 administrador

$menu->pantallaDespedida();

$db = Conexion::closeConexion();
