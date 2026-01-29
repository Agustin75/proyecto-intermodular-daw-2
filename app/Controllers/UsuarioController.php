<?php


class UsuarioController extends Controller
{




    /* ===========================
     *       INICIAR SESIÓN
     *    ============================*/
    public function iniciarSesion()
    {
        $params = [
            'nombre' => '',
            'contrasenya'   => ''
        ];

        try {

            if ($this->session->getUserLevel() > 1) {
                header("Location: index.php?ctl=inicio");
                exit;
            }

            if (isset($_POST['bIniciarSesion'])) {

                $nombre = recoge('nombre');
                $contrasenya   = recoge('contrasenya');

                $params['nombre'] = $nombre;

                if ($nombre === '' || $contrasenya === '') {
                    $params['mensaje'] = 'Debes rellenar todos los campos.';
                } else {

                    $m = new Usuario();
                    $usuario = $m->buscarUsuario($nombre);
                    var_dump($contrasenya);
                    if (comprobarhash($contrasenya, $usuario['contrasenya'])) {
                        var_dump($contrasenya);
                        $this->session->login(
                            $usuario['id'],
                            $usuario['nombre'],
                            $usuario['nivel']
                        );

                        header('Location: index.php?ctl=inicio');
                        exit;
                    } else {
                        $params['mensaje'] = 'Usuario o contraseña incorrectos.';
                    }
                }
            }
        } catch (Throwable $e) {
            $this->handleError($e);
        }

        require __DIR__ . '/../templates/formInicioSesion.php';
    }


    public function registrarUsuario()
    {

        if ($this->session->getUserLevel() > 1) {
            header("Location: index.php?ctl=inicio");
            exit;
        }

        $params = [
            'nombre' => '',
            'contrasenya' => '',
            'email' => '',

            'nivel' => 2,

        ];

        $errores = [];

        if (isset($_POST['bRegistro'])) {

            $nombre      = recoge('nombre');
            $email       = recoge('email');
            $contrasenya = recoge('contrasenya');
            $nivel       = $this->session->getUserLevel();
            $imagen      = recoge('imagen');

            $nivelesPerm = [1 => 1, 2 => 2];

            $params = [
                'nombre' => $nombre,
                'email' => $email,
                'contrasenya' => $contrasenya,
                'nivel' => $nivel,
                'imagen' => $imagen

            ];

            cTexto($nombre, "nombre", $errores);
            cEmail($email, "email", $errores);
            cUser($contrasenya, "contrasenya", $errores);
            $nivel = 2;
            $imagen = "default";
            if (empty($errores)) {

                try {
                    $m = new Usuario();

                    if ($m->crearUsuario(
                        $nombre,
                        encriptar($contrasenya),
                        $email,
                        $imagen

                    )) {
                        header("Location: index.php?ctl=inicio");
                        exit;
                    } else {
                        $params['mensaje'] = 'No se ha podido registrar el usuario.';
                    }
                } catch (Throwable $e) {
                    $this->handleError($e);
                }
            }
        }
        require __DIR__ . '/../templates/Registro.php';
    }

    public function cambiarImagen()
    {
        $errores = [];
        $params = [
            'id' => '',
            'imagen'   => ''
        ];

        try {


            if (isset($_POST['bCambiarImagen'])) {

                $imagen = recoge('imagen');
                $id   = $this->session->getUserId();

                $params['imagen'] = $imagen;
                $params['id'] = $id;



                $m = new Usuario();
                $usuario = $m->cambiarImagen($imagen, $id);
            }
        } catch (Throwable $e) {
            $this->handleError($e);
        }

        require __DIR__ . '/../templates/cambioImagen.php';
    }

    public function seleccionarFavorito()
    {
        $errores = [];
        $params = [
            'id' => '',
            'id_pkmn'   => '',
            'fav' => '',
        ];
        $Perm = [true => true, false => false];
        $id_pkmn = recoge('id_pokemon');
        $fav = recogeArray('fav');
        $id   = $this->session->getUserId();
        try {
            if (isset($_POST['bIniciarSesion'])) {

                $fav_state = false;

                $params['id_pkmn'] = $id_pkmn;
                $params['id'] = $id;

                if (cCheck($fav, 'fav', $errores, $Perm) == true) {
                    $fav_state = true;
                } else {
                    $fav_state = false;
                }

                $m = new Usuario();
                $usuario = $m->elegirFavorito($id, $fav_state, $id_pkmn);
            }
        } catch (Throwable $e) {
            $this->handleError($e);
        }
        require __DIR__ . '/../templates/cartaPkmn.php';
    }
    public function cambiarNombre()
    {
        $errores = [];
        $params = [
            'nombre_old' => '',
            'nombre_new'   => ''
        ];

        $old = $this->session->getUserName();
        $params['nombre_old'] = $old;
        $new = recoge('new');

        $id   = $this->session->getUserId();
        try {
            if (isset($_POST['bCambiarNombre'])) {
                $m = new Usuario();

                if ($m->buscarUsuario($new) == "") {
                    if (cTexto($new, "nombre", $errores) == true) {
                        $m->cambiarNombre($new, $id);
                        header("Location: index.php?ctl=inicio");
                        exit;
                    }
                } else {
                    $params['mensaje'] = 'No se ha podido cambiar el nombre, ya existe.';
                }
            }
        } catch (Throwable $e) {
            $this->handleError($e);
        }
        require __DIR__ . '/../templates/cambioNombre.php';
    }

    public function Salir()
    {
        try {


            $this->session->logout();

            header("Location: index.php?ctl=inicio");
            exit;
        } catch (Throwable $e) {
            $this->handleError($e);
        }
    }
}
