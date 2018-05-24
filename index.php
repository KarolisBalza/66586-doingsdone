<?php
require_once "functions.php";
require_once "init.php";

session_start();

$show_complete_tasks = rand(0, 1);


if ($_GET["page"] == "logout") {
    if (isset($_SESSION["user"])) {
        $_SESSION = [];
    }
}

$usersId = $_SESSION["user"]["id"];
$projectsId = 0;
$postedName = "";
$errors =
    [
        "titleError" => false,
        "dateError" => false,
        "errors" => false
    ];

if ($_GET["page"] == "registration") {
    header("Location: registration.php");
}

if (!isset($_SESSION["user"]) AND $_GET["page"] != "registration") {
    header("Location: guest.php");
}

if ($_GET["page"] == "login") {
    header("Location: login.php");
}

if(isset($_SESSION["user"])) {
    $usersName = getUsersNameById($link, $_SESSION["user"]["id"]);
}

if (isset($_SESSION["user"])) {
    if (!$link) {
        exit(mysqli_connect_error());
    } else {
        $projectsTypes = getProjectsTypes($link, $usersId);
        array_unshift($projectsTypes, ["id" => 0, "title" => "Входяшие"]);
        if (isset($_GET["id"])) {
            $projectsId = (int)$_GET["id"];
        }
        $tasksData = getTasksDataById($link, $projectsId, $usersId);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" AND isset($_POST["task_add"])) {
        $postedName = $_POST["name"];
        $postedDate = $_POST["date"];
        $postedFile = $_POST["preview"];
        $postedProject = $_POST["project"];
        if (empty($postedName)) {
            $errors["titleError"] = true;
            $errors["errors"] = true;
        }
        if (empty($postedProject)) {
            $postedProject = NULL;
        }
        if (!checkDatesValidity($postedDate)) {
            $errors["dateError"] = true;
            $errors["errors"] = true;
        }
        if (empty($postedDate)) {
            $postedDate = NULL;
        }

        if (!$errors["errors"]) {
            if (!empty($_FILES["preview"]["name"])) {
                $fileUrl = uploadFile($_FILES);
            }
            $sql = "INSERT INTO tasks (title, file, projects_id, deadline, users_id) VALUES (?, ?, ? , ?, ?)";
            $stmt = mysqli_prepare($link, $sql);
            mysqli_stmt_bind_param($stmt, "ssisi", $postedName, $fileUrl, $postedProject, $postedDate, $usersId);
            $res = mysqli_stmt_execute($stmt);
            if ($res) {
                if ($postedProject == NULL) {
                    header("Location: " . "index.php");
                } else {
                    header("Location: " . "index.php?id=" . $postedProject);
                }
            }
        }
    }
}

$pageContent = includeLayout(
    "templates" . DIRECTORY_SEPARATOR . "index.php",
    [
        "show_complete_tasks" => $show_complete_tasks,
        "tasksData" => $tasksData
    ]
);

$addTask = includeLayout(
    "templates" . DIRECTORY_SEPARATOR . "addtask.php",
    [
        "projectsTypes" => $projectsTypes,
        "errors" => $errors,
        "postedName" => $postedName
    ]
);


$layoutContent = includeLayout(
    "templates" . DIRECTORY_SEPARATOR . "layout.php",
    [
        "title" => "Дела в Порядке",
        "usersName" => $usersName,
        "projectsTypes" => $projectsTypes,
        "tasksData" => $tasksData,
        "pageContent" => $pageContent,
        "projectsId" => $projectsId,
        "link" => $link,
        "usersId" => $usersId,
        "addTask" => $addTask,
        "errors" => $errors
    ]
);

print $layoutContent;




