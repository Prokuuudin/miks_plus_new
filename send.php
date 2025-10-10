<?php
// session_start();
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// require 'phpmailer/PHPMailer.php';
// require 'phpmailer/SMTP.php';
// require 'phpmailer/Exception.php';

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     header('Content-Type: application/json');

//     $config = include('config.php');
//     $recaptchaSecret = $config['recaptcha_secret'];
//     $smtpUser = $config['smtp_user'];
//     $smtpPass = $config['smtp_pass'];

//     function clean_input($data) {
//         return htmlspecialchars(trim($data));
//     }

//     $name = clean_input($_POST['name'] ?? '');
//     $email = clean_input($_POST['email'] ?? '');
//     $tel = clean_input($_POST['tel'] ?? '');
//     $message = clean_input($_POST['message'] ?? '');
//     $agreement = $_POST['agreement'] ?? '';
//     $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

//     $errors = [];

//     // Validate name
//     if (empty($name)) {
//         $errors[] = 'Name is required.';
//     } elseif (!preg_match("/^[a-zA-Z-' ]+$/", $name)) {
//         $errors[] = 'Invalid name format.';
//     }

//     // Validate email
//     if (empty($email)) {
//         $errors[] = 'Email is required.';
//     } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//         $errors[] = 'Invalid email format.';
//     } else {
//         $domain = substr(strrchr($email, "@"), 1);
//         if (!checkdnsrr($domain, "MX")) {
//             $errors[] = 'Email domain does not exist.';
//         }
//     }

//     // Validate phone
//     if (empty($tel)) {
//         $errors[] = 'Phone number is required.';
//     } elseif (!preg_match("/^\+?[0-9]{10,15}$/", $tel)) {
//         $errors[] = 'Invalid phone number format.';
//     }

//     // Validate agreement checkbox
//     if (empty($agreement)) {
//         $errors[] = 'You must accept the privacy policy.';
//     }

//     // Validate reCAPTCHA
//     if (empty($recaptchaResponse)) {
//         $errors[] = 'Please confirm that you are not a robot.';
//     } else {
//         $recaptchaVerifyUrl = 'https://www.google.com/recaptcha/api/siteverify';

//         $ch = curl_init();
//         curl_setopt($ch, CURLOPT_URL, $recaptchaVerifyUrl);
//         curl_setopt($ch, CURLOPT_POST, true);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
//             'secret' => $recaptchaSecret,
//             'response' => $recaptchaResponse,
//             'remoteip' => $_SERVER['REMOTE_ADDR'] ?? null
//         ]));
//         $recaptchaResponseData = curl_exec($ch);
//         curl_close($ch);

//         $recaptchaResult = json_decode($recaptchaResponseData, true);

//         if (!$recaptchaResult['success']) {
//             $errors[] = 'reCAPTCHA verification failed. Please try again.';
//         } elseif (isset($recaptchaResult['hostname']) && $recaptchaResult['hostname'] !== $_SERVER['SERVER_NAME']) {
//             $errors[] = 'Invalid reCAPTCHA hostname.';
//         }
//     }

//     if (!empty($errors)) {
//         echo json_encode(['success' => false, 'errors' => $errors]);
//         exit;
//     }

//     // Send email via PHPMailer
//     $mail = new PHPMailer(true);
//     try {
//         $mail->isSMTP();
//         $mail->Host = 'smtp.gmail.com';
//         $mail->SMTPAuth = true;
//         $mail->Username = $smtpUser;
//         $mail->Password = $smtpPass;
//         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
//         $mail->Port = 587;
        
//         $mail->CharSet = 'UTF-8';
//         $mail->Encoding = 'base64';

//         $mail->setFrom($email, '=?UTF-8?B?'.base64_encode($name).'?=');
//         $mail->addAddress('service@nservice.lv');
//         $mail->isHTML(false);
//         $mail->Subject = '=?UTF-8?B?'.base64_encode('New message from the website').'?=';
//         $mail->Body = "Name: $name\nEmail: $email\nPhone: $tel\nMessage:\n$message";
//         $mail->AltBody = strip_tags($mail->Body);

