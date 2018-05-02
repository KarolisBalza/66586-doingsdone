<?php
require_once ("functions.php");

$show_complete_tasks = rand(0, 1);

$projectsTypes = ["Все", "Входящие", "Учеба", "Работа", "Домашние дела", "Авто"];

$tasksData = [
  [
        "name" => "Собеседование в IT компании",
        "date" => "01.06.2018",
        "projectType" => $projectsTypes[3],
        "isDone" => false
  ],
  [
        "name" => "Выполнить тестовое задание",
        "date" => "25.05.2018",
        "projectType" => $projectsTypes[3],
        "isDone" => false
  ],
  [
        "name" => "Сделать задание первого раздела",
        "date" => "21.04.2018",
        "projectType" => $projectsTypes[2],
        "isDone" => true
  ],
  [
        "name" => "Встреча с другом",
        "date" => "22.04.2018",
        "projectType" => $projectsTypes[1],
        "isDone" => false
  ],
  [
        "name" => "Купить корм для кота",
        "date" => "",
        "projectType" => $projectsTypes[4],
        "isDone" => false
  ],
  [
        "name" => "Заказать пиццу",
        "date" => "",
        "projectType" => $projectsTypes[4],
        "isDone" => false
  ],
];

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

$pageContent = includeLayout("templates\layout.php", $projectsTypes, $tasksData);


print ("$pageContent");


