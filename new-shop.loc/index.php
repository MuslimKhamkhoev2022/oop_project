<?php
require_once 'Database.php';

$users = Database::getInstance()->query("SELECT * FROM users");

if ($users->error()){
    echo 'we have an error';
}else{
    echo 'everything is okay';
}
die();


foreach ($users as $user) {
    echo $user->username . '<br>';
}