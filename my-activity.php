<?php
/**
 * My Activity - MakeAIBucks
 */
require_once 'includes/config.php';

// SEO
$SEO_DATA = [
    'title' => 'My Activity — MakeAIBucks',
    'description' => 'View your recently used AI tools and saved bookmarks.'
];

$sessionId = session_id();
$db = db();

// Fetch Bookmarked Tools
$bookmarks = $db->fetchAll("
    SELECT t.* from tools t 
    JOIN bookmarks b ON t.slug = b.tool_slug 
    WHERE b.session_id = ? AND t.is_active = 1
", [$sessionId]);

// Fetch Recent Results (via IP/Session)
$recentResults = $db->fetchAll("
    SELECT r.*, t.name as tool_name 
    FROM results r 
    JOIN tools t ON r.tool_slug = t.slug 
    WHERE (r.session_id = ? OR r.ip_address = ?) 
    ORDER BY r.created_at DESC LIMIT 10
", [$sessionId, getClientIp()]);

include 'includes/header.php';
?>

<div class="bg-bg-page min-h-screen pb-24">
    <div class="max-w-7xl mx-auto px-6 pt-12">
        <h1 class="text-4xl md:text-6xl font-black text-slate-900 mb-4 tracking-tighter">My Activity</h1>
        <p class="text-lg text-slate-500 font-medium mb-12">Your personal workspace for saved tools and recent generations.</p>

        <div class="grid lg:grid-cols-12 gap-12">
            <!-- Saved Tools -->
            <div class="lg:col-span-12">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-black text-slate-900 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">bookmark</span>
                        Saved Tools
                    </h2>
                </div>

                <?php if (empty($bookmarks)): ?>
                    <div class="bg-white border border-slate-200 rounded-[40px] p-16 text-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-300 mx-auto mb-6">
                            <span class="material-symbols-outlined text-3xl">bookmark_add</span>
                        </div>
                        <p class="text-slate-500 font-bold mb-8 italic">You haven't saved any tools yet.</p>
                        <a href="<?= url('tools') ?>" class="inline-block bg-primary text-white px-8 py-4 rounded-xl font-bold hover:scale-105 transition-all">Browse Tool Library</a>
                    </div>
                <?php else: ?>
                    <div class="grid md:grid-cols-3 gap-6">
                        <?php foreach ($bookmarks as $tool): ?>
                        <div class="bg-white p-8 rounded-3xl border border-slate-200 hover:border-primary transition-all group">
                            <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center text-primary mb-6">
                                <span class="material-symbols-outlined text-2xl">bolt</span>
                            </div>
                            <h3 class="text-xl font-black text-slate-900 mb-2"><?= h($tool['name']) ?></h3>
                            <p class="text-xs text-slate-500 mb-6 font-medium"><?= h($tool['category_slug']) ?></p>
                            <a href="<?= url('tool/' . $tool['slug']) ?>" class="w-full bg-slate-900 text-white py-3 rounded-xl flex items-center justify-center font-bold text-sm hover:bg-primary transition-all">
                                Open Tool
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Recent Generations -->
            <div class="lg:col-span-12 mt-12">
                <h2 class="text-2xl font-black text-slate-900 mb-8 flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary">history</span>
                    Recent Generations
                </h2>

                <?php if (empty($recentResults)): ?>
                    <div class="bg-white border border-slate-200 rounded-[40px] p-16 text-center">
                        <p class="text-slate-400 font-bold italic">No recent activity found.</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($recentResults as $res): ?>
                        <div class="bg-white border border-slate-200 p-6 rounded-[32px] flex flex-col md:flex-row md:items-center gap-6 hover:shadow-lg transition-all">
                            <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center text-primary shrink-0">
                                <span class="material-symbols-outlined">description</span>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-black text-slate-900 mb-1"><?= h($res['tool_name']) ?></h4>
                                <p class="text-xs text-slate-500 font-medium"><?= timeAgo($res['created_at']) ?></p>
                            </div>
                            <a href="<?= url('result/' . $res['slug']) ?>" class="bg-slate-100 hover:bg-slate-200 text-slate-900 px-6 py-3 rounded-xl font-bold text-xs transition-all">
                                View Result
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
