
<?php
// verify.php
require_once 'config.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = sanitize($_POST['token']);
    $stmt = $pdo->prepare("SELECT id FROM students WHERE verification_token = ?");
    $stmt->execute([$token]);
    
    if ($stmt->rowCount() > 0) {
        $stmt = $pdo->prepare("UPDATE students SET email_verified = 1, verification_token = NULL WHERE verification_token = ?");
        $stmt->execute([$token]);
        $message = "<div class='text-green-400'>Account verified successfully! <a href='login.php' class='underline'>Login here</a></div>";
    } else {
        $message = "<div class='text-red-400'>Invalid verification token.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white/10 backdrop-blur-lg p-8 rounded-2xl shadow-2xl w-full max-w-md border border-white/20 text-center text-white">
        <h2 class="text-2xl font-bold mb-6">Verify Your Email</h2>
        <?php echo $message; ?>
        <form action="" method="POST" class="mt-4 space-y-4">
            <input type="text" name="token" placeholder="Enter Token" required class="w-full bg-white/5 border border-white/10 rounded p-3 text-white text-center text-xl tracking-widest">
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 py-3 rounded-xl font-bold transition">Verify Account</button>
        </form>
    </div>
</body>
</html>
