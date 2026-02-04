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
    'index.php?ctl=inicio',
    600
);

// Comprobaciones de seguridad (fingerprint + timeout)
$session->checkSecurity();

// -------------------------------------------------------------
// Mapa de rutas
// -------------------------------------------------------------
$map = [
    // General functions
    'inicio'        => ['controller' => 'InicioController', 'action'  => 'inicio',           'nivel' => USER_GUEST],
    'registro'      => ['controller' => 'UsuarioController', 'action' => 'registrarUsuario', 'nivel' => USER_GUEST],
    'iniciarSesion' => ['controller' => 'UsuarioController', 'action' => 'iniciarSesion',    'nivel' => USER_GUEST],
    'wiki'          => ['controller' => 'WikiController',    'action' => 'verWiki',          'nivel' => USER_GUEST],
    'wikiPokemon'   => ['controller' => 'WikiController',    'action' => 'verPokemon',       'nivel' => USER_GUEST],
    'juegos'        => ['controller' => 'JuegosController',  'action' => 'verJuegos',        'nivel' => USER_GUEST],
    
    // Registered user functions
    'miPerfil'      => ['controller' => 'UsuarioController', 'action' => 'mostrarPerfil',    'nivel' => USER_REGISTERED],
    'cambiarNombre' => ['controller' => 'UsuarioController', 'action' => 'cambiarNombre',    'nivel' => USER_REGISTERED],
    'cambiarImagen' => ['controller' => 'UsuarioController', 'action' => 'cambiarImagen',    'nivel' => USER_REGISTERED],
    'cerrarSesion'  => ['controller' => 'UsuarioController', 'action' => 'salir',            'nivel' => USER_REGISTERED],
    
    // Admin user functions
    'mostrarTools'      => ['controller' => 'AdminController', 'action' => 'mostrarTools',    'nivel' => USER_ADMIN],
    'crearJuego'        => ['controller' => 'AdminController', 'action' => 'crearJuego',      'nivel' => USER_ADMIN],
    // TODO: A editar cuando añadamos la funcionalidad
    // 'editarJuego'  => ['controller' => 'UsuarioController', 'action' => 'editarJuego', 'nivel' => USER_ADMIN],
    'crearTrivia'       => ['controller' => 'AdminController',      'action' => 'vistaTrivia',     'nivel' => USER_ADMIN],
    'crearAdivinanza'   => ['controller' => 'AdminController',      'action' => 'vistaAdivinanza', 'nivel' => USER_ADMIN],
    'crearClasificar'   => ['controller' => 'ClasificarController', 'action' => 'crearClasificar', 'nivel' => USER_ADMIN],
    
    // API functions
    'wikiFilterByType'         => ['controller' => 'APIWikiController',   'action' => 'filterByType',       'nivel' => USER_GUEST],
    'wikiFilterByGeneration'   => ['controller' => 'APIWikiController',   'action' => 'filterByGeneration', 'nivel' => USER_GUEST],
    'activarUser'              => ['controller' => 'APIUsuarioController', 'action' => 'activarUser',       'nivel' => USER_ADMIN]
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
