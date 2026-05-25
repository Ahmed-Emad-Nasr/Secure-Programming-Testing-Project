<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login-secure.php');
    exit;
}

$host = 'localhost';
$dbName = 'demo';
$dbUser = 'root';
$dbPass = '';

$user = null;
$message = '';

$conn = new mysqli($host, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    $message = 'Database connection failed: ' . $conn->connect_error;
} else {
    $stmt = $conn->prepare('SELECT * FROM users WHERE id = ?');
    if ($stmt) {
        $stmt->bind_param('i', $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
        } else {
            $message = 'User not found.';
        }

        $stmt->close();
    } else {
        $message = 'Failed to prepare query.';
    }

    $conn->close();
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
    <title>User Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="container">
        <h1>User Profile</h1>

        <?php if ($message): ?>
            <div class="message"><?php echo safe($message); ?></div>
        <?php endif; ?>

        <?php if ($user): ?>
            <div class="profile-info">
                <h2>Welcome, <?php echo safe($user['first_name'] ?? $user['username']); ?>!</h2>
                <p><strong>Username:</strong> <?php echo safe($user['username']); ?></p>
                <?php if (!empty($user['email'])): ?>
                    <p><strong>Email:</strong> <?php echo safe($user['email']); ?></p>
                <?php endif; ?>
                <?php if (!empty($user['first_name'])): ?>
                    <p><strong>First Name:</strong> <?php echo safe($user['first_name']); ?></p>
                <?php endif; ?>
                <?php if (!empty($user['last_name'])): ?>
                    <p><strong>Last Name:</strong> <?php echo safe($user['last_name']); ?></p>
                <?php endif; ?>
                <!-- Add more fields as needed -->
            </div>
            <a href="logout.php" class="logout-btn">Logout</a>
        <?php endif; ?>
    </main>
</body>
</html>