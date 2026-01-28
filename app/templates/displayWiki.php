<?php ob_start() ?>

<!-- <h1 class="text-center"><?php echo $params['page_title'] ?></h1> -->
<h1 class="text-center">Lista de Pokemon</h1>
<h3 class="text-center">Selecciona un Pokemon para ver su información</h3>
<!-- <div class="text-center">
    <img src="<?= $params["image"] ?>" alt="Imagen de wiki">
</div> -->

<form action="index.php?ctl=wiki" method="post" name="formFilterByType">
    <label for="typeSelect">Tipo: </label>
    <select name="typeSelect" id="typeSelect">
        <option value="">-- Selecciona un tipo --</option>
        <?php foreach ($params['type_list'] as $type): ?>
            <option value="<?= $type["id"] ?>"><?= ucfirst($type["name"]) ?></option>
        <?php endforeach; ?>
    </select>
</form>

<select name="pokemonSelect" id="pokemonSelect">
    <option value="">-- Selecciona un Pokemon --</option>
<?php foreach ($params['pokemon_list'] as $pokemon): ?>
    <option value="<?= $pokemon["id"] ?>"><?= $pokemon["id"] . " - " . ucfirst($pokemon["name"]) ?></option>
<?php endforeach; ?>
</select>

<?php $contenido = ob_get_clean() ?>

<?php $script = "wiki.js"; ?>

<?php include 'layout.php' ?>