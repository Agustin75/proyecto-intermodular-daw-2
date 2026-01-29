<?php
// -------------------------------------------------------------
// Front Controller del mini-framework
// -------------------------------------------------------------

require_once __DIR__ . '/../app/libs/Config.php';
require_once __DIR__ . '/../app/libs/bGeneral.php';
require_once __DIR__ . '/../app/libs/bSeguridad.php';
require_once __DIR__ . '/../app/core/autoload.php';


// -------------------------------------------------------------
// Sesión segura
// -------------------------------------------------------------
$session = new SessionManager(
    loginPage: 'index.php?ctl=inicio',
    timeout: 600
);

// DEBUG: Descomenta esta línea para simular un usuario logueado
// $session->login(1, "Nombre", USER_ADMIN);

// Comprobaciones de seguridad (fingerprint + timeout)
$session->checkSecurity();

// -------------------------------------------------------------
// Mapa de rutas
// -------------------------------------------------------------
$map = [
    'inicio' => ['controller' => 'InicioController', 'action' => 'inicio', 'nivel' => USER_GUEST],
    'registro' => ['controller' => 'UsuarioController', 'action' => 'registrarUsuario', 'nivel' => USER_GUEST],
    'iniciarSesion' => ['controller' => 'UsuarioController', 'action' => 'iniciarSesion', 'nivel' => USER_GUEST],
    'cambiarNombre' => ['controller' => 'UsuarioController', 'action' => 'cambiarNombre', 'nivel' => USER_REGISTERED],
    'cambiarImagen' => ['controller' => 'UsuarioController', 'action' => 'cambiarImagen', 'nivel' => USER_REGISTERED],
    'cerrarSesion' => ['controller' => 'UsuarioController', 'action' => 'salir', 'nivel' => USER_REGISTERED],
    'wiki'   => ['controller' => 'WikiController',   'action' => 'displayWiki', 'nivel' => USER_GUEST],
    'wikiPokemon'              => ['controller' => 'WikiController',   'action' => 'displayPokemon',     'nivel' => USER_GUEST],

    // Funciones API
    'wikiFilterByType'         => ['controller' => 'APIWikiController',   'action' => 'filterByType',       'nivel' => USER_GUEST],
    'wikiFilterByGeneration'   => ['controller' => 'APIWikiController',   'action' => 'filterByGeneration', 'nivel' => USER_GUEST],
];

// -------------------------------------------------------------
// Resolución de ruta
// -------------------------------------------------------------
$ruta = $_GET['ctl'] ?? 'inicio';

if (!isset($map[$ruta])) {
    header("HTTP/1.0 404 Not Found");
    echo "<h1>Error 404: Ruta '$ruta' no encontrada</h1>";
    exit;
}

$controllerName = $map[$ruta]['controller'];
$actionName     = $map[$ruta]['action'];
$requiredLevel  = $map[$ruta]['nivel'];

// -------------------------------------------------------------
// Comprobación de permisos
// -------------------------------------------------------------
if (!$session->hasLevel($requiredLevel)) {
    header("HTTP/1.0 403 Forbidden");
    echo "<h1>403: No tienes permisos para acceder a esta acción</h1>";
    exit;
}

// -------------------------------------------------------------
// Ejecución del controlador
// -------------------------------------------------------------
$controller = new $controllerName($session);

if (!method_exists($controller, $actionName)) {
    header("HTTP/1.0 404 Not Found");
    echo "<h1>Error 404: Acción '$actionName' no encontrada en $controllerName</h1>";
    exit;
}

$controller->$actionName();
