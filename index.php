<?php
require_once "functions.php";

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


$pageContent = includeLayout("templates" . DIRECTORY_SEPARATOR . "index.php", [
    "show_complete_tasks" => $show_complete_tasks,
    "tasksData" => $tasksData ]);

$layoutContent = includeLayout("templates" . DIRECTORY_SEPARATOR ."layout.php", [
    "title" => "Дела в Порядке",
    "projectsTypes" => $projectsTypes,
    "tasksData" => $tasksData,
    "pageContent" => $pageContent]);



print ($layoutContent);


