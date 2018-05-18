<?php
require_once "functions.php";

$usersId = 1;
$projectsId = 0;

$link = mysqli_connect("localhost", "root", "", "doingsdone");
mysqli_set_charset($link, "utf8");

if(!$link) {
    exit(mysqli_connect_error());
}
else {
    $projectsTypes = getProjectsTypes($link, $usersId);
    array_unshift($projectsTypes, ["id" => 0, "title" => "Входяшие"]);
    if (isset($_GET["id"])) {
        $projectsId = (int) $_GET["id"];
    }
    $tasksData = getTasksDataById($link, $projectsId, $usersId);
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
    "pageContent" => $pageContent,
        "projectsId" => $projectsId,
        "link" => $link,
        "usersId" => $usersId
    ]
);

print ($layoutContent);


