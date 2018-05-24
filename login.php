<?php

require_once "functions.php";
require_once "init.php";

$login = true;

$loginErrors =
    [
        "emptyEmail" => false,
        "emptyPassword" => false,
        "emailNotFound" => false,
        "incorrectPassword" => false,
        "errors" => false
    ];

$userEmail = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" AND isset($_POST["login"])) {
    $userEmail = $_POST["email"];
    $userPassword = $_POST["password"];
    if (empty($userEmail)) {
        $loginErrors["emptyEmail"] = true;
        $loginErrors["errors"] = true;
    }
    if (empty($userPassword)) {
        $loginErrors["emptyPassword"] = true;
        $loginErrors["errors"] = true;
    }
    if (!checkIfEmailExists($link, $userEmail)) {
        $loginErrors["emailNotFound"] = true;
        $loginErrors["errors"] = true;
    }
    if (!checkIfPasswordCorrect($link, $userEmail, $userPassword)) {
        $loginErrors["incorrectPassword"] = true;
        $loginErrors["errors"] = true;
    }
    if ($loginErrors["errors"] == false) {
        $usersId = getUsersIdByEmail($link, $userEmail);
        session_start();
        $_SESSION["user"] = $usersId;
        header("Location: index.php");
    }

};

$login = includeLayout(
    "templates" . DIRECTORY_SEPARATOR . "login.php",
    [
        "login" => $login,
        "loginErrors" => $loginErrors,
        "userEmail" => $userEmail
    ]
);

$layoutContent = includeLayout(
    "templates" . DIRECTORY_SEPARATOR . "guest.php",
    [
        "login" => $login
    ]
);

print $layoutContent;