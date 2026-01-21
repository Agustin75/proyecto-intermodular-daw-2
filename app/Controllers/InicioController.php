<?php
class InicioController extends Controller{
   
   
    public function inicio()
    {

        $params = array(
            'mensaje' => 'Bienvenido',
            'mensaje2' => 'Este esqueleto puedes utilizarlo para tu proyecto',
            'fecha' => date('d-m-Y')
        );
        

        require __DIR__ . '/../templates/inicio.php';
    }


    

    
}
