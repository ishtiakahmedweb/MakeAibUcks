<?php
/**
 * Public Result Page - MakeAIBucks
 */
require_once '../includes/config.php';

$slug = get('slug');

if (!$slug) {
    redirect(url('tools'));
}

$db = db();
$result = $db->fetchOne("
    SELECT r.*, t.name as tool_name, t.description as tool_desc, t.category_slug 
    FROM results r 
    JOIN tools t ON r.tool_slug = t.slug 
    WHERE r.slug = ?
", [$slug]);

if (!$result) {
    redirect(url('tools'));
}

// SEO
$SEO_DATA = [
    'title' => $result['page_title'] . ' — MakeAIBucks',
    'description' => truncate($result['output_text'], 160)
];

$inputs = json_decode($result['inputs_json'], true) ?: [];

// Fetch Comments
$comments = $db->fetchAll("SELECT * FROM comments WHERE result_id = ? AND is_approved = 1 ORDER BY created_at DESC", [$result['id']]);

// Update view count
$db->query("UPDATE results SET view_count = view_count + 1 WHERE id = ?", [$result['id']]);

include '../includes/header.php';
?>

<div class="bg-bg-page min-h-screen">
    <main class="max-w-5xl mx-auto px-6 py-12">
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-black uppercase tracking-widest text-slate-500 mb-10">
            <a class="hover:text-primary transition-colors" href="<?= url() ?>">Home</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <a class="hover:text-primary transition-colors" href="<?= url('tools') ?>">Results</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-slate-900"><?= h($result['tool_name']) ?></span>
        </nav>

        <div class="bg-white border border-slate-200 rounded-[40px] shadow-sm overflow-hidden mb-12">
            <!-- Header bar -->
            <div class="bg-bg-sidebar p-10 md:p-16 text-white relative">
                <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(#16a34a 0.5px, transparent 0.5px); background-size: 20px 20px;"></div>
                
                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 bg-primary/20 border border-primary/30 text-primary px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest mb-6 backdrop-blur-sm">
                        <span class="material-symbols-outlined text-xs">verified</span> Public AI Result
                    </div>
                    <h1 class="text-4xl md:text-6xl font-black mb-6 tracking-tighter leading-tight"><?= h($result['tool_name']) ?> Output</h1>
                    
                    <div class="flex flex-wrap items-center gap-8">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                                <span class="material-symbols-outlined text-sm">event</span>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none mb-1">Generated</p>
                                <p class="text-sm font-bold"><?= timeAgo($result['created_at']) ?></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                                <span class="material-symbols-outlined text-sm">visibility</span>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none mb-1">Views</p>
                                <p class="text-sm font-bold"><?= formatNumber($result['view_count']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="p-8 md:p-16">
                <!-- Inputs reveal -->
                <div class="mb-12 border-b border-slate-100 pb-12">
                    <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6">Parameters used:</h3>
                    <div class="flex flex-wrap gap-3">
                        <?php foreach ($inputs as $key => $val): ?>
                            <div class="bg-slate-50 border border-slate-200 px-5 py-3 rounded-2xl">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-0.5"><?= h($key) ?></span>
                                <span class="text-sm font-bold text-slate-900"><?= h($val) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- AI Output -->
                <div class="prose prose-slate prose-lg max-w-none text-slate-900 font-medium leading-relaxed selection:bg-primary selection:text-white whitespace-pre-wrap mb-12">
                    <?= $result['output_text'] ?>
                </div>

                <!-- Actions -->
                <div class="flex flex-wrap gap-4 pt-12 border-t border-slate-100">
                    <button onclick="copyCurrentUrl()" class="bg-slate-900 text-white px-8 py-4 rounded-2xl font-black text-sm flex items-center gap-3 hover:bg-black transition-all">
                        <span class="material-symbols-outlined">share</span> SHARE URL
                    </button>
                    <a href="<?= url('tool/' . $result['tool_slug']) ?>" class="bg-primary text-white px-8 py-4 rounded-2xl font-black text-sm flex items-center gap-3 hover:bg-primary-dark transition-all shadow-xl shadow-primary/20">
                        <span class="material-symbols-outlined">bolt</span> TRY THIS TOOL
                    </a>
                </div>
            </div>
        </div>

        <!-- Comments Section -->
        <div class="max-w-3xl mx-auto space-y-12">
            <h2 class="text-3xl font-black text-slate-900 flex items-center gap-4">
                Feedback & Results <span class="text-slate-300 text-sm font-bold font-body tracking-normal"><?= count($comments) ?> Comments</span>
            </h2>
            
            <form id="comment-form" class="bg-white border border-slate-200 rounded-[32px] p-8 space-y-6">
                <input type="hidden" name="result_id" value="<?= $result['id'] ?>">
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest px-2">Your Name</label>
                        <input name="name" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-3.5 text-sm font-bold focus:ring-2 focus:ring-primary outline-none" placeholder="e.g. ContentCreator" required/>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest px-2">Comment</label>
                        <input name="content" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-3.5 text-sm font-bold focus:ring-2 focus:ring-primary outline-none" placeholder="Great result! or How can I improve this?" required/>
                    </div>
                </div>
                <button type="submit" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-900 py-4 rounded-2xl font-black text-sm transition-all">
                    POST COMMENT
                </button>
            </form>

            <div class="space-y-6">
                <?php if (empty($comments)): ?>
                    <div class="text-center py-10">
                        <p class="text-slate-400 font-bold italic">No comments yet. Be the first to share your thoughts!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="bg-white border border-slate-100 p-8 rounded-[32px] flex gap-6">
                            <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-slate-400">person</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-3 mb-2">
                                    <h4 class="font-black text-slate-900"><?= h($comment['name']) ?></h4>
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest"><?= timeAgo($comment['created_at']) ?></span>
                                </div>
                                <p class="text-slate-500 font-medium leading-relaxed"><?= h($comment['content']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<script>
function copyCurrentUrl() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        showToast('Result URL copied to clipboard!');
    });
}

document.getElementById('comment-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const btn = form.querySelector('button');
    btn.disabled = true;
    
    try {
        const formData = new FormData(form);
        const response = await fetch('<?= url('api/comment.php') ?>', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        
        if (result.success) {
            showToast('Comment posted! It will appear after review.');
            form.reset();
        } else {
            showToast(result.message, 'error');
        }
    } catch (e) {
        showToast('Error posting comment.', 'error');
    } finally {
        btn.disabled = false;
    }
});
</script>

<?php include '../includes/footer.php'; ?>
