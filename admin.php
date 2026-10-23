<?php
session_start();
require 'db.php';

// The assignment asks for a specific username and password but limits the DB to 1 table. 
// We securely hardcode the admin credentials using password_hash().
$admin_user = "admin";
// Hash for "123456" generated via password_hash('123456', PASSWORD_DEFAULT);
$admin_pass_hash = '$2y$10$YourGeneratedHashGoesHereOrUseTheOneBelow'; 
$admin_pass_hash = password_hash('123456', PASSWORD_DEFAULT); 

$error = "";

if (isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    
    if ($user === $admin_user && password_verify($pass, $admin_pass_hash)) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin.php");
        exit;
    } else {
        $error = "<div class='msg error'>Invalid username or password.</div>";
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav>
            <a href="index.php">Home</a>
            <a href="contact.php">Contact</a>
            <a href="admin.php">Admin Panel</a>
        </nav>
    </header>
    <div class="container">
        
        <?php if (!isset($_SESSION['admin_logged_in'])): ?>
            <h2>Admin Login</h2>
            <?php echo $error; ?>
            <form action="admin.php" method="POST">
                <label>Username:</label>
                <input type="text" name="username" required>
                <label>Password:</label>
                <input type="password" name="password" required>
                <button type="submit" name="login">Login</button>
            </form>
            
        <?php else: ?>
            <h2>Admin Dashboard</h2>
            <a href="admin.php?logout=true" style="float: right; color: red;">Logout</a>
            <div style="clear:both;"></div>
            
            <h3>Contact Messages</h3>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Date Sent</th>
                </tr>
                <?php
                $result = $conn->query("SELECT * FROM contact_messages ORDER BY date_sent DESC");
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . nl2br(htmlspecialchars($row['message'])) . "</td>";
                        echo "<td>" . $row['date_sent'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No messages found.</td></tr>";
                }
                ?>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>