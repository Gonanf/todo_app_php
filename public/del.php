<?php
    try{
        $bd = new \PDO("sqlite:base.db");
        $result = $bd -> prepare("DELETE FROM items WHERE id = :id;");
        $result -> execute ([':id' => $_POST['item']]);
        echo json_encode($_POST['item']);
    }catch (\PDOException $e) {
        echo $e->getMessage();
    }
?>