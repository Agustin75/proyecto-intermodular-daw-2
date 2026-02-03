<?php ob_start() ?>


	<div class="container text-center p-4">
		<div class="col-md-12" id="cabecera">
			<h1 class="h1Inicio">Dev Tools</h1>
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

<?php 
$m = new Usuario;
$all = $m->listarUsuarios();

if($all != false){

foreach($all as $i){
	$id = $i['id'];
echo "<p> Nombre: " . $i['nombre']  ;
echo "<p>Email: " . $i['email'] . "</p>  " . "</p> <p class=id>" . $id ."</p><input type=checkbox></input>";
}
}

?>
	</div>

	


<?php $contenido = ob_get_clean() ?>
<?php $script = "activar.js"; ?>
<?php include 'layout.php' ?>