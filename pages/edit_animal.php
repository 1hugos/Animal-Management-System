<?php
include_once '../includes/auth.php';
include_once '../includes/db.php';

$error_message = '';
$success_message = '';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

// Sprawdź, czy przekazano identyfikator zwierzęcia
if (!isset($_GET['animal_id'])) {
    header("Location: index.php");
    exit();
}

$animal_id = $_GET['animal_id'];

// Pobierz dane zwierzęcia do edycji
$animalData = getAnimalDataById($animal_id, $conn);

// Sprawdź, czy zwierzę istnieje
if (!$animalData) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $species = $_POST['species'];
    $birthdate = $_POST['birthdate'];
    $description = $_POST['description'];
    $action = "Edycja danych zwięrzecia.";

    if (empty($name) || empty($species) || empty($birthdate)) {
        $error_message = "Wypełnij wszystkie wymagane pola!";
    } else {
        // Aktualizuj dane zwierzęcia w bazie danych
        if (updateAnimal($animal_id, $name, $species, $birthdate, $description, $conn)) {
            $success_message = "Dane zwierzęcia zostały zaktualizowane pomyślnie!";

            setSessionData('success_message', $success_message);

            $user_id = getSessionData('user_id');
            addLog($user_id, $action, $conn);

            header("Location: index.php");
            exit();
        } else {
            $error_message = "Błąd podczas aktualizacji danych zwierzęcia: " . $conn->error;

            setSessionData('error_message', $error_message);
        }
    }
}

$pageTitle = "Edit Animal";
include '../includes/header.php';
?>

<main>
    <section>
        <h2>Edit Animal</h2>
        <form action="edit_animal.php?animal_id=<?php echo $animal_id; ?>" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name_edit" name="name" required value="<?php echo $animalData['name']; ?>">

            <label for="species">Species:</label>
            <input type="text" id="species_edit" name="species" required value="<?php echo $animalData['species']; ?>">

            <label for="birthdate">Birthdate:</label>
            <input type="date" id="birthdate_edit" name="birthdate" required value="<?php echo $animalData['birthdate']; ?>">

            <label for="description">Description:</label>
            <textarea id="description_edit" class="description-area" name="description" rows="4"><?php echo $animalData['description']; ?></textarea>

            <button type="submit">Save Changes</button>
        </form>
    </section>
</main>

<?php
include '../includes/footer.php';
?>
