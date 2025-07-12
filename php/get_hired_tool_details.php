<?php
require_once 'config.php';

if (!isLoggedIn() || !isAdmin()) {
    http_response_code(403);
    echo json_encode(['error' => 'Admin access required']);
    exit;
}

$hired_tool_id = $_GET['id'] ?? '';

if (!$hired_tool_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing hired tool ID']);
    exit;
}

try {
    $conn = getDBConnection();
    
    $query = "SELECT ht.*, p.name as product_name, p.price as product_price, 
                     u.username, u.first_name, u.last_name, u.email
              FROM hired_tools ht 
              JOIN products p ON ht.product_id = p.id 
              JOIN users u ON ht.user_id = u.id
              WHERE ht.id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->execute([$hired_tool_id]);
    $hired_tool = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$hired_tool) {
        http_response_code(404);
        echo json_encode(['error' => 'Hired tool not found']);
        exit;
    }
    
    echo json_encode([
        'success' => true,
        'data' => $hired_tool
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?> 