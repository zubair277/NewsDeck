<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$searchTerm = trim($data['search_term'] ?? '');

if ($searchTerm === '') {
    echo json_encode(['success' => false, 'message' => 'Empty search term']);
    exit;
}

try {
    $conn = getDBConnection();
    $stmt = $conn->prepare("INSERT INTO search_history (user_id, search_term) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user_id'], $searchTerm]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?> 