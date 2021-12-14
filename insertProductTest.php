<?php

require_once 'vendor/autoload.php';

use Doctrine\DBAL\DriverManager;

$connectionParams = array(
    'url' => "mysql://root:@localhost/db_name?serverVersion=5.7",
);

$name = 'dylan';

$sql = "INSERT INTO product (name, price) VALUES ('', 5)";

$sql = substr_replace($sql, $name, 43, 0);

print($sql);

$conn = DriverManager::getConnection($connectionParams);

$stmt = $conn->query($sql); // Simple, but has several drawbacks
