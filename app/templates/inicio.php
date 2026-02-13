<?php ob_start() ?>
<h1 class="text-center"><?php echo $params['page_title'] ?></h1>

<?php foreach ($params['paragraphs'] as $paragraph): ?>
    <p class="text-center"><?= $paragraph ?></p>
<?php endforeach; ?>

<?php $contenido = ob_get_clean() ?>

<?php include 'layout.php' ?>