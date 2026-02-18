<?php ob_start() ?><br>
<h1 class="text-center"><?php echo $params['page_title'] ?></h1><br><br>

<div class="text-center">
    <img width="65%" src="images/inicio.png" alt="Imagen de profesor Juniper">
</div>
<br><br>
<?php foreach ($params['paragraphs'] as $paragraph): ?>
    <p class="text-center"><?= $paragraph ?></p>
<?php endforeach; ?>
<br><br>
<?php $contenido = ob_get_clean() ?>

<?php include 'layout.php' ?>