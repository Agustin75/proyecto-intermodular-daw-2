<?php ob_start() ?>

<div class="container text-center p-4">
	<div class="col-md-12" id="cabecera">
		<h1>OPCIONES</h1>
	</div>
</div>

<div class="container text-center py-2">
	<div class="col-md-12">
		<?php if (isset($params['mensaje'])) : ?>
			<b><span style="color: rgba(200, 119, 119, 1);"><?php echo $params['mensaje'] ?></span></b>
		<?php endif; ?>
	</div>
</div>
<img src="images/avatars/<?= $params["userImage"] ?>.png" alt="<?= $params["userImage"] ?>">
<div>
	<p>Nombre: <?= $params["userName"] ?></p>
</div>

<a href="index.php?ctl=cambiarNombre">Cambiar Nombre</a>
<br>
<a href="index.php?ctl=cambiarImagen">Cambiar Imagen</a>

<?php $contenido = ob_get_clean() ?>

<?php include 'layout.php' ?>