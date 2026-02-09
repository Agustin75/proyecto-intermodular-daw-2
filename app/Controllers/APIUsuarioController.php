<?php
class APIUsuarioController extends Controller
{
    public function activarUser()
    {



        $errors = [];
        try {

            if (isset($_GET['id'])) {

                $id = recoge('id');
                $act = recoge('act');

                if (empty($errors)) {
                    $m = new Usuario();

                    $m->activarUsuario($id, $act);
                }
            }
        } catch (Throwable $e) {
        }
    }

    public function seleccionarFavorito()
    {
        $errors = [];
        $act = recoge('act');
        $id_pkmn = recoge('id');
        $id = $this->session->getUserId();
        $m = new PokemonUsuario;
        $check = $m->obtenerPokemonUsuario($id, true);

        if (count($check) >= 6 && $act == true) {
            $errors = "Se ha llegado al numero máximo de favoritos";
        } else {
            $m->asignarFavorito($id, $id_pkmn, $act);
        }
    }
}
