<?php
/**
 * Tool Requests - MakeAIBucks
 */
require_once '../includes/config.php';

// SEO
$SEO_DATA = [
    'title' => 'Request a Tool — MakeAIBucks',
    'description' => 'Tell us what AI tool to build next. Browse most requested tools and vote for your favorites.'
];

$db = db();
$sortBy = get('sort', 'votes');
$order = ($sortBy === 'newest') ? 'created_at DESC' : 'votes DESC';

$requests = $db->fetchAll("SELECT * FROM tool_requests ORDER BY $order LIMIT 20");

include '../includes/header.php';
?>

<div class="bg-bg-page min-h-screen">
    <!-- Hero Section -->
    <header class="hero-gradient pt-24 pb-32 px-6">
        <div class="max-w-5xl mx-auto text-center">
            <h1 class="text-5xl md:text-7xl font-black text-white mb-6 tracking-tighter leading-tight">
                Request a <span class="income-text">New Tool</span>
            </h1>
            <p class="text-slate-400 text-lg md:text-xl max-w-2xl mx-auto font-medium">
                Our roadmap is driven by you. Most upvoted requests get built first by our engineering team.
            </p>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 -mt-16 relative z-10 pb-24">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <!-- Left Column: Submit Request -->
            <div class="lg:col-span-12">
                <div class="bg-white rounded-[40px] p-8 md:p-12 shadow-2xl border border-slate-200">
                    <div class="grid lg:grid-cols-2 gap-12 items-center">
                        <div>
                            <h2 class="text-3xl font-black text-slate-900 mb-6">Submit a request</h2>
                            <p class="text-slate-500 font-medium mb-8 leading-relaxed">
                                Need a specific AI generator for your niche? Tell us what you want to earn more from, and we'll handle the prompt engineering and backend.
                            </p>
                            
                            <form id="request-form" class="space-y-6">
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest px-2">Tool Name</label>
                                        <input name="name" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 font-bold focus:ring-2 focus:ring-primary outline-none" placeholder="e.g. AI Etsy Tag Generator" required/>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest px-2">Category</label>
                                        <select name="category" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 font-bold focus:ring-2 focus:ring-primary outline-none">
                                            <option>E-Commerce</option>
                                            <option>Freelancing</option>
                                            <option>Content Creation</option>
                                            <option>Marketing & SEO</option>
                                            <option>Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest px-2">Description</label>
                                    <textarea name="description" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 font-bold focus:ring-2 focus:ring-primary outline-none" placeholder="What should the tool do? (e.g. It should take a product URL and generate 20 tags)" rows="3" required></textarea>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest px-2">Use Case / Email (Optional)</label>
                                    <input name="email" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 font-bold focus:ring-2 focus:ring-primary outline-none" placeholder="Your email for notification (optional)"/>
                                </div>
                                <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white py-5 rounded-2xl font-black text-lg transition-all shadow-xl shadow-primary/20">
                                    SUBMIT REQUEST
                                </button>
                            </form>
                        </div>
                        
                        <div class="hidden lg:block bg-slate-50 p-10 rounded-[40px] border border-slate-100">
                            <h3 class="text-xl font-black text-slate-900 mb-6 flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary">new_releases</span>
                                Request Guidelines
                            </h3>
                            <ul class="space-y-6">
                                <li class="flex gap-4">
                                    <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-black shrink-0">1</div>
                                    <p class="text-sm text-slate-500 font-medium">Be specific about the inputs and the expected output for the tool.</p>
                                </li>
                                <li class="flex gap-4">
                                    <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-black shrink-0">2</div>
                                    <p class="text-sm text-slate-500 font-medium">Explain how this tool helps people make bucks (Income potential).</p>
                                </li>
                                <li class="flex gap-4">
                                    <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-black shrink-0">3</div>
                                    <p class="text-sm text-slate-500 font-medium">Check the leaderboard below to see if someone already requested it.</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Leaderboard -->
            <div class="lg:col-span-12 mt-12">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
                    <h2 class="text-4xl font-black text-slate-900 tracking-tight">Community Roadmap</h2>
                    <div class="flex items-center gap-4 bg-white border border-slate-200 rounded-2xl px-6 py-3">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Sort by:</span>
                        <a href="?sort=votes" class="text-sm font-black <?= $sortBy === 'votes' ? 'text-primary' : 'text-slate-400 hover:text-slate-900' ?>">Popular</a>
                        <span class="w-1 h-1 rounded-full bg-slate-200"></span>
                        <a href="?sort=newest" class="text-sm font-black <?= $sortBy === 'newest' ? 'text-primary' : 'text-slate-400 hover:text-slate-900' ?>">Newest</a>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-8">
                    <?php if (empty($requests)): ?>
                        <div class="md:col-span-2 text-center py-20 bg-white rounded-[40px] border border-slate-100">
                            <p class="text-slate-400 font-bold italic">No requests yet. Be the first!</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($requests as $idx => $req): ?>
                        <div class="group bg-white border border-slate-200 p-8 rounded-[40px] flex gap-8 items-start hover:border-primary transition-all hover:shadow-2xl hover:shadow-primary/5">
                            <div class="text-4xl font-black text-slate-100 group-hover:text-primary/10 transition-colors hidden sm:block">
                                <?= str_pad($idx + 1, 2, '0', STR_PAD_LEFT) ?>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-xl font-black text-slate-900"><?= h($req['name']) ?></h3>
                                    <?php 
                                    $statusColor = [
                                        'pending'   => 'bg-slate-100 text-slate-500',
                                        'reviewing' => 'bg-amber-50 text-amber-600',
                                        'building'  => 'bg-blue-50 text-blue-600',
                                        'launched'  => 'bg-green-100 text-green-600'
                                    ][$req['status']] ?? 'bg-slate-100 text-slate-500';
                                    ?>
                                    <span class="text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-lg <?= $statusColor ?>">
                                        <?= $req['status'] ?>
                                    </span>
                                </div>
                                <div class="flex items-center gap-3 mb-4">
                                    <span class="text-[10px] font-black bg-slate-50 text-slate-500 px-3 py-1 rounded-full border border-slate-100 uppercase"><?= h($req['category']) ?></span>
                                    <span class="text-primary text-xs font-black flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">trending_up</span> <?= $req['votes'] ?> Votes
                                    </span>
                                </div>
                                <p class="text-sm text-slate-500 font-medium line-clamp-2 leading-relaxed mb-6 italic"><?= h($req['description']) ?></p>
                                
                                <button onclick="vote('<?= $req['id'] ?>')" class="flex items-center gap-2 bg-slate-50 hover:bg-primary hover:text-white px-6 py-2.5 rounded-xl text-[10px] font-black uppercase transition-all">
                                    <span class="material-symbols-outlined text-sm">arrow_upward</span> UPVOTE
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
async function vote(id) {
    try {
        const formData = new FormData();
        formData.append('id', id);
        const response = await fetch('<?= url('api/vote.php') ?>', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if (result.success) {
            showToast('Vote recorded!');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(result.message, 'error');
        }
    } catch (e) {
        showToast('Error voting.', 'error');
    }
}

document.getElementById('request-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = e.target.querySelector('button');
    btn.disabled = true;
    
    try {
        const formData = new FormData(e.target);
        const response = await fetch('<?= url('api/request-tool.php') ?>', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if (result.success) {
            showToast('Request submitted successfully!');
            e.target.reset();
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast(result.message, 'error');
        }
    } catch (e) {
        showToast('Error submitting request.', 'error');
    } finally {
        btn.disabled = false;
    }
});
</script>

<?php include '../includes/footer.php'; ?>
