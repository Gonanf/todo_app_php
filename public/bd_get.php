<?php
	$lista = [];
try{
	$bd = new \PDO("sqlite:base.db");


	if (isset($_COOKIE['id'])){
	


		$result = $bd -> prepare("SELECT * FROM items WHERE usuario_id = :cookie;");
		$result -> execute ([':cookie' => $_COOKIE['id']]);
	while ($r = $result->fetch(\PDO::FETCH_ASSOC)){
		$lista[] = [
			"titulo" => $r['titulo'],
			"terminado" => $r['terminado'],
		];
	}
	}
	echo json_encode($lista);
}catch (\PDOException $e) {
    echo $e->getMessage();
}



?>
