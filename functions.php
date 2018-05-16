<?php

// считает количество проектов по категориям
function getProjectsCount ($array, $projectsName) {
    $projectsCount = 0;
    if($projectsName == "Все") {
        return count($array);
    }
    foreach ($array as $key => $item) {
        if($projectsName == $item["projectType"]){
            $projectsCount++;
        }
    }
    return $projectsCount;
}

// добовляет шаблон
function includeLayout ($file, $data) {
    if (!file_exists($file)) {
        return "";
    }
    extract($data);
    ob_start();
    require_once "$file";
    $content = ob_get_contents();
    ob_get_clean();

    return $content;
};

// считает сколько часов осталось до дедлайна
function checkTimeLeft ($date) {
    $currentDate = strtotime(date("d.m.Y"));
    $hoursLeft = (strtotime($date) - $currentDate) / 3600;
    return $hoursLeft;
}

function getTasksDataById($link, $tasksId) {
    $sql = "SELECT * FROM tasks WHERE projects_id = $tasksId";
    $result = mysqli_query($link, $sql);

    if(mysqli_num_rows($result) !=0) {
        $tasksData = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $tasksData;
    }
    else {
        http_response_code(404);
        include("templates/error.php");
        exit();
    }
}

function getTasksData ($link) {
    $sql = "SELECT * FROM tasks";
    $result = mysqli_query($link, $sql);

    if($result) {
        $tasksData = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $tasksData;
    }
    else {
        exit(mysqli_error($link));
    }
}


function getProjectsTypes($link) {
    $sql = "SELECT * FROM projects";
    $result = mysqli_query($link, $sql);

    if($result) {
        $projectsTypes = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $projectsTypes;
    }
    else {
        exit(mysqli_error($link));
    }
}
