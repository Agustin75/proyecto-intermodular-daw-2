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
                    // We check that an user was found and that the user found is active before checking the password
                    if (($usuario != false && count($usuario) > 0) &&
                         $usuario["activo"] &&
                         comprobarhash($contrasenya, $usuario['contrasenya'])) {
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
            'nivel' => USER_REGISTERED,
        ];

        $errores = [];

        if (isset($_POST['bRegistro'])) {

            $nombre      = recoge('nombre');
            $email       = recoge('email');
            $contrasenya = recoge('contrasenya');
            $nivel       = $this->session->getUserLevel();
            $imagen      = recoge('imagen');

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
            $imagen = "default";
            if (empty($errores)) {
                try {
                    $m = new Usuario();
                    $idUsuario = $m->crearUsuario($nombre, encriptar($contrasenya), $email, $imagen);
                    if ($idUsuario != false) {
                        // User registered, we send the email with the activation token
                        $mailer = new Mailer();

                        // We create a new unique token
                        $token = uniqid();
                        // TODO: These are a few methods to possibly generate the url, will need to figure out which one works once it's properly set up in a docker or with a domain name. Currently, it works with a hardocded path
                        // $urlRoot = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
                        $bodyEmail = "Clickea el siguiente link para activar tu cuenta: ";
                        // $bodyEmail .= "<a href=\"" . $_SERVER["PHP_SELF"] . "?ctl=confirmarCuenta&token=" . $token . "\">Activar</a>";
                        $bodyEmail .= "<a href=\"https://www.pokehunt.com\index.php?ctl=confirmarCuenta&token=" . $token . "\">Activar</a>";
                        // The token expires 10 minutes from now
                        $expira = time() + 3600;

                        $mValidacion = new Validacion();
                        $mValidacion->guardarToken($idUsuario, $token, $expira);

                        $mailer->enviar($email, "Activación de cuenta", $bodyEmail);

                        // TODO: Mostrar sin usar params mensaje porque sale como un error. Limpiar los campos también
                        $params["mensaje"] = "Para finalizar el registro tendrás que activar tu cuenta. Comprueba tu correo y clickea el enlace de activación.";
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

    public function mostrarPerfil()
    {
        try {
        } catch (Throwable $e) {
            $this->handleError($e);
        }
        require __DIR__ . '/../templates/miPerfil.php';
    }

    public function mostrarTools()
    {
        try {
        } catch (Throwable $e) {
            $this->handleError($e);
        }
        require __DIR__ . '/../templates/DevTools.php';
    }

    public function perfilPokemon()
    {
        $errores = [];
        $params = [];
        // Variable to know if user is looking at their own Profile and they can change their favorite Pokemon
        $params["editable"] = false;

        try {
            $userId = recoge("id");

            if (empty($userId)) {
                $userId = $this->session->getUserId();
                $params["editable"] = true;
            } else {
                cNum($userId, "id", $errores);
            }

            $mUsuario = new Usuario();
            $usuario = $mUsuario->obtenerUsuario($userId);

            $params["userId"] = $userId;
            $params["userName"] = $usuario["nombre"];
            $params["userImage"] = $usuario["imagen"];
            $params["favorites"] = [];
            $params["allPok"] = [];

            $mPokemonUsuario = new PokemonUsuario();
            $favorites = $mPokemonUsuario->obtenerPokemonUsuario($userId, true);

            $mPokeApi = new PokeAPI();
            $currPokemon = [];
            foreach ($favorites as $index => $favoritePokemon) {
                $pokemonId = $favoritePokemon["id_pokemon"];
                $currPokemon["id"] = $pokemonId;
                $currPokemon["name"] = $mPokeApi->getPokemonName($pokemonId);
                $currPokemon["image"] = $mPokeApi->getPokemonNormalSprite($pokemonId);
                $params["favorites"][$index] = $currPokemon;
            }

            $allPokemon = $mPokemonUsuario->obtenerPokemonUsuario($userId, false);
            foreach ($allPokemon as $index => $pokemon) {
                $pokemonId = $pokemon["id_pokemon"];
                $currPokemon["id"] = $pokemonId;
                $currPokemon["name"] = $mPokeApi->getPokemonName($pokemonId);
                $currPokemon["image"] = $mPokeApi->getPokemonNormalSprite($pokemonId);
                $currPokemon["favorited"] = $pokemon["favorito"] == 1;
                $params["allPokemon"][$index] = $currPokemon;
            }
        } catch (Throwable $e) {
            $this->handleError($e);
        }

        require __DIR__ . '/../templates/perfilPokemon.php';
    }
}
