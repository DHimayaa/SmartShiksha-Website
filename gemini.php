<?php
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$userMessage = $data["message"] ?? "";

if (!$userMessage) {
  echo json_encode(["reply" => "Please type a message."]);
  exit;
}

// REPLACE with a secure method of storing your API key!
$apiKey = "AIzaSyBpshwwwanoOkVHiqcv_ki1CeiwTeEckTY"; // Secure environment variable

if (!$apiKey) {
  echo json_encode(["reply" => "API key is missing."]);
  exit;
}

$projectContext =  <<<EOD
You are a helpful assistant for an educational web platform called SmartShiksha.
- The platform helps students monitor and improve their academic behavior and performance.
- It provides tools to track subjects, study progress, attendance, and behavioral feedback.
- A major focus is supporting students preparing for O/L (Ordinary Level) exams.
- Students can log in to check their performance, ask questions, and get study guidance.
- The system tracks pass or fail status based on test scores, attendance, and behavior.
- Your job is to assist students by explaining how SmartShiksha works, interpreting their results, and giving helpful advice for passing their exams.
EOD;

$payload = [
  "contents" => [[
    "role" => "user",
    "parts" => [["text" => $projectContext . "\nUser: " . $userMessage]]
  ]]
];

$ch = curl_init("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=$apiKey");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);
$reply = $result["candidates"][0]["content"]["parts"][0]["text"] ?? "Sorry, I couldn't get a reply. Please try again later.";

echo json_encode(["reply" => $reply]);
