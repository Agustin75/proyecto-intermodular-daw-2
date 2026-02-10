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

        <div class="alert alert-success text-center">
            <h2>¡Bien hecho!</h2>
            <img src="<?= $params["imagen_pokemon_recompensa"] ?>" 
                alt="Imagen de <?= $params["nombre_pokemon_recompensa"] ?>">
            <p>¡Has ganado un <?= ucfirst($params["nombre_pokemon_recompensa"]) ?>!</p>
        </div>

        <div class="container text-center">
            <a class="btn btn-success" href="index.php?ctl=juegos">Volver</a>
        </div>

    <!-- ============================
         ESTADO: PERDIDO
    ============================= -->
    <?php elseif ($params["gameState"] === GAME_STATE_LOST): ?>

        <div class="alert alert-danger text-center">
            <h2>Buen intento</h2>
            <p>No te rindas, ¡hazte con todos!</p>
        </div>

        <div class="container text-center">
            <a class="btn btn-success" href="index.php?ctl=juegos">Volver</a>
        </div>

    <!-- ============================
         ESTADO: JUGANDO
    ============================= -->
    <?php elseif ($params["gameState"] === GAME_STATE_PLAYING): ?>

        
        <br>

        <form action="index.php?ctl=jugarAdivinanza" method="POST" class="text-center">
            <p> <?=  $params['pista1'] ?> </p><br>
        <p> <?=  $params['pista2'] ?> </p><br>
        <p> <?=  $params['pista3'] ?> </p><br>

            <input type="hidden" name="id" value="<?= $params["id"] ?>">
            <input type="hidden" name="id_pkmn" value="<?= $params["id_pkmn"] ?>">

            <div class="container">
            <textarea>
                
            </textarea>
        </div>

            <br>
            <button class="btn btn-success" type="submit" name="bEnviarTrivia">
                Confirmar
            </button>
        </form>

    <?php endif; ?>

<?php endif; ?>

<?php $contenido = ob_get_clean() ?>
<?php include 'layout.php' ?>
