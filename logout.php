<?php
require_once 'core/init.php';

$user = new Users();
$user->logout();

Redirect::to('index.php');