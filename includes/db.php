<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "animal_management_system_db";

// Utwórz połączenie z bazą danych
$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdź połączenie
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Funkcja do rejestracji nowego użytkownika
function registerUser($username, $password, $firstName, $lastName, $role_id, $conn) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO users (username, password, first_name, last_name, role_id) VALUES ('$username', '$hashedPassword', '$firstName', '$lastName', $role_id)";
    
    return $conn->query($query);
}

// Funkcja do pobierania danych użytkownika na podstawie nazwy użytkownika
function getUserByUsername($username, $conn) {
    $username = $conn->real_escape_string($username);

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

// Funkcja do pobierania ID roli na podstawie nazwy roli
function getRoleIdByName($roleName, $conn) {
    $roleName = $conn->real_escape_string($roleName);

    $query = "SELECT role_id FROM roles WHERE role_name = '$roleName'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['role_id'];
    } else {
        return null;
    }
}

// Pobierz ID roli użytkownika
function getUserRole($user_id, $conn) {
    $query = "SELECT role_id FROM users WHERE user_id = $user_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['role_id'];
    } else {
        return null;
    }
}

// Funkcja do pobierania danych użytkownika
function getUserData($user_id, $conn) {
    $query = "SELECT * FROM users WHERE user_id = $user_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return false;
    }
}

function updateUserProfile($user_id, $new_first_name, $new_last_name, $conn) {
    $update_query = "UPDATE users SET first_name = ?, last_name = ? WHERE user_id = ?";
    
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssi", $new_first_name, $new_last_name, $user_id);
    
    return $stmt->execute();
}

// Funkcja do aktualizacji hasła użytkownika w bazie danych
function updateUserPassword($user_id, $new_password, $conn) {
    // Hashowanie nowego hasła przed zapisem do bazy danych
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    $query = "UPDATE users SET password = '$hashed_password' WHERE user_id = $user_id";

    if ($conn->query($query) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Funkcja do dodawania nowego zwierzęcia do bazy danych
function addAnimal($name, $species, $birthdate, $user_id, $description, $conn) {
    $name = $conn->real_escape_string($name);
    $species = $conn->real_escape_string($species);
    $birthdate = $conn->real_escape_string($birthdate);
    $user_id = intval($user_id);
    $description = $conn->real_escape_string($description);

    $query = "INSERT INTO animals (name, species, birthdate, owner_id, description) VALUES ('$name', '$species', '$birthdate', $user_id, '$description')";

    return $conn->query($query);
}

// Funkcja do aktualizacji danych zwierzęcia w bazie danych
function updateAnimal($animal_id, $name, $species, $birthdate, $description, $conn) {
    $name = $conn->real_escape_string($name);
    $species = $conn->real_escape_string($species);
    $birthdate = $conn->real_escape_string($birthdate);
    $description = $conn->real_escape_string($description);

    $query = "UPDATE animals SET name = '$name', species = '$species', birthdate = '$birthdate', description = '$description' WHERE animal_id = $animal_id";

    return $conn->query($query);
}

// Dodaj tę funkcję do pliku db.php
function addLog($user_id, $action, $conn) {
    $query = "INSERT INTO logs (user_id, action, timestamp) VALUES (?, ?, NOW())";

    $stmt = $conn->prepare($query);

    $stmt->bind_param("is", $user_id, $action);

    return $stmt->execute();
}

// Pobierz dane o zwierzęciu
function getAnimalDataById($animal_id, $conn) {
    $query = "SELECT * FROM animals WHERE animal_id = $animal_id";

    $result = $conn->query($query);

    if ($result === false) {
        die("Błąd zapytania: " . $conn->error);
    }

    // Sprawdź, czy znaleziono zwierzę
    if ($result->num_rows > 0) {
        $animalData = $result->fetch_assoc();
        return $animalData;
    } else {
        return null;
    }
}

// Pobierz listę zwierząt dla danego użytkownika
function getAnimalsByUserId($user_id, $conn)
{
    $query = "SELECT * FROM animals WHERE owner_id = $user_id";
    $result = $conn->query($query);

    if ($result === false) {
        die("Błąd zapytania: " . $conn->error);
    }

    // Sprawdź, czy są jakieś zwierzęta
    if ($result->num_rows > 0) {
        $animals = $result->fetch_all(MYSQLI_ASSOC);
        return $animals;
    } else {
        return [];
    }
}

// Pobierz wszystkie zwierzęta
function getAllAnimals($conn) {
    $query = "SELECT * FROM animals";
    $result = $conn->query($query);

    // Sprawdź, czy zapytanie się powiodło
    if ($result === false) {
        die("Błąd zapytania: " . $conn->error);
    }

    $animals = [];
    while ($row = $result->fetch_assoc()) {
        $animals[] = $row;
    }

    return $animals;
}

// Funkcja do usuwania zwierzęcia z bazy danych
function deleteAnimal($animal_id, $conn) {
    $animal_id = intval($animal_id); // Przyjmujemy, że animal_id jest liczbą całkowitą
    $query = "DELETE FROM animals WHERE animal_id = $animal_id";

    return $conn->query($query);
}
?>