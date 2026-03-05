<?php
/**
 * Admin Results - MakeAIBucks
 */
require_once '../includes/config.php';
requireAdmin();

$db = db();
$action = get('action');
$id = (int)get('id');

if ($action === 'delete' && $id) {
    $db->query("DELETE FROM results WHERE id = ?", [$id]);
    redirect('results.php?msg=Result deleted.');
}

$results = $db->fetchAll("SELECT * FROM results ORDER BY created_at DESC LIMIT 100");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shared Results — MakeAIBucks Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@800&family=DM+Sans:wght@500;700&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        h1, h2, h3 { font-family: 'Syne', sans-serif; }
        .sidebar-active { background: rgba(22, 163, 74, 0.1); border-color: #16a34a; color: #16a34a; }
    </style>
</head>
<body class="bg-[#f6f8f7] min-h-screen flex">

    <!-- Sidebar (Same) -->
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
            <a href="results.php" class="sidebar-active flex items-center gap-3 px-6 py-4 rounded-xl text-sm font-bold border border-transparent transition-all">
                <span class="material-symbols-outlined text-lg">database</span> Shared Results
            </a>
            <a href="settings.php" class="flex items-center gap-3 px-6 py-4 rounded-xl text-sm font-bold text-slate-400 hover:text-white hover:bg-white/5 transition-all">
                <span class="material-symbols-outlined text-lg">settings</span> Site Settings
            </a>
        </nav>
    </aside>

    <main class="flex-1 p-10 overflow-auto">
        <header class="mb-12">
            <h2 class="text-4xl font-black text-slate-900 tracking-tight">Public Results</h2>
            <p class="text-sm text-slate-500 font-medium">Monitoring AI output for quality and compliance.</p>
        </header>

        <div class="bg-white rounded-[32px] border border-slate-200 overflow-hidden shadow-sm">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 italic">
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase">Tool / URL</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase">Views</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase">Output Snippet</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase">IP Address</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php foreach ($results as $r): ?>
                    <tr>
                        <td class="px-8 py-4">
                            <span class="font-bold text-slate-900 block"><?= h($r['tool_slug']) ?></span>
                            <a href="<?= url('result/' . $r['slug']) ?>" target="_blank" class="text-[10px] text-primary font-black uppercase tracking-widest hover:underline">View Page &rarr;</a>
                        </td>
                        <td class="px-8 py-4 text-sm font-black text-slate-900"><?= $r['view_count'] ?></td>
                        <td class="px-8 py-4 text-xs font-medium text-slate-500 italic truncate max-w-xs"><?= h(truncate($r['output_text'], 60)) ?></td>
                        <td class="px-8 py-4 text-xs font-mono text-slate-400"><?= h($r['ip_address']) ?></td>
                        <td class="px-8 py-4 text-right">
                            <a href="?action=delete&id=<?= $r['id'] ?>" onclick="return confirm('Delete?')" class="text-red-400 hover:text-red-500 font-bold text-xs uppercase">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
