<?php
declare(strict_types=1);

ini_set(option:'display_errors', value: 1);
ini_set(option:'display_startup_errors', value: 1);
error_reporting(error_level: E_ALL);

date_default_timezone_set('Europe/Paris');

require_once __DIR__ . '/../database/pdo.php';