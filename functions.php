<?php


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

function includeLayout ($file, $data) {
    if (!file_exists($file)) {
        return '';
    }
    extract($data);
    ob_start();
    require_once($file);
    $content = ob_get_contents();
    ob_get_clean();

    return $content;
};
