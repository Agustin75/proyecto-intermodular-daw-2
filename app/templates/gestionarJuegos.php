<?php ob_start() ?>

<h1 class="text-center">Gestión de Juegos</h1>
<br>

<div class="row justify-content-around">


    <!-- ============================
         COLUMNA ADIVINANZA
    ============================= -->
    <div class="col-12 col-lg-5 col-xxl-3 border p-2 m-2">
        <div class="text-center">
            <h2>Adivinanza</h2>
            <a href="index.php?ctl=crearAdivinanza" class="btn btn-primary">Crear Adivinanza</a>
        </div>
        <br>
        <?php if (empty($params["adivinar"])): ?>
            <p>No hay trivias creadas todavía.</p>
        <?php else: ?>

            <ul>
                <?php foreach ($params["adivinar"] as $t): ?>
                    <li style="margin-bottom:10px;">
                        <div class="d-flex justify-content-between">
                            <?= $t["id_pokemon"] . " - " . $t["pokemon_name"] ?>
                            <div>
                                <a href="index.php?ctl=editarAdivinanza&id=<?= $t["id"] ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="index.php?ctl=eliminarAdivinanza&id=<?= $t["id"] ?>" class="btn btn-danger btn-sm">Eliminar</a>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
    <!-- ============================
         COLUMNA TRIVIA
    ============================= -->
    <div class="col-12 col-lg-5 col-xxl-3 border p-2 m-2">
        <div class="text-center">
            <h2>Trivia</h2>
            <a href="index.php?ctl=crearTrivia" class="btn btn-primary">Crear Trivia</a>
        </div>
        <br>

        <?php if (empty($params["trivias"])): ?>
            <p>No hay trivias creadas todavía.</p>
        <?php else: ?>

            <ul>
                <?php foreach ($params["trivias"] as $t): ?>
                    <li style="margin-bottom:10px;">
                        <div class="d-flex justify-content-between">
                            <?= $t["id_pokemon"] . " - " . $t["pokemon_name"] ?>
                            <div>
                                <a href="index.php?ctl=editarTrivia&id=<?= $t["id"] ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="index.php?ctl=eliminarTrivia&id=<?= $t["id"] ?>" class="btn btn-danger btn-sm">Eliminar</a>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
    <!-- ============================
         COLUMNA CLASIFICAR
    ============================= -->
    <div class="col-12 col-lg-5 col-xxl-3 border p-2 m-2">
        <div class="text-center">
            <h2>Clasificar</h2>
            <a href="index.php?ctl=crearClasificar" class="btn btn-primary">Crear Clasificar</a>
        </div>
        <br>
        <?php if (empty($params["clasificar"])): ?>
            <p>No hay juegos de clasificar creados todavía.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($params["clasificar"] as $t): ?>
                    <li style="margin-bottom:10px;">
                        <div class="d-flex justify-content-between">
                            <?= $t["id_pokemon"] . " - " . $t["pokemon_name"] ?>
                            <!-- Div to align both buttons together -->
                            <div>
                                <a href="index.php?ctl=editarClasificar&idClasificar=<?= $t["id"] ?>" class="btn btn-warning btn-sm text-right">Editar</a>
                                <a href="index.php?ctl=eliminarClasificar&idClasificar=<?= $t["id"] ?>" class="btn btn-danger btn-sm text-right">Eliminar</a>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

</div>


<?php $contenido = ob_get_clean() ?>

<?php include 'layout.php' ?>