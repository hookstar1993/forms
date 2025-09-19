<?php
// Check if the form was submitted using POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // --- Configuration ---
    // IMPORTANT: Enter the email address where you want to receive form submissions.
    $recipient_email = "2023.hardeep@gmail.com";

    // --- Sanitize and retrieve form data ---
    // trim() removes whitespace from the beginning and end of the string.
    // htmlspecialchars() converts special characters to HTML entities to prevent XSS attacks.
    $name = trim(htmlspecialchars($_POST["name"]));
    $email = trim(htmlspecialchars($_POST["email"]));
    $subject = trim(htmlspecialchars($_POST["subject"]));
    $message = trim(htmlspecialchars($_POST["message"]));

    // --- Basic Validation ---
    // Check if any of the required fields are empty
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        // Send a JSON error response back to the JavaScript
        http_response_code(400); // Bad Request
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit;
    }

    // Check for a valid email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Send a JSON error response back to the JavaScript
        http_response_code(400); // Bad Request
        echo json_encode(["status" => "error", "message" => "Please enter a valid email address."]);
        exit;
    }

    // --- Prepare Email ---
    // Email subject line
    $email_subject = "New Contact Form Submission: " . $subject;

    // Email body content
    $email_body = "You have received a new message from your website contact form.\n\n";
    $email_body .= "--------------------------------------------------\n";
    $email_body .= "Name: " . $name . "\n";
    $email_body .= "Email: " . $email . "\n";
    $email_body .= "Subject: " . $subject . "\n";
    $email_body .= "Message:\n" . $message . "\n";
    $email_body .= "--------------------------------------------------\n";

    // Email headers
    // This makes sure the email is sent from the user's email address
    $headers = "From: " . $name . " <" . $email . ">\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // --- Send Email ---
    // Use PHP's built-in mail() function
    if (mail($recipient_email, $email_subject, $email_body, $headers)) {
        // If email sent successfully, send a JSON success response
        http_response_code(200); // OK
        echo json_encode(["status" => "success", "message" => "Your message has been sent successfully."]);
    } else {
        // If mail() function fails, send a JSON error response
        http_response_code(500); // Internal Server Error
        echo json_encode(["status" => "error", "message" => "Oops! Something went wrong and we couldn't send your message."]);
    }

} else {
    // If the request method is not POST, show an error
    http_response_code(405); // Method Not Allowed
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>

