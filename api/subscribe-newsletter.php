<?php
require_once '../db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$email = filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL);

if (!$email) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit();
}

try {
    $stmt = $pdo->prepare("INSERT INTO newsletter_subscribers (email) VALUES (?)");
    $stmt->execute([$email]);
    
    echo json_encode(['success' => true, 'message' => 'Successfully subscribed!']);
} catch(PDOException $e) {
    if ($e->getCode() == 23000) {
        echo json_encode(['success' => false, 'message' => 'Email already subscribed']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Subscription failed']);
    }
}
