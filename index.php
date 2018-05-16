<?php
require_once "functions.php";

$link = mysqli_connect("localhost", "root", "", "doingsdone");
mysqli_set_charset($link, "utf8");

if(!$link) {
    exit(mysqli_connect_error());
}
else {
    if (isset($_GET["id"])) {
        $id = (int) $_GET["id"];
        $tasksData = getTasksDataById($link, $id);
    }
    else {
        $tasksData = getTasksData($link);
    }
    $projectsTypes = getProjectsTypes($link);
}


$pageContent = includeLayout(
    "templates" . DIRECTORY_SEPARATOR . "index.php",
    [
    "show_complete_tasks" => $show_complete_tasks,
    "tasksData" => $tasksData
    ]
);

$layoutContent = includeLayout(
    "templates" . DIRECTORY_SEPARATOR ."layout.php",
    [
    "title" => "Дела в Порядке",
    "projectsTypes" => $projectsTypes,
    "tasksData" => $tasksData,
    "pageContent" => $pageContent
    ]
);

print ($layoutContent);


