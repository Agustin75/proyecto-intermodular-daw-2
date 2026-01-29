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
		<form ACTION="index.php?ctl=cambiarImagen" METHOD="post" NAME="formCambiarImagen">
			<h5><b>Cambiar Imagen</b></h5>
			<select NAME="imagen" PLACEHOLDER="Nuevo nombre"><br>
                <option value="imagen1">Imagen 1</option>
                <option value="default">Por Defecto</option>
                <option value="imagen2">Imagen 2</option>
            </select>
			<input TYPE="submit" NAME="bCambiarImagen" VALUE="Aceptar"><br>
		</form>	
	</div>
	
	


<?php $contenido = ob_get_clean() ?>

<?php include 'layout.php' ?>