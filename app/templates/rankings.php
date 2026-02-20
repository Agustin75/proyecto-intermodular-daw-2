<?php ob_start() ?>

<h1 class="text-center">Rankings</h1>
<img class="img-fluid mx-auto d-block" src="images/trainer_ranking.png">
<h2 class="text-center">Pokémon Capturados</h2>


<table class="table table-bordered text-center align-middle">
    <thead>
        <tr class="alert-warning text-dark">
            <th class="text-dark">Usuario</th>
            <th class="text-dark" >Capturados</th>
            <th class="text-dark" >Favoritos</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($params["users"]) == 0): ?>
            <tr>
                <td colspan="3">Aún no hay usuarios que hayan capturado un Pokémon. ¡Sé el primero!</td>
            </tr>
        <?php else: ?>
            <?php foreach ($params["users"] as $user): ?>
                <tr class="alert-secondary text-dark">
                    <td>
                        <img src="images/avatars/<?= $user["image"] ?>.png" alt="Imagen de perfil de <?= $user["name"] ?>"><br>
                        <a href="index.php?ctl=mostrarPerfil&id=<?= $user["id"] ?>"><?= $user["name"] ?></a>
                    </td>
                    <td class="text-dark"><?= $user["amount"] ?></td>
                    <td >
                        <?php if (empty($user["favorites"])): ?>
                            Este usuario no tiene ningún Pokémon favorito
                        <?php else: ?>
                            <?php foreach ($user["favorites"] as $favorito): ?>
                                <img src="<?= $favorito["image"] ?>" alt="Imagen de <?= $favorito["name"] ?>">
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php $contenido = ob_get_clean() ?>

<?php include 'layout.php' ?>