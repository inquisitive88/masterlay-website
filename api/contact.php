<?php
// ============================================
// Masterlay Renovations - Contact Form Handler
// ============================================

header('Content-Type: application/json');

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Load dependencies
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/config.php';

// Collect input RAW — escaping happens exactly once, at output.
// (FILTER_SANITIZE_SPECIAL_CHARS entity-encodes &, quotes and even line
// breaks at intake; combined with htmlspecialchars() in the template that
// double-encoded, showing literal &#38; / &#13;&#10; in the emails.)
$oneLine = static fn($v) => trim(preg_replace('/[\r\n]+/', ' ', (string) $v));
$name    = mb_substr($oneLine($_POST['name'] ?? ''), 0, 150);
$email   = trim((string) filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));
$phone   = mb_substr($oneLine($_POST['phone'] ?? ''), 0, 40);
$service = mb_substr($oneLine($_POST['service'] ?? ''), 0, 100);
$message = mb_substr(trim((string) ($_POST['message'] ?? '')), 0, 5000); // multi-line, breaks preserved
$budget  = mb_substr($oneLine($_POST['budget'] ?? ''), 0, 100);

// Server-side validation
$errors = [];

if (strlen($name) < 2) {
    $errors[] = 'Please enter your full name.';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}

$cleanedPhone = preg_replace('/[\s\-\(\)\+\.]/', '', $phone);
if (!preg_match('/^\d{10,11}$/', $cleanedPhone)) {
    $errors[] = 'Please enter a valid phone number.';
}

if (strlen($message) < 10) {
    $errors[] = 'Please describe your project (at least 10 characters).';
}

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

// Build email
try {
    $mail = get_masterlay_mailer();

    $mail->addAddress(EMAIL, SITE_NAME);
    $mail->addReplyTo($email, $name);

    $mail->isHTML(true);
    $mail->Subject = 'New Contact Form Inquiry from ' . $name;

    // Build HTML email body
    $serviceLabel = $service ?: 'Not specified';
    $budgetLabel  = $budget ?: 'Not specified';

    // HTML-escape ONCE, here at output
    $nameH = htmlspecialchars($name);
    $emailH = htmlspecialchars($email);
    $phoneH = htmlspecialchars($phone);
    $serviceLabelH = htmlspecialchars($serviceLabel);
    $budgetLabelH = htmlspecialchars($budgetLabel);
    $mail->Body = "
    <div style='font-family: Arial, Helvetica, sans-serif; max-width: 600px; margin: 0 auto; background-color: #f9f9f9; padding: 0;'>
        <div style='background-color: #0A0A0A; padding: 30px 40px; text-align: center;'>
            <h1 style='color: #FAA416; margin: 0; font-size: 24px;'>New Contact Inquiry</h1>
            <p style='color: #b9b9b9; margin: 8px 0 0; font-size: 14px;'>Submitted via masterlayrenovations.ca</p>
        </div>
        <div style='background-color: #ffffff; padding: 30px 40px;'>
            <table style='width: 100%; border-collapse: collapse;'>
                <tr>
                    <td style='padding: 12px 0; border-bottom: 1px solid #eee; color: #666; font-size: 13px; width: 130px; vertical-align: top;'>Name</td>
                    <td style='padding: 12px 0; border-bottom: 1px solid #eee; color: #333; font-size: 14px; font-weight: 600;'>{$nameH}</td>
                </tr>
                <tr>
                    <td style='padding: 12px 0; border-bottom: 1px solid #eee; color: #666; font-size: 13px; vertical-align: top;'>Email</td>
                    <td style='padding: 12px 0; border-bottom: 1px solid #eee; color: #333; font-size: 14px;'><a href='mailto:{$emailH}' style='color: #FAA416;'>{$emailH}</a></td>
                </tr>
                <tr>
                    <td style='padding: 12px 0; border-bottom: 1px solid #eee; color: #666; font-size: 13px; vertical-align: top;'>Phone</td>
                    <td style='padding: 12px 0; border-bottom: 1px solid #eee; color: #333; font-size: 14px;'><a href='tel:{$phoneH}' style='color: #FAA416;'>{$phoneH}</a></td>
                </tr>
                <tr>
                    <td style='padding: 12px 0; border-bottom: 1px solid #eee; color: #666; font-size: 13px; vertical-align: top;'>Service</td>
                    <td style='padding: 12px 0; border-bottom: 1px solid #eee; color: #333; font-size: 14px;'>{$serviceLabelH}</td>
                </tr>
                <tr>
                    <td style='padding: 12px 0; border-bottom: 1px solid #eee; color: #666; font-size: 13px; vertical-align: top;'>Budget</td>
                    <td style='padding: 12px 0; border-bottom: 1px solid #eee; color: #333; font-size: 14px;'>{$budgetLabelH}</td>
                </tr>
                <tr>
                    <td style='padding: 12px 0; color: #666; font-size: 13px; vertical-align: top;'>Message</td>
                    <td style='padding: 12px 0; color: #333; font-size: 14px; line-height: 1.6;'>" . nl2br(htmlspecialchars($message)) . "</td>
                </tr>
            </table>
        </div>
        <div style='background-color: #0A0A0A; padding: 20px 40px; text-align: center;'>
            <p style='color: #8a8a8a; margin: 0; font-size: 12px;'>&copy; " . date('Y') . " " . SITE_NAME . " &bull; " . ADDRESS . "</p>
        </div>
    </div>";

    // Plain text fallback
    $mail->AltBody = "New Contact Inquiry\n\n"
        . "Name: {$name}\n"
        . "Email: {$email}\n"
        . "Phone: {$phone}\n"
        . "Service: {$serviceLabel}\n"
        . "Budget: {$budgetLabel}\n"
        . "Message:\n{$message}\n";

    $mail->send();

    echo json_encode(['success' => true, 'message' => 'Your message has been sent successfully. We\'ll get back to you within 24 hours.']);

} catch (\Exception $e) {
    error_log('Contact form mail error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Sorry, there was an error sending your message. Please try calling us or emailing us directly.']);
}
