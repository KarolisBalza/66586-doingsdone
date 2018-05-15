<?php
require_once "functions.php";
require "data.php";

$con = mysqli_connect("localhost", "root", "", "doingsdone");

if(!$con) {
    print(mysqli_connect_error());
}
else {
    $sql = "SELECT title FROM projects WHERE users_id = 1";
    $result = mysqli_query($con, $sql);

    if($result) {
        $projectsTypes = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    else {
        print(mysqli_error($con));
    }

    $sql = "SELECT * FROM tasks WHERE projects_id = 4";
    $result = mysqli_query($con, $sql);

    if($result) {
        $tasksData = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    else {
        print(mysqli_errno($con));
    }
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


