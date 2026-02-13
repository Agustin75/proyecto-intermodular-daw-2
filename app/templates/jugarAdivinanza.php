<?php ob_start() ?>
<head> <link rel=stylesheet href="../web/css/estilo.css" type="text/css"></head>

<h1 class="text-center">Adivina el Pokémon</h1>

<?php if ($params["gameFound"] === false): ?>

    <div class="alert alert-warning text-center">
        <?= $params["mensaje"] ?>
    </div>

    <div class="container text-center mt-3">
        <a class="btn btn-success" href="index.php?ctl=juegos">Volver</a>
    </div>

<?php else: ?>

    <?php if (!empty($params["mensaje"])): ?>
        <div class="alert alert-danger text-center">
            <?= $params['mensaje'] ?>
        </div>
    <?php endif; ?>

    <br>

    <!-- ============================
         ESTADO: GANADO
    ============================= -->
    <?php if ($params["gameState"] === GAME_STATE_WON): ?>

        <div class="alert alert-success text-center ">
            <h2 class="text-dark">¡Bien hecho!</h2>
            <img src="<?= $params["imagen_pokemon_recompensa"] ?>" 
                alt="Imagen de <?= $params["nombre_pokemon_recompensa"] ?>">
            <p class="text-dark">¡Has ganado un <?= ucfirst($params["nombre_pokemon_recompensa"]) ?>!</p>
        </div>

        <div class="container text-center">
            <a class="btn btn-success" href="index.php?ctl=juegos">Volver</a>
        </div>

    <!-- ============================
         ESTADO: PERDIDO
    ============================= -->
    <?php elseif ($params["gameState"] === GAME_STATE_LOST): ?>

        <div class="alert alert-danger text-center text-dark">
            <h2 class="text-dark">Buen intento</h2>
            <p class="text-dark">No te rindas, ¡hazte con todos!</p>
        </div>

        <div class="container text-center">
            <a class="btn btn-success" href="index.php?ctl=juegos">Volver</a>
        </div>

    <!-- ============================
         ESTADO: JUGANDO
    ============================= -->
    <?php elseif ($params["gameState"] === GAME_STATE_PLAYING): ?>
 <?php if ($params["tipo"] === ADIVINANZA_GRITO): ?>
            <h4 class="text-center">Grito de pokemon:</h4>
           <audio id="audio" controls>

             <source id="audio" src="<?= $params['tipo_object'] ?>" type="audio/ogg">
           </audio>
 <?php endif; ?>

 <?php if ($params["tipo"] === ADIVINANZA_SILUETA): ?>
            <h4 class="text-center">Silueta de pokemon:</h4>
            <div id="sileutadiv">
            <img id="silueta" src=" <?=  $params['tipo_object'] ?>">
            </div>
 <?php endif; ?>
 
 <?php if ($params["tipo"] === ADIVINANZA_DESCRIPCION): ?>
            <h4 class="text-center">Descripción de pokemon:</h4>
           <p id="descripcion"> <?=  $params['tipo_object'] ?> </p>
 <?php endif; ?>
 
        <br>

        <form  action="index.php?ctl=jugarAdivinanza" method="POST" class=" game text-center">
            <p id="pista"> <?=  $params['pista1'] ?> </p><br>
        <p id="pista"> <?=  $params['pista2'] ?> </p><br>
        <p id="pista"> <?=  $params['pista3'] ?> </p><br>

            <input type="hidden" name="id" value="<?= $params["id"] ?>">
            <input type="hidden" name="correctPokemonId" value="<?= $params["correctPokemonId"] ?>">

            <div class="container">
            <input name="respuesta">
                
            </input>
        </div>

            <br>
            <button class="btn btn-success" type="submit" name="bEnviar">
                Confirmar
            </button>
        </form>

    <?php endif; ?>

<?php endif; ?>

<?php $contenido = ob_get_clean() ?>
<?php include 'layout.php' ?>
