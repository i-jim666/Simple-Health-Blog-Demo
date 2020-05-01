<?php

$user = 'root';
$password  = '';

$server = "mysql:host=localhost;dbname=health_blog";


try{
    $pdo = new PDO($server, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    global $pdo;
    
} catch(PDOException $e){
	die('Database Connection Problem: '.$e->getMessage());
}

