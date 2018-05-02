<?php

function includeLayout ($file, $projectsTypes, $tasksData) {
    ob_start();
    require_once ($file);
    $content = ob_get_contents();
    ob_get_clean();
    return $content;
};
