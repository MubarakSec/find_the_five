<?php
// Sample configuration. Copy to db.php and adjust credentials for your setup.

$db_server = 'the_server';
$db_username = 'username';
$db_password = 'change_me';
$db_name = 'database_name';
try {
    $connection = mysqli_connect($db_server, $db_username, $db_password, $db_name);
} catch (mysqli_sql_exception) {
    echo "Connection failed.";
}
