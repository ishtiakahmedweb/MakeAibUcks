<?php
/**
 * Admin Settings - MakeAIBucks
 */
require_once '../includes/config.php';
requireAdmin();

$db = db();
$message = '';

if (isPost()) {
    foreach ($_POST['settings'] as $key => $value) {
        $db->setSetting($key, $value);
    }
    $message = 'Settings updated successfully.';
}

$settings = [
    'gemini_api_key' => getSetting('gemini_api_key'),
    'groq_api_key'   => getSetting('groq_api_key'),
    'site_name'      => getSetting('site_name', 'MakeAIBucks'),
    'admin_password' => '', // Hide current hash
    'adsense_code'   => getSetting('adsense_code'),
    'analytics_id'   => getSetting('analytics_id'),
    'rate_limit_hour'=> getSetting('rate_limit_hour', 10),
    'rate_limit_day' => getSetting('rate_limit_day', 30)
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings — MakeAIBucks Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@800&family=DM+Sans:wght@500;700&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        h1, h2, h3 { font-family: 'Syne', sans-serif; }
        .sidebar-active { background: rgba(22, 163, 74, 0.1); border-color: #16a34a; color: #16a34a; }
    </style>
</head>
<body class="bg-[#f6f8f7] min-h-screen flex">

    <!-- Sidebar (Same as dashboard) -->
    <aside class="w-64 bg-[#0b1120] text-white flex flex-col shrink-0">
        <div class="p-8">
            <h1 class="text-xl font-black tracking-tighter">make<span class="text-green-500">ai</span>bucks</h1>
            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1">Admin Panel</p>
        </div>
        <nav class="flex-1 px-4 space-y-2">
            <a href="dashboard.php" class="flex items-center gap-3 px-6 py-4 rounded-xl text-sm font-bold text-slate-400 hover:text-white hover:bg-white/5 transition-all">
                <span class="material-symbols-outlined text-lg">dashboard</span> Dashboard
            </a>
            <a href="tools.php" class="flex items-center gap-3 px-6 py-4 rounded-xl text-sm font-bold text-slate-400 hover:text-white hover:bg-white/5 transition-all">
                <span class="material-symbols-outlined text-lg">construction</span> Manage Tools
            </a>
            <a href="requests.php" class="flex items-center gap-3 px-6 py-4 rounded-xl text-sm font-bold text-slate-400 hover:text-white hover:bg-white/5 transition-all">
                <span class="material-symbols-outlined text-lg">format_list_bulleted</span> Requests
            </a>
            <a href="settings.php" class="sidebar-active flex items-center gap-3 px-6 py-4 rounded-xl text-sm font-bold border border-transparent transition-all">
                <span class="material-symbols-outlined text-lg">settings</span> Site Settings
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-10 overflow-auto">
        <header class="mb-12">
            <h2 class="text-4xl font-black text-slate-900 tracking-tight">Configuration</h2>
            <p class="text-sm text-slate-500 font-medium">Manage API keys, limits, and system settings.</p>
        </header>

        <?php if ($message): ?>
            <div class="bg-green-100 border border-green-200 text-green-600 px-6 py-4 rounded-2xl text-sm font-bold mb-8">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="max-w-4xl space-y-8">
            <!-- AI Engine Settings -->
            <div class="bg-white p-8 rounded-[32px] border border-slate-200 shadow-sm">
                <h3 class="text-xl font-black text-slate-900 mb-8 flex items-center gap-3">
                    <span class="material-symbols-outlined text-green-500">bolt</span>
                    AI Engine Connectivity
                </h3>
                <div class="grid gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Google Gemini API Key</label>
                        <input type="text" name="settings[gemini_api_key]" value="<?= h($settings['gemini_api_key']) ?>" 
                               class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-green-500 outline-none">
                        <p class="text-[10px] text-slate-400 ml-2 italic">Primary engine for high-quality content.</p>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Groq API Key (Fallback)</label>
                        <input type="text" name="settings[groq_api_key]" value="<?= h($settings['groq_api_key']) ?>" 
                               class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-green-500 outline-none">
                    </div>
                </div>
            </div>

            <!-- Site Limits -->
            <div class="bg-white p-8 rounded-[32px] border border-slate-200 shadow-sm">
                <h3 class="text-xl font-black text-slate-900 mb-8 flex items-center gap-3">
                    <span class="material-symbols-outlined text-green-500">speed</span>
                    Usage & Rate Limiting
                </h3>
                <div class="grid grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Generations per Hour (per IP)</label>
                        <input type="number" name="settings[rate_limit_hour]" value="<?= h($settings['rate_limit_hour']) ?>" 
                               class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-green-500 outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Generations per Day (per IP)</label>
                        <input type="number" name="settings[rate_limit_day]" value="<?= h($settings['rate_limit_day']) ?>" 
                               class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 text-sm font-bold focus:ring-2 focus:ring-green-500 outline-none">
                    </div>
                </div>
            </div>

            <button type="submit" class="bg-slate-900 text-white px-12 py-5 rounded-2xl font-black text-sm hover:bg-green-500 transition-all shadow-xl shadow-slate-900/10">
                SAVE SYSTEM CONFIGURATION
            </button>
        </form>
    </main>
</body>
</html>
