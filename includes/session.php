<?php
session_start();

function setSessionData($key, $value) {
    $_SESSION[$key] = $value;
}

function getSessionData($key) {
    return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
}

function destroySessionData($key) {
    if (isset($_SESSION[$key])) {
        unset($_SESSION[$key]);
    }
}

function destroySession() {
    session_unset();
    session_destroy();
}
?>