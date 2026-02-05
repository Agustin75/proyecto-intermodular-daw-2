<?php


class AdminController extends Controller
{

public function crearJuegos()
{
    $mTrivia = new Trivia();
    $api = new PokeAPI();

    // Obtener trivias desde la BD
    $trivias = $mTrivia->obtenerTodasLasTrivias();

    // Añadir nombre del Pokémon usando la API
    foreach ($trivias as $i => $t) {
        $pokemon = $api->getPokemonById($t["id_pokemon"]);
        $trivias[$i]["pokemon_name"] = ucfirst($pokemon["name"]);
    }

    $params = [
        "trivias" => $trivias
    ];

    require __DIR__ . '/../templates/crearJuegos.php';
}






    public function mostrarTools()
    {
       
        require __DIR__ . '/../templates/DevTools.php';
    }

   public function crearJuego() {

        require __DIR__ . '/../templates/crearJuegos.php';


    }

    public function entrarJuegos() {

        require __DIR__ . '/../templates/verJuegos.php';


    }

 public function vistaAdivinanza() {

       $params = [
        'modo'   => '',
        'id' => '',
        'id_pkmn' => '',
        'id_tipo' => '',
        'pista1' => '',
        'pista2' => '',
        'pista3' => '',
    ];
    
        if($params['modo'] !== "nueva"){
            $id = recoge('id');
            $params['id'] = $id;

            /*$m = new Clasificacion;
            $all = $m->obtenerTrivia($id);
            $params['id_pkmn'] = $all['enunciado'['id_pokemon']];
            $params['pregunta'] = $all['enunciado'['pregunta']];
            $params['tiempo'] = $all['opciones'['tiempo']];
            $params['opciones'] = $all['opciones'];*/

        }





        require __DIR__ . '/../templates/crearAdivinanza.php';


    }


}
