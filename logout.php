<?php
// 328/login/login.php

//Turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
session_destroy();
$_SESSION = array();
header('location: login.php');