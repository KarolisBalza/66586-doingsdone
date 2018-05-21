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

function getProjectsCount ($link, $projectsId, $usersId) {
    if (!$projectsId) {
        $sql = "SELECT * FROM tasks WHERE projects_id IS NULL AND users_id = ?";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, 'i',$usersId);
    }
    else {
        $sql = "SELECT * FROM tasks WHERE projects_id = ? AND users_id = ?";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, 'ii',$projectsId,$usersId);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $count = mysqli_num_rows($result);
    return $count;
};

/**
 * Добовляет шаблон
 *
 * @param string $file Ссылка на шаблон
 * @param array $data Данные для вставки в шаблон
 *
 * @return string Подготовленный шаблон
 */
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


/**
 * Считает сколько часов осталось до дедлайна
 *
 * @param string $date дедлайн задачи
 *
 * @return string количество часов до дедлайна
 */
function checkTimeLeft ($date) {
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

function getTasksDataById($link, $projectsId, $usersId) {
    if (!$projectsId) {
        $sql = "SELECT *, DATE_FORMAT(deadline, '%Y-%m-%d') AS deadline FROM tasks WHERE projects_id IS NULL AND users_id = ?";
        $stmt = db_get_prepare_stmt($link, $sql, [$usersId]);
    }
    else {
        $sql = "SELECT *, DATE_FORMAT(deadline, '%Y-%m-%d') AS deadline FROM tasks WHERE projects_id = ? AND users_id = ?";
        $stmt = db_get_prepare_stmt($link, $sql, [$projectsId, $usersId]);
    }
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $result = mysqli_fetch_all($res, MYSQLI_ASSOC);

    if(count($result) !=0 ) {
        $tasksData = $result;
        return $tasksData;
    }
    else {
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
function getProjectsTypes($link, $usersId) {
    $sql = "SELECT * FROM projects WHERE users_id = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "i", $usersId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if($result) {
        $projectsTypes = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $projectsTypes;
    }
    else {
        return [];
    }
}

function checkDatesValidity($date) {
    $isValid = false;
    $d = DateTime::createFromFormat('Y-m-d H:i', $date);
    if (($d && $d->format('Y-m-d H:i') == $date) OR empty($date)) {
        $isValid = true;
    }
    return $isValid;
}

function uploadFile($file) {
    if(!file_exists("uploads")) {
        mkdir("uploads", 0777, true);
    }
    $fileName = str_replace(' ', '-', $file['preview']['name']);
    $fileName = preg_replace('/[^A-Za-z0-9.\-]/', '', $file['preview']['name']);
    $fileUrl = '/uploads/' . $fileName;
    move_uploaded_file($file['preview']['tmp_name'], "uploads/" . $fileName );
    return $fileUrl;
}

