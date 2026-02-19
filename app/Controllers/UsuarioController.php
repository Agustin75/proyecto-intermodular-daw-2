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
                        comprobarhash($contrasenya, $usuario['contrasenya'])
                    ) {
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
            $imagen = DEFAULT_AVATAR;
            if (empty($errores)) {
                try {
                    $m = new Usuario();
                    $idUsuario = $m->crearUsuario($nombre, encriptar($contrasenya), $email, $imagen);
                    if ($idUsuario !== false) {
                        // User registered, we send the email with the activation token
                        $mailer = new Mailer();

                        // We create a new unique token
                        $token = uniqid();
                        $bodyEmail = "<p>Clickea el siguiente link para activar tu cuenta: ";
                        $bodyEmail .= "<a href=\"https://localhost/index.php?ctl=confirmarCuenta&token=" . $token . "\">Activar</a></p>";
                        $bodyEmail .= "<p>El enlace expira pasados 10 minutos.</p>";
                        // The token expires 10 minutes from now
                        $expira = time() + 3600;

                        $mValidacion = new Validacion();
                        $mValidacion->guardarToken($idUsuario, $token, $expira);

                        $mailer->enviar($email, "Activación de cuenta", $bodyEmail);

                        // TODO: Mostrar sin usar params mensaje porque sale como un error. Limpiar los campos también
                        $params["info"] = "Para finalizar el registro tendrás que activar tu cuenta. Comprueba tu correo y clickea el enlace de activación.";
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
        $id = $this->session->getUserId();
        try {
            $m = new Usuario();

            $params = [
                'currImage' => $m->obtenerUsuario($id)["imagen"]
            ];

            if (isset($_POST['bCambiarImagen'])) {
                $imagen = recoge('newImage');

                if(!file_exists("images/avatars/" . $imagen . ".png") || !$m->cambiarImagen($imagen, $id)) {
                    $params["mensaje"] = "No se pudo cambiar la imagen.";
                } else {
                    // We send the player back to their profile so they can see the image they selected
                    // TODO: Remove this and uncomment the following line if the images are shown in "Cambiar Imagen"
                    // $params["currImage"] = $imagen;
                    header("Location: index.php?ctl=mostrarPerfil");
                    exit;
                }
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
                        $this->session->setUserName($new);
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

    public function pedirUsuario()
    {
        $errores = [];

        $usuario = recoge('nombreUsuario');

        try {
            if (isset($_POST['bEnviar'])) {
                $m = new Usuario();

                if (cTexto($usuario, "nombreUsuario", $errores)) {
                    $datosUsuario = $m->buscarUsuario($usuario);

                    $params["info"] = "Recibirás un correo con un enlace para cambiar tu contraseña. Si no lo recibes, verifica que no esté en tu buzón de spam o que el nombre de usuario sea correcto.";

                    if ($datosUsuario !== false) {
                        $email = $datosUsuario["email"];
                        $idUsuario = $datosUsuario["id"];

                        // User registered, we send the email with the activation token
                        $mailer = new Mailer();

                        // We create a new unique token
                        $token = uniqid();
                        $bodyEmail = "<p>Clickea el siguiente link para resetear tu contraseña: ";
                        $bodyEmail .= "<a href=\"https://localhost/index.php?ctl=cambiarPassword&token=" . $token . "\">Activar</a></p>";
                        $bodyEmail .= "<p>El enlace expira pasados 10 minutos.</p>";
                        // The token expires 10 minutes from now
                        $expira = time() + 3600;

                        $mValidacion = new Validacion();
                        $mValidacion->guardarToken($idUsuario, $token, $expira);

                        $mailer->enviar($email, "Cambio de contraseña", $bodyEmail);
                    }

                    // We stay in the same page
                }
            }
        } catch (Throwable $e) {
            $this->handleError($e);
        }

        require __DIR__ . '/../templates/pedirUsuario.php';
    }

    /**
     * Handles showing the screen to change the password using the token and actually changing the password
     */
    public function cambiarPassword()
    {
        $errores = [];

        try {
            // If we arrived here through the form to change the password
            if (isset($_POST['bCambiarPassword'])) {
                // We obtain the new password
                $newPassword = recoge('newPassword');

                // If the password is valid
                if (cTexto($newPassword, "newPassword", $errores, 30, 1, "0123456789!@#%^&*()_+-=")) {
                    // We obtain and validate the user id
                    $userId = recoge("userId");
                    if (cNum($userId, "userId", $errores)) {
                        $userId = intval($userId);

                        $m = new Usuario();
                        // We try changing the user's password to the new password
                        if ($m->cambiarPassword(encriptar($newPassword), $userId)) {
                            $params["success"] = "La contraseña se ha actualizado correctamente.";
                        } else {
                            $params["mensaje"] = "No se pudo cambiar la contraseña.";
                            // If it didn't succeed, we save the user id in params to send it again later
                            $params["userId"] = $userId;
                        }
                    }
                }
                // We came here through the link in the email
            } else {
                // We obtain the token
                $token = recoge("token");

                // We obtain the register assigned to said token
                $mValidacion = new Validacion();
                $register = $mValidacion->confirmarToken($token);

                // We check if the token exists in the table
                if ($register != false && count($register) > 0) {
                    // We check if the token is still valid
                    if ($register["valido_hasta"] > time()) {
                        // If it is, we save the id to use in the form
                        $params["userId"] = $register["id_user"];
                    }

                    // We remove the token from the database regardless of whether the password change was successful
                    $mValidacion->eliminarToken($token);
                // The user did not enter this page with a valid token, return them to the home screen
                } else {
                    // The token doesn't exist in the table, so the user is using an expired or already used link, redirect them to home
                    header("Location: index.php?ctl=inicio");
                    exit;
                }
            }
        } catch (Exception $e) {
            $this->handleError($e);
        }

        require __DIR__ . '/../templates/cambiarPassword.php';
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

    public function mostrarOpciones()
    {
        $params = [
            "userName" => $this->session->getUserName()
        ];

        try {
            $mUsuario = new Usuario();
            $usuario = $mUsuario->obtenerUsuario($this->session->getUserId());

            if ($mUsuario !== false) {
                $params["userImage"] = $usuario["imagen"];
            } else {
                header("Location: index.php?ctl=inicio");
                exit;
            }
        } catch (Throwable $e) {
            $this->handleError($e);
        }

        require __DIR__ . '/../templates/opciones.php';
    }

    public function mostrarTools()
    {
        try {
        } catch (Throwable $e) {
            $this->handleError($e);
        }
        require __DIR__ . '/../templates/DevTools.php';
    }

    public function mostrarPerfil()
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
            $params["allPokemon"] = [];

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
