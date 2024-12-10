<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
if(!isset($_SESSION['user'])) {
    header('Location:.');
    exit;
}
$user = $_SESSION['user'];

try {
    $connection = new \PDO(
      'mysql:host=localhost;dbname=pokemon_database',
      'pokemon_user',
      'pokemon_password',
      array(
        PDO::ATTR_PERSISTENT => true,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8')
    );
} catch(PDOException $e) {
    echo 'no connection';
    exit;
}

if(isset($_POST['id'])) {
    $id = $_POST['id'];
} else {
    $url = '.?op=updateproduct&result=noid';
    header('Location: ' . $url);
    exit;
}


if(isset($_POST['name'])) {
    $name = trim($_POST['name']);
} else {
    header('Location: .');
    exit;
}

if(isset($_POST['weight'])) {
    $price = $_POST['weight'];
} else {
    header('Location: .');
    exit;
}

if(isset($_POST['height'])) {
    $price = $_POST['height'];
} else {
    header('Location: .');
    exit;
}

if(isset($_POST['type'])) {
    $price = $_POST['type'];
} else {
    header('Location: .');
    exit;
}

if(isset($_POST['num_evolution'])) {
    $price = $_POST['num_evolution'];
} else {
    header('Location: .');
    exit;
}

$sql = 'update pokemon  set name = :name, weight = :weight, height = :height, tipo = :tipo, num_evolution = :num_evolution where id = :id';
$sentence = $connection->prepare($sql);
    
$parameters = [
    'name' => $name,
    'weight' => $weight,
    'height' => $height,
    'tipo' => $tipo,
    'num_evolution' => $num_evolution,
    'id' => $id  
];

foreach($parameters as $nombreParametro => $valorParametro) {
    $sentence->bindValue($nombreParametro, $valorParametro);
}

try {           
    $sentence->execute();
            
    $resultado = $sentence->rowCount();

    $url = '.?op=editpokemon&result=' . $resultado;
} catch(PDOException $e) {


     $_SESSION['error']['db'] = 'Error: ' . $e->getMessage();

     header('Location: edit.php?op=editpokemon&error=db');
     exit;
}
header('Location: ' . $url);