//         $mail->send();
//         echo json_encode(['success' => true, 'message' => 'The form was successfully submitted!']);
//     } catch (Exception $e) {
//         echo json_encode(['success' => false, 'errors' => ['Error sending email: ' . $mail->ErrorInfo]]);
//     }
// } else {
//     echo json_encode(['success' => false, 'errors' => ['Request method is not allowed.']]);
// }


// send.php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', '1');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/phpmailer/PHPMailer.php';
require __DIR__ . '/phpmailer/SMTP.php';
require __DIR__ . '/phpmailer/Exception.php';

// Load config (must return array with keys below)
$config = require __DIR__ . '/config.php';
$recaptchaSecret = $config['recaptcha_secret'] ?? '';
$smtpUser = $config['smtp_user'] ?? '';
$smtpPass = $config['smtp_pass'] ?? '';
$smtpHost = $config['smtp_host'] ?? 'smtp.gmail.com';
$smtpPort = (int)($config['smtp_port'] ?? 587);
$smtpSecure = $config['smtp_secure'] ?? PHPMailer::ENCRYPTION_STARTTLS; // 'tls' or 'ssl' or PHPMailer::ENCRYPTION_STARTTLS

// Basic settings
$uploadDir = __DIR__ . '/files/uploads';
$allowedExt = ['pdf', 'doc', 'docx', 'png', 'jpg', 'jpeg', 'gif'];
$maxFileSize = 10 * 1024 * 1024; // 10 MB per file
$maxFiles = 5; // limit number of attachments
$errors = [];

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'errors' => ['Request method is not allowed.']]);
    exit;
}

// Helper
function clean_input(string $s): string {
    return htmlspecialchars(trim($s), ENT_QUOTES, 'UTF-8');
}

// Read inputs
$name = clean_input($_POST['name'] ?? '');
$email = clean_input($_POST['email'] ?? '');
$tel = clean_input($_POST['tel'] ?? '');
$messageText = clean_input($_POST['message'] ?? '');
$agreement = $_POST['agreement'] ?? '';
$recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

// Validate
if ($name === '') {
    $errors[] = 'Name is required.';
} elseif (!preg_match('/^[\p{L}\p{M}\' \-]+$/u', $name)) {
    $errors[] = 'Invalid name format.';
}

if ($email === '') {
    $errors[] = 'Email is required.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format.';
} else {
    // Optional: check MX unless running on localhost
    $domain = substr(strrchr($email, "@"), 1);
    if (php_sapi_name() !== 'cli' && !in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1'], true)) {
        if (!checkdnsrr($domain, 'MX')) {
            $errors[] = 'Email domain does not exist.';
        }
    }
}

if ($tel === '') {
    $errors[] = 'Phone number is required.';
} elseif (!preg_match('/^\+?[0-9]{7,15}$/', $tel)) {
    // len 7..15 allows local short numbers if needed; adjust to your needs
    $errors[] = 'Invalid phone number format.';
}

if (empty($agreement)) {
    $errors[] = 'You must accept the privacy policy.';
}

