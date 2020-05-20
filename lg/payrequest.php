<?php
/**
 * Created by pg.
 * User: littlefox
 * Date: 2020/05/19
 * Time: 4:30 오후
 * File: payrequest.php
 */

$BASE_DIR = $_SERVER['DOCUMENT_ROOT'];
$ENV_DIR  = $BASE_DIR . '/LGU+/src/';
require_once $BASE_DIR . '/vendor/autoload.php';
$CONFIG_PATH = $BASE_DIR . '/LGU+/lgdacom';

$pay = new \Lg\Payment($CONFIG_PATH);
