<?php
session_start();
$host = 'localhost';
$dbName = 'demo';
$dbUser = 'root';
$dbPass = '';
$username = '';
$password = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $conn = new mysqli($host, $dbUser, $dbPass, $dbName);
    if ($conn->connect_error) {
        $message = 'Database connection failed: ' . $conn->connect_error;
    } else {
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        echo "<pre>DEBUG: Query = $query</pre>"; // Debug line to see the actual query
        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
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

        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vulnerable Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="container">
        <h1>Vulnerable Login Page</h1>

        <form method="POST" action="login-vulnerable.php" class="login-form">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password">

            <button type="submit">Login</button>
        </form>

        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <section class="js-demo">
            <h2>JavaScript Demo</h2>
            <p>Type text or JavaScript code below and click Run. This page uses <code>innerHTML</code> and <code>eval()</code> unsafely.</p>
            <textarea id="js-input" placeholder="alert('XSS demo')" rows="4"></textarea>
            <button id="js-run">Run Demo</button>
            <div id="js-output" class="output"></div>
        </section>
    </main>

    <script src="vulnerable.js"></script>
</body>
</html>
