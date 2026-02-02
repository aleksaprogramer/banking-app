<?php

$json_env = file_get_contents('././env.json');
$env = json_decode($json_env);

$servername = $env->servername;
$username = $env->username;
$password = $env->password;
$dbname = $env->dbname;

$db = mysqli_connect($servername, $username, $password, $dbname);

if (!$db) {
    exit('Connection to database failed.');

}