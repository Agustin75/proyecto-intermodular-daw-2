<?php ob_start(); ?>

<h1 class="text-center">#<?= $params["id"] ?> - <?= $params["nombre"] ?></h1>

<div class="container mt-4">

    <div class="row justify-content-center">

        <div class="col-md-4 text-center">
            <h4>Normal</h4>
            <img src="<?= $params["imagenes"]["normal"] ?>" class="img-fluid">
        </div>

        <div class="col-md-4 text-center">
            <h4>Shiny</h4>
            <img src="<?= $params["imagenes"]["shiny"] ?>" class="img-fluid">
        </div>

    </div>

    <hr>

    <h3>Tipos</h3>
    <p>
        <?php foreach ($params["tipos"] as $t): ?>
            <span class="badge bg-primary"><?= ucfirst($t) ?></span>
        <?php endforeach; ?>
    </p>

    <h3>Generación</h3>
    <p><?= $params["generacion"] ?></p>

    <h3>Descripción</h3>
    <p><?= $params["descripcion"] ?></p>

    <h3>Grito</h3>
    <?php if (!empty($params["grito"])): ?>
        <audio controls>
            <source src="<?= $params["grito"] ?>" type="audio/ogg">
        </audio>
    <?php else: ?>
        <p>No disponible.</p>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="index.php?ctl=wiki" class="btn btn-primary">Volver</a>
    </div>

</div>

<?php $contenido = ob_get_clean(); ?>
<?php include 'layout.php'; ?>
