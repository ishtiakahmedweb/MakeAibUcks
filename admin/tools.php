<?php
/**
 * Admin Tool Management - MakeAIBucks
 */
require_once '../includes/config.php';
requireAdmin();

$db = db();
$action = get('action', 'list');
$id = (int)get('id');

$message = '';

if (isPost()) {
    $data = [
        'name'          => post('name'),
        'slug'          => post('slug') ?: slugify(post('name')),
        'description'   => post('description'),
        'category_slug' => post('category_slug'),
        'system_prompt' => $_POST['system_prompt'], // No sanitize for prompt
        'fields_json'   => $_POST['fields_json'],
        'features_json' => $_POST['features_json'],
        'how_to_use'    => post('how_to_use'),
        'tip_text'      => post('tip_text'),
        'difficulty'    => post('difficulty'),
        'is_active'     => isset($_POST['is_active']) ? 1 : 0,
        'is_featured'   => isset($_POST['is_featured']) ? 1 : 0
    ];

    if ($action === 'add') {
        $db->insert('tools', $data);
        $message = 'Tool created successfully.';
        $action = 'list';
    } elseif ($action === 'edit' && $id) {
        // Simple update helper needed or direct query
        $db->query("
            UPDATE tools SET 
            name=?, slug=?, description=?, category_slug=?, system_prompt=?, 
            fields_json=?, features_json=?, how_to_use=?, tip_text=?, 
            difficulty=?, is_active=?, is_featured=?
            WHERE id=?
        ", [
            $data['name'], $data['slug'], $data['description'], $data['category_slug'], 
            $data['system_prompt'], $data['fields_json'], $data['features_json'],
            $data['how_to_use'], $data['tip_text'], $data['difficulty'],
            $data['is_active'], $data['is_featured'], $id
        ]);
        $message = 'Tool updated successfully.';
        $action = 'list';
    }
}

if ($action === 'delete' && $id) {
    $db->query("DELETE FROM tools WHERE id = ?", [$id]);
    $message = 'Tool deleted.';
    $action = 'list';
}

