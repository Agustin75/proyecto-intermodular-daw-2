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
    <div>

<?php echo "<p> ID: " . $this->session->getUserId() . "</p>";
    echo "<p> Nombre: " . $this->session->getUserName() . "</p>";
?>




<?php $contenido = ob_get_clean() ?>

<?php include 'layout.php' ?>