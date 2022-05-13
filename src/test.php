<?php

header('Content-Type: application/json');

$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');
$name = getenv('DB_NAME');

try{

    $pdo = new PDO('mysql:dbname='.$name.';host='.$host.';port='.$port , $username , $password , [
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
    $pdo->exec('SET NAMES UTF8');

} catch (Exception $ex) {

    $log = [
            'message' => $ex->getMessage(),
            'dbname' => $name,
            'host' => $host,
            'port' => $port,
            'username' => $username,
            'password' => $password,
            'trace' => $ex->getTrace(),
        ];
    
    echo json_encode($log, JSON_UNESCAPED_UNICODE);
    error_log(json_encode($log, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), 0);
    exit;
}

$sth = $pdo->prepare('SELECT * FROM Users WHERE UserID = 1');
$sth->execute();
$row = $sth->fetch();

echo json_encode($row, JSON_UNESCAPED_UNICODE);