$categories = $db->fetchAll("SELECT name, slug FROM categories");
$tools = ($action === 'list') ? $db->fetchAll("SELECT * FROM tools ORDER BY id DESC") : [];
$tool = ($action === 'edit' && $id) ? $db->fetchOne("SELECT * FROM tools WHERE id = ?", [$id]) : null;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tools — MakeAIBucks Admin</title>
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
            <a href="tools.php" class="sidebar-active flex items-center gap-3 px-6 py-4 rounded-xl text-sm font-bold border border-transparent transition-all">
                <span class="material-symbols-outlined text-lg">construction</span> Manage Tools
            </a>
            <a href="requests.php" class="flex items-center gap-3 px-6 py-4 rounded-xl text-sm font-bold text-slate-400 hover:text-white hover:bg-white/5 transition-all">
                <span class="material-symbols-outlined text-lg">format_list_bulleted</span> Requests
            </a>
            <a href="settings.php" class="flex items-center gap-3 px-6 py-4 rounded-xl text-sm font-bold text-slate-400 hover:text-white hover:bg-white/5 transition-all">
                <span class="material-symbols-outlined text-lg">settings</span> Site Settings
            </a>
        </nav>
    </aside>

    <main class="flex-1 p-10 overflow-auto">
        <header class="flex items-center justify-between mb-12">
            <div>
                <h2 class="text-4xl font-black text-slate-900 tracking-tight">Tools</h2>
                <p class="text-sm text-slate-500 font-medium">Create and refine AI tool configurations.</p>
            </div>
            <?php if ($action === 'list'): ?>
                <a href="?action=add" class="bg-primary text-white px-8 py-4 rounded-2xl font-black text-sm flex items-center gap-2 hover:scale-105 transition-all shadow-xl shadow-primary/20">
                    <span class="material-symbols-outlined">add_circle</span> NEW TOOL
                </a>
            <?php endif; ?>
        </header>

        <?php if ($message): ?>
            <div class="bg-green-100 border border-green-200 text-green-600 px-6 py-4 rounded-2xl text-sm font-bold mb-8">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <?php if ($action === 'list'): ?>
            <div class="bg-white rounded-[32px] border border-slate-200 overflow-hidden shadow-sm">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase">Tool Name</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase">Category</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase">Uses</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase">Status</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php foreach ($tools as $t): ?>
                        <tr>
                            <td class="px-8 py-4">
                                <span class="font-bold text-slate-900 block"><?= h($t['name']) ?></span>
                                <span class="text-[10px] text-slate-400 font-mono">/tool/<?= h($t['slug']) ?></span>
                            </td>
                            <td class="px-8 py-4 text-sm font-medium text-slate-500"><?= h($t['category_slug']) ?></td>
                            <td class="px-8 py-4 text-sm font-bold text-slate-900"><?= formatNumber($t['uses_count']) ?></td>
                            <td class="px-8 py-4">
                                <span class="<?= $t['is_active'] ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' ?> text-[10px] font-black uppercase px-2 py-1 rounded">
                                    <?= $t['is_active'] ? 'Active' : 'Draft' ?>
                                </span>
                            </td>
                            <td class="px-8 py-4 text-right space-x-2">
                                <a href="?action=edit&id=<?= $t['id'] ?>" class="text-primary hover:underline font-bold text-xs uppercase">Edit</a>
                                <a href="?action=delete&id=<?= $t['id'] ?>" onclick="return confirm('Delete tool?')" class="text-red-400 hover:text-red-600 font-bold text-xs uppercase">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php elseif ($action === 'add' || $action === 'edit'): ?>
            <form method="POST" class="max-w-5xl space-y-10 pb-20">
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="bg-white p-8 rounded-[32px] border border-slate-200 shadow-sm space-y-6">
                        <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-4">Basic Info</h3>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase px-2">Tool Name</label>
                            <input name="name" value="<?= $tool ? h($tool['name']) : '' ?>" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-3 font-bold focus:ring-2 focus:ring-primary outline-none" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase px-2">Slug (Auto-generated if empty)</label>
                            <input name="slug" value="<?= $tool ? h($tool['slug']) : '' ?>" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-3 font-bold focus:ring-2 focus:ring-primary outline-none">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase px-2">Category</label>
                            <select name="category_slug" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-3 font-bold outline-none">
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['slug'] ?>" <?= $tool && $tool['category_slug']==$cat['slug'] ? 'selected' : '' ?>><?= $cat['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="flex gap-8 px-2 pt-4">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="is_active" <?= !$tool || $tool['is_active'] ? 'checked' : '' ?> class="w-5 h-5 rounded text-primary focus:ring-primary border-slate-300">
                                <span class="text-sm font-bold text-slate-700">Live Tool</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="is_featured" <?= $tool && $tool['is_featured'] ? 'checked' : '' ?> class="w-5 h-5 rounded text-primary focus:ring-primary border-slate-300">
                                <span class="text-sm font-bold text-slate-700">Featured Tool</span>
                            </label>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-[32px] border border-slate-200 shadow-sm space-y-6">
                        <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-4">Prompts & Structure</h3>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase px-2">System Prompt (AI Background)</label>
                            <textarea name="system_prompt" rows="6" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-3 font-bold focus:ring-2 focus:ring-primary outline-none"><?= $tool ? h($tool['system_prompt']) : '' ?></textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase px-2">Fields JSON (Input Config)</label>
                            <textarea name="fields_json" rows="4" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-3 font-mono text-xs focus:ring-2 focus:ring-primary outline-none"><?= $tool ? h($tool['fields_json']) : '[]' ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-[32px] border border-slate-200 shadow-sm space-y-6">
                    <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-4">Content & Tips</h3>
                    <div class="grid md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase px-2">How to Use (Sidebar Instructions)</label>
                            <textarea name="how_to_use" rows="4" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-3 font-bold focus:ring-2 focus:ring-primary outline-none"><?= $tool ? h($tool['how_to_use']) : '' ?></textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase px-2">Pro Tip (Floating Text)</label>
                            <textarea name="tip_text" rows="4" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-3 font-bold focus:ring-2 focus:ring-primary outline-none"><?= $tool ? h($tool['tip_text']) : '' ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="bg-primary text-white px-12 py-5 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20">
                        <?= $tool ? 'UPDATE TOOL CONFIG' : 'CREATE NEW TOOL' ?>
                    </button>
                    <a href="tools.php" class="bg-white border border-slate-200 text-slate-500 px-8 py-5 rounded-2xl font-bold text-sm flex items-center">CANCEL</a>
                </div>
            </form>
        <?php endif; ?>
    </main>
</body>
</html>
