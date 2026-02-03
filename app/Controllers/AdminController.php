<?php


class AdminController extends Controller
{





    public function mostrarTools()
    {
       
        require __DIR__ . '/../templates/DevTools.php';
    }

   public function juegosAdmin() {

        require __DIR__ . '/../templates/JuegosAdmin.php';


    }

    public function entrarJuegos() {

        require __DIR__ . '/../templates/verJuegos.php';


    }
     public function vistaTrivia() {

       $params = [
        'modo'   => '',
        'id' => '',
        'id_pkmn' => '',
        'pregunta' => '',
        'tiempo' => '',
        'opciones' => '',
    ];
    
        if($params['modo'] !== "nueva"){
            $id = recoge('id');
            $params['id'] = $id;

            $m = new Trivia;
            $all = $m->obtenerTrivia($id);
            $params['id_pkmn'] = $all['enunciado'['id_pokemon']];
            $params['pregunta'] = $all['enunciado'['pregunta']];
            $params['tiempo'] = $all['opciones'['tiempo']];
            $params['opciones'] = $all['opciones'];

        }





        require __DIR__ . '/../templates/vistaTrivia.php';


    }
     public function vistaClasificar() {

       $params = [
        'modo'   => '',
        'id' => '',
        'id_pkmn' => '',
        'id_tipo' => '',
        'num_pkmn' => '',
        'num_opciones' => '',
    ];
    
        if($params['modo'] !== "nueva"){
            $id = recoge('id');
            $params['id'] = $id;

            /*$m = new Clasificar;
            $all = $m->obtenerClasificar($id);
            $params['id_pkmn'] = $all['enunciado'['id_pokemon']];
            $params['pregunta'] = $all['enunciado'['pregunta']];
            $params['tiempo'] = $all['opciones'['tiempo']];
            $params['opciones'] = $all['opciones'];*/

        }





        require __DIR__ . '/../templates/vistaClasificar.php';


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





        require __DIR__ . '/../templates/vistaAdivinanza.php';


    }


}
