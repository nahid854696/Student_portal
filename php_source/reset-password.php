
<?php
// reset-password.php
require_once 'config.php';

$message = '';
$error = '';
$token = $_GET['token'] ?? '';

if (!$token) {
    die("Invalid access.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset'])) {
    $new_pass = $_POST['password'];
    $confirm_pass = $_POST['confirm_password'];

    if ($new_pass !== $confirm_pass) {
        $error = "Passwords do not match.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM students WHERE reset_token = ? AND reset_expiry > NOW()");
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        if ($user) {
            $hashed = password_hash($new_pass, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("UPDATE students SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE id = ?");
            $stmt->execute([$hashed, $user['id']]);
            $message = "Password updated successfully! <a href='login.php' class='underline'>Login now</a>";
        } else {
            $error = "Token expired or invalid.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Set New Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white/10 backdrop-blur-lg p-8 rounded-2xl shadow-2xl w-full max-w-md border border-white/20 text-white">
        <h2 class="text-2xl font-bold text-center mb-6">New Password</h2>

        <?php if($message): ?>
            <div class="bg-green-500/20 border border-green-500 text-green-200 p-3 rounded mb-4"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if($error): ?>
            <div class="bg-red-500/20 border border-red-500 text-red-200 p-3 rounded mb-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="" method="POST" class="space-y-4">
            <div>
                <label class="text-gray-300 text-sm">New Password</label>
                <input type="password" name="password" required class="w-full bg-white/5 border border-white/10 rounded p-3 text-white focus:outline-none focus:border-blue-500">
            </div>
            <div>
                <label class="text-gray-300 text-sm">Confirm Password</label>
                <input type="password" name="confirm_password" required class="w-full bg-white/5 border border-white/10 rounded p-3 text-white focus:outline-none focus:border-blue-500">
            </div>
            <button type="submit" name="reset" class="w-full bg-blue-600 hover:bg-blue-700 py-3 rounded-xl font-bold transition">Reset Password</button>
        </form>
    </div>
</body>
</html>
