<?php
// contactus.php

// === Database settings ===
$host = "localhost";
$db   = "smartshiksha";   //  Make sure this DB exists
$user = "root";           //  Your DB username
$pass = "12345678";               // Your DB password (set it if you have one)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die(" DB Connection Failed: " . $e->getMessage());
}

$success = false;
$errors  = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name    = trim($_POST["name"] ?? "");
    $email   = trim($_POST["email"] ?? "");
    $subject = trim($_POST["subject"] ?? "");
    $message = trim($_POST["message"] ?? "");

    // === Validation ===
    if ($name === "") $errors[] = "Name is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if ($subject === "") $errors[] = "Subject is required.";
    if ($message === "") $errors[] = "Message cannot be empty.";

    // === Insert if no errors ===
    if (!$errors) {
        try {
            $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, subject, message) 
                                   VALUES (:name, :email, :subject, :message)");
            $stmt->execute([
                ":name"    => $name,
                ":email"   => $email,
                ":subject" => $subject,
                ":message" => $message
            ]);
            $success = true;
        } catch (Exception $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Us - SmartShiksha</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Poppins:wght@600&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; }
    .font-heading { font-family: 'Poppins', sans-serif; }
    :root { --primary-teal:#1B5E6F; --accent-orange:#F4965A; }
    .text-primary { color: var(--primary-teal); }
    .bg-accent { background-color: var(--accent-orange); }
  </style>
</head>
<body class="bg-gray-50 text-gray-800">

<header class="bg-white shadow-sm">
  <div class="max-w-7xl mx-auto flex items-center justify-between p-4">
    <a href="index.php" class="flex items-center space-x-3">
      <img src="Assets/2.png" alt="SmartShiksha Logo" class="w-10 h-10 object-contain rounded">
      <span class="font-heading text-xl text-primary font-bold">SmartShiksha</span>
    </a>
    <a href="index.html" class="text-sm text-primary hover:underline">← Back to Home</a>
  </div>
</header>

<main class="max-w-4xl mx-auto py-12 px-4">
  <h1 class="text-4xl font-heading font-bold text-primary text-center mb-8">Contact Us</h1>

  <?php if ($success): ?>
    <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded mb-6">
      ✅ Thank you! Your message has been submitted successfully.
    </div>
  <?php endif; ?>

  <?php if ($errors): ?>
    <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-6">
      <ul class="list-disc pl-5">
        <?php foreach ($errors as $err): ?>
          <li><?= htmlspecialchars($err) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="POST" action="contactus.php" class="space-y-6 bg-white p-8 rounded-lg shadow-md">
    <div>
      <label class="block text-gray-700 font-medium mb-2">Name</label>
      <input type="text" name="name" required
             class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent outline-none">
    </div>
    <div>
      <label class="block text-gray-700 font-medium mb-2">Email</label>
      <input type="email" name="email" required
             class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent outline-none">
    </div>
    <div>
      <label class="block text-gray-700 font-medium mb-2">Subject</label>
      <input type="text" name="subject" required
             class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent outline-none">
    </div>
    <div>
      <label class="block text-gray-700 font-medium mb-2">Message</label>
      <textarea name="message" rows="5" required
                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent outline-none"></textarea>
    </div>
    <div class="text-center">
      <button type="submit" class="bg-accent text-white font-semibold py-3 px-6 rounded-full hover:bg-orange-500 transition transform hover:scale-105">
        Send Message
      </button>
    </div>
  </form>
</main>

</body>
</html>
