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
            $this->handleError($e);
        }
    }

    public function confirmarCuenta()
    {
        try {
            $token = recoge("token");

            $mValidacion = new Validacion();
            $register = $mValidacion->confirmarToken($token);

            if ($register != false && count($register) > 0) {
                // We check if the token is still valid
                if ($register["valido_hasta"] > time()) {
                    // We activate the user's account
                    $mUsuario = new Usuario();
                    $mUsuario->activarUsuario($register["id_user"], true);
                }

                // We remove the token from the database regardless of whether the activation was successful
                $mValidacion->eliminarToken($token);
            }
        } catch (Exception $e) {
            $this->handleError($e);
        }
    }

    public function seleccionarFavorito()
    {
        $errors = [];
        $act = recoge('act');
        $id_pkmn = recoge('id');
        $id = $this->session->getUserId();
        try {
            $m = new PokemonUsuario;
            $check = $m->obtenerPokemonUsuario($id, true);

            if (count($check) >= 6 && $act == true) {
                $errors = "Se ha llegado al numero máximo de favoritos";
            } else {
                $m->asignarFavorito($id, $id_pkmn, $act);
            }
        } catch (Exception $e) {
            $this->handleError($e);
        }
    }
}
