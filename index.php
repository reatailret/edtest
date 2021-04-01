<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "Http/IndexController.php";

use Http\IndexController;

session_start();


$ctl = new IndexController();
$data = $ctl->index();
extract($data);
include("Views/Index.php");
