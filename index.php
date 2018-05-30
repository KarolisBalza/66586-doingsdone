<?php


require_once "functions.php";
require_once "init.php";

session_start();

$show_complete_tasks = "";

if (isset($_GET["page"])) {
    if ($_GET["page"] === "logout") {
        if (isset($_SESSION["user"])) {
            $_SESSION = [];
        }
    }
}


if (isset($_SESSION["user"]["id"])) {
    $usersId = $_SESSION["user"]["id"];
}else {
    $usersId = 0;
}


$projectsId = null;
$postedName = "";
$postedTitle = "";
$errors =
    [
        "titleError" => false,
        "dateError" => false,
        "errors" => false,
        "projectExists" => false,
        "projectEmpty" => false
    ];
$addProjectErrors = [
    "emptyTitle" => false,
    "projectExists" => false,
    "errors" => false
];

if (!isset($_SESSION["user"])) {
    header("Location: guest.php");
}

if (isset($_GET["page"])) {
    if ($_GET["page"] != "registration") {
        header("Location: guest.php");
    }
}

if (isset($_GET["page"])) {
    if ($_GET["page"] === "login") {
        header("Location: login.php");
    }
}

if (isset($_GET["page"])) {
    if ($_GET["page"] === "registration") {
        header("Location: registration.php");
    }
}

if (isset($_SESSION["user"])) {
    $usersName = getUsersNameById($link, $_SESSION["user"]["id"]);
}

if (isset($_GET["task_id"])) {
    $taskId = $_GET["task_id"];
    checkTaskAsDone($link, $taskId);
}

$projectsTypes = [];

if (isset($_SESSION["user"])) {
    if (!$link) {
        exit(mysqli_connect_error());
    } else {
        $projectsTypes = getProjectsTypes($link, $usersId);
        if (isset($_GET["id"])) {
            $projectsId = (int)$_GET["id"];
        }
        $tasksData = getTasksDataById($link, $projectsId, $usersId);
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" AND isset($_POST["task_add"])) {
    $fileUrl = NULL;
    $postedName = $_POST["name"];
    $postedDate = $_POST["date"];
    if (!empty($_POST["project"])) {
        $postedProject = $_POST["project"];
    }
    if (isset($_POST["preview"])) {
        $postedFile = $_POST["preview"];
    }
    if (empty($postedName)) {
        $errors["titleError"] = true;
        $errors["errors"] = true;
    }
    if (empty($postedProject)) {
        $postedProject = NULL;
    }
    if (!checkDatesValidity($postedDate)) {
        $errors["dateError"] = true;
        $errors["errors"] = true;
    }
    if (empty($postedDate)) {
        $postedDate = NULL;
    }

    if (!$errors["errors"]) {
        if (!empty($_FILES["preview"]["name"])) {
            $fileUrl = uploadFile($_FILES);
        }
        addNewTask($link, $postedName, $fileUrl, $postedProject, $postedDate, $usersId);
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" AND isset($_POST["project_add"])) {
    $projectName = $_POST["name"];
    if (empty($projectName)) {
        $addProjectErrors["emptyTitle"] = true;
        $addProjectErrors["errors"] = true;
    } else {
        if (addNewProject($link, $usersId, $projectName)) {
            header("Location: " . "index.php?id=1");
        } else {
            $postedTitle = $projectName;
            $addProjectErrors["projectExists"] = true;
            $addProjectErrors["errors"] = true;
        }
    }
}


if (isset($_GET["today"])) {
    $tasksData = getTasksDataByDate($link, $usersId, $projectsId, "today");
} else if (isset($_GET["tomorrow"])) {
    $tasksData = getTasksDataByDate($link, $usersId, $projectsId, "tomorrow");
} else if (isset($_GET["failed"])) {
    $tasksData = getTasksDataByDate($link, $usersId, $projectsId, "failed");
} else {
    $tasksData = getTasksDataById($link,  $projectsId, $_SESSION["user"]["id"] ?? 0);
}

if(isset($_GET["show_completed"])){
    if($_GET["show_completed"] === "1"){
        $show_complete_tasks = 1;
    }
    else $show_complete_tasks = 0;
}

$pageContent = includeLayout(
    "templates" . DIRECTORY_SEPARATOR . "index.php",
    [
        "show_complete_tasks" => $show_complete_tasks,
        "tasksData" => $tasksData,
        "projectsId" => $projectsId
    ]
);

$addTask = includeLayout(
    "templates" . DIRECTORY_SEPARATOR . "addtask.php",
    [
        "projectsTypes" => $projectsTypes,
        "errors" => $errors,
        "postedName" => $postedName
    ]
);

$addProject = includeLayout(
    "templates" . DIRECTORY_SEPARATOR . "addproject.php",
    [
        "addProjectErrors" => $addProjectErrors,
        "postedTitle" => $postedTitle
    ]
);

$layoutContent = includeLayout(
    "templates" . DIRECTORY_SEPARATOR . "layout.php",
    [
        "title" => "Дела в Порядке",
        "usersName" => $usersName ?? "",
        "projectsTypes" => $projectsTypes,
        "tasksData" => $tasksData,
        "pageContent" => $pageContent,
        "projectsId" => $projectsId,
        "link" => $link,
        "usersId" => $usersId,
        "addTask" => $addTask,
        "addProject" => $addProject,
        "errors" => $errors,
        "addProjectErrors" => $addProjectErrors
    ]
);

print $layoutContent;




