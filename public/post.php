<?php
try{
	$bd = new \PDO("sqlite:base.db");


	if (isset($_COOKIE['id'])){
		$result = $bd -> prepare("INSERT INTO items(titulo,terminado,usuario_id) VALUES(:titulo,0,:id);");
		$result -> execute ([':titulo' => $_POST['titulo'], ':id' => $_COOKIE['id']]);
	}
}catch (\PDOException $e) {
    echo $e->getMessage();
}

header("Location: /");
die();



?>
