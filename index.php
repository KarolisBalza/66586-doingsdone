<?php
require_once "functions.php";

$userId = 1;

$link = mysqli_connect("localhost", "root", "", "doingsdone");
mysqli_set_charset($link, "utf8");

if(!$link) {
    exit(mysqli_connect_error());
}
else {
    $projectsTypes = getProjectsTypes($link, $userId);
    $tasksData = getTasksData($link, $userId);
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


