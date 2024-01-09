<?php
include_once '../includes/auth.php';
include_once '../includes/session.php';
include_once '../includes/db.php';

$user_id = getSessionData('user_id');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_first_name = $_POST['new_first_name'];
    $new_last_name = $_POST['new_last_name'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    // Sprawdź, czy nowe hasło i potwierdzenie nowego hasła są identyczne
    if ($new_password !== $confirm_new_password) {
        setSessionData('error_message', 'Błąd: Nowe hasło i potwierdzenie nowego hasła nie są identyczne.');
        exit();
    }

    // Aktualizacja danych użytkownika w bazie danych
    $updateUserDataResult = updateUserProfile($user_id, $new_first_name, $new_last_name, $conn);
    $updateUserPasswordResult = updateUserPassword($user_id, $new_password, $conn);

    if ($updateUserDataResult && $updateUserPasswordResult) {
        setSessionData('success_message', 'Dane użytkownika zostały zaktualizowane!');
        header("Location: users.php");
        exit();
    } else {
        setSessionData('error_message', 'Błąd podczas aktualizacji danych użytkownika: ' . $conn->error);
        header("Location: users.php");
        exit();
    }
}
?>

<?php
    $pageTitle = "User Data";
    include '../includes/header.php';
?>

<main>
    <section>
        <h2>User Data</h2>
        <p>Welcome, <?php echo $user_data['first_name'] . ' ' . $user_data['last_name']; ?>!</p>

        <form action="users.php" method="post">
            <label for="new_first_name">New First Name:</label>
            <input type="text" id="new_first_name" name="new_first_name" value="<?php echo $user_data['first_name']; ?>" required>

            <label for="new_last_name">New Last Name:</label>
            <input type="text" id="new_last_name" name="new_last_name" value="<?php echo $user_data['last_name']; ?>">

            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required>

            <label for="confirm_new_password">Confirm New Password:</label>
            <input type="password" id="confirm_new_password" name="confirm_new_password" required>

            <button type="submit">Update Profile</button>
        </form>

        <p><a href="index.php">Back to Home</a></p>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
