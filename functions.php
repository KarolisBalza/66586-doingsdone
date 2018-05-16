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

function getProjectsTypes($link, $user) {
    $sql = "SELECT title FROM projects WHERE users_id = $user";
    $result = mysqli_query($link, $sql);

    if($result) {
        $projectsTypes = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $projectsTypes;
    }
    else {
        exit(mysqli_error($link));
    }
}

function getTasksData ($link, $user) {
    $sql = "SELECT * FROM tasks WHERE users_id = $user";
    $result = mysqli_query($link, $sql);

    if($result) {
        $tasksData = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $tasksData;
    }
    else {
        exit(mysqli_error($link));
    }
}
