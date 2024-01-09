<?php
include_once '../includes/db.php';

function createDatabaseBackup($conn, $servername, $username, $password, $dbname) {
    // Katalog, do którego zostanie zapisany zrzut bazy danych
    $backupDirectory = '../sql/';

    // Nazwa pliku, do którego zostanie zapisany zrzut bazy danych
    $backupFile = $backupDirectory . 'backup_' . date('Y-m-d_H-i-s') . '.sql';

    // Pełna ścieżka do mysqldump
    $mysqldumpPath = 'D:\\XAMPP\\mysql\\bin\\mysqldump';

    // Komenda SQL do utworzenia zrzutu bazy danych
    $command = "$mysqldumpPath --host=$servername --user=$username --password=$password --databases $dbname > $backupFile 2>&1";

    // Wykonanie komendy
    exec($command, $output, $resultCode);

    // Sprawdzenie czy zrzut został utworzony poprawnie
    if ($resultCode === 0) {
        echo "Zrzut bazy danych został utworzony pomyślnie.";
    } else {
        echo "Błąd podczas tworzenia zrzutu bazy danych:<br>";
        echo "<pre>" . implode("\n", $output) . "</pre>";
    }

    // Zamknięcie połączenia z bazą danych
    $conn->close();
}

?>