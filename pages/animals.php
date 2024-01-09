<?php
include_once '../includes/auth.php';
include_once '../includes/db.php';

$error_message = '';
$success_message = '';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'];
    $species = $_POST['species'];
    $birthdate = $_POST['birthdate'];
    $description = $_POST['description'];

    if (empty($name) || empty($species) || empty($birthdate)) {
        $error_message = "Wypełnij wszystkie wymagane pola!";
    } else {
        $user_id = getSessionData('user_id');

        // Dodaj zwierzę do bazy danych
        if (addAnimal($name, $species, $birthdate, $user_id, $description, $conn)) {
            $success_message = "Zwierzę zostało dodane pomyślnie!";

            setSessionData('success_message', $success_message);

            header("Location: index.php");
            exit();
        } else {
            $error_message = "Błąd podczas dodawania zwierzęcia: " . $conn->error;

            setSessionData('error_message', $error_message);
        }
    }
}
?>

<?php
$pageTitle = "Add Animal";
include '../includes/header.php';
?>

<main>
    <section>
        <h2>Add Animal</h2>
        <form action="animals.php" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="species">Species:</label>
            <input type="text" id="species" name="species" required>

            <label for="birthdate">Birthdate:</label>
            <input type="date" id="birthdate" name="birthdate" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4"></textarea>

            <button type="submit">Add Animal</button>
        </form>
    </section>
</main>

<?php
include '../includes/footer.php';
?>
