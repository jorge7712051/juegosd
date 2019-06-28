<?php
error_reporting(0);
ini_set('display_error', 'On');



#constantes

$Directorio = 'juegosd'; 
defined('RUTA_RAIZ') or define('RUTA_RAIZ', "http://".$_SERVER['HTTP_HOST']."/$Directorio/");


$Entorno = 'Desarrollo';

switch ($Entorno) {
    case 'Desarrollo':
        # Conexiones por mticlass desarrollo
        defined("JUEGO") or define("JUEGO", "juego");             
       
        
         
        break;

    
    default:
        break;
}
?>