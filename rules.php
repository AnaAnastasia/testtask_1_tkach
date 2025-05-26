<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Controllers\RuleController;
use App\Services\DbConnection;

$db = new DbConnection();
(new RuleController($db))->handle();