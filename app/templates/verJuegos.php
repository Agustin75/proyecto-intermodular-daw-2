<?php ob_start() ?>
<head> <link rel=stylesheet href="../web/css/estilo.css" type="text/css"></head>

<div class="container text-center">
    <h1>¡Comienza la PokeHunt!</h1>
    <p>Juega nuestros diferentes juegos para conseguir nuevos Pokemon para tu colección y para aumentar tu puntuación global.</p>
    <p>Cada Pokemon se puede conseguir de un juego específico. Juega distintos juegos y ¡hazte con todos!</p>
    <p>Compite con otros entrenadores en el <a href="index.php?ctl=rankings">Ranking</a> global y conviértete en el Maestro Pokémon.</p>
</div>

<div class="container">
    <div class="row d-flex justify-content-between">
        <div class="col-12 col-sm-3 border border-dark d-flex flex-column p-2 text-center">
            <h3>Adivina el Pokémon</h3>
            <img src="images/adivinanza_cover.png" alt="Imagen sobre el juego de adivinanzas">
            <p>Adivina el Pokémon según su silueta, grito o descripción</p>
            <p>Tienes 4 intentos para adivinar el Pokémon</p>
            <p>Por cada intento fallido revelará una nueva pista</p>
            <p>Cuanto menos intentos utilices, mayor será tu puntuación</p>
            <?php if ($params["user_level"] >= USER_REGISTERED): ?>
                <a class="btn btn-success mt-auto mx-auto" href="index.php?ctl=jugarAdivinanza">Jugar</a>
            <?php endif; ?>
        </div>
        <div class="col-12 col-sm-3 border border-dark d-flex flex-column p-2 text-center">
            <h3>Trivia</h3>
            <img src="images/trivia.png" alt="Imagen sobre el juego de trivias">
            <p>Selecciona todas las respuestas correctas a la pregunta mostrada</p>
            <p>Tienes un límite de tiempo para responder a la pregunta</p>
            <p>Cuanto más opciones correctas selecciones, mayor será tu puntuación</p>
            <?php if ($params["user_level"] >= USER_REGISTERED): ?>
                <a class="btn btn-success mt-auto mx-auto" href="index.php?ctl=jugarTrivia">Jugar</a>
            <?php endif; ?>
        </div>
        <div class="col-12 col-sm-3 border border-dark d-flex flex-column p-2 text-center">
            <h3>Clasifica los Pokémon</h3>
            <img src="images/clasificar_cover.png" alt="Imagen sobre el juego de clasificar">
            <p>En este juego verás múltiples Pokémon. Para cada uno, deberás elegir cuál es su tipo o generación.</p>
            <p>Cuanto menos errores cometas, mayor será tu puntuación</p>
            <?php if ($params["user_level"] >= USER_REGISTERED): ?>
                <a class="btn btn-success mt-auto mx-auto" href="index.php?ctl=jugarClasificar">Jugar</a>
            <?php endif; ?>
        </div>
    </div>
</div>
<img id=center src="../web/images/trainer_think.png">
<?php if ($params["user_level"] == USER_GUEST): ?>
    <div class="container text-center pt-2">
        <h2>Empieza tu aventura</h2>
        <p><a href="index.php?ctl=iniciarSesion">Inicia sesión</a> para jugar.</p>
        <p>Si no tienes una cuenta aún, <a href="index.php?ctl=registro">regístrate</a>.</p>
    </div>
<?php endif; ?>

<?php $contenido = ob_get_clean() ?>

<?php include 'layout.php' ?>