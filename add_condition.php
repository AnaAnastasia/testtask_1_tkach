<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Controllers\ConditionController;
use App\Services\DbConnection;

$db = new DbConnection();
(new ConditionController($db))->handle();