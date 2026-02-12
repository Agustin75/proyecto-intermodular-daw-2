<?php ob_start(); ?>

<h1>Error encontrado</h1>
<?php if (isset($params['mensaje'])): ?>
    <b><span style="color: rgba(200, 119, 119, 1);">
    <?php
        echo $params['mensaje'];
        echo "</span></b>";
    ?>
<?php endif; ?>

<?php $contenido = ob_get_clean() ?>

<?php include 'layout.php' ?>