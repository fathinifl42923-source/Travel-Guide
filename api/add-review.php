<?php
require_once '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $place_id = $_POST['place_id'] ?? 0;
    $rating = $_POST['rating'] ?? 0;
    $comment = trim($_POST['comment'] ?? '');
    
    if ($place_id && $rating >= 1 && $rating <= 5 && $comment) {
        try {
            // Check if user already reviewed
            $stmt = $pdo->prepare("SELECT id FROM reviews WHERE place_id = ? AND user_id = ?");
            $stmt->execute([$place_id, $_SESSION['user_id']]);
            
            if ($stmt->fetch()) {
                $_SESSION['error'] = "You have already reviewed this destination";
            } else {
                $stmt = $pdo->prepare("INSERT INTO reviews (place_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
                $stmt->execute([$place_id, $_SESSION['user_id'], $rating, $comment]);
                $_SESSION['success'] = "Review submitted successfully!";
            }
        } catch(PDOException $e) {
            $_SESSION['error'] = "Failed to submit review";
        }
    } else {
        $_SESSION['error'] = "Please fill all fields and provide a valid rating";
    }
    
    header("Location: ../place-detail.php?id=" . $place_id);
    exit();
}
