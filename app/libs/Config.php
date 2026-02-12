<?php
define('DB_HOST', 'localhost');
//Aqui ponemos la BD de nuestro proyecto
define('DB_NAME', 'proyecto');
define('DB_USER', 'root');
define('DB_PASS', '');

define("USER_GUEST", 1);
define("USER_REGISTERED", 2);
define("USER_ADMIN", 3);

/* ---------------------------------------------------------
   CONFIGURACIÓN SMTP PARA ENVÍO DE CORREOS (GMAIL)
   Estos valores están basados en tu script funcional.
   --------------------------------------------------------- */

// Servidor SMTP de Gmail
define('SMTP_HOST', 'smtp.gmail.com');

// Puerto para conexión segura SSL
define('SMTP_PORT', 465);

// Usuario SMTP (tu correo Gmail)
define('SMTP_USER', 'noreply.pokehunt@gmail.com');

// Contraseña de aplicación generada por Google
define('SMTP_PASS', 'qkmxuqprcultchrf');

// Tipo de cifrado: SSL en tu caso
define('SMTP_SECURE', 'ssl'); 
// Equivalente a PHPMailer::ENCRYPTION_SMTPS

// Dirección del remitente
define('SMTP_FROM', 'noreply.pokehunt@gmail.com');

// Nombre visible del remitente
define('SMTP_FROM_NAME', 'PokeHunt');

// --- Gestión de Rutas Dinámicas ---

/**
 * Calculamos la ruta base del proyecto para el navegador.
 * str_replace asegura que las barras sean siempre '/' incluso en sistemas Windows.
 */
$projectDir = str_replace('\\', '/', dirname($_SERVER['PHP_SELF'], 2));

/**
 * BASE_URL: La raíz del proyecto (ej: /MVC_COMPLETO_SESIONES)
 * Si el proyecto se encuentra en la raíz del servidor, se define como cadena vacía.
 */
define('BASE_URL', ($projectDir === DIRECTORY_SEPARATOR) ? '' : $projectDir);

// --- Rutas de Activos (Assets) para el Navegador ---
// Estas constantes se usan en etiquetas HTML: <link>, <script>, <img>
define('CSS_PATH', BASE_URL . '/web/css/');
define('JS_PATH',  BASE_URL . '/web/js/');
define('IMG_PATH', BASE_URL . '/web/images/');

/**
 * Ficheros Específicos
 * Nombre del archivo CSS principal que se cargará en el layout.
 */
//css
define('LAYOUT_CSS', 'estilo.css');

// Website-specific variables
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