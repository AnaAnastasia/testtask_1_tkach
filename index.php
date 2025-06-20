<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Controllers\IndexController;
use App\Services\DbConnection;

$db = new DbConnection();
(new IndexController($db))->handle();