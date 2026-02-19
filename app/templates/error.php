<?php ob_start(); ?>

<h1>Ha habido un error</h1>
<?php if (isset($params['mensaje'])): ?>
    <strong><p style="color: rgba(200, 119, 119, 1);"><?= $params['mensaje'] ?></p></strong>
<?php endif; ?>

<?php $contenido = ob_get_clean() ?>

<?php include 'layout.php' ?>