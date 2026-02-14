<?php ob_start() ?>

<div class="container text-center p-4">
    <div class="col-md-12" id="cabecera">
        <h1>PERFIL DE <?= strtoupper($params["userName"]) ?></h1>
    </div>
</div>

<div class="container text-center py-2">
    <div class="col-md-12">
        <?php if (isset($params['mensaje'])) : ?>
            <b><span style="color: rgba(200, 119, 119, 1);"><?php echo $params['mensaje'] ?></span></b>
        <?php endif; ?>
    </div>
</div>
<div class="text-center">
    <img src="images/avatars/<?= $params["userImage"] ?>.png">
</div>
<h3> Favoritos </h3>
<div class="row">
    <?php if (count($params["favorites"]) === 0): ?>
        <p>No se ha elegido ningún Pokémon favorito.</p>
    <?php endif; ?>
    <?php foreach ($params["favorites"] as $pokemon): ?>
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <a href="index.php?ctl=verPokemon&pokemonId=<?= $pokemon["id"] ?>"><img src="<?= $pokemon["image"] ?>" alt="Imagen de <?= $pokemon["name"] ?>"></a>
            <br>
            <a href="index.php?ctl=verPokemon&pokemonId=<?= $pokemon["id"] ?>"><?= $pokemon["name"] ?></a>
        </div>
    <?php endforeach; ?>
</div>

<h3> Capturados </h3>
<div class="row">
    <?php if (count($params["allPokemon"]) === 0): ?>
        <p>No ha capturado ningún Pokémon.</p>
    <?php endif; ?>
    <?php foreach ($params["allPokemon"] as $pokemon): ?>
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <a href="index.php?ctl=verPokemon&pokemonId=<?= $pokemon["id"] ?>"><img src="<?= $pokemon["image"] ?>" alt="Imagen de <?= $pokemon["name"] ?>"></a>
            <br>
            <a href="index.php?ctl=verPokemon&pokemonId=<?= $pokemon["id"] ?>"><?= $pokemon["name"] ?></a>
            <input type="hidden" value="<?= $pokemon["id"] ?>">
            <?php if ($params["editable"]): ?>
                <input type="checkbox" <?= $pokemon["favorited"] ? "checked" : "" ?>>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<?php $contenido = ob_get_clean() ?>
<?php $script = "fav.js"; ?>
<?php include 'layout.php' ?>