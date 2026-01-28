<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>
    <title>POKEHUNT</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php echo 'css/' . $mvc_vis_css ?>" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <div class="container-fluid">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="text-center"><b>POKEHUNT</b></h1>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-6 text-start">
                <?php
                $menu = $this->menu();
                foreach ($menu as [$texto, $ruta]): ?>
                    <a href="index.php?ctl=<?= $ruta ?>">
                        <?= $texto ?></a>
                <?php endforeach; ?>
            </div>

            <div class="col-6 text-end">
                <?php
                $userMenu = $this->userMenu();
                foreach ($userMenu as [$texto, $ruta]): ?>
                    <a href="index.php?ctl=<?= $ruta ?>"><?= $texto ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <hr>

    <div class="container-fluid">
        <div class="container">
            <div id="contenido">
                <?= $contenido ?>
            </div>
        </div>
    </div>

    <div class="container-fluid pie p-2 my-5">
        <div class="container">
            <h5 class="text-center"> FOOTER VA AQUÍ </h5>
        </div>
    </div>
    <?php if (isset($script)): ?>
        <script type="text/javascript" src="<?= "js/" . $script ?>"></script>
    <?php endif; ?>
</body>

</html>