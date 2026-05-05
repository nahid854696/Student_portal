
<?php
// login.php
require_once 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $identifier = sanitize($_POST['identifier']); // email or username
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM students WHERE email = ? OR username = ?");
    $stmt->execute([$identifier, $identifier]);
    $user = $stmt->fetch();

    if ($user) {
        // Brute force check
        if ($user['login_attempts'] >= 5 && (time() - strtotime($user['last_attempt']) < 900)) {
            $error = "Account locked for 15 minutes due to too many failed attempts.";
        } else {
            if (password_verify($password, $user['password'])) {
                if ($user['email_verified'] == 0) {
                    $error = "Please verify your email first. <a href='verify.php' class='underline'>Verify Now</a>";
                } else {
                    // Reset attempts on success
                    $stmt = $pdo->prepare("UPDATE students SET login_attempts = 0 WHERE id = ?");
                    $stmt->execute([$user['id']]);

                    $_SESSION['student_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['last_activity'] = time();

                    header("Location: dashboard.php");
                    exit();
                }
            } else {
                // Increment attempts
                $stmt = $pdo->prepare("UPDATE students SET login_attempts = login_attempts + 1, last_attempt = NOW() WHERE id = ?");
                $stmt->execute([$user['id']]);
                $error = "Invalid password!";
            }
        }
    } else {
        $error = "User not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#020617] min-h-screen flex items-center justify-center p-4">
    <div class="bg-[#0f172a] p-8 rounded-xl shadow-2xl w-full max-w-md border border-white/5">
        <h2 class="text-2xl font-bold text-white text-center mb-6">Sign In</h2>
        
        <?php if($error): ?>
            <div class="bg-red-500/20 border border-red-500 text-red-200 p-3 rounded mb-4 text-sm"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="" method="POST" class="space-y-4">
            <div>
                <label class="text-gray-300 text-sm">Email or Username</label>
                <input type="text" name="identifier" required class="w-full bg-white/5 border border-white/10 rounded p-2.5 text-white focus:outline-none focus:border-blue-500">
            </div>
            
            <div>
                <label class="text-gray-300 text-sm">Password</label>
                <input type="password" name="password" required class="w-full bg-white/5 border border-white/10 rounded p-2.5 text-white focus:outline-none focus:border-blue-500">
            </div>

            <div class="flex items-center justify-between text-xs text-gray-400">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember"> Remember Me
                </label>
                <a href="forgot-password.php" class="hover:text-blue-400">Forgot Password?</a>
            </div>

            <button type="submit" name="login" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition duration-300 shadow-lg shadow-blue-500/20">
                Sign In
            </button>
        </form>
        <p class="text-center text-gray-400 mt-6 text-sm">
            New student? <a href="register.php" class="text-blue-400 hover:underline font-semibold">Create Account</a>
        </p>
    </div>
</body>
</html>
