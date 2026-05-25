<?php
session_start();
$host = 'localhost';
$dbName = 'demo';
$dbUser = 'root';
$dbPass = '';
$username = '';
$password = '';
$loginSuccess = false;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $conn = new mysqli($host, $dbUser, $dbPass, $dbName);
    if ($conn->connect_error) {
        $message = 'Database connection failed: ' . $conn->connect_error;
    } else {
        $stmt = $conn->prepare('SELECT * FROM users WHERE username = ? AND password = ?');
        if ($stmt) {
            $stmt->bind_param('ss', $username, $password);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows === 1) {
                $user = $result->fetch_assoc();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'] ?? '';
                $_SESSION['first_name'] = $user['first_name'] ?? '';
                $_SESSION['last_name'] = $user['last_name'] ?? '';
                header('Location: profile.php');
                exit;
            } else {
                $message = 'Invalid username or password.';
            }

            $stmt->close();
        } else {
            $message = 'Failed to prepare login query.';
        }

        $conn->close();
    }
}

function safe($value) {
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="container">
        <h1>Secure Login Page</h1>

        <form method="POST" action="login-secure.php" class="login-form">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo safe($username); ?>" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>

        <?php if ($message): ?>
            <div class="message"><?php echo safe($message); ?></div>
        <?php endif; ?>

        <section class="js-demo">
            <h2>JavaScript Demo</h2>
            <p>This page uses <code>textContent</code> and avoids <code>eval()</code> completely.</p>
            <textarea id="js-input" placeholder="Type a message" rows="4"></textarea>
            <button id="js-run">Show Safe Message</button>
            <div id="js-output" class="output"></div>
        </section>
    </main>

    <script src="secure.js"></script>
</body>
</html>
