<?php
include_once '../includes/auth.php';
include_once '../includes/session.php';
include_once '../includes/db.php';

if (!isLoggedIn()) {
    destroySession();
    header("Location: home.php");
    exit();
}

$user_id = getSessionData('user_id');
$role = getUserRole($user_id, $conn);

$pageTitle = "Animal List";
include '../includes/header.php';

// Sprawdź, czy żądanie to żądanie usuwania zwierzęcia
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_animal') {
    if (!isset($_POST['animal_id'])) {
        die('Brak animal_id.');
    }

    $animal_id = $_POST['animal_id'];
    if (deleteAnimal($animal_id, $conn)) {
        setSessionData('success_message', 'Zwierzę zostało usunięte pomyślnie!');
        header("Location: index.php");
        exit();
    } else {
        setSessionData('error_message', 'Błąd podczas usuwania zwierzęcia.');
        header("Location: index.php");
        exit();
    }
}
?>

<main>
    <section class="animals-container">
        <div class="header-container">
            <h2 class="header-list">Animal List</h2>
            <div class="button-container">
                <a href="animals.php" class="add-button">Add Animal</a>
            </div>
        </div>
        
        <?php
        if ($error_message = getSessionData('error_message')) {
            echo "<p class='error'>$error_message</p>";
            destroySessionData('error_message');
        }

        if ($success_message = getSessionData('success_message')) {
            echo "<p class='success'>$success_message</p>";
            destroySessionData('success_message');
        }

        // Pobierz listę zwierząt w zależności od roli użytkownika
        if ($role === "2") {
            $animals = getAllAnimals($conn);
        } else {
            $animals = getAnimalsByUserId($user_id, $conn);
        }

        // Wyświetl listę dostępnych zwierząt w formie tabeli
        echo '<table class="animal-table">';

        echo '<thead>';
        echo '<tr>';
        echo '<th>Name</th>';
        echo '<th>Species</th>';
        echo '<th>Birthdate</th>';
        echo '<th>Description</th>';
        echo '</tr>';
        echo '</thead>';

        foreach ($animals as $animal) {
            echo '<tr>';
            echo '<td>' . $animal['name'] . '</td>';
            echo '<td>' . $animal['species'] . '</td>';
            echo '<td>' . $animal['birthdate'] . '</td>';
            echo '<td class="description-form">' . $animal['description'] . '</td>';
            echo '<td class="action-cell">';
            echo '<form class="action-form" method="post" action="../pdf/pdf_generator.php">';
            echo '<input type="hidden" name="action" value="generate_animal_info_pdf">';
            echo '<input type="hidden" name="animal_id" value="' . $animal['animal_id'] . '">';
            echo '<button type="submit" class="pdf-button">PDF</button>';
            echo '</form>';
            echo '<a href="edit_animal.php?animal_id=' . $animal['animal_id'] . '" class="edit-button">Edit</a>';
            echo '<form class="action-form" method="post" action="index.php">';
            echo '<input type="hidden" name="action" value="delete_animal">';
            echo '<input type="hidden" name="animal_id" value="' . $animal['animal_id'] .'">';
            echo '<button type="submit" class="delete-button">Delete</button>';
            echo '</form>';
            echo '</td>';          
            echo '</tr>';
        }     
        echo '</table>';
        ?>

    </section>
</main>

<?php
    include '../includes/footer.php';
?>
