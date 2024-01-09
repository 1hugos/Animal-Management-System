<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Animal Management System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <header>
        <h1>Animal Management System</h1>
        <nav>
            <ul>
                <?php
                include_once '../includes/session.php';
                include_once '../includes/db.php';
                // Dodaj powitanie z imieniem zalogowanego użytkownika
                if (isset($_SESSION['user_id'])) {
                    $user_data = getUserData($_SESSION['user_id'], $conn);
                    echo '<li>Welcome, ' . $user_data['first_name'] . '!</li>';
                }
                ?>
                
                <li><a href="index.php">Home</a></li>

                <?php
                // Dodaj przyciski "Register" i "Login" tylko jeśli użytkownik nie jest zalogowany
                if (!isset($_SESSION['user_id'])) {
                    echo '<li><a href="register.php">Register</a></li>';
                    echo '<li><a href="login.php">Login</a></li>';
                } else {
                    echo '<li><a href="users.php">User Data</a></li>';
                    echo '<li><a href="logout.php">Logout</a></li>';
                }
                ?>
            </ul>
        </nav>
    </header>

    <main>
    
    <div class="message-container">
        <div class="error-container">
            <?php
            if ($error_message = getSessionData('error_message')) {
                echo "<p id='error_message' class='error'>$error_message</p>";
                destroySessionData('error_message');
            }
            ?>
        </div>
        <div class="success-container">
            <?php
            if ($success_message = getSessionData('success_message')) {
                echo "<p id='success_message' class='success'>$success_message</p>";
                destroySessionData('success_message');
            }
            ?>
        </div>
    </div>

<script>
    setTimeout(function() {
        const errorMessage = document.getElementById('error_message');
        const successMessage = document.getElementById('success_message');

        if (errorMessage) {
            errorMessage.style.display = 'none';
        }

        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 3000);
</script>