<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>
    <title>POKEHUNT</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400..700&display=swap" rel="stylesheet">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" type="text/css" href="<?= "css/" . LAYOUT_CSS ?>" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body class="d-flex flex-column min-vh-100">
    <div class="container-fluid">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="text-center">
                        <a href="index.php?ctl=inicio"><img class="logo" src="images/logo_proyect.png" alt="Logo de PokeHunt"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if ($this->session->getUserName() != ""): ?>
        <div class="container text-end">
            <p>User: <?= $this->session->getUserName() ?></p>
        </div>
    <?php endif; ?>

    <div class="container">
        <div class="row menu-container">
            <div class="col-6 text-start  ">

                <?php
                $menu = $this->menu();

                echo "<div  style='display: inline;'>";
                foreach ($menu as [$texto, $ruta, $img]): ?>
                
                    <div class="elevar">
                        <a style="text-decoration: none;" href="index.php?ctl=<?= $ruta ?>">
                            <span class="text"><?= $texto ?></span>
                        <img alt="<?= $texto ?>" class="icon" src="images/<?= $img ?>">
                    </a>
                        
                    </div>
                
                <?php endforeach; ?>
</div>
            </div>

            <div class="col-6 text-end">
                <?php
                $userMenu = $this->userMenu();
                 echo "<div  style='display: inline;'>";
                foreach ($userMenu as [$texto, $ruta, $img]): ?>
                   <div class="elevar">
                        <a style="text-decoration: none;" href="index.php?ctl=<?= $ruta ?>">
                            <span class="text"><?= $texto ?></span>
                        <img alt="<?= $texto ?>" class="icon" src="images/<?= $img ?>">
                    </a>
                        
                    </div>

                <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid my-2">
        <div class="container">
            <div id="contenido">
                <?= $contenido ?>
            </div>
        </div>
    </div>

    <div class="pie mt-auto p-2">
        <div class="container text-center">
            <a href="" class="text-center px-2">contact@pokehunt.com</a>
            <a href="" class="text-center px-2">Políticas de Privacidad</a>
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