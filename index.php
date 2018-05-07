<?php
require_once "functions.php";
require "data.php";

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