// reCAPTCHA verification
if ($recaptchaResponse === '') {
    $errors[] = 'Please confirm that you are not a robot.';
} else {
    $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
    $postData = http_build_query([
        'secret' => $recaptchaSecret,
        'response' => $recaptchaResponse,
        'remoteip' => $_SERVER['REMOTE_ADDR'] ?? null
    ]);

    $ch = curl_init($verifyUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $verifyResponse = curl_exec($ch);
    $curlErr = curl_error($ch);
    curl_close($ch);

    if ($verifyResponse === false) {
        $errors[] = 'reCAPTCHA verification failed (network). ' . $curlErr;
    } else {
        $res = json_decode($verifyResponse, true);
        if (empty($res['success'])) {
            $errors[] = 'reCAPTCHA verification failed. Please try again.';
        } elseif (isset($res['hostname']) && ($res['hostname'] !== ($_SERVER['SERVER_NAME'] ?? ''))) {
            // optional stricter check
            $errors[] = 'Invalid reCAPTCHA hostname.';
        }
    }
}

// If basic validation failed — return errors now
if (!empty($errors)) {
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

// Ensure upload dir exists
if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
    $errors[] = 'Failed to create uploads directory.';
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

// Handle files (cv[])
$attachments = [];
if (!empty($_FILES['cv']) && is_array($_FILES['cv']['name'])) {
    $fileCount = count($_FILES['cv']['name']);
    // limit file count
    if ($fileCount > $maxFiles) {
        $errors[] = 'Too many files. Maximum ' . $maxFiles . ' files allowed.';
    } else {
        for ($i = 0; $i < $fileCount; $i++) {
            $origName = $_FILES['cv']['name'][$i];
            $tmpName = $_FILES['cv']['tmp_name'][$i];
            $err = $_FILES['cv']['error'][$i];
            $size = $_FILES['cv']['size'][$i];

            if ($err !== UPLOAD_ERR_OK) {
                // skip silently or collect messages
                $errors[] = "Error uploading file: $origName (code $err).";
                continue;
            }
            if ($size <= 0) {
                $errors[] = "Empty file: $origName.";
                continue;
            }
            if ($size > $maxFileSize) {
                $errors[] = "File too large: $origName (max " . ($maxFileSize / 1024 / 1024) . " MB).";
                continue;
            }

            $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedExt, true)) {
                $errors[] = "File type not allowed: $origName.";
                continue;
            }

            // sanitize filename and make unique
            $safeBase = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', pathinfo($origName, PATHINFO_FILENAME));
            $uniqueName = $safeBase . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
            $destPath = $uploadDir . '/' . $uniqueName;

            if (!move_uploaded_file($tmpName, $destPath)) {
                $errors[] = "Failed to save file: $origName.";
                continue;
            }

            // push to attachments list (we'll attach these files)
            $attachments[] = $destPath;
        }
    }
}

// If any file-related errors occurred — return them
if (!empty($errors)) {
    // Try to cleanup any saved files
    foreach ($attachments as $p) {
        if (is_file($p)) {
            @unlink($p);
        }
    }
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

// Prepare email body
$body = "<h3>New contact form submission</h3>";
$body .= "<p><strong>Name:</strong> " . nl2br($name) . "</p>";
$body .= "<p><strong>Email:</strong> " . nl2br($email) . "</p>";
$body .= "<p><strong>Phone:</strong> " . nl2br($tel) . "</p>";
if ($messageText !== '') {
    $body .= "<p><strong>Message:</strong><br>" . nl2br($messageText) . "</p>";
}

// Send email via PHPMailer
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = $smtpHost;
    $mail->SMTPAuth = true;
    $mail->Username = $smtpUser;
    $mail->Password = $smtpPass;
    $mail->SMTPSecure = $smtpSecure;
    $mail->Port = $smtpPort;

    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    // Use SMTP user as From to avoid rejection; set reply-to to user's email
    $mail->setFrom($smtpUser, 'Website Form');
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mail->addReplyTo($email, $name);
    }

    // Recipient(s) — change to desired recipient
    $recipient = $config['recipient_email'] ?? 'service@nservice.lv';
    $mail->addAddress($recipient);

    $mail->isHTML(true);
    $mail->Subject = 'New message from the website';
    $mail->Body = $body;
    $mail->AltBody = strip_tags($body);

    // Attach files if any
    foreach ($attachments as $filePath) {
        if (is_file($filePath)) {
            $mail->addAttachment($filePath);
        }
    }

    $mail->send();

    // Cleanup uploaded files after sending
    foreach ($attachments as $filePath) {
        if (is_file($filePath)) {
            @unlink($filePath);
        }
    }

    echo json_encode(['success' => true, 'message' => 'The form was successfully submitted!']);
    exit;
} catch (Exception $ex) {
    // cleanup files
    foreach ($attachments as $filePath) {
        if (is_file($filePath)) {
            @unlink($filePath);
        }
    }
    $errMsg = $mail->ErrorInfo ?: $ex->getMessage();
    echo json_encode(['success' => false, 'errors' => ['Error sending email: ' . $errMsg]]);
    exit;
}
