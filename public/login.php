<?php

header('Content-Type: application/json');
try {
$bd = new \PDO("sqlite:base.db");

$bd -> exec("CREATE TABLE IF NOT EXISTS usuarios (id INTEGER PRIMARY KEY,nombre TEXT, contra TEXT);");
$bd -> exec("CREATE TABLE IF NOT EXISTS items (id INTEGER PRIMARY KEY, titulo TEXT, terminado INTEGER, usuario_id INTEGER);");

if ($_SERVER['REQUEST_METHOD'] == "GET"){
	if (isset($_COOKIE['id'])){
		$result = $bd -> prepare("SELECT * FROM usuarios WHERE id = :cookie;");
		$result -> execute ([':cookie' => $_COOKIE['id']]);
		echo json_encode($result->fetch(\PDO::FETCH_ASSOC));
	}
	return;
}

if ($_SERVER['REQUEST_METHOD'] == "POST"){
	$nombre = $_POST['nombre'];
	$contra = $_POST['contra'];
	$res = $bd -> prepare("SELECT * FROM usuarios WHERE nombre = :nombre AND contra = :contra;");
	$res -> execute([':nombre' => $nombre, ':contra' => $contra]);
	if ($res->fetch(\PDO::FETCH_ASSOC)){
			echo json_encode("Mal coso");
			echo json_encode($res->fetch(\PDO::FETCH_ASSOC));
			return;
	}

	$res = $bd -> prepare("INSERT INTO usuarios(nombre,contra) VALUES(:nombre,:contra);");
	$res -> execute([':nombre' => $nombre, ':contra' => $contra]);

	$res = $bd -> prepare("SELECT * FROM usuarios WHERE nombre = :nombre AND contra = :contra;");
		$res -> execute([':nombre' => $nombre, ':contra' => $contra]);

	$id = $res -> fetch(\PDO::FETCH_ASSOC)['id'];
	setcookie('id',$id, time() + (86400 * 30), "/");
}
} catch (\PDOException $e){
	echo $e -> getMessage();
}

header("Location: /");
die();
?>
