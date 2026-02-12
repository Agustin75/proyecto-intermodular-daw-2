<?php
define('DB_HOST', 'localhost');
//Aqui ponemos la BD de nuestro proyecto
define('DB_NAME', 'proyecto');
define('DB_USER', 'root');
define('DB_PASS', '');

define("USER_GUEST", 1);
define("USER_REGISTERED", 2);
define("USER_ADMIN", 3);

define("MODE_CREATE", 1);
define("MODE_EDIT", 2);

define("GAME_STATE_PLAYING", 1);
define("GAME_STATE_WON", 2);
define("GAME_STATE_LOST", 3);

define("CLASIFICAR_TIPO", 1);
define("CLASIFICAR_GENERACION", 2);

define("ADIVINANZA_GRITO", 1);
define("ADIVINANZA_SILUETA", 2);
define("ADIVINANZA_DESCRIPCION", 3);
$mvc_vis_css = "estilo.css";

?>