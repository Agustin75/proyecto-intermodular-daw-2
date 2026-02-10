<?php ob_start() ?>


<h1 class="text-center">
    <?= ($params['modo'] === MODE_EDIT) ? 'Editar Adivinanza' : 'Crear Adivinanza' ?>
</h1>

<br>

<?php if (!empty($params['mensaje'])): ?>
    <div class="alert alert-danger text-center">
        <?= $params['mensaje'] ?>
    </div>
<?php endif; ?>
<?php if ($params['modo'] === MODE_EDIT) ?>
<form action="index.php?ctl=<?= ($params['modo'] === MODE_EDIT) ? 'editarAdivinanza' : 'crearAdivinanza' ?>" method="POST">
<input type=hidden name="id" value="<?= $params['id']?>"> 
    <!-- ============================
         Pista 1
    ============================= -->
    <label for="pista1">Pista 1:</label><br>
    <textarea id="pista1" name="pista1" rows="3" cols="60"><?= htmlspecialchars($params['pista1']) ?></textarea>
    <br><br>

   <!-- ============================
         Pista 1
    ============================= -->
    <label for="pista2">Pista 2:</label><br>
    <textarea id="pista2" name="pista2" rows="3" cols="60"><?= htmlspecialchars($params['pista2']) ?></textarea>
    <br><br>

       <!-- ============================
         Pista 3
    ============================= -->
    <label for="pista3">Pista 3:</label><br>
    <textarea id="pista3" name="pista3" rows="3" cols="60"><?= htmlspecialchars($params['pista3']) ?></textarea>
    <br><br>

    <!-- =======================
    TIPO
    =============-->
    <label for="tipo">Tipo por: </label>
            <select name="tipo" id="selectTipoAdivinar">
                <option value="-1">-- Selecciona un tipo de juego --</option>
                <?php foreach ($params["tiposAdivinar"] as $tipo): ?>
                    <option value="<?= $tipo["id"] ?>"> <?= $tipo["tipo_adivinanza"] ?></option>
                <?php endforeach; ?>
            </select><br>
    <!-- ============================
         POKÉMON RECOMPENSA
    ============================= -->
 <!-- <label for="typeSelect">Tipo: </label>
<select name="typeSelect" id="typeSelect">
    <option value="">-- Selecciona un tipo --</option>
    <?php foreach ($params['type_list'] as $type): ?>
        <option value="<?= $type["id"] ?>"><?= ucfirst($type["name"]) ?></option>
    <?php endforeach; ?>
</select>

<label for="generationSelect">Generación: </label>
<select name="generationSelect" id="generationSelect">
    <option value="">-- Selecciona una generación --</option>
    <?php for ($i = 1; $i <= $params['num_generations']; $i++): ?>
        <option value="<?= $i ?>"><?= $i ?></option>
    <?php endfor; ?>
</select>-->

<br><br>
<label for="pokemonNameInput">Pokémon: </label>

<input type="text" id="pokemonNameInput" name="id_pokemon" list="pokemonList">
<datalist id="pokemonList"> 
    <?php foreach ($params['pokemon_list'] as $pokemon): ?>
        <!-- TODO: Change this funcion to use the PokemonCapitalize function isntead of ucfirst? -->
        <!-- We add the &#8291; character to be able to listen to on click events of the datalist -->
        <option data-id="<?= $pokemon["id"] ?>" value="<?= $pokemon["id"] . " - " . ucfirst($pokemon["name"]) . "&#8291;" ?>"></option>
    <?php endforeach; ?>
</datalist><br><br>    
    <!-- ============================
         BOTONES
    ============================= -->
    <button type="submit" name="<?= ($params['modo'] === MODE_EDIT) ? 'bEditarAdivinanza' : 'bCrearAdivinanza' ?>">
        <?= ($params['modo'] === MODE_EDIT) ? 'Actualizar Adivinanza' : 'Guardar Adivinanza' ?>
    </button>

    <?php if ($params['modo'] === MODE_EDIT): ?>
        <button type="submit" name="bEliminarAdivinanza" formaction="index.php?ctl=eliminarAdivinanza">
            Eliminar Trivia
        </button>
    <?php endif; ?>

</form>





<?php $contenido = ob_get_clean() ?>

<?php $script = ["wikiNoRedirect.js", "generarOpciones.js"]; ?>

<?php include 'layout.php' ?>