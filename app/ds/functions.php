<?php
//
//function &getConnection(){
//    $dsn = 'mysql:dbname=sugeno_galaxy;host=127.0.0.1';
//    $user = 'root';
//    $password = 'maskandar123';
//    $db = new PDO($dsn, $user, $password);
//    return $db;
//}

function closeConnection ($db){
    $db = NULL;
}

//create the database if does not exist
function createCarsTable($db){
    $db->exec("
    CREATE TABLE IF NOT EXISTS cars (
    id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT primary key, 
    manufacturer TEXT, 
    year INTEGER, 
    price INTEGER
    )");
    
    if($db->errorCode()>0){
        echo $db->errorInfo()[2]; 
    }
}

function resetCarsTable($db){
    dropCarsTable($db);
    createCarsTable($db);
    loadDefaultCars($db);
}

function dropCarsTable($db){
    $db->exec("DROP TABLE IF EXISTS cars");
}

function loadDefaultCars($db){
    $query = $db->prepare('INSERT INTO cars (id, manufacturer, year, price) VALUES(:id, :manufacturer, :year, :price)');
    $data = array(
        array(
            'id' => 1,
            'manufacturer' => 'Honda',
            'year' => 2010,
            'price' => 200000
        ),
        array(
            'id' => 2,
            'manufacturer' => 'Jaguar',
            'year' => 2012,
            'price' => 400000
        ),
        array(
            'id' => 3,
            'manufacturer' => 'BMW',
            'year' => 2000,
            'price' => 75000
        ),
        array(
            'id' => 4,
            'manufacturer' => 'Mercedes',
            'year' => 1980,
            'price' => 1000
        )
    );

    foreach($data as $index => $value){
        $query->bindValue(':id', $value['id'], PDO::PARAM_INT);
        $query->bindValue(':manufacturer', $value['manufacturer'], PDO::PARAM_STR);
        $query->bindValue(':year', $value['year'], PDO::PARAM_INT);
        $query->bindValue(':price', $value['price'], PDO::PARAM_INT);
        $query->execute();
    }
}

function &loadCars($db){
    $select = $db->prepare('SELECT * FROM cars ORDER BY id ASC LIMIT 100');
    $select->execute();

    return $select;
}

function carsTableExists($db){
    $result = $db->query("SELECT COUNT(*) FROM `information_schema`.`TABLES` WHERE table_name='cars'");

    $row = $result->fetch(PDO::FETCH_NUM);

    return $row[0] > 0;
}