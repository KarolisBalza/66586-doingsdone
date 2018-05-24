<?php

require_once "mysql_helper.php";

/**
 * Считает количество проектов по категориям
 *
 * @param mysqli $link Ресурс соединения
 * @param int $projectsId ID проекта
 * @param int $usersId ID пользователя
 *
 * @return int $count количество проектов
 */

function getProjectsCount($link, $projectsId, $usersId)
{
    if (!$projectsId) {
        $sql = "SELECT * FROM tasks WHERE projects_id IS NULL AND users_id = ?";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $usersId);
    } else if($projectsId == 1) {
        $sql = "SELECT * FROM tasks WHERE users_id = ?";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $usersId);
    } else {
        $sql = "SELECT * FROM tasks WHERE projects_id = ? AND users_id = ?";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, 'ii', $projectsId, $usersId);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $count = mysqli_num_rows($result);
    return $count;
}


/**
 * Добовляет шаблон
 *
 * @param string $file Ссылка на шаблон
 * @param array $data Данные для вставки в шаблон
 *
 * @return string Подготовленный шаблон
 */
function includeLayout($file, $data)
{
    if (!file_exists($file)) {
        return "";
    }
    extract($data);
    ob_start();
    require_once "$file";
    $content = ob_get_contents();
    ob_get_clean();

    return $content;
}


/**
 * Считает сколько часов осталось до дедлайна
 *
 * @param string $date дедлайн задачи
 *
 * @return string количество часов до дедлайна
 */
function checkTimeLeft($date)
{
    $currentDate = strtotime(date("d.m.Y"));
    $hoursLeft = (strtotime($date) - $currentDate) / 3600;
    return $hoursLeft;
}

/**
 * Получает данные задач для конкретного пользователя и конкретного проекта
 *
 * @param mysqli $link Ресурс соединения
 * @param int $projectsId ID проекта
 * @param int $usersId ID пользователя
 *
 * @return array данные задач для конкретного пользователя и конкретного проекта
 */

function getTasksDataById($link, $projectsId, $usersId)
{
    if (!$projectsId) {
        $sql = "SELECT *, DATE_FORMAT(deadline, '%Y-%m-%d') AS deadline FROM tasks WHERE projects_id IS NULL AND users_id = ?";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "i", $usersId);
    } else if($projectsId == 1) {
        $sql = "SELECT *, DATE_FORMAT(deadline, '%Y-%m-%d') AS deadline FROM tasks WHERE users_id = ?";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "i", $usersId);
    } else {
        $sql = "SELECT *, DATE_FORMAT(deadline, '%Y-%m-%d') AS deadline FROM tasks WHERE projects_id = ? AND users_id = ?";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $projectsId, $usersId);
    }
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $result = mysqli_fetch_all($res, MYSQLI_ASSOC);

    if (count($result) != 0) {
        $tasksData = $result;
        return $tasksData;
    } else {
        return [];
    }
}

/**
 * Получает проекты для конкретного пользователя
 *
 * @param mysqli $link Ресурс соединения
 * @param int $usersId ID пользователя
 *
 * @return array проекты для конкретного пользователя
 */
function getProjectsTypes($link, $usersId)
{
    $sql = "SELECT * FROM projects WHERE users_id = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "i", $usersId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $projectsTypes = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $projectsTypes;
    } else {
        return [];
    }
}

function checkDatesValidity($date)
{
    $isValid = false;
    $d = DateTime::createFromFormat('Y-m-d H:i', $date);
    if (($d && $d->format('Y-m-d H:i') == $date) OR empty($date)) {
        $isValid = true;
    }
    return $isValid;
}

function uploadFile($file)
{
    if (!file_exists("uploads")) {
        mkdir("uploads", 0777, true);
    }
    $fileName = str_replace(' ', '-', $file['preview']['name']);
    $fileName = preg_replace('/[^A-Za-z0-9.\-]/', '', $file['preview']['name']);
    $fileUrl = '/uploads/' . $fileName;
    move_uploaded_file($file['preview']['tmp_name'], "uploads/" . $fileName);
    return $fileUrl;
}

function checkIfEmailExists($link, $email)
{
    $sql = "SELECT email FROM users WHERE email = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_num_rows($result);
}

function checkIfPasswordCorrect($link, $email, $password)
{
    $sql = "SELECT password FROM users WHERE email = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $passwordFromDb = mysqli_fetch_assoc($result);
    return password_verify($password, $passwordFromDb["password"]);
}

function getUsersIdByEmail($link, $email)
{
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

function addNewUser($link, $email, $password, $name)
{
    $password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (email, password, name) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $email, $password, $name);
    $res = mysqli_stmt_execute($stmt);
    return $res;
}

function getUsersNameById($link, $usersId)
{
    $sql = "SELECT name FROM users WHERE id = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "s", $usersId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

function addNewProject($link, $usersId, $projectName)
{
    $sql = "SELECT title FROM projects WHERE users_id = ? AND title = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'is', $usersId, $projectName);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) == 0) {
        $sql = "INSERT INTO projects (title, users_id) VALUES (?, ?)";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "si", $projectName, $usersId);
        return mysqli_stmt_execute($stmt);
    }
    return false;
}

function checkTaskAsDone($link, $taskId)
{
    $sql = "UPDATE tasks SET doneDate = NOW() WHERE id = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $taskId);
    mysqli_stmt_execute($stmt);
}

function getTasksDataByDate($link, $usersId, $date)
{
    switch ($date) {
        case "today":
            $sql = "SELECT *, DATE_FORMAT(deadline, '%Y-%m-%d') AS deadline FROM tasks WHERE users_id = ? AND STR_TO_DATE(CURDATE(), \"%Y-%m-%d\") = STR_TO_DATE(deadline, \"%Y-%m-%d\")";
            break;
        case "tomorrow":
            $sql = "SELECT *, DATE_FORMAT(deadline, '%Y-%m-%d') AS deadline FROM tasks WHERE users_id = ? AND STR_TO_DATE(CURDATE() + INTERVAL 1 DAY, \"%Y-%m-%d\") = STR_TO_DATE(deadline, \"%Y-%m-%d\")";
            break;
        case "failed":
            $sql = "SELECT *, DATE_FORMAT(deadline, '%Y-%m-%d') AS deadline FROM tasks WHERE users_id = ? AND STR_TO_DATE(CURDATE(), \"%Y-%m-%d\") > STR_TO_DATE(deadline, \"%Y-%m-%d\") AND doneDate = NULL";
            break;
    }
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "i", $usersId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $result = mysqli_fetch_all($res, MYSQLI_ASSOC);
    return $result;
}



