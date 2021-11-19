<?php

$pdo;

$id = filter_input(INPUT_GET, 'id');
$key = 'id_'.$id;

$memcache = new Memcache();
$memcache->addServer(getenv('MEMCACHED_HOST'), getenv('MEMCACHED_PORT'));
//$memcache->delete($key);
//exit;
$result = $memcache->get($key);

header('Content-Type: application/json');

if(!empty($result)){
    
    echo $result;
    exit;
}

try{

    $pdo = new PDO('mysql:dbname='. getenv('DB_NAME').';host='. getenv('DB_HOST').';port='. getenv('DB_PORT') , getenv('DB_USERNAME') , getenv('DB_PASSWORD') , [
//        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET time_zone = \''.TIMEZONE.'\'' ,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
    $pdo->exec('SET NAMES UTF8');

} catch (Exception $ex) {

    exit(json_encode([$ex->getMessage(), $ex->getTrace()]));
}

$statement = 'SELECT * FROM test WHERE Serial = :serial';

$sth = $pdo->prepare($statement);
$sth->execute(['serial' => $id]);

//$rows = $sth->fetchAll(PDO::FETCH_OBJ);
$row = $sth->fetch(PDO::FETCH_OBJ);

$result = json_encode($row);
echo $result;
if(!empty($result)) $memcache->set ($key, $result);
