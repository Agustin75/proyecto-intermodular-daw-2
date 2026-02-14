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
	<thead>
		<tr>
			<th>Nombre</th>
			<th>Email</th>
			<th>Estado</th>
		</tr>
	</thead>
	<tbody>
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
		echo "<tr class='" . $class . "'>";
		echo "<td>" . $i['nombre'] . "</td>";
		echo "<td>" . $i['email'] . "</td>";
		echo "<td><input type='hidden' value='" . $id . "'><input type='checkbox' " . $checked . "></td>";
		echo "</tr>";
	}
}

?>
	</tbody>
</table>
	</div>

	


<?php $contenido = ob_get_clean() ?>
<?php $script = "activar2.js"; ?>
<?php include 'layout.php' ?>