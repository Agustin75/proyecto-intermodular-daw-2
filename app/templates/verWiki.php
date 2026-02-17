<?php ob_start() ?>

<h1 class="text-center">Lista de Pokemon</h1>
<h3 class="text-center">Selecciona un Pokemon para ver su información</h3>

<div class="container">
    <div class="row text-center">
        <div class="col-0 col-md-2"></div>
        <div class="col-12 col-md-8">
            <img class="img-fluid w-50" src="images/Pokedex.png" alt="Imagen de la Pokedex de Blanco y Negro con los starters de Kanto">
        </div>
        <div class="col-0 col-md-2"></div>
    </div>
</div>
    
<div class="container pt-3">
    <h2 class="text-center">Filters</h2>
    <div class="row text-center">
        <div class="col-12 col-sm-6">
            <h3>Tipo</h3>
            <select name="typeSelect" id="typeSelect">
                <option value="">-- Selecciona un tipo --</option>
                <?php foreach ($params['type_list'] as $type): ?>
                    <option value="<?= $type["id"] ?>"><?= ucfirst($type["name"]) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-12 col-sm-6">
                <h3>Generación</h3>
                <select name="generationSelect" id="generationSelect">
                    <option value="">-- Selecciona una generación --</option>
                    <?php for ($i = 1; $i <= $params['num_generations']; $i++): ?>
                        <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
        </div>

<div class="container text-center">
    <h3>Pokémon</h3>
    <input type="text" id="pokemonNameInput" name="pokemonNameInput" list="pokemonList" placeholder="Busca un Pokémon">
</div>

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