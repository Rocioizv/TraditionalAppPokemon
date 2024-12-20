<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
if(!isset($_SESSION['user'])) {
    header('Location:.');
    exit;
}
$name = isset($_SESSION['old']['name']) ? $_SESSION['old']['name'] : '';
$weight = isset($_SESSION['old']['weight']) ? $_SESSION['old']['weight'] : '';
$height = isset($_SESSION['old']['height']) ? $_SESSION['old']['height'] : '';
$type = isset($_SESSION['old']['type']) ? $_SESSION['old']['type'] : '';
$num_evolution = isset($_SESSION['old']['num_evolution']) ? $_SESSION['old']['num_evolution'] : '';
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
    header('Location: ..');
    exit;
}

if(isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $url = '.?op=editproduct&result=noid';
    header('Location: ' . $url);
    exit;
}


$sql = 'select * from pokemon where id = :id';
$sentence = $connection->prepare($sql);
$parameters = ['id' => $id];
foreach($parameters as $nombreParametro => $valorParametro) {
    $sentence->bindValue($nombreParametro, $valorParametro);
}

try {
    $sentence->execute();
    $row = $sentence->fetch();
} catch(PDOException $e) {
    header('Location:.');
    exit;
}

if($row == null) {
    header('Location: .');
    exit;
}

$id = $row['id'];
if($name == '') {
    $name = $row['name'];
}
if($weight == '') {
    $weight = $row['weight'];
}
if($height == '') {
    $height = $row['height'];
}
if($type == '') {
    $type = $row['type'];
}
if($num_evolution == '') {
    $num_evolution = $row['num_evolution'];
}
$connection = null;


?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>dwes</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>
    <body>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <a class="navbar-brand" href="..">dwes</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="..">Home</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="./">Product</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="./">Pokemon</a>
                    </li>
                </ul>
            </div>
        </nav>
        <main role="main">
            <div class="jumbotron">
                <div class="container">
                    <h4 class="display-4">Pokemon</h4>
                </div>
            </div>
            <div class="container">
            <?php
                if(isset($_GET['op']) && isset($_GET['result'])) {
                    if($_GET['result'] > 0) {
                        ?>
                        <div class="alert alert-primary" role="alert">
                            result: <?= $_GET['op'] . ' ' . $_GET['result'] ?>
                        </div>
                        <?php 
                    } else {
                        ?>
                        <div class="alert alert-danger" role="alert">
                            result: <?= $_GET['op'] . ' ' . $_GET['result'] ?>
                        </div>
                        <?php
                        }
                }
                ?>
                <div>
                    <form action="update.php" method="post">
                    <div class="form-group">
                            <label for="name">Name</label>
                            <input value="<?= $name ?>" required type="text" class="form-control" id="name" name="name" placeholder="pokemon name">
                        </div>
                        <div class="form-group">
                            <label for="weight">Weight</label>
                            <input value="<?= $weight ?>" required type="number" step="0.001" class="form-control" id="weight" name="weight" placeholder="weight">
                        </div>
                        <div class="form-group">
                            <label for="height">Height </label>
                            <input value="<?= $height ?>" required type="text" class="form-control" id="height" name="height" placeholder="height">
                        </div>
                        <div class="form-group">
                            <label for="type">Type</label>
                                <select required class="form-control" id="type" name="type" placeholder="type">
                                    <option value="Fire" <?= $type === 'Fire' ? 'selected' : '' ?>>Fire</option>
                                    <option value="Water" <?= $type === 'Water' ? 'selected' : '' ?>>Water</option>
                                    <option value="Grass" <?= $type === 'Grass' ? 'selected' : '' ?>>Grass</option>
                                    <option value="Electric" <?= $type === 'Electric' ? 'selected' : '' ?>>Electric</option>
                                </select>                        
                        </div>
                        <div class="form-group">
                            <label for="num_evolution">Evolutions</label>
                            <input value="<?= $num_evolution ?>" required type="number" class="form-control" id="num_evolution" name="num_evolution" placeholder="Evolutions ">
                        </div>
                        <button type="submit" class="btn btn-primary">edit</button>
                    </form>
                </div>
                <hr>
            </div>
        </main>
        <footer class="container">
            <p>&copy; IZV 2024</p>
        </footer>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
</html>