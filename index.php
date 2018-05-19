<?php
require_once "functions.php";

$usersId = 1;
$projectsId = 0;
$errors =
    [
        "titleError" => false,
        "dateError" => false,
        "errors" => false
    ];

$link = mysqli_connect("localhost", "root", "", "doingsdone");
mysqli_set_charset($link, "utf8");


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

if ($_SERVER["REQUEST_METHOD"] == "POST" AND $_POST["tasks_add"] = "Добавить") {
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
                header("Location: " . "http://66586-doingsdone/index.php?id=0");
            } else {
                header("Location: " . "http://66586-doingsdone/index.php?id=" . $postedProject);
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

print ($layoutContent);


