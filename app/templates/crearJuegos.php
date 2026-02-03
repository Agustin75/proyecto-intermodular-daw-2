<?php ob_start() ?>
<div>
<a href="index.php?ctl=crearTrivia">Crear juego de Trivia</a><br>
<a href="index.php?ctl=crearAdivinanza">Crear juego de Adivinanza</a><br>
<a href="index.php?ctl=crearClasificar">Crear juego de Clasificar</a>
</div>

<?php $contenido = ob_get_clean() ?>

<?php include 'layout.php' ?>