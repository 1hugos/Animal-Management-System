<?php
include_once '../includes/auth.php';
include_once '../includes/db.php';
include_once '../sql/backup_db.php';

// Stwórz zrzut bazy danych
createDatabaseBackup($conn, $servername, $username, $password, $dbname);

// Wyloguj użytkownika
logoutUser();

// Przekieruj użytkownika na stronę główną
header("Location: index.php");
exit();
?>