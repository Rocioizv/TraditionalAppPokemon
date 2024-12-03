<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['user'])) {
    header('Location:.');
    exit;
}

try {
    $connection = new \PDO(
        'mysql:host=localhost;dbname=pokemon_database',
        'pokemon_user',
        'pokemon_password',
        array(
            PDO::ATTR_PERSISTENT => true,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8'
        )
    );
} catch (PDOException $e) {
    echo 'no connection';
    exit;
}

$resultado = 0;
$url = 'create.php?op=insertpokemon&result=' . $resultado;

if (isset($_POST['name']) && isset($_POST['weight']) && isset($_POST['height']) && isset($_POST['type']) && isset($_POST['num_evolution'])) {
    $name = $_POST['name'];
    $weight = $_POST['weight'];
    $height = $_POST['height'];
    $type = $_POST['type'];
    $num_evolution = $_POST['num_evolution'];
    $ok = true;
    $name = trim($name);

    if (strlen($name) < 2 || strlen($name) > 100) {
        $ok = false;
    }
    if (!(is_numeric($weight) && $weight > 0 && $weight <= 1000.00)) {
        $ok = false;
    }

    if (!(is_numeric($height) && $height > 0 && $height <= 100.00)) {
        $ok = false;
    }

    $tipos_validos = ['Fuego', 'Agua', 'Planta', 'Eléctrico'];
    if (!in_array($type, $tipos_validos)) {
        $ok = false;
    }

    if (!(is_numeric($num_evolution) && intval($num_evolution) >= 0)) {
        $ok = false;
    }

    try {
        $sql = 'INSERT INTO pokemon (name, weight, height, type, num_evolution) VALUES (:name, :weight, :height, :type, :num_evolution)';
        $sentence = $connection->prepare($sql);
        $parameters = [
            'name' => $name,
            'weight' => $weight,
            'height' => $height,
            'type' => $type,
            'num_evolution' => $num_evolution
        ];
    
        foreach ($parameters as $nombreParametro => $valorParametro) {
            $sentence->bindValue($nombreParametro, $valorParametro);
        }
    
        // Intentar ejecutar la sentencia SQL
        if ($sentence->execute()) {
            $resultado = $connection->lastInsertId();
            $url = 'index.php?op=insertpokemon&result=' . $resultado;
        } else {
            $resultado = 0;
            $url = 'create.php?op=insertpokemon&result=' . $resultado;
        }
    } catch (PDOException $e) {
        echo 'Error de base de datos: ' . $e->getMessage();
        $resultado = 0;
        $url = 'create.php?op=insertpokemon&result=' . $resultado;
    }
    
    // if ($ok) {
    //     unset($_SESSION['old']);
    //     $sql = 'insert into pokemon (name, weight, height, type, num_evolution) values (:name, :weight, :height, :type, :num_evolution)';
    //     $sentence = $connection->prepare($sql);
    //     $parameters = [
    //         'name' => $name,
    //         'weight' => $weight,
    //         'height' => $height,
    //         'type' => $type,
    //         'num_evolution' => $num_evolution
    //     ];

    //     if ($stmt->execute()) {
    //         echo "Pokemon insertado correctamente.";
    //     } else {
    //         echo "Error al insertar Pokemon.";
    //     }
    //     foreach ($parameters as $nombreParametro => $valorParametro) {
    //         $sentence->bindValue($nombreParametro, $valorParametro);
    //     }

    //     try {
    //         $sentence->execute();
    //         $resultado = $connection->lastInsertId();
    //         $url = 'index.php?op=insertpokemon&result=' . $resultado;
    //     } catch (PDOException $e) {
    //     }
    // }
}
if ($resultado == 0) {
    $_SESSION['old']['name'] = $name;
    $_SESSION['old']['weight'] = $weight;
    $_SESSION['old']['height'] = $height;
    $_SESSION['old']['type'] = $type;
    $_SESSION['old']['num_evolution'] = $num_evolution;
}

header('Location: ' . $url);
