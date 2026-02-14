<?php ob_start() ?>
	
	<div class="container text-center p-4">
		<div class="col-md-12" id="cabecera">
			<h1>CAMBIAR IMAGEN</h1>
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
			<select NAME="newImage" PLACEHOLDER="Nueva imagen"><br>
				<?php for($i = 0; $i < NUM_AVATARS; $i++): ?>
					<?php $imageName = AVATAR_NAMING_CONVENTION . ($i < 10 ? ("0" . $i) : $i); ?>
                	<option value="<?= $imageName ?>" <?= $params["currImage"] == $imageName ? "selected" : "" ?>>Imagen <?= $i + 1 ?></option>
				<?php endfor; ?>
            </select>
			<input TYPE="submit" NAME="bCambiarImagen" VALUE="Aceptar"><br>
		</form>	
	</div>
	
	


<?php $contenido = ob_get_clean() ?>

<?php include 'layout.php' ?>