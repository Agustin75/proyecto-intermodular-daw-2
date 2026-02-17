<?php ob_start() ?>

<h1 class="text-center">
    <?php if ($params["modo"] === MODE_CREATE): ?>
        Crear Clasificar
    <?php else: ?>
        Editar Clasificar
    <?php endif; ?>
</h1>
<h4 class="text-center">
    <?php if ($params["modo"] === MODE_CREATE): ?>
        Crea un nuevo juego de Clasificar
    <?php else: ?>
        Edita un juego de Clasificar
    <?php endif; ?>
</h4>

<?php if (!empty($params["mensaje"])): ?>
    <div class="alert alert-danger text-center">
        <?= $params['mensaje'] ?>
    </div>
<?php endif; ?>
<br>

<!-- We use d-flex to align the form container to the center of the screen -->
<div class="d-flex justify-content-center">
    <div class="crear-clasificar-container">
        <form ACTION="index.php?ctl=<?= $params["modo"] === MODE_CREATE ? "crearClasificar" : "guardarClasificar" ?>" METHOD="post">
            <?php if ($params["modo"] === MODE_EDIT): ?>
                <input type="hidden" id="idClasificar" name="idClasificar" value="<?= htmlspecialchars($params['idClasificar']) ?>">
            <?php endif; ?>
            <div class="d-flex justify-content-between">
                <label for="idPokemon">Pokémon de recompensa: </label>
                <input type="text" id="idPokemon" name="idPokemon" list="pokemonList" size="8" value="<?= htmlspecialchars($params['idPokemon']) ?>">
            </div>
            <div class="d-flex justify-content-between">
                <label for="selectTipoClasificar">Clasificar por: </label>
                <select name="idTipo" id="selectTipoClasificar">
                    <option value="-1">-- Tipo de juego --</option>
                    <?php foreach ($params["tiposClasificar"] as $tipo): ?>
                        <option value="<?= $tipo["id"] ?>" <?= $tipo["id"] === $params["idTipo"] ? 'selected="selected"' : "" ?>><?= $tipo["tipo_clasificar"] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="d-flex justify-content-between">
                <label for="numPokemon">Número de Pokémon a mostrar: </label>
                <input type="number" id="numPokemon" name="numPokemon" size="2" value="<?= htmlspecialchars($params['numPokemon']) ?>">
            </div>
            <div class="d-flex justify-content-between">
                <label for="numOpciones">Número de opciones: </label>
                <input type="number" id="numOpciones" name="numOpciones" size="2" value="<?= htmlspecialchars($params['numOpciones']) ?>">
            </div>
            <div class="d-flex justify-content-between">
                <label for="numRequerido">Respuestar correctas necesarias: </label>
                <input type="number" id="numRequerido" name="numRequerido" size="2" value="<?= htmlspecialchars($params['numRequerido']) ?>">
            </div>
            <div class="text-center pt-3">
                <?php if ($params["modo"] === MODE_CREATE): ?>
                    <input class="btn btn-primary" TYPE="submit" name="bCrearClasificar" VALUE="Crear"><br>
                <?php else: ?>
                    <input class="btn btn-primary" TYPE="submit" name="bGuardarClasificar" VALUE="Guardar">
                    <a href="index.php?ctl=eliminarClasificar&idClasificar=<?= htmlspecialchars($params['idClasificar']) ?>" class="btn btn-danger">Eliminar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<br>

<datalist id="pokemonList">
    <?php foreach ($params['pokemon_list'] as $pokemon): ?>
        <!-- TODO: Change this funcion to use the PokemonCapitalize function isntead of ucfirst? -->
        <!-- We add the &#8291; character to be able to listen to on click events of the datalist -->
        <option data-id="<?= $pokemon["id"] ?>" value="<?= $pokemon["id"] . " - " . ucfirst($pokemon["name"]) . "&#8291;" ?>"></option>
    <?php endforeach; ?>
</datalist>

<?php $contenido = ob_get_clean() ?>

<?php $script = "vistaClasificar.js" ?>

<?php include 'layout.php' ?>