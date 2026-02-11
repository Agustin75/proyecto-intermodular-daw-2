<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>
    <title>POKEHUNT</title>
    <head> 
        <link rel=stylesheet href="../web/css/estilo.css" type="text/css"></head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400..700&display=swap" rel="stylesheet">
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
                foreach ($menu as [$texto, $ruta, $img]): ?>
                <a href="index.php?ctl=<?= $ruta ?>">   <img alt="<?= $texto ?>" id="icon" src="../web/images/<?= $img ?>"></a>

                <?php endforeach; ?>
            </div>

            <div class="col-6 text-end">
                <?php
                $userMenu = $this->userMenu();
                foreach ($userMenu as [$texto, $ruta, $img]): ?>
                                   <a href="index.php?ctl=<?= $ruta ?>">   <img alt="<?= $texto ?>" id="icon" src="../web/images/<?= $img ?>"></a>

               
                   <?php endforeach; ?>
                <?php
                if ($this->session->getUserName() != "")
                    echo "Logged as: " .  $this->session->getUserName(); ?>
            </div>
        </div>
        <?php
        $adminMenu = $this->adminMenu();
        if (!empty($adminMenu)): ?>
            <div class="row">
                <div class="text-start">
                    <?php
                    foreach ($adminMenu as [$texto, $ruta]): ?>
                        <a href="index.php?ctl=<?= $ruta ?>"><?= $texto ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
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
    <?php if (is_array($script)): ?>
        <?php foreach ($script as $s): ?>
            <script type="text/javascript" src="js/<?= $s ?>"></script>
        <?php endforeach; ?>
    <?php else: ?>
        <script type="text/javascript" src="js/<?= $script ?>"></script>
    <?php endif; ?>
<?php endif; ?>

</body>

</html>