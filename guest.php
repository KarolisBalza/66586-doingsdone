<?php

require_once "functions.php";

$login = "";

$layoutContent = includeLayout(
    "templates" . DIRECTORY_SEPARATOR . "guest.php",
    [
        "login" => $login
    ]
);

print $layoutContent;