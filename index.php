<?php
require_once "functions.php";

$usersId = 1;
$projectsId = 0;
$titleError = false;
$dateError = false;
$errors = false;

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postedName = $_POST["name"];
    $postedDate = $_POST["date"];
    $postedFile = $_POST["preview"];
    if (empty($postedName)){
        $titleError = true;
        $errors = true;
    }
    if(!empty($postedDate)){
        $d = DateTime::createFromFormat('Y-m-d H:i', $postedDate);
        if(!($d && $d->format('Y-m-d H:i') == $postedDate)) {
            $dateError = true;
            $errors = true;
        }
    }
    if(empty($postedDate)) {
        $postedDate = NULL;
    }
    if(!empty($postedFile)) {
        $fileName = $postedFile;
        $filePath = __DIR__. "/uploads/";
        $fileUrl = '/uploads/' . $fileName;
        move_uploaded_file($_FILES['preview']['tmp_name'], $filePath . $fileName);
        $file = ("<a href='$fileUrl'>$fileName</a>");
    }
    if(!$errors) {
        $sql = "INSERT INTO tasks (title, file, projects_id, deadline, users_id) VALUES (?, ?, NULL , ?, ?)";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "sssi", $postedName, $file, $postedDate, $usersId);
        $res = mysqli_stmt_execute($stmt);
        var_dump($postedDate);
        var_dump($res);
    }
}


$pageContent = includeLayout(
    "templates" . DIRECTORY_SEPARATOR . "index.php",
    [
        "show_complete_tasks" => $show_complete_tasks,
        "tasksData" => $tasksData
    ]
);

$addTask = includeLayout(
    "templates" . DIRECTORY_SEPARATOR . "addtask.php",
    [
        "titleError" => $titleError,
        "dateError" => $dateError,
        "errors" => $errors,
        "postedName" => $postedName
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
        "usersId" => $usersId,
        "addTask" => $addTask,
        "errors" => $errors
    ]
);

print ($layoutContent);


