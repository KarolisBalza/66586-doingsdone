<?php

function register ($link) {

    $registrationErrors =
        [
            "emailEmptyError" => false,
            "emailTakenError" => false,
            "emailValidityError" => false,
            "emailError" => false,
            "passwordError" => false,
            "nameError" => false,
            "errors" => false
        ];

    $userName = "";
    $userEmail = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST" AND isset($_POST["register"])) {
        $userName = $_POST["name"];
        $userEmail = $_POST["email"];
        $userPassword = $_POST["password"];

        if(empty($userName)) {
            $registrationErrors["nameError"] = true;
            $registrationErrors["errors"] = true;
        }
        if(empty($userPassword)){
            $registrationErrors["passwordError"] = true;
            $registrationErrors["errors"] = true;
        }
        if(empty($userEmail)){
            $registrationErrors["emailEmptyError"] = true;
            $registrationErrors["emailError"] = true;
            $registrationErrors["errors"] = true;
        }
        if(!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
            $registrationErrors["emailValidityError"] = true;
            $registrationErrors["emailError"] = true;
            $registrationErrors["errors"] = true;
        }
        if($registrationErrors["emailEmptyError"] OR $registrationErrors["emailValidityError"]) {
            $registrationErrors["emailError"] = true;
        }
        if(checkIfEmailExists($link, $userEmail)){
            $registrationErrors["emailTakenError"] = true;
            $registrationErrors["emailError"] = true;
            $registrationErrors["errors"] = true;
        }
        if(!$registrationErrors["errors"]) {
            if(addNewUser($link, $userEmail, $userPassword, $userName)) {
                header("Location: index.php?page=login");
            }
        }
    }

    $layoutContent = includeLayout(
        "templates" . DIRECTORY_SEPARATOR . "register.php",
        [
            "registrationErrors" => $registrationErrors,
            "userName" => $userName,
            "userEmail" => $userEmail
        ]
    );

    print ($layoutContent);

}