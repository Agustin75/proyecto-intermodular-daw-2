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
$pokemons = $m->obtenerPokemonUsuario($this->session->getUserId(), true);
foreach ($pokemons as $pokemon) {
	echo "<tr><td><a>" . $pokemon['nombre'] . "</a></td></tr>";
}
?>
			</table>

<table>
	<tr> Capturados </tr>
<?php
//NOTE: LINKS NOT ADDED YET
$pokemons = $m->obtenerPokemonUsuario($this->session->getUserId(), false);
foreach ($pokemons as $pokemon) {
	$m = new PokeAPI;

	$yarp = $m->getPokemonById($pokemon['id_pokemon']);
	$yerp = $m->getPokemonNormalSprite($pokemon['id_pokemon']);
	echo "<tr><td><a>" . $yarp['name'] . "</a><input type='hidden' value=" . $pokemon['id_pokemon'] . "></input> <input type=checkbox></td></tr>";
	echo "<td><img src=" . $yerp . "></td>";
	

}
?>
</table>

<?php $contenido = ob_get_clean() ?>
<?php $script = "fav.js"; ?>
<?php include 'layout.php' ?>