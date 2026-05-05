
<?php
// forgot-password.php
require_once 'config.php';
require_once 'mailer.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['forgot'])) {
    $email = sanitize($_POST['email']);
    
    $stmt = $pdo->prepare("SELECT id, fullname FROM students WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $stmt = $pdo->prepare("UPDATE students SET reset_token = ?, reset_expiry = ? WHERE email = ?");
        if ($stmt->execute([$token, $expiry, $email])) {
            if (sendPasswordReset($email, $token, $user['fullname'])) {
                $message = "A password reset link has been sent to your email.";
            } else {
                $error = "Failed to send email. Link (dev): http://localhost/student-portal/reset-password.php?token=$token";
            }
        }
    } else {
        $error = "No account found with that email address.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white/10 backdrop-blur-lg p-8 rounded-2xl shadow-2xl w-full max-w-md border border-white/20 text-white">
        <h2 class="text-2xl font-bold text-center mb-4">Reset Password</h2>
        <p class="text-gray-400 text-sm text-center mb-6">Enter your registered email address.</p>

        <?php if($message): ?>
            <div class="bg-green-500/20 border border-green-500 text-green-200 p-3 rounded mb-4 text-sm"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if($error): ?>
            <div class="bg-red-500/20 border border-red-500 text-red-200 p-3 rounded mb-4 text-sm"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="" method="POST" class="space-y-4">
            <div>
                <label class="text-gray-300 text-sm">Email Address</label>
                <input type="email" name="email" required class="w-full bg-white/5 border border-white/10 rounded p-3 text-white focus:outline-none focus:border-blue-500">
            </div>
            <button type="submit" name="forgot" class="w-full bg-blue-600 hover:bg-blue-700 py-3 rounded-xl font-bold transition">Send Reset Link</button>
        </form>
        <div class="text-center mt-6">
            <a href="login.php" class="text-sm text-gray-400 hover:text-white underline">Back to Login</a>
        </div>
    </div>
</body>
</html>
