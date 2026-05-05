
<?php
include "db.php";

$message = "";
$email = $_GET['email'] ?? '';

if (isset($_POST['verify'])) {
    $otp_input = $_POST['otp'];
    
    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND otp='$otp_input'");
    
    if (mysqli_num_rows($result) > 0) {
        // ওটিপি মিলে গেলে স্ট্যাটাস আপডেট করা (যদি কলাম থাকে) অথবা সরাসরি সাকসেস মেসেজ
        mysqli_query($conn, "UPDATE users SET otp='0' WHERE email='$email'"); // ওটিপি ক্লিয়ার করা
        $message = "<div class='text-green-400'>Verification Successful! <a href='login.php' class='underline'>Login now</a></div>";
    } else {
        $message = "<div class='text-red-400'>Invalid OTP. Please try again.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#020617] text-white min-h-screen flex items-center justify-center p-4">
    <div class="bg-[#0f172a] p-8 rounded-xl shadow-2xl w-full max-w-md border border-white/5 text-center">
        <h2 class="text-2xl font-bold mb-4">Verify OTP</h2>
        <p class="text-gray-400 mb-6">Enter the code sent to: <br><strong><?php echo $email; ?></strong></p>

        <?php echo $message; ?>

        <form method="POST" class="mt-4 space-y-4">
            <input type="text" name="otp" placeholder="6-digit OTP" required class="w-full bg-white/5 border border-white/10 rounded-lg p-3 text-white text-center text-xl tracking-widest focus:outline-none focus:border-blue-500">
            <button type="submit" name="verify" class="w-full bg-blue-600 hover:bg-blue-700 py-3 rounded-lg font-bold transition">Verify Account</button>
        </form>
    </div>
</body>
</html>
