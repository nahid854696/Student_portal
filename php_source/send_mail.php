
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function sendMail($to, $subject, $message) {
    $mail = new PHPMailer(true);

    try {
        // --- SMTP সেটিংস ---
        $mail->isSMTP();
        $mail->SMTPDebug  = 0; // যদি মেইল না যায়, তবে এটি ২ করে দিন এরর দেখার জন্য
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        
        // !!! এখানে আপনার আসল তথ্য দিন !!!
        $mail->Username   = 'your-email@gmail.com'; 
        $mail->Password   = 'your-app-password'; // জিমেইলের ১৬ অক্ষরের অ্যাপ পাসওয়ার্ড
        
        $mail->SMTPSecure = 'tls'; 
        $mail->Port       = 587;

        // --- লোকালহোস্টের জন্য SSL বাইপাস (এটি ছাড়া মেইল যাবে না) ---
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // --- মেইল কন্টেন্ট ---
        $mail->setFrom('your-email@gmail.com', 'Student Portal');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        if($mail->send()) {
            return true;
        }
    } catch (Exception $e) {
        // এরর লগ চেক করার জন্য
        error_log("Mail Error: " . $mail->ErrorInfo);
        return false;
    }
    return false;
}
?>
