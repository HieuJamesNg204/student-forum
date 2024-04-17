<?php
session_start();
include 'includes/functions.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: intro.php");
    exit();
}

// Fetch user information
$userId = $_SESSION["user_id"];
$user = getUserById($userId);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["subject"]) && isset($_POST["content"])) {
    $subject = $_POST["subject"];
    $content = $_POST["content"];

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = TRUE;
        $mail->SMTPSecure = 'tls';
        $mail->Username = 'HieuJames204@gmail.com';
        $mail->Password = 'dhnq gvjf gnoy jmbf';
        $mail->Port = 587;

        $userEmail = $user["email"];
        $mail->setFrom($userEmail);
        $mail->addAddress('HieuJames204@gmail.com');

        $mail->Subject = $subject;
        $mail->Body    = $content;

        if ($mail->send()) {
            echo "<script>alert('Mail has been sent to the admin. Please wait until they reply.'); window.location.href='index.php';</script>";
            exit();
        } else {
            echo "<script>alert('Apology for the error while sending the mail! You can try again.'); window.location.href='contact_admin.php';</script>";
            exit();
        }
    } catch (Exception $e) {
        echo "<script>alert('Apology for the error while sending the mail! You can try again.'); window.location.href='contact_admin.php';</script>";
        exit();
    } catch (\Exception $e) {
        echo "<script>alert('Apology for the error while sending the mail! You can try again.'); window.location.href='contact_admin.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Mail subject and content are not provided.'); window.location.href='contact_admin.php';</script>";
    exit();
}