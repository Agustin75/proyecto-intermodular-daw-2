<?php ob_start() ?>
<head> 
    <link rel=stylesheet href="../web/css/estilo.css" type="text/css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400..700&display=swap" rel="stylesheet">
</head>
<h1 class="text-center"><?php echo $params['page_title'] ?></h1>

<div class="text-center">
    <img src="<?= $params["image"] ?>" alt="Imagen de bienvenida">
</div>

<?php foreach ($params['paragraphs'] as $paragraph): ?>
    <p class="text-center"><?= $paragraph ?></p>
<?php endforeach; ?>

<?php $contenido = ob_get_clean() ?>

<?php include 'layout.php' ?>