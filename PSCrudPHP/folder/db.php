<?php

$db = 'psPHPPDO';

$config = [
    'mysql_host' => 'localhost',
    'mysql_user' => 'root',
    'mysql_password' => ''
];

$mysqli = new mysqli(
    $config['mysql_host'],
    $config['mysql_user'],
    $config['mysql_password']
);

if ($mysqli->connect_error) {
    die('Errore nella connessione al database: ' . $mysqli->connect_error);
}


$sql = 'CREATE DATABASE IF NOT EXISTS ' . $db;
if (!$mysqli->query($sql)) {
    die('Errore nella creazione del database: ' . $mysqli->error);
}

$sql = 'USE ' . $db;
$mysqli->query($sql);


$sql = 'CREATE TABLE IF NOT EXISTS users ( 
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    firstname VARCHAR(255) NOT NULL, 
    lastname VARCHAR(255) NOT NULL, 
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    admin BOOLEAN NOT NULL DEFAULT 0
)';

if (!$mysqli->query($sql)) {
    die('Errore nella creazione della tabella: ' . $mysqli->error);
}


$sqlSelect = 'SELECT * FROM users;';
$res = $mysqli->query($sqlSelect);

if ($res->num_rows === 0) {
    $password = password_hash('Pa$$w0rd!', PASSWORD_DEFAULT);
   
    $sqlInsert = 'INSERT INTO users (firstname, lastname, email, password, admin) 
        VALUES ("black", "blue", "some@example.com", "'.$password.'", 1)';

    if (!$mysqli->query($sqlInsert)) {
        die('Errore nell\'inserimento del record: ' . $mysqli->error);
    } else {
        echo 'Record aggiunto con successo!!!';
    }
}


?>
