<?php
class Controller {
    protected SessionManager $session; 
    protected string $currentRoute; 
    public function __construct(SessionManager $session) { 
        
        $this->session = $session; 
        // Detectar la ruta actual (ctl) 
        $this->currentRoute = $_GET['ctl'] ?? 'inicio'; 
    }
    //Método que se encarga de cargar el menu que corresponda según el tipo de usuario
    //Además marca el elemento activo según la ruta actual
    //Podemos incluir menús contextuales según la acción

  protected function menu(): array
{
    $nivel = $this->session->getUserLevel();
    $ruta  = $this->currentRoute;

    // ---------------------------------------------------------
    // 1. Menú base según nivel
    // ---------------------------------------------------------
    $menusBase = [
        1 => [
            ['Inicio', 'inicio']      
        ],
        2 => [
             ['Inicio', 'inicio'] 
        ],
        3 => [
           ['Inicio', 'inicio'] 
        ]
    ];

    // Seleccionar menú base
    $menu = $menusBase[$nivel];

    // ---------------------------------------------------------
    // 2. Menús contextuales según la acción
    // ---------------------------------------------------------
    $menusContextuales = [

        // Sección de libros
        'inicio' => [
            ['Iniciar Sesión', 'iniciarSesion'],
            ['Registro', 'registro']
            
        ] 
    ];

    // Si la acción actual tiene menú contextual, lo añadimos
    if (isset($menusContextuales[$ruta])) {
        $menu =  $menusContextuales[$ruta];
    }

    // ---------------------------------------------------------
    // 3. Marcar elemento activo
    // ---------------------------------------------------------
    foreach ($menu as &$item) {
        $item['active'] = ($item[1] === $ruta);
    }

    return $menu;
}

// Método para el manejo de errores y excepciones

protected function handleError(Throwable $e): void
{
    switch (true) {

        case $e instanceof PDOException:
            $logFile = "../app/log/logPDOException.txt";
            break;

        case $e instanceof Exception:
            $logFile = "../app/log/logException.txt";
            break;

        default: // Error, TypeError, ParseError, etc.
            $logFile = "../app/log/logError.txt";
            break;
    }

    error_log(
        $e->getMessage() . " | " . microtime() . PHP_EOL,
        3,
        $logFile
    );

    header('Location: index.php?ctl=error');
    exit;
}

   
    public function error()
    {

        require __DIR__ . '/../templates/error.php';
    }

    
}
