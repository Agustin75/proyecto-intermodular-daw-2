<?php ob_start() ?>

<div class="container text-center p-4">
	<div class="col-md-12" id="cabecera">
		<h1 class="h1Inicio">CAMBIAR CONTRASEÑA</h1>
	</div>
</div>

<div class="container text-center py-2">
	<div class="col-md-12">
		<?php if (isset($params['mensaje'])) : ?>
			<b><span style="color: rgba(200, 119, 119, 1);"><?php echo $params['mensaje'] ?></span></b>
		<?php endif; ?>
	</div>
</div>

<?php if (isset($params["success"])): ?>
	<div class="container text-center py-2">
		<div class="col-md-12">
			<b><span class="alert alert-success"><?php echo $params['success'] ?></span></b>
		</div>
	</div>
<?php else: ?>
	<div class="container text-center p-4">
		<form ACTION="index.php?ctl=cambiarPassword" METHOD="post" NAME="formCambiarPassword">
			<h5><b>Cambiar Contraseña</b></h5>
			<p><input TYPE="hidden" NAME="userId" value="<?= $params["userId"] ?>"><br></p>
			<p><input TYPE="password" NAME="newPassword" PLACEHOLDER="Nueva contraseña"><br></p>
			<input TYPE="submit" NAME="bCambiarPassword" VALUE="Cambiar"><br>
		</form>
	</div>
<?php endif; ?>
<?php $contenido = ob_get_clean() ?>

<?php include 'layout.php' ?>