<?php ob_start() ?>

<h1 class="text-center">Lista de Pokemon</h1>
<h3 class="text-center">Selecciona un Pokemon para ver su información</h3>
<!-- <img src="" alt="Imagen de wiki"> -->

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

<br>

<input type="text" id="pokemonNameInput" name="pokemonNameInput" list="pokemonList">
<datalist id="pokemonList">
    <?php foreach ($params['pokemon_list'] as $pokemon): ?>
        <!-- TODO: Change this funcion to use the PokemonCapitalize function isntead of ucfirst? -->
        <!-- We add the &#8291; character to be able to listen to on click events of the datalist -->
        <option data-id="<?= $pokemon["id"] ?>" value="<?= $pokemon["id"] . " - " . ucfirst($pokemon["name"]) . "&#8291;" ?>"></option>
    <?php endforeach; ?>
</datalist>

<?php $contenido = ob_get_clean() ?>

<?php $script = "wiki.js"; ?>

<?php include 'layout.php' ?>