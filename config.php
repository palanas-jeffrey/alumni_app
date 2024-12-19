<?php
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/');
$dotenv->load();

$PAYMONGO_SECRET_KEY = $_ENV['PAYMONGO_SECRET_KEY'];
?>