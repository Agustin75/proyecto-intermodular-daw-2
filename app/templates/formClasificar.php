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

<div class="container-fluid text-left">
    <div class="container">
        <form ACTION="index.php?ctl=<?= $params["modo"] === MODE_CREATE ? "crearClasificar" : "editarClasificar" ?>" METHOD="post">
            <label for="idPokemon">Pokémon de recompensa: </label>
            <input type="text" id="idPokemon" name="idPokemon" list="pokemonList" value="<?= htmlspecialchars($params['idPokemon']) ?>"><br>
            <label for="selectTipoClasificar">Clasificar por: </label>
            <select name="idTipo" id="selectTipoClasificar">
                <option value="-1">-- Selecciona un tipo de juego --</option>
                <?php foreach ($params["tiposClasificar"] as $tipo): ?>
                    <option value="<?= $tipo["id"] ?>"<?= $tipo["id"] === $params["idTipo"] ? 'selected="selected"' : "" ?>><?= $tipo["tipo_clasificar"] ?></option>
                <?php endforeach; ?>
            </select><br>
            <label for="numPokemon">Número de Pokémon a mostrar: </label>
            <input type="number" id="numPokemon" name="numPokemon" value="<?= htmlspecialchars($params['numPokemon']) ?>"><br>
            <label for="numOpciones">Número de opciones: </label>
            <input type="number" id="numOpciones" name="numOpciones" value="<?= htmlspecialchars($params['numOpciones']) ?>"><br>
            <label for="numRequerido">Número de clasificaciones correctas: </label>
            <input type="number" id="numRequerido" name="numRequerido" value="<?= htmlspecialchars($params['numRequerido']) ?>"><br>
            <?php if ($params["modo"] === MODE_CREATE): ?>
                <input TYPE="submit" name="bCrearClasificar" VALUE="Crear"><br>
            <?php else: ?>
                <input TYPE="submit" name="bEditarClasificar" VALUE="Editar"><br>
            <?php endif; ?>
        </form>
    </div>
</div>

<br>
<?php if ($params["modo"] === MODE_EDIT): ?>
    <button id="bEliminarClasificar">Eliminar</button>
<?php endif; ?>

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