<?php
include_once '../includes/session.php';

function loginUser($user_id, $username) {
    setSessionData('user_id', $user_id);
    setSessionData('username', $username);
}

function logoutUser() {
    destroySession();
}

function isLoggedIn() {
    return getSessionData('user_id') !== null;
}
?>
