<?php
require_once '../db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$place_id = $data['place_id'] ?? 0;

if (!$place_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid place ID']);
    exit();
}

try {
    $stmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = ? AND place_id = ?");
    $stmt->execute([$_SESSION['user_id'], $place_id]);
    
    echo json_encode(['success' => true]);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
