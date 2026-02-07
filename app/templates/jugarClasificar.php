<?php ob_start() ?>

<h1 class="text-center">Clasifica los Pokémon</h1>
<?php if ($params["gameFound"] === false): ?>
    <div class="alert alert-warning text-center">No hay juegos de Clasificar disponibles</div>
<?php elseif ($params["idTipo"] === CLASIFICAR_TIPO): ?>
    <h4 class="text-center">¿Puedes decir cuál es el tipo de este Pokémon?</h4>
<?php else: ?>
    <h4 class="text-center">¿Puedes decir a qué generación pertenece este Pokémon?</h4>
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
<?php elseif ($params["gameState"] === GAME_STATE_LOST): ?>
    <div class="alert alert-danger text-center">
        <h2>Buen intento</h2>
        <img src="" alt="Imagen de derrota">
        <p>No te rindas, ¡hazte con todos!</p>
    </div>
<?php endif; ?>
<?php if (isset($params["game"])): ?>
    <div class="container-fluid text-left">
        <div class="container">
            <form action="index.php?ctl=jugarClasificar" method="POST" name="formJuegoClasificar">
                <!-- <?= var_dump($params) ?> -->
                <input type="hidden" name="gameId" value="<?= $params["gameId"] ?>">
                <?php foreach ($params["game"] as $index => $question): ?>
                    <div>
                        <img src="<?= $question["pokemon_image"] ?>" alt="Imagen de <?= $question["pokemon_name"] ?>"><br>
                        <input type="hidden" name="pokemon_ids[]" value="<?= $question["pokemon_id"] ?>">
                        <input type="hidden" name="pokemon_names[]" value="<?= $question["pokemon_name"] ?>">
                        <input type="hidden" name="pokemon_images[]" value="<?= $question["pokemon_image"] ?>">
                        <!-- We send the options for each Pokemon in case the player fails to fill them all up -->
                        <?php foreach ($question["options"] as $number => $value): ?>
                            <input type="hidden" name="options[<?= $index ?>][]" value="<?= $value ?>">
                            <!-- name=question0 id=option-0-0 value=fire -->
                            <input type="radio" name="question<?= $index ?>" id="option-<?= $index ?>-<?= $number ?>" value="<?= $value ?>" <?php if (isset($params["question" . $index]) && $params["question" . $index] == $value): ?>checked<?php endif; ?>>
                            <label for="option-<?= $index ?>-<?= $number ?>"><?= $value ?></label><br>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>

                <input class="btn btn-success" TYPE="submit" name="bEnviar" VALUE="Enviar"><br>
            </form>
        </div>
    </div>
<?php endif; ?>

<br>

<?php $contenido = ob_get_clean() ?>

<?php include 'layout.php' ?>