<?php ob_start() ?>

<h1 class="text-center">Clasifica los Pokémon</h1>
<?php if ($params["gameFound"] === false): ?>
    <div class="alert alert-warning text-center">No hay juegos de Clasificar disponibles</div>
    <div class="container text-center">
        <a class="btn btn-success" href="index.php?ctl=juegos">Volver</a>
    </div>
    <!-- We check if the Player is currently playing a game. If they won, we don't show the following headers -->
<?php elseif ($params["gameState"] === GAME_STATE_PLAYING): ?>
    <?php if ($params["idTipo"] === CLASIFICAR_TIPO): ?>
        <?php if (count($params["game"]) === 1): ?>
            <h4 class="text-center">¿Sabes cuál de los tipos mostrados pertenece a este Pokémon?</h4>
        <?php else: ?>
            <h4 class="text-center">¿Sabes cuál de los tipos mostrados pertenece a cada Pokémon?</h4>
        <?php endif; ?>
    <?php else: ?>
        <?php if (count($params["game"]) === 1): ?>
            <h4 class="text-center">¿Sabes a qué generación pertenece este Pokémon?</h4>
        <?php else: ?>
            <h4 class="text-center">¿Sabes a qué generación pertenece cada Pokémon?</h4>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>

<?php if (!empty($params["mensaje"])): ?>
    <div class="alert alert-danger text-center">
        <?= $params['mensaje'] ?>
    </div>
<?php endif; ?>
<br>

<?php if ($params["gameState"] === GAME_STATE_WON): ?>
    <div class="alert alert-success text-center">
        <h2>¡Bien hecho!</h2>
        <img src="<?= $params["imagen_pokemon_recompensa"] ?>" alt="Imagen de <?= $params["nombre_pokemon_recompensa"] ?>">
        <p>¡Capturaste un <?= $params["nombre_pokemon_recompensa"] ?>!</p>
    </div>
    <div class="container text-center">
        <a class="btn btn-success" href="index.php?ctl=juegos">Volver</a>
    </div>
<?php elseif ($params["gameState"] === GAME_STATE_LOST): ?>
    <div class="alert alert-danger text-center">
        <h2>Buen intento</h2>
        <img src="" alt="Imagen de derrota">
        <p>No te rindas, ¡hazte con todos!</p>
    </div>
    <div class="container text-center">
        <a class="btn btn-success" href="index.php?ctl=juegos">Volver</a>
    </div>
<?php endif; ?>
<?php if (isset($params["game"])): ?>
    <form class="game" action="index.php?ctl=jugarClasificar" method="POST" name="formJuegoClasificar">
        <div class="row g-2 justify-content-center">
            <input type="hidden" name="gameId" value="<?= $params["gameId"] ?>">
            <?php foreach ($params["game"] as $index => $question): ?>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <!-- TODO: Add a specific class for Pokemon cards to design it better, since bootstrap is limited? -->
                    <div class="border border-success alert alert-success p-0 text-center">
                        <img src="<?= $question["pokemon_image"] ?>" alt="Imagen de <?= $question["pokemon_name"] ?>"><br>
                        <input type="hidden" name="pokemon_ids[]" value="<?= $question["pokemon_id"] ?>">
                        <input type="hidden" name="pokemon_names[]" value="<?= $question["pokemon_name"] ?>">
                        <input type="hidden" name="pokemon_images[]" value="<?= $question["pokemon_image"] ?>">
                        <!-- We send the options for each Pokemon in case the player fails to fill them all up -->
                        <div class="row justify-content-center m-0">
                            <?php foreach ($question["options"] as $number => $value): ?>
                                <input type="hidden" name="options[<?= $index ?>][]" value="<?= $value ?>">
                                <!-- name=question0 id=option-0-0 value=fire -->
                                <label class="col-6 border border-success text-center py-2 py-md-0">
                                    <input type="radio" name="question<?= $index ?>" value="<?= $value ?>" <?php if (isset($params["question" . $index]) && $params["question" . $index] == $value): ?>checked<?php endif; ?>> <?= ucfirst($value) ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <input class="btn btn-success" TYPE="submit" name="bEnviar" VALUE="Enviar"><br>
        </div>
    </form>
<?php endif; ?>

<br>

<?php $contenido = ob_get_clean() ?>

<?php include 'layout.php' ?>