<?php
require_once 'config.php';

if (!isLoggedIn() || !isAdmin()) {
    http_response_code(403);
    echo json_encode(['error' => 'Admin access required']);
    exit;
}

$user_id = $_GET['id'] ?? '';

if (!$user_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing user ID']);
    exit;
}

try {
    $conn = getDBConnection();
    
    $query = "SELECT id, username, email, first_name, last_name, phone, address, role, created_at 
              FROM users WHERE id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        http_response_code(404);
        echo json_encode(['error' => 'User not found']);
        exit;
    }
    
    echo json_encode([
        'success' => true,
        'data' => $user
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?> 