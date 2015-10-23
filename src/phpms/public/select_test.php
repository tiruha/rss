<?php

$dsn = 'mysql:dbname=phpms;host=localhost;charset=utf8';
$user = 'phpms';
$pass = 'phpms';
$db = null;
try {
    $db = new PDO($dsn, $user, $pass);
} catch (Exception $e) {
    echo $e->getMessage();
}

echo "SQLを実行して結果出力<br>\n";
$sql = 'SELECT id, name FROM test';
foreach ($db->query($sql) as $row) {
    var_dump($row);
    echo "<br>\n";
}

?>

