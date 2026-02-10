<?php ob_start() ?>
<head> <link rel=stylesheet href="../web/css/estilo.css" type="text/css"></head>

<h1 class="text-center">Gestión de Juegos</h1>
<br>

<div style="display:flex; gap:20px; justify-content:space-between;">

    <!-- ============================
         COLUMNA TRIVIA
    ============================= -->
    <div style="width:33%; border:1px solid #ccc; padding:15px;">
        <h2>Trivia</h2>

        <a href="index.php?ctl=crearTrivia" class="btn btn-primary">Crear Trivia</a>
        <br><br>

        <?php if (empty($params["trivias"])): ?>
            <p>No hay trivias creadas todavía.</p>
        <?php else: ?>
            
            <ul>
                <?php foreach ($params["trivias"] as $t): ?>
                    <li style="margin-bottom:10px;">
                        <?= $t["pokemon_name"] ?> (ID <?= $t["id_pokemon"] ?>)

                        <a href="index.php?ctl=editarTrivia&id=<?= $t["id"] ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="index.php?ctl=eliminarTrivia&id=<?= $t["id"] ?>" class="btn btn-danger btn-sm">Eliminar</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <!-- ============================
         COLUMNA ADIVINANZA
    ============================= -->
    <div style="width:33%; border:1px solid #ccc; padding:15px;">
        <h2>Adivinanza</h2>
        <a href="index.php?ctl=crearAdivinanza" class="btn btn-primary">Crear Adivinanza</a>
        <br><br>
          <?php if (empty($params["clasificar"])): ?>
            <p>No hay juegos de clasificar creados todavía.</p>
        <?php else: ?>
    </div>

    <!-- ============================
         COLUMNA CLASIFICAR
    ============================= -->
    <div style="width:33%; border:1px solid #ccc; padding:15px;">
        <h2>Clasificar</h2>
        <a href="index.php?ctl=crearClasificar" class="btn btn-primary">Crear Clasificar</a>
        <br><br>
        <?php if (empty($params["clasificar"])): ?>
            <p>No hay juegos de clasificar creados todavía.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($params["clasificar"] as $t): ?>
                    <li style="margin-bottom:10px;">
                        <?= $t["id_pokemon"] . " - " . $t["pokemon_name"] ?>
                        <a href="index.php?ctl=editarClasificar&idClasificar=<?= $t["id"] ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="index.php?ctl=eliminarClasificar&idClasificar=<?= $t["id"] ?>" class="btn btn-danger btn-sm">Eliminar</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

</div>


<?php $contenido = ob_get_clean() ?>

<?php include 'layout.php' ?>