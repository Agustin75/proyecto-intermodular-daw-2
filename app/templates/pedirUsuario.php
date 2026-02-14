<?php ob_start() ?>

<div class="container text-center p-4">
	<div class="col-md-12" id="cabecera">
		<h1>CAMBIO DE CONTRASEÑA</h1>
	</div>
</div>

<div class="container text-center py-2">
	<div class="col-md-12">
		<?php if (isset($params['mensaje'])) : ?>
			<b><span style="color: rgba(200, 119, 119, 1);"><?php echo $params['mensaje'] ?></span></b>
		<?php elseif (isset($params["info"])): ?>
			<!-- We tell the user the email has been sent -->
			<div class="alert alert-warning"><?php echo $params['info'] ?></div>
		<?php endif; ?>
	</div>
</div>
<div class="container text-center p-4">
	<form ACTION="index.php?ctl=pedirUsuario" METHOD="post" NAME="formPedirUsuario">
		<h5><b>Ingresa tu usuario</b></h5>
		<p>Te enviaremos un correo para que puedas cambiar tu contraseña</p>
		<p><input TYPE="text" NAME="nombreUsuario" PLACEHOLDER="Usuario"><br></p>
		<input TYPE="submit" NAME="bEnviar" VALUE="Enviar"><br>
	</form>
</div>

<?php $contenido = ob_get_clean() ?>

<?php include 'layout.php' ?>