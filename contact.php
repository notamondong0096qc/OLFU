<?php
require 'db.php';
$step = 1;
$visitor_name = "";
$message_status = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Step 1: Submit Name
    if (isset($_POST['submit_name'])) {
        $visitor_name = htmlspecialchars(trim($_POST['visitor_name']));
        if (!empty($visitor_name)) {
            $step = 2;
        }
    }
    // Step 2: Submit Full Form
    elseif (isset($_POST['submit_message'])) {
        $visitor_name = htmlspecialchars(trim($_POST['visitor_name']));
        $email = htmlspecialchars(trim($_POST['email']));
        $message = htmlspecialchars(trim($_POST['message']));
        
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $visitor_name, $email, $message);
        
        if ($stmt->execute()) {
            $message_status = "<div class='msg success'>Success! Your message has been sent to the database.</div>";
        } else {
            $message_status = "<div class='msg error'>Error saving message.</div>";
        }
        $stmt->close();
        $step = 2; // Keep them on step 2 to see the message
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Me</title>
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
        <h2>Contact Me</h2>
        <?php echo $message_status; ?>

        <?php if ($step == 1): ?>
            <form action="contact.php" method="POST">
                <label for="visitor_name">Please enter your name:</label>
                <input type="text" id="visitor_name" name="visitor_name" required>
                <button type="submit" name="submit_name">Continue</button>
            </form>
        <?php elseif ($step == 2): ?>
            <h3>Welcome <?php echo $visitor_name; ?> to my Portfolio!</h3>
            <form action="contact.php" method="POST">
                <input type="hidden" name="visitor_name" value="<?php echo $visitor_name; ?>">
                
                <label for="email">Your Email:</label>
                <input type="email" id="email" name="email" required>
                
                <label for="message">Your Message:</label>
                <textarea id="message" name="message" rows="5" required></textarea>
                
                <button type="submit" name="submit_message">Send Message</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>