<?php

include_once '../includes/db.php';

$error_msg = '';

function processRegistration($conn) {
    global $error_msg;

    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $confirm_password = htmlspecialchars($_POST['confirm_password']);
    $first_name = htmlspecialchars($_POST['first_name']);
    $last_name = htmlspecialchars($_POST['last_name']);

    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error_msg = "Wypełnij wszystkie pola!";
    } elseif ($password !== $confirm_password) {
        $error_msg = "Hasła nie są identyczne!";
    } else {
        $roleName = 'user';
        $role_id = getRoleIdByName($roleName, $conn);

        if ($role_id !== null) {
            if (registerUser($username, $password, $first_name, $last_name, $role_id, $conn)) {
                header("Location: index.php");
                exit();
            } else {
                $error_msg = "Błąd podczas rejestracji użytkownika: " . $conn->error;
            }
        } else {
            $error_msg = "Błąd: Nie można znaleźć roli 'user'.";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    processRegistration($conn);
}

$pageTitle = "Register";
include '../includes/header.php';
?>

<main>
    <section>
        <h2>Register</h2>
        <div class="message-container">
            <?php
            if (!empty($error_msg)) {
                echo '<div class="error-container">';
                    echo "<p id='error_message' class='error'>$error_msg</p>";
                echo '</div>';
            }
            ?>
        </div>

        <form action="register.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required autocomplete="username">

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required autocomplete="new-password">

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required autocomplete="new-password">

            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" autocomplete="given-name">

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" autocomplete="family-name">

            <button type="submit">Register</button>
        </form>
    </section>
</main>

<?php
include '../includes/footer.php';
?>
