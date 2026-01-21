
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title>BIBLIOTECA</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="<?php echo 'css/'.$mvc_vis_css ?>" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
	<div class="container-fluid">
		<div class="container">
			<div class="row">
				<div class="col-md-11">
					<h1 class="text-center"><b>MVC EJEMPLO</b></h1>	
				</div>
				<div class="col-md-1">
				
				</div>
			</div>
		</div>
	</div>
	
	<?php	
	$menu = $this->menu ();
    foreach ($this->menu() as [$texto, $ruta]): ?> 
    <a href="index.php?ctl=<?= $ruta ?>">
        <?= $texto ?></a> 
    <?php endforeach; ?> 
    <hr>
    
    
	<div class="container-fluid">
		<div class="container">
			<div id="contenido">
			
            <?php echo $contenido ?>

			</div>
		</div>
	</div>
	
	<div class="container-fluid pie p-2 my-5">
		<div class="container">
			<h5 class="text-center"> Aqui pones tu texto</h5>
		</div>
	</div>

</body>
</html>