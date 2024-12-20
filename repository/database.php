<?php

// Fonction qui connecte PDO à la base de données et retourne l'objet PDO connecté
function getConnection(): PDO
{
    $host        = 'localhost';
    $dbName      = 'akinator';
    $sqlUser     = 'root';
    $sqlPassword = '';

    $pdo = new PDO(
        'mysql:host=' . $host . ';port=3306;dbname=' . $dbName . ';charset=utf8',
        $sqlUser,
        $sqlPassword
    );

    return $pdo;
}
