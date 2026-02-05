<?php ob_start() ?>
	
	<div class="container text-center p-4">
		<div class="col-md-12" id="cabecera">
			<h1 class="h1Inicio">POKEHUNT</h1>
		</div>
	</div>

	<div class="container text-center py-2">
		<div class="col-md-12">
			<?php if(isset($params['mensaje'])) :?>
				<b><span style="color: rgba(200, 119, 119, 1);"><?php echo $params['mensaje'] ?></span></b>
			<?php endif; ?>
		</div>
	</div>
	<img src="imagen">  
    <div>

<?php
    echo "<h1> Pokemons de " . $this->session->getUserName() . "</h1>";
?>
	</div>
<table>
	<tr> Favoritos </tr>
	
<?php 
$m = new PokemonUsuario();
$pokemons = $m->obtenerPokemonsUsuario($this->session->getUserId(), true);
foreach ($pokemons as $pokemon) {
	echo "<tr><td><a>" . $pokemon['nombre'] . "</a></td></tr>";
}
?>
			</table>

<table>
	<tr> Capturados </tr>
<?php
//NOTE: LINKS NOT ADDED YET
$pokemons = $m->obtenerPokemonsUsuario($this->session->getUserId(), false);
foreach ($pokemons as $pokemon) {
	echo "<tr><td><a>" . $pokemon['nombre'] . "</a></td></tr>";
}
?>
</table>

<?php $contenido = ob_get_clean() ?>

<?php include 'layout.php' ?>