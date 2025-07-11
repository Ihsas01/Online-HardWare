<?php
require_once 'config.php';

header('Content-Type: application/json');

try {
    // Validate input
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $required_fields = ['tool_id', 'name', 'email', 'phone', 'start_date', 'duration'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }

    // Sanitize input
    $tool_id = filter_var($_POST['tool_id'], FILTER_VALIDATE_INT);
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $start_date = filter_var($_POST['start_date'], FILTER_SANITIZE_STRING);
    $duration = filter_var($_POST['duration'], FILTER_VALIDATE_INT);
    $notes = isset($_POST['notes']) ? filter_var($_POST['notes'], FILTER_SANITIZE_STRING) : '';

    if (!$tool_id || !$email) {
        throw new Exception('Invalid input data');
    }

    // Validate date
    $start_date_obj = new DateTime($start_date);
    $today = new DateTime();
    if ($start_date_obj < $today) {
        throw new Exception('Start date cannot be in the past');
    }

    // Get database connection
    $conn = getDBConnection();

    // Check if tool exists and is available for hire
    $stmt = $conn->prepare("SELECT id, name, daily_rate FROM products WHERE id = ? AND available_for_hire = 1");
    $stmt->execute([$tool_id]);
    $tool = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$tool) {
        throw new Exception('Tool not available for hire');
    }

    // Calculate end date and total amount
    $end_date = clone $start_date_obj;
    $end_date->modify("+$duration days");
    
    $total_amount = $tool['daily_rate'] * $duration;

    // Begin transaction
    $conn->beginTransaction();

    // Insert rental request
    $stmt = $conn->prepare("
        INSERT INTO rental_requests 
        (product_id, customer_name, customer_email, customer_phone, start_date, duration, notes)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $tool_id,
        $name,
        $email,
        $phone,
        $start_date,
        $duration,
        $notes
    ]);

    $rental_request_id = $conn->lastInsertId();

    // Insert into rental history
    $stmt = $conn->prepare("
        INSERT INTO rental_history 
        (rental_request_id, product_id, customer_name, start_date, end_date, total_amount)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $rental_request_id,
        $tool_id,
        $name,
        $start_date,
        $end_date->format('Y-m-d'),
        $total_amount
    ]);

    // Commit transaction
    $conn->commit();

    // Send email notification to admin
    $subject = "New Tool Rental Request - $tool[name]";
    $message = "A new rental request has been submitted:\n\n";
    $message .= "Tool: $tool[name]\n";
    $message .= "Customer: $name\n";
    $message .= "Email: $email\n";
    $message .= "Phone: $phone\n";
    $message .= "Start Date: $start_date\n";
    $message .= "Duration: $duration days\n";
    $message .= "Total Amount: $" . number_format($total_amount, 2) . "\n";
    if ($notes) {
        $message .= "\nNotes: $notes\n";
    }

    mail(ADMIN_EMAIL, $subject, $message);

    // Send confirmation email to customer
    $customer_subject = "Your Tool Rental Request - I-I Brothers";
    $customer_message = "Dear $name,\n\n";
    $customer_message .= "Thank you for your rental request for $tool[name]. ";
    $customer_message .= "We will review your request and contact you shortly.\n\n";
    $customer_message .= "Request Details:\n";
    $customer_message .= "Tool: $tool[name]\n";
    $customer_message .= "Start Date: $start_date\n";
    $customer_message .= "Duration: $duration days\n";
    $customer_message .= "Total Amount: $" . number_format($total_amount, 2) . "\n\n";
    $customer_message .= "If you have any questions, please don't hesitate to contact us.\n\n";
    $customer_message .= "Best regards,\nI-I Brothers Team";

    mail($email, $customer_subject, $customer_message);

    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Rental request submitted successfully'
    ]);

} catch (Exception $e) {
    // Rollback transaction if started
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }

    // Return error response
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} 