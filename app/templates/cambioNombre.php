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
	<?php if(isset($params['nombre_old'])) :?>
				<p><?php echo  $params['nombre_old'] ?></p>
			<?php endif; ?>
	<div class="container text-center p-4">
		<form ACTION="index.php?ctl=cambiarNombre" METHOD="post" NAME="formCambiarNombre">
			<h5><b>Cambiar Nombre</b></h5>
			<p><input TYPE="text" NAME="new" PLACEHOLDER="Nuevo nombre"><br></p>
			<input TYPE="submit" NAME="bCambiarNombre" VALUE="Aceptar"><br>
		</form>	
	</div>
	
	


<?php $contenido = ob_get_clean() ?>

<?php include 'layout.php' ?>