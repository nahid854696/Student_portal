
<?php
include "db.php";
include "send_mail.php";

$error = "";
$success = "";

if (isset($_POST['signup'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $otp = rand(100000, 999999);

    // চেক করা হচ্ছে ইমেইল আগে থেকেই আছে কি না
    $checkEmail = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($checkEmail) > 0) {
        $error = "Email already exists!";
    } else {
        $sql = "INSERT INTO users(name, email, password, otp) VALUES('$name', '$email', '$password', '$otp')";
        
        if (mysqli_query($conn, $sql)) {
            // মেইল পাঠানোর চেষ্টা
            $mailStatus = sendMail($email, "OTP Verification", "Hello $name, <br> Your Verification OTP is: <b>$otp</b>");
            
            if ($mailStatus) {
                header("Location: verify_otp.php?email=$email");
                exit();
            } else {
                // মেইল না গেলে এই মেসেজটি দেখাবে
                $error = "Registration Successful! <br> 
                          <span class='text-yellow-500'>Note: OTP Email could not be sent.</span> <br>
                          Your OTP for testing: <b>$otp</b> <br>
                          <small>Please check your SMTP settings in send_mail.php</small>";
            }
        } else {
            $error = "Database Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up - EduPortal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#020617] text-white min-h-screen flex items-center justify-center p-4">
    <div class="bg-[#0f172a] p-8 rounded-xl shadow-2xl w-full max-w-md border border-white/5">
        <h2 class="text-3xl font-bold text-center mb-6">Sign Up</h2>

        <?php if($error): ?>
            <div class="bg-red-500/10 border border-red-500/50 text-red-400 p-3 rounded mb-4 text-sm">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="text-gray-400 text-sm">Full Name</label>
                <input type="text" name="name" required class="w-full bg-white/5 border border-white/10 rounded-lg p-3 text-white focus:outline-none focus:border-blue-500" placeholder="Enter your name">
            </div>

            <div>
                <label class="text-gray-400 text-sm">Email Address</label>
                <input type="email" name="email" required class="w-full bg-white/5 border border-white/10 rounded-lg p-3 text-white focus:outline-none focus:border-blue-500" placeholder="name@example.com">
            </div>

            <div>
                <label class="text-gray-400 text-sm">Password</label>
                <input type="password" name="password" required class="w-full bg-white/5 border border-white/10 rounded-lg p-3 text-white focus:outline-none focus:border-blue-500" placeholder="••••••••">
            </div>

            <button type="submit" name="signup" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition duration-300">
                Sign Up
            </button>
        </form>

        <p class="text-center text-gray-500 mt-6 text-sm">
            Already have an account? <a href="login.php" class="text-blue-500 hover:underline">Log In</a>
        </p>
    </div>
</body>
</html>
