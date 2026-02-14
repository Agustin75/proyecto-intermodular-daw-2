<?php ob_start() ?>


	<div class="container text-center p-4">
		<div class="col-md-12" id="cabecera">
			<h1>DEV TOOLS</h1>
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
<table>
	<tr>
<?php 
$m = new Usuario;
$all = $m->listarUsuarios();

if($all != false){

foreach($all as $i){
	$id = $i['id'];
	if($i['activo'] == 1){
		$class = "activo";
		$checked = "checked";
	}else{
		$class = "inactivo";
		$checked = "";
	}
echo "<td class=" . $class . "> Nombre: " . $i['nombre']  ;
echo "<td>Email: " . $i['email'] . "</td>  " . "</td> <td><input type='hidden' value=" . $id . "></input><input type=checkbox" . $checked ."></input></td>";
}
}

?>
	</tr>
</table>
	</div>

	


<?php $contenido = ob_get_clean() ?>
<?php $script = "activar2.js"; ?>
<?php include 'layout.php' ?>