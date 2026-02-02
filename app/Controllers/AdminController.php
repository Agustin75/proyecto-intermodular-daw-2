<?php


class AdminController extends Controller
{





    public function mostrarTools()
    {
        try {
        } catch (Throwable $e) {
            $this->handleError($e);
        }
        require __DIR__ . '/../templates/DevTools.php';
    }



    public function juegosAdmin() {
try {

 
            
        } catch (Throwable $e) {
            $this->handleError($e);
        }
        require __DIR__ . '/../templates/JuegosAdmin.php';


    }

    public function crearTrivia(){
        
    }
}
