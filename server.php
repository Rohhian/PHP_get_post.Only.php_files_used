<?php

include 'header.php';

$connection = new PDO("mysql:host=localhost;dbname=test", "root", "");

if (isset($_POST['getAll'])) {
    koik();
} elseif (isset($_POST['sisestus'])) {
    lisamine($_POST['nimi']);
} elseif (isset($_POST['delete'])) {
    kustutamine($_POST['indeks']);
}

function lisamine($name)
{
    global $connection;
    try {
        $preparedSql = $connection->prepare("INSERT INTO inimene (nimi) VALUES (:name)");
        $preparedSql->execute([':name' => $name]);
    } catch (Exception $e) {
        if ($e->errorInfo[1] === 1062) echo "Duplicate entry <br><br>";
    }
    koik();
}

function koik()
{
    global $connection;
    $preparedSql = $connection->prepare("SELECT id, nimi FROM inimene ORDER BY id");
    $preparedSql->execute();
    $arr = $preparedSql->fetchAll(PDO::FETCH_ASSOC);

    echo "Row Count: " . $preparedSql->rowCount() . "<br><hr>";
    echo "<strong>ID NAME</strong><br>";
    foreach ($arr as $item) {
        echo $item['id'] . '&nbsp;&nbsp;';
        echo $item['nimi'] . "<br>";
        $arr2[] = $item['nimi'];
    }
    $json = json_encode($arr);
    $json2 = json_encode($arr2);
    echo "<br><br>" . "<h3>This is how it looks in JSON file:</h3>";
    echo $json . "<br><br>";
    echo "<h3>This is how it looks in JSON2 file:</h3>";
    echo $json2 . "<br><br>";

    echo "<h3>This is how it looks in PHP-decoded JSON file:</h3>";
    $decodedJSON = json_decode($json);
    print_r($decodedJSON);
    echo "<br><br><h3>This is how it looks in PHP-decoded JSON2 file:</h3>";
    $decodedJSON2 = json_decode($json2);
    print_r($decodedJSON2);
//    echo var_dump($arr) . "<br><br>";
//    echo print_r($arr);
}

function kustutamine($indeks)
{
    global $connection;
    $preparedSql = $connection->prepare("DELETE FROM inimene WHERE id = :indeks");
    $preparedSql->execute([':indeks' => $indeks]);
    koik();
}