<?php ob_start(); ?>

<h1 class="text-center">#<?= $params["id"] ?> - <?= $params["nombre"] ?></h1>
<div class="text-center mt-4">
<div class="container mt-4">

    <div class="row justify-content-center">

        <div class="col-md-4 text-center">
            <h4>Normal</h4>
            <img width="60%" src="<?= $params["imagenes"]["normal"] ?>">
        </div>

        <div class="col-md-4 text-center">
            <h4>Shiny</h4>
            <img width="60%" src="<?= $params["imagenes"]["shiny"] ?>">
        </div>

    </div>

    <hr>

    <h3>Tipos</h3><br>
    <p>
        <?php foreach ($params["tipos"] as $t): ?>
            <span class="badge bg-primary"><?= ucfirst($t) ?></span>
        <?php endforeach; ?>
    </p>
<hr>
    <h3>Generación</h3>
    <p><?= $params["generacion"] ?></p>
<hr>
    <h3>Descripción</h3><br>
    <p><?= $params["descripcion"] ?></p><br>
<hr>
    <h3>Grito</h3><br>
    <?php if (!empty($params["grito"])): ?>
        <audio controls>
            <source src="<?= $params["grito"] ?>" type="audio/ogg">
        </audio><br><br>
    <?php else: ?>
        <p>No disponible.</p>
    <?php endif; ?>
<hr>
    <div class="text-center mt-4">
        <a href="index.php?ctl=wiki" class="btn btn-primary">Volver</a><br><br>
    </div>
    </div>
</div>

<?php $contenido = ob_get_clean(); ?>
<?php include 'layout.php'; ?>
