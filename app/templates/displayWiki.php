<?php ob_start() ?>

<!-- <h1 class="text-center"><?php echo $params['page_title'] ?></h1> -->

<!-- <div class="text-center">
    <img src="<?= $params["image"] ?>" alt="Imagen de wiki">
</div> -->

<!-- <?php foreach ($params['paragraphs'] as $paragraph): ?> -->
    <!-- <p class="text-center"><?= $paragraph ?></p> -->
<!-- <?php endforeach; ?> -->

<?php $contenido = ob_get_clean() ?>

<?php include 'layout.php' ?>