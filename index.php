<?php
require_once "functions.php";
require_once "controllers/register.php";
require_once "controllers/index.php";
require_once "controllers/guest.php";

$show_complete_tasks = rand(0, 1);

$link = mysqli_connect("localhost", "root", "", "doingsdone");
mysqli_set_charset($link, "utf8");

if($_GET["page"] == "logout") {
    if(isset($_COOKIE["user"])) {
        unset($_COOKIE["user"]);
    }
    setcookie("user", "", time() - 3600, "/");
}

if($_GET["page"] == "registration") {
    register($link);
}

if(isset($_COOKIE["user"])) {
    index($link, $show_complete_tasks);
}

if (!isset($_COOKIE["user"]) AND $_GET["page"] != "registration") {
    guest($link);
}



