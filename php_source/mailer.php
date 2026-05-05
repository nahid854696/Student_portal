
<?php
/**
 * mailer.php
 * This file handles sending emails using PHPMailer.
 * 
 * SETUP INSTRUCTIONS:
 * 1. Download PHPMailer from: https://github.com/PHPMailer/PHPMailer
 * 2. Extract into a folder named 'PHPMailer' in your project root.
 * 3. Use a Gmail "App Password" (not your regular password).
 *    Get it here: https://myaccount.google.com/apppasswords
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function sendOTP($email, $otp, $fullname) {
    $mail = new PHPMailer(true);

    try {
        // --- SMTP CONFIGURATION ---
        $mail->isSMTP();
        $mail->SMTPDebug  = 2; 
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        
        $mail->Username   = 'YOUR_GMAIL@gmail.com'; 
        $mail->Password   = 'YOUR_GMAIL_APP_PASSWORD'; 
        
        $mail->SMTPSecure = 'tls'; 
        $mail->Port       = 587;
        
        // SSL বাইপাস (লোকালহোস্টের জন্য খুবই গুরুত্বপূর্ণ)
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $mail->Timeout    = 60;

        // --- EMAIL CONTENT ---
        $mail->setFrom('YOUR_GMAIL@gmail.com', 'EduPortal Support');
        $mail->addAddress($email, $fullname);

        $mail->isHTML(true);
        $mail->Subject = 'Verification Code for Student Registration';
        
        // Modern HTML Template
        $mail->Body = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; border: 1px solid #ddd; border-radius: 10px; padding: 20px;'>
            <h2 style='color: #2563eb; text-align: center;'>Email Verification</h2>
            <p>Hello <strong>$fullname</strong>,</p>
            <p>Thank you for registering at EduPortal. Please use the following One-Time Password (OTP) to verify your account:</p>
            <div style='background: #f3f4f6; padding: 15px; text-align: center; border-radius: 8px; margin: 20px 0;'>
                <span style='font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #1e40af;'>$otp</span>
            </div>
            <p>This code will expire in 10 minutes. If you did not request this, please ignore this email.</p>
            <hr style='border: 0; border-top: 1px solid #eee; margin: 20px 0;'>
            <p style='font-size: 12px; color: #666; text-align: center;'>&copy; 2026 EduPortal. All rights reserved.</p>
        </div>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }
}

function sendPasswordReset($email, $token, $fullname) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'YOUR_GMAIL@gmail.com'; 
        $mail->Password   = 'YOUR_GMAIL_APP_PASSWORD'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('YOUR_GMAIL@gmail.com', 'EduPortal Support');
        $mail->addAddress($email, $fullname);

        $mail->isHTML(true);
        $mail->Subject = 'Reset Your Password - EduPortal';
        $resetLink = "http://localhost/student-portal/reset-password.php?token=" . $token;
        
        $mail->Body = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px;'>
            <h2 style='color: #2563eb;'>Password Reset Request</h2>
            <p>You requested to reset your password. Click the button below to proceed:</p>
            <a href='$resetLink' style='display: inline-block; padding: 12px 25px; background: #2563eb; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0;'>Reset Password</a>
            <p>If the button doesn't work, copy this link: $resetLink</p>
            <p>This link expires in 1 hour.</p>
        </div>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>
