<?php ob_start(); ?>

<div class="container text-center alert-danger p-3">
    <h1 class="text-danger">ERROR</h1>
    <img src="images/error.png" alt="Imagen de error">
    <?php if (isset($params['mensaje'])): ?>
        <p class="text-danger"><?= $params['mensaje'] ?></p>
    <?php endif; ?>
</div>

<?php $contenido = ob_get_clean() ?>

<?php include 'layout.php' ?>