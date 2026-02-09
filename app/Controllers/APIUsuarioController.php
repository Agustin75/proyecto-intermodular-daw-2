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
}
