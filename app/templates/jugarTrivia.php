<?php ob_start() ?>

<h1 class="text-center">Trivia Pokémon</h1>

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

        <h4 class="text-center">
            <?= htmlspecialchars($params["trivia"]["enunciado"]["pregunta"]) ?>
        </h4>

        <br>

        <form class="game" action="index.php?ctl=jugarTrivia" method="POST" class="text-center">

            <input type="hidden" name="idTrivia" value="<?= $params["idTrivia"] ?>">
            <input type="hidden" name="idPokemon" value="<?= $params["idPokemon"] ?>">

            <div class="container">
                <?php if (isset($params["trivia"]["opciones"]) && !empty($params["trivia"]["opciones"])): ?>
                    <?php foreach ($params["trivia"]["opciones"] as $i => $op): ?>
                        <div class="form-check text-start">
                            <input class="form-check-input" 
                                type="checkbox" 
                                name="opcion[]" 
                                value="<?= $i ?>" 
                                id="op<?= $i ?>">

                            <label class="form-check-label" for="op<?= $i ?>">
                                <?= htmlspecialchars($op->texto) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
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
