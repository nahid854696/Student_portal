
<?php
// dashboard.php
require_once 'config.php';
checkLogin();

$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$_SESSION['student_id']]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-[#020617] text-gray-200 min-h-screen">
    <nav class="bg-[#0f172a] border-b border-white/5 p-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold text-white">EduPortal</h1>
            <div class="flex items-center gap-4">
                <span class="text-sm hidden md:block text-gray-400">Welcome, <?php echo $user['fullname']; ?></span>
                <a href="logout.php" class="text-gray-400 hover:text-white px-4 py-2 rounded-lg text-sm transition">Logout</a>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sidebar Profile -->
        <div class="bg-[#0f172a] p-6 rounded-xl border border-white/5 h-fit shadow-lg">
            <div class="text-center">
                <img src="uploads/<?php echo $user['profile_image']; ?>" class="w-24 h-24 rounded-full mx-auto object-cover border-2 border-blue-500 mb-4" alt="Profile">
                <h2 class="text-xl font-bold text-white"><?php echo $user['fullname']; ?></h2>
                <p class="text-gray-500 text-sm">@<?php echo $user['username']; ?></p>
            </div>
            <div class="mt-8 space-y-3">
                <button class="w-full bg-blue-600 hover:bg-blue-700 py-2.5 rounded-lg text-sm font-semibold transition">Edit Profile</button>
                <button class="w-full bg-gray-800 hover:bg-gray-700 py-2.5 rounded-lg text-sm font-semibold border border-gray-700 transition">Settings</button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white/10 backdrop-blur-lg p-8 rounded-2xl border border-white/20">
                <h3 class="text-xl font-bold mb-6">Student Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white/5 p-4 rounded-xl border border-white/10">
                        <p class="text-xs text-gray-400 uppercase tracking-widest mb-1">Email Address</p>
                        <p class="font-semibold"><?php echo $user['email']; ?></p>
                    </div>
                    <div class="bg-white/5 p-4 rounded-xl border border-white/10">
                        <p class="text-xs text-gray-400 uppercase tracking-widest mb-1">Student ID</p>
                        <p class="font-semibold"><?php echo $user['student_id']; ?></p>
                    </div>
                    <div class="bg-white/5 p-4 rounded-xl border border-white/10">
                        <p class="text-xs text-gray-400 uppercase tracking-widest mb-1">Department</p>
                        <p class="font-semibold"><?php echo $user['department']; ?></p>
                    </div>
                    <div class="bg-white/5 p-4 rounded-xl border border-white/10">
                        <p class="text-xs text-gray-400 uppercase tracking-widest mb-1">Phone</p>
                        <p class="font-semibold"><?php echo $user['phone']; ?></p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gradient-to-br from-blue-600/40 to-blue-800/40 p-6 rounded-2xl border border-blue-500/30">
                    <p class="text-4xl font-bold">3.85</p>
                    <p class="text-sm text-blue-200">Current GPA</p>
                </div>
                <div class="bg-gradient-to-br from-purple-600/40 to-purple-800/40 p-6 rounded-2xl border border-purple-500/30">
                    <p class="text-4xl font-bold">12</p>
                    <p class="text-sm text-purple-200">Courses</p>
                </div>
                <div class="bg-gradient-to-br from-green-600/40 to-green-800/40 p-6 rounded-2xl border border-green-500/30">
                    <p class="text-4xl font-bold">94%</p>
                    <p class="text-sm text-green-200">Attendance</p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
