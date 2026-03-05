<?php
/**
 * Admin Requests - MakeAIBucks
 */
require_once '../includes/config.php';
requireAdmin();

$db = db();
$action = get('action');
$id = (int)get('id');

if ($action === 'status' && $id) {
    $status = get('status');
    $db->query("UPDATE tool_requests SET status = ? WHERE id = ?", [$status, $id]);
    redirect('requests.php?msg=Status updated.');
}

if ($action === 'delete' && $id) {
    $db->query("DELETE FROM tool_requests WHERE id = ?", [$id]);
    redirect('requests.php?msg=Request deleted.');
}

$requests = $db->fetchAll("SELECT * FROM tool_requests ORDER BY created_at DESC");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requests — MakeAIBucks Admin</title>
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
            <a href="requests.php" class="sidebar-active flex items-center gap-3 px-6 py-4 rounded-xl text-sm font-bold border border-transparent transition-all">
                <span class="material-symbols-outlined text-lg">format_list_bulleted</span> Requests
            </a>
            <a href="settings.php" class="flex items-center gap-3 px-6 py-4 rounded-xl text-sm font-bold text-slate-400 hover:text-white hover:bg-white/5 transition-all">
                <span class="material-symbols-outlined text-lg">settings</span> Site Settings
            </a>
        </nav>
    </aside>

    <main class="flex-1 p-10 overflow-auto">
        <header class="mb-12">
            <h2 class="text-4xl font-black text-slate-900 tracking-tight">Community Requests</h2>
            <p class="text-sm text-slate-500 font-medium">Manage and update the roadmap.</p>
        </header>

        <div class="bg-white rounded-[32px] border border-slate-200 overflow-hidden shadow-sm">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 italic">
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase">Tool / Category</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase">Votes</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase">Description</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase">Status</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php foreach ($requests as $r): ?>
                    <tr>
                        <td class="px-8 py-4">
                            <span class="font-bold text-slate-900 block"><?= h($r['name']) ?></span>
                            <span class="text-[10px] text-slate-400 font-black uppercase tracking-widest"><?= h($r['category']) ?></span>
                        </td>
                        <td class="px-8 py-4 px-8 text-sm font-black text-green-500"><?= $r['votes'] ?></td>
                        <td class="px-8 py-4 text-xs font-medium text-slate-500 max-w-xs truncate"><?= h($r['description']) ?></td>
                        <td class="px-8 py-4">
                            <select onchange="window.location='?action=status&id=<?= $r['id'] ?>&status='+this.value" class="bg-slate-50 border-none text-[10px] font-black uppercase rounded-lg px-2 py-1 outline-none cursor-pointer">
                                <option value="pending" <?= $r['status']=='pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="reviewing" <?= $r['status']=='reviewing' ? 'selected' : '' ?>>Reviewing</option>
                                <option value="building" <?= $r['status']=='building' ? 'selected' : '' ?>>Building</option>
                                <option value="launched" <?= $r['status']=='launched' ? 'selected' : '' ?>>Launched</option>
                            </select>
                        </td>
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
