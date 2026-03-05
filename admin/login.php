<?php
/**
 * Admin Login - MakeAIBucks
 */
require_once '../includes/config.php';

$error = '';

if (isPost()) {
    $password = post('password');
    if (adminLogin($password)) {
        redirect(url('admin/dashboard.php'));
    } else {
        $error = 'Invalid administrative password.';
    }
}

if (isAdminLoggedIn()) {
    redirect(url('admin/dashboard.php'));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — MakeAIBucks</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@800&family=DM+Sans:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        h1 { font-family: 'Syne', sans-serif; }
        .hero-gradient { background: linear-gradient(135deg, #020d05 0%, #0c2810 100%); }
    </style>
</head>
<body class="bg-[#020d05] flex items-center justify-center min-h-screen p-6">
    <div class="w-full max-w-md">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-black text-white tracking-tighter mb-2">make<span class="text-green-500">ai</span>bucks</h1>
            <p class="text-slate-500 text-sm font-bold uppercase tracking-widest">Administrative Access</p>
        </div>

        <div class="bg-white/5 border border-white/10 p-8 md:p-12 rounded-[40px] backdrop-blur-xl shadow-2xl">
            <?php if ($error): ?>
                <div class="bg-red-500/10 border border-red-500/20 text-red-500 p-4 rounded-2xl text-xs font-bold mb-8 text-center uppercase tracking-wider">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-8">
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-2">Master Password</label>
                    <input type="password" name="password" required autofocus
                           class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-bold focus:ring-2 focus:ring-green-500 outline-none transition-all"
                           placeholder="••••••••••••">
                </div>

                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white py-5 rounded-2xl font-black text-sm transition-all shadow-xl shadow-green-500/20">
                    AUTHORIZE SESSION
                </button>
            </form>
        </div>
        
        <p class="text-center mt-8">
            <a href="<?= url() ?>" class="text-slate-600 hover:text-white text-xs font-bold transition-all flex items-center justify-center gap-2">
                &larr; Return to Public Site
            </a>
        </p>
    </div>
</body>
</html>
