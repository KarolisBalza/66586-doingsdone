<?php

require_once "functions.php";

$layoutContent = includeLayout(
    "templates" . DIRECTORY_SEPARATOR . "guest.php",
    [
        "login" => $login
    ]
);

print $layoutContent;