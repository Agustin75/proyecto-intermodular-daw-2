<?php ob_start() ?>

<h1 class="text-center">Adivina el Pokémon</h1>
<?php if ($params["gameFound"] === false): ?>
    <div class="alert alert-warning text-center">No hay juegos de Adivinar disponibles</div>
    <div class="container text-center">
        <a class="btn btn-success" href="index.php?ctl=juegos">Volver</a>
    </div>
    <!-- We check if the Player is currently playing a game. If they won, we don't show the following headers -->
<?php elseif ($params["gameState"] === GAME_STATE_PLAYING): ?>
    <?php if ($params["idTipo"] === CLASIFICAR_TIPO): ?>
      
      
        <h2> Pista 1: </h2>
      <p><?= $params["pista1"] ?></p>
      
      
        <?php endif; ?>
    <?php endif; ?>

