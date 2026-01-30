<?php
class JuegosController extends Controller
{
    public function verJuegos()
    {
        $params = array(
            "user_level" => $this->session->getUserLevel()
        );

        require __DIR__ . '/../templates/verJuegos.php';
    }
}
