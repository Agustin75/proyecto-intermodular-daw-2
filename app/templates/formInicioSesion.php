<?php ob_start() ?>
	
	<div class="container text-center p-4">
		<div class="col-md-12" id="cabecera">
			<h1 class="h1Inicio">BIBLIOTECA VIRTUAL</h1>
		</div>
	</div>

	<div class="container text-center py-2">
		<div class="col-md-12">
			<?php if(isset($params['mensaje'])) :?>
				<b><span style="color: rgba(200, 119, 119, 1);"><?php echo $params['mensaje'] ?></span></b>
			<?php endif; ?>
		</div>
	</div>
	
	<div class="container text-center p-4">
		<form ACTION="index.php?ctl=iniciarSesion" METHOD="post" NAME="formIniciarSesion">
			<h5><b>Iniciar sesión</b></h5>
			<p><input TYPE="text" NAME="nombre" PLACEHOLDER="Nombre de usuario"><br></p>
			<p><input TYPE="password" NAME="contrasenya" PLACEHOLDER="Contraseña"><br></p>	
			<input TYPE="submit" NAME="bIniciarSesion" VALUE="Aceptar"><br>
		</form>	
	</div>
	
	


<?php $contenido = ob_get_clean() ?>

<?php include 'layout.php' ?>