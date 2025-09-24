<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $config = include('config.php');
    $recaptchaSecret = $config['recaptcha_secret'];
    $smtpUser = $config['smtp_user'];
    $smtpPass = $config['smtp_pass'];

    function clean_input($data) {
        return htmlspecialchars(trim($data));
    }

    $name = clean_input($_POST['name'] ?? '');
    $email = clean_input($_POST['email'] ?? '');
    $tel = clean_input($_POST['tel'] ?? '');
    $message = clean_input($_POST['message'] ?? '');
    $agreement = $_POST['agreement'] ?? '';
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

    $errors = [];

    // Validate name
    if (empty($name)) {
        $errors[] = 'Name is required.';
    } elseif (!preg_match("/^[a-zA-Z-' ]+$/", $name)) {
        $errors[] = 'Invalid name format.';
    }

    // Validate email
    if (empty($email)) {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    } elseif (!preg_match("/^[^\s@]+@[^\s@]+\.[^\s@]+$/", $email)) {
        $errors[] = 'Invalid email structure.';
    } else {
        $domain = substr(strrchr($email, "@"), 1);
        if (!checkdnsrr($domain, "MX")) {
            $errors[] = 'Email domain does not exist.';
        }
    }

    // Validate phone
    if (empty($tel)) {
        $errors[] = 'Phone number is required.';
    } elseif (!preg_match("/^\\+?[0-9]{10,15}$/", $tel)) {
        $errors[] = 'Invalid phone number format.';
    }

    // Validate agreement checkbox
    if (empty($agreement)) {
        $errors[] = 'You must accept the privacy policy.';
    }

    // Validate reCAPTCHA
    if (empty($recaptchaResponse)) {
        $errors[] = 'Please confirm that you are not a robot.';
    } else {
        $recaptchaVerifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptchaResponseData = file_get_contents("$recaptchaVerifyUrl?secret=$recaptchaSecret&response=$recaptchaResponse");
        $recaptchaResult = json_decode($recaptchaResponseData, true);

        if (!$recaptchaResult['success']) {
            $errors[] = 'reCAPTCHA verification failed. Please try again.';
        }
    }

    if (!empty($errors)) {
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit;
    }

    // Send email via PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $smtpUser;
        $mail->Password = $smtpPass;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        
        $mail->CharSet = 'UTF-8'; // Устанавливаем кодировку письма
        $mail->Encoding = 'base64'; // Кодируем тело письма в base64

        $mail->setFrom($email, '=?UTF-8?B?'.base64_encode($name).'?=');
        $mail->addAddress('service@nservice.lv');
        $mail->isHTML(false);
        $mail->Subject = '=?UTF-8?B?'.base64_encode('New message from the website').'?=';
        $mail->Body = "Name: $name\nEmail: $email\nPhone: $tel\nMessage:\n$message";
        $mail->AltBody = strip_tags($mail->Body);

        $mail->send();
        echo json_encode(['success' => true, 'message' => 'The form was successfully submitted!']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'errors' => ['Error sending email: ' . $mail->ErrorInfo]]);
    }
} else {
    echo json_encode(['success' => false, 'errors' => ['Request method is not allowed.']]);
}
?>
