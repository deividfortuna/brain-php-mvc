<?php
/**
 * 
 * @author Deivid Fortuna <deividfortuna@gmail.com>
 */
use Brain\System;

ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('America/Sao_Paulo');

$root = dirname( $_SERVER["PHP_SELF"] ) == DIRECTORY_SEPARATOR ? "" : dirname( $_SERVER["PHP_SELF"] );
define( "ROOT", $root . '/');

define('DIR_ROOT', __DIR__.DIRECTORY_SEPARATOR);


header("Content-type: text/html;charset=utf-8");

// Configura o sistema
require_once 'lib/brain/Constants.php';
//include_once 'vendor/autoload.php';
require_once 'lib/brain/System.php';

$Brain = new System();
$Brain->wake();
