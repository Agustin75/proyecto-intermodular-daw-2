<?php
class APIUsuarioController extends Controller
{
    public function activarUser()
    {
        $errors = [];
        try {
            // We check to see if the user has sent a Pokemon type to search for
            if (isset($_GET['id'])) {
                // We get the Pokemon type from the select
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
}
