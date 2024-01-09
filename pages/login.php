<?php
include_once '../includes/auth.php';
include_once '../includes/db.php';

// Jeżeli użytkownik jest już zalogowany, przekieruj go na stronę główną
if (isLoggedIn()) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $action = "Logowanie";

    if (empty($username) || empty($password)) {
        $error_message = "Wypełnij wszystkie pola!";
    } else {
        $user = getUserByUsername($username, $conn);

        if ($user && password_verify($password, $user['password'])) {
            loginUser($user['user_id'], $user['username']);
            addLog($user['user_id'], $action, $conn);
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Nieprawidłowe dane logowania!";
        }
    }
}
?>

<?php
$pageTitle = "Login";
include '../includes/header.php';
?>

<main>
    <section>
        <h2>Login</h2>
        <?php
        if (isset($error_message)) {
            echo "<p class='error'>$error_message</p>";
        }
        ?>
        <form action="login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>
    </section>
</main>

<?php
include '../includes/footer.php';
?>
