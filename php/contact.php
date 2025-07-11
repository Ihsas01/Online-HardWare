<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iibrothers";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get form data
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';

    // Validate input
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        throw new Exception("All fields are required");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format");
    }

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message, created_at) 
                           VALUES (:name, :email, :subject, :message, NOW())");

    // Bind parameters
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':subject', $subject);
    $stmt->bindParam(':message', $message);

    // Execute the statement
    $stmt->execute();

    // Send email notification (optional)
    $to = "info@iibrothers.com";
    $email_subject = "New Contact Form Submission: $subject";
    $email_body = "You have received a new message from your website contact form.\n\n" .
                 "Name: $name\n" .
                 "Email: $email\n" .
                 "Subject: $subject\n" .
                 "Message:\n$message";
    $headers = "From: $email\n";
    $headers .= "Reply-To: $email";

    mail($to, $email_subject, $email_body, $headers);

    // Return success response
    echo json_encode([
        'status' => 'success',
        'message' => 'Thank you for your message! We will get back to you soon.'
    ]);

} catch (Exception $e) {
    // Return error response
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

// Close the connection
$conn = null;
?> 