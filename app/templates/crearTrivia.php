<?php ob_start() ?>


<h1 class="text-center">
    <?= ($params['modo'] === 'editar') ? 'Editar Trivia' : 'Crear Trivia' ?>
</h1>

<br>

<?php if (!empty($params['mensaje'])): ?>
    <div class="alert alert-danger text-center">
        <?= $params['mensaje'] ?>
    </div>
<?php endif; ?>

<form action="index.php?ctl=<?= ($params['modo'] === 'editar') ? 'editarTrivia&id=' . $params['id'] : 'crearTrivia' ?>" method="POST">


    <!-- ============================
         ENUNCIADO
    ============================= -->
    <label for="enunciado">Enunciado:</label><br>
    <textarea id="enunciado" name="enunciado" rows="3" cols="60"><?= htmlspecialchars($params['pregunta']) ?></textarea>
    <br><br>

    <!-- ============================
         TIEMPO
    ============================= -->
    <label for="tiempo">Tiempo (segundos):</label>
    <input type="number" id="tiempo" name="tiempo" min="1" value="<?= $params['tiempo'] ?>">
    <br><br>

    <!-- ============================
         NÚMERO DE OPCIONES
    ============================= -->
    <label for="numOpciones">Número de opciones:</label>
    <input type="number" id="numOpciones" name="numOpciones" min="2" max="10"
           value="<?= !empty($params['opciones']) ? count($params['opciones']) : 2 ?>">
    <button type="button" id="generarOpciones">Generar</button>
    <label for="info">(Marca el checkbox si esa opcion es correcta)</label>
    <br><br>

    <!-- ============================
         OPCIONES DINÁMICAS
    ============================= -->
    <div id="contenedorOpciones">

        <?php if (!empty($params['opciones'])): ?>
            <?php foreach ($params['opciones'] as $i => $op): ?>
                <div class="opcion-item">
                    <input type="checkbox" name="opcionCorrecta[]" value="<?= $i ?>" <?= ($op->correcta) ? 'checked' : '' ?>>
                    <input type="text" name="opcionTexto[]" value="<?= htmlspecialchars($op->texto) ?>" placeholder="Opción <?= $i + 1 ?>">
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>

    <br><br>
    <label for="info">Selecciona el Pokémon recompensa:</label>
    <br><br>

    <!-- ============================
         POKÉMON RECOMPENSA
    ============================= -->
  <label for="typeSelect">Tipo: </label>
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
</select>

<br><br>
<label for="pokemonNameInput">Pokémon: </label>

<input type="text" id="pokemonNameInput" name="pokemonNameInput" list="pokemonList">
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
    <button type="submit" name="<?= ($params['modo'] === 'editar') ? 'bEditarTrivia' : 'bCrearTrivia' ?>">
        <?= ($params['modo'] === 'editar') ? 'Actualizar Trivia' : 'Guardar Trivia' ?>
    </button>

    

</form>





<?php $contenido = ob_get_clean() ?>

<?php $script = ["wikiNoRedirect.js", "generarOpciones.js"]; ?>

<?php include 'layout.php' ?>