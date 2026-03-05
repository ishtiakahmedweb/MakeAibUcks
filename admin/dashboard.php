<?php
/**
 * Admin Dashboard - MakeAIBucks
 */
require_once '../includes/config.php';
requireAdmin();

$db = db();

// Basic Stats
$stats = [
    'total_tools'       => $db->fetchOne("SELECT COUNT(*) as count FROM tools")['count'],
    'total_results'     => $db->fetchOne("SELECT COUNT(*) as count FROM results")['count'],
    'total_requests'    => $db->fetchOne("SELECT COUNT(*) as count FROM tool_requests")['count'],
    'total_subscribers' => $db->fetchOne("SELECT COUNT(*) as count FROM subscribers")['count'],
    'recent_gens'       => $db->fetchAll("SELECT * FROM generations ORDER BY created_at DESC LIMIT 5")
];

// PHP Info check for AI Engine
$curlEnabled = function_exists('curl_version');
$geminiKeySet = getSetting('gemini_api_key') !== '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — MakeAIBucks Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@800&family=DM+Sans:wght@500;700&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        h1, h2, h3 { font-family: 'Syne', sans-serif; }
        .sidebar-active { background: rgba(22, 163, 74, 0.1); border-color: #16a34a; color: #16a34a; }
    </style>
</head>
<body class="bg-[#f6f8f7] min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-[#0b1120] text-white flex flex-col shrink-0">
        <div class="p-8">
            <h1 class="text-xl font-black tracking-tighter">make<span class="text-green-500">ai</span>bucks</h1>
            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1">Admin Panel</p>
        </div>
        
        <nav class="flex-1 px-4 space-y-2">
            <a href="dashboard.php" class="sidebar-active flex items-center gap-3 px-6 py-4 rounded-xl text-sm font-bold border border-transparent transition-all">
                <span class="material-symbols-outlined text-lg">dashboard</span> Dashboard
            </a>
            <a href="tools.php" class="flex items-center gap-3 px-6 py-4 rounded-xl text-sm font-bold text-slate-400 hover:text-white hover:bg-white/5 border border-transparent transition-all">
                <span class="material-symbols-outlined text-lg">construction</span> Manage Tools
            </a>
            <a href="requests.php" class="flex items-center gap-3 px-6 py-4 rounded-xl text-sm font-bold text-slate-400 hover:text-white hover:bg-white/5 border border-transparent transition-all">
                <span class="material-symbols-outlined text-lg">format_list_bulleted</span> Requests
            </a>
            <a href="results.php" class="flex items-center gap-3 px-6 py-4 rounded-xl text-sm font-bold text-slate-400 hover:text-white hover:bg-white/5 border border-transparent transition-all">
                <span class="material-symbols-outlined text-lg">database</span> Shared Results
            </a>
            <a href="settings.php" class="flex items-center gap-3 px-6 py-4 rounded-xl text-sm font-bold text-slate-400 hover:text-white hover:bg-white/5 border border-transparent transition-all">
                <span class="material-symbols-outlined text-lg">settings</span> Site Settings
            </a>
        </nav>

        <div class="p-8 border-t border-white/5">
            <a href="logout.php" class="flex items-center gap-3 text-slate-500 hover:text-red-500 transition-colors text-sm font-bold">
                <span class="material-symbols-outlined text-lg">logout</span> Logout
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-10 overflow-auto">
        <header class="flex items-center justify-between mb-12">
            <div>
                <h2 class="text-4xl font-black text-slate-900 tracking-tight">Overview</h2>
                <p class="text-sm text-slate-500 font-medium">Monitoring MakeAIBucks performance and activity.</p>
            </div>
            
            <div class="flex gap-4">
                <div class="bg-white border border-slate-200 px-6 py-3 rounded-2xl flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full <?= $curlEnabled ? 'bg-green-500 animate-pulse' : 'bg-red-500' ?>"></div>
                    <span class="text-xs font-black text-slate-900 uppercase tracking-widest">
                        Server: <?= $curlEnabled ? 'Online' : 'cURL Missing' ?>
                    </span>
                </div>
                <div class="bg-white border border-slate-200 px-6 py-3 rounded-2xl flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full <?= $geminiKeySet ? 'bg-green-500' : 'bg-red-500' ?>"></div>
                    <span class="text-xs font-black text-slate-900 uppercase tracking-widest">
                        AI Engine: <?= $geminiKeySet ? 'Active' : 'Unconfigured' ?>
                    </span>
                </div>
            </div>
        </header>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <div class="bg-white p-8 rounded-[32px] border border-slate-200 shadow-sm">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Live Tools</p>
                <p class="text-4xl font-black text-slate-900"><?= $stats['total_tools'] ?></p>
            </div>
            <div class="bg-white p-8 rounded-[32px] border border-slate-200 shadow-sm">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Generated Results</p>
                <p class="text-4xl font-black text-slate-900"><?= $stats['total_results'] ?></p>
            </div>
            <div class="bg-white p-8 rounded-[32px] border border-slate-200 shadow-sm">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Tool Requests</p>
                <p class="text-4xl font-black text-slate-900"><?= $stats['total_requests'] ?></p>
            </div>
            <div class="bg-white p-8 rounded-[32px] border border-slate-200 shadow-sm">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Mailing List</p>
                <p class="text-4xl font-black text-slate-900"><?= $stats['total_subscribers'] ?></p>
            </div>
        </div>

        <!-- Recent Activity & System -->
        <div class="grid lg:grid-cols-12 gap-8">
            <div class="lg:col-span-8">
                <div class="bg-white rounded-[32px] border border-slate-200 overflow-hidden shadow-sm">
                    <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-xl font-black text-slate-900">Real-time Activity</h3>
                        <span class="bg-green-50 text-green-600 text-[10px] font-black uppercase px-3 py-1 rounded-lg">Live</span>
                    </div>
                    <div class="p-0">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100 italic">
                                    <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase">Tool</th>
                                    <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase">IP Address</th>
                                    <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase">Engine</th>
                                    <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase">Time</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <?php foreach ($stats['recent_gens'] as $gen): ?>
                                <tr>
                                    <td class="px-8 py-4 text-sm font-bold text-slate-900"><?= h($gen['tool_slug']) ?></td>
                                    <td class="px-8 py-4 text-sm font-medium text-slate-500 font-mono"><?= h($gen['ip_address']) ?></td>
                                    <td class="px-8 py-4">
                                        <span class="bg-slate-100 px-2 py-1 rounded text-[10px] font-black uppercase"><?= $gen['api_type'] ?></span>
                                    </td>
                                    <td class="px-8 py-4 text-xs font-bold text-slate-400"><?= timeAgo($gen['created_at']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4 space-y-8">
                <div class="bg-slate-900 rounded-[32px] p-8 text-white">
                    <h3 class="text-xl font-black mb-6">Quick Actions</h3>
                    <div class="space-y-4">
                        <a href="tools.php?action=add" class="flex items-center gap-4 bg-white/10 hover:bg-green-500 p-4 rounded-2xl transition-all group">
                            <span class="material-symbols-outlined text-green-500 group-hover:text-white">add_circle</span>
                            <span class="text-sm font-bold">New Tool</span>
                        </a>
                        <a href="settings.php" class="flex items-center gap-4 bg-white/10 hover:bg-green-500 p-4 rounded-2xl transition-all group">
                            <span class="material-symbols-outlined text-green-500 group-hover:text-white">vpn_key</span>
                            <span class="text-sm font-bold">Rotate API Keys</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
