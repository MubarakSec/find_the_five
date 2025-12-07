<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$db_server = getenv('DB_HOST') ?: 'localhost';
$db_username = getenv('DB_USER') ?: 'root';
$db_password = getenv('DB_PASS') ?: '';
$db_name = getenv('DB_NAME') ?: 'find_the_five';

try {
    $connection = mysqli_connect($db_server, $db_username, $db_password, $db_name);
    mysqli_set_charset($connection, 'utf8mb4');
} catch (mysqli_sql_exception) {
    exit('Connection failed.');
}
