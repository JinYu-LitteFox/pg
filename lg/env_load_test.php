<?php

/**
 * Created by pg.
 * User: littlefox
 * Date: 2020/05/19
 * Time: 3:22 오후
 * File: env_load_test.php
 */

$BASE_DIR = $_SERVER['DOCUMENT_ROOT'];
$ENV_DIR  = $BASE_DIR . '/LGU+/src/';
require_once $BASE_DIR . '/vendor/autoload.php';
$dotenv = \Dotenv\Dotenv::createImmutable($ENV_DIR);
$dotenv->load();
foreach ($_ENV as $key => $value) {
    echo $key .' => '. $value;
    echo "<br />";
}
