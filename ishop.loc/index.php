<?php
require_once 'init.php';

//var_dump(Session::get(Config::get('session.user_session')));

$user = new User;
echo $user->data()->username;

