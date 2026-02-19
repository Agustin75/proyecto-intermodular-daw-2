<?php
class InicioController extends Controller
{
    public function inicio()
    {
        $params = array(
            'page_title' => 'Bienvenido al mundo de PokeHunt',
            "paragraphs" => [
                "Ve a la Wiki para aprender más sobre los Pokémon, o empieza a capturar Pokémon en la sección de Juegos.",
                "Compite con otros entrenadores en los Rankings. ¿Lograrás ser el mejor maestro Pokémon?"
            ],
            "image" => "images/logo_proyect.png"
        );

        require __DIR__ . '/../templates/inicio.php';
    }
